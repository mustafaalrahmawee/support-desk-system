# Backend Patterns — Laravel

## Zweck

Technische Laravel-Muster für die Implementierung.

Für fachliche Wahrheit, Grundregeln und Dokumenthierarchie gilt `docs/README.md`.
Für technische Arbeitsregeln und QA-Verhalten gilt `CLAUDE.md`.

---

## Technische Muster

- Controller bleiben dünn — Fachlogik gehört in Actions
- FormRequests enthalten nur formale Validierung
- Autorisierung läuft über Policies
- Fachlich zusammenhängende Änderungen laufen atomar über `DB::transaction()`
- Audit wird innerhalb fachlich relevanter Actions ausgelöst
- Jobs rufen Actions auf und enthalten keine Fachlogik
- Soft Delete wird nicht direkt im Controller orchestriert
- Fachliche Exceptions statt generischer Exceptions verwenden

---

## Request-Lifecycle

Route → Middleware → FormRequest → Controller → Policy → Action → Model → Response

---

## Ordnerstruktur

    api/app/
    ├── Actions/
    ├── Http/
    │   ├── Controllers/
    │   ├── Requests/
    │   └── Middleware/
    ├── Jobs/
    ├── Models/
    ├── Policies/
    ├── Services/
    └── Support/
        ├── Enums/
        └── Exceptions/

---

## Model-Muster

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('tickets')]
#[Fillable([
    'subject',
    'description',
    'status',
    'priority',
    'channel',
    'category_id',
    'customer_id',
    'contract_id',
    'assigned_internal_user_id',
])]
class Ticket extends Model
{
    use SoftDeletes;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function assignedInternalUser(): BelongsTo
    {
        return $this->belongsTo(InternalUser::class, 'assigned_internal_user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }
}
```

---

## FormRequest-Muster

```php
<?php

namespace App\Http\Requests\Tickets;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'channel' => ['required', Rule::in(['email', 'phone', 'whatsapp', 'web', 'external'])],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'contract_id' => ['nullable', 'integer', 'exists:contracts,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required' => 'Bitte einen Betreff eingeben.',
            'description.required' => 'Bitte eine Beschreibung eingeben.',
            'channel.required' => 'Bitte einen Kanal auswählen.',
            'channel.in' => 'Der gewählte Kanal ist ungültig.',
            'category_id.required' => 'Bitte eine Kategorie auswählen.',
            'category_id.exists' => 'Die gewählte Kategorie existiert nicht.',
            'customer_id.required' => 'Bitte einen Customer auswählen.',
            'customer_id.exists' => 'Der gewählte Customer existiert nicht.',
            'contract_id.exists' => 'Der gewählte Contract existiert nicht.',
        ];
    }
}
```

---

## Controller-Muster

```php
<?php

namespace App\Http\Controllers\Tickets;

use App\Actions\Tickets\StoreTicketAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tickets\StoreTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

class StoreTicketController extends Controller
{
    public function __invoke(
        StoreTicketRequest $request,
        StoreTicketAction $action
    ): JsonResponse {
        $this->authorize('create', Ticket::class);

        $ticket = $action->execute($request->validated(), $request->user());

        return response()->json([
            'message' => 'Vorgang erfolgreich.',
            'data' => $ticket,
        ], 201);
    }
}
```

---

## Policy-Muster

```php
<?php

namespace App\Policies;

use App\Models\InternalUser;
use App\Models\Ticket;

class TicketPolicy
{
    public function create(InternalUser $user): bool
    {
        return $user->hasRole('support_agent');
    }

    public function update(InternalUser $user, Ticket $ticket): bool
    {
        return $user->hasRole('support_agent');
    }

    public function viewAny(InternalUser $user): bool
    {
        return $user->hasRole('support_agent');
    }

    public function view(InternalUser $user, Ticket $ticket): bool
    {
        return $user->hasRole('support_agent');
    }
}
```

---

## Action-Muster

```php
<?php

namespace App\Actions\Tickets;

use App\Models\InternalUser;
use App\Models\Ticket;
use App\Services\Audit\AuditService;
use Illuminate\Support\Facades\DB;

class StoreTicketAction
{
    public function __construct(
        private readonly AuditService $auditService
    ) {
    }

    public function execute(array $data, InternalUser $actor): Ticket
    {
        return DB::transaction(function () use ($data, $actor) {
            $ticket = Ticket::query()->create([
                'subject' => $data['subject'],
                'description' => $data['description'],
                'status' => 'open',
                'priority' => $data['priority'] ?? 'normal',
                'channel' => $data['channel'],
                'category_id' => $data['category_id'],
                'customer_id' => $data['customer_id'],
                'contract_id' => $data['contract_id'] ?? null,
                'assigned_internal_user_id' => $data['assigned_internal_user_id'] ?? null,
            ]);

            $this->auditService->logInternalUserAction(
                user: $actor,
                action: 'ticket_created',
                auditable: $ticket,
                oldValues: null,
                newValues: $ticket->toArray(),
            );

            return $ticket;
        });
    }
}
```

---

## Service-Muster

```php
<?php

namespace App\Services\Audit;

use App\Models\AuditLog;
use App\Models\InternalUser;
use Illuminate\Database\Eloquent\Model;

class AuditService
{
    public function logInternalUserAction(
        InternalUser $user,
        string $action,
        Model $auditable,
        ?array $oldValues,
        ?array $newValues,
    ): void {
        AuditLog::query()->create([
            'internal_user_id' => $user->id,
            'context_type' => 'internal_user',
            'context_name' => null,
            'action' => $action,
            'auditable_type' => $auditable::class,
            'auditable_id' => $auditable->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}
```

---

## Exception-Muster

```php
<?php

namespace App\Support\Exceptions;

use RuntimeException;

class TicketStatusTransitionNotAllowedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Der Statuswechsel ist fachlich nicht erlaubt.');
    }
}
```

---

## Response-Muster

```php
return response()->json([
    'message' => 'Vorgang erfolgreich.',
    'data' => $resource,
], 200);
```

```php
return response()->json([
    'message' => 'Vorgang erfolgreich.',
    'data' => $resource,
], 201);
```

---

## DB-Transaction-Muster

```php
use Illuminate\Support\Facades\DB;

DB::transaction(function () use ($data) {
    // fachlich zusammenhängende Änderungen
});
```

---

## Soft-Delete-Muster

```php
<?php

namespace App\Actions\Customers;

use App\Models\Customer;
use App\Services\Audit\AuditService;
use Illuminate\Support\Facades\DB;

class SoftDeleteCustomerAction
{
    public function __construct(
        private readonly AuditService $auditService
    ) {
    }

    public function execute(Customer $customer, $actor): void
    {
        DB::transaction(function () use ($customer, $actor) {
            $customer->contacts()->each(function ($contact) {
                $contact->delete();
            });

            $customer->actor?->delete();
            $customer->delete();

            $this->auditService->logInternalUserAction(
                user: $actor,
                action: 'customer_soft_deleted',
                auditable: $customer,
                oldValues: ['deleted_at' => null],
                newValues: ['deleted_at' => now()->toDateTimeString()],
            );
        });
    }
}
```

---

## Job-Muster

```php
<?php

namespace App\Jobs\Inbound;

use App\Actions\Inbound\ProcessInboundReviewCaseAction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessInboundReviewCaseJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $inboundReviewCaseId
    ) {
    }

    public function handle(ProcessInboundReviewCaseAction $action): void
    {
        $action->execute($this->inboundReviewCaseId);
    }
}
```

---

## Route-Muster

```php
use App\Http\Controllers\Tickets\StoreTicketController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tickets', StoreTicketController::class);
});
```

---

## Verbotene Muster

Zusätzlich zu den Regeln in `CLAUDE.md` und `docs/README.md` gelten:

- FormRequests mit komplexer Fachlogik
- Generische Exceptions für fachliche Konflikte
- Direktes `$model->delete()` im Controller
- Jobs mit eigener Fachlogik