# Smart Support Desk System

## Zweck

Diese Datei ist die zentrale Referenz für das gesamte Projekt.

Sie beschreibt den technischen Stack, die Architekturprinzipien für Backend und Frontend, die Dokumentstruktur, die Pipeline pro Use Case und die Regeln für die Arbeit mit Claude Code und anderen Agenten.

---

## Technischer Stack

### Backend

- Laravel 13, PHP 8.4, PostgreSQL 16
- Auth: Laravel Sanctum (Token-basiert)
- Queue: Redis (oder database)
- QA: Claude Code Subagent (`backend-qa`) über `curl` und `php artisan tinker`
- Container: Docker (`support_desk_api`, PHP 8.4-cli-alpine)

### Frontend

- Vue.js, Tailwind CSS, Pinia, ofetch, Vuelidate
- UI-Erzeugung: Claude Code + Frontend Design Plugin (`frontend-design@claude-plugins-official`)
- QA: Claude Code Subagent (`frontend-qa`) über Playwright MCP
- Container: Docker (`support_desk_app`)

### Infrastruktur

- Docker Compose (PostgreSQL, Laravel API, Vue.js App)
- Ports: API `8000`, App `5173`, DB `5432`

---

## Dokumentstruktur

```
docs/
├── domain/                       ← fachliche Grundlage (unverändert)
│   ├── 01_miniworld.md
│   ├── 02_business-rules.md
│   └── 03_er.md
├── README.md                     ← diese Datei
├── by-domain/                    ← Koordinator pro Domain (Session-Orchestrierung)
│   ├── auth.md
│   ├── users.md
│   ├── customers.md
│   ├── contacts.md
│   ├── contracts.md
│   ├── tickets.md
│   ├── messages.md
│   ├── inbound.md
│   ├── categories.md
│   └── media.md
└── by-use-case/                  ← komplette Pipeline pro Use Case (Arbeitseinheit)
    ├── uc36_login.md
    ├── uc37_logout.md
    ├── uc38_profile_show.md
    ├── uc39_profile_update.md
    └── ...
```

### Rollen der Ebenen

**`domain/`** — fachliche Wahrheit. Miniworld, Business Rules, ER-Modell. Wird immer zuerst gelesen. Wird nie durch andere Dateien ersetzt.

**`by-domain/`** — Koordinator. Beschreibt welche Use Cases zu einer Domain gehören, in welcher Reihenfolge sie implementiert werden, welche Abhängigkeiten bestehen, welche gemeinsamen Bausteine existieren und welche Session-Bundles sinnvoll sind. Enthält keine Pipeline selbst.

**`by-use-case/`** — Pipeline-Träger. Jede Datei beschreibt die komplette Pipeline für einen Use Case in 9 Abschnitten: Use Case → API-Contract → Backend-Architektur → Backend-QA → Frontend-Architektur → Screen-Flow → UI-Regeln → Frontend-Design-Prompt → Frontend-QA. Eine Datei, ein Use Case, ein vollständiger Durchlauf.

### Warum diese Struktur?

Eine `by-domain/` Datei mit 9 Use Cases und jeweils 9 Abschnitten wird schnell 1.500+ Zeilen lang — das gleiche Monster-Datei-Problem wie vorher. Stattdessen bleibt die Domain-Datei schlank (Orchestrierung), und die eigentliche Arbeit läuft über die atomaren `by-use-case/` Dateien. Claude Code kann jederzeit eine ganze Domain abarbeiten (über den Koordinator) oder nur einen einzelnen Use Case (direkt die Datei).

### Leseregeln

- `domain/` wird immer zuerst gelesen
- `by-domain/` wird gelesen, um die Session zu planen
- `by-use-case/` wird gelesen, um die Pipeline auszuführen
- `README.md` wird einmal am Anfang gelesen

---

## Backend-Architektur

### Request-Lifecycle

```
Route → Middleware → FormRequest → Controller → Policy → Action → Model → Response
```

FormRequest wird VOR dem Controller-Body ausgeführt (Dependency Injection).

### Schichten und Verantwortlichkeiten

**Controller** — dünn halten. Darf nur: Request entgegennehmen, Policy prüfen, eine Action aufrufen, Response zurückgeben. Keine Fachlogik.

**FormRequest** — formale Validierung (Pflichtfelder, Formate, Enum-Werte, exists, unique). Keine komplexen Fachprüfungen. `authorize()` auf `return true` setzen — Autorisierung über Policy im Controller. Für benutzernahe Formulare sollen explizite deutsche Validierungsnachrichten über `messages()` definiert werden, damit verständliche Fehlermeldungen ans Frontend geliefert werden.

**Policy** — rollenbasierte Berechtigungen. Welche Rolle was darf, steht in `docs/domain/02_business-rules.md`. Keine Rollen-Zuordnungen erfinden.

**Action** — Kern der Fachlogik. Eine Action pro Use Case. `execute()` als einzige öffentliche Methode. `DB::transaction()` für atomare Operationen. Audit am Ende auslösen. Fachliche Exceptions werfen.

**Model** — Daten, Beziehungen, Scopes, SoftDeletes, kleine Hilfen. Keine Orchestrierungslogik.

**Services** — Querschnittslogik (Audit, Inbound-Resolver, Media-Handling).

### Laravel 13 Syntax

- `#[Fillable([...])]`, `#[Hidden([...])]`, `#[Table('...')]`, `#[Casts([...])]` statt `protected $fillable`
- `#[Middleware('auth:sanctum')]` auf Controller
- `#[Tries(3)]`, `#[Timeout(30)]`, `#[Queue('...')]` auf Jobs
- `Queue::route()` für zentrales Queue-Routing im AppServiceProvider

### PHP 8.4 Syntax

- Property Hooks für berechnete Werte auf Models
- Asymmetric Visibility für Value Objects
- Method Chaining auf `new` ohne Klammern

### Backend-Ordnerstruktur

```
api/app/
├── Actions/         (Auth/, InternalUsers/, Customers/, Contacts/, Contracts/,
│                     Tickets/, Messages/, Inbound/, Categories/, Media/)
├── Http/
│   ├── Controllers/ (Auth/, Admin/, Customers/, Contacts/, Contracts/,
│   │                 Tickets/, Messages/, Inbound/, Categories/, Media/)
│   ├── Requests/    (gleiche Aufteilung)
│   └── Middleware/
├── Jobs/            (Inbound/, Audit/, Notifications/, Media/)
├── Models/
├── Policies/
├── Services/        (Audit/, Inbound/, Media/)
└── Support/
    ├── Enums/
    └── Exceptions/
```

### Jobs und Queues

Jobs rufen Actions auf — sie enthalten selbst keine Fachlogik. Verwendung für: Inbound-Verarbeitung, Audit-Logging, Benachrichtigungen, Medienverarbeitung.

### Response-Format

```json
{
  "message": "Vorgang erfolgreich.",
  "data": { }
}
```

HTTP-Status-Codes: 200 (Erfolg), 201 (Erstellt), 401 (Nicht authentifiziert), 403 (Nicht autorisiert), 404 (Nicht gefunden), 409 (Fachlicher Konflikt), 422 (Validierungsfehler).

### Fachliche Exceptions

Fachliche Fehler als spezifische Exceptions, nicht generische. Im Exception Handler auf HTTP-Status mappen (409 für Konflikte, 422 für fachliche Validierung).

### Soft Delete und Audit

Soft Delete über Actions steuern, nicht direkt im Controller. Audit immer innerhalb der Action aufrufen. Welche Vorgänge auditpflichtig sind und welche abhängigen Objekte bei Soft Delete betroffen sind, steht in den Business Rules und Use Cases.

### Atomare Transaktionen — Pflicht bei

- Customer mit erstem Contact anlegen
- Customer-Merge
- Internen Benutzer deaktivieren + Actor synchronisieren
- Customer deaktivieren + abhängige Objekte synchronisieren
- Inbound-Prüffall entscheiden → Customer + Ticket + Nachricht erzeugen
- Eingehende Nachricht zuordnen

---

## Frontend-Architektur

### Grundprinzip

Fachlogik kommt aus den Backend-Dokumenten. Das Frontend darf fachliche Regeln nicht selbst erfinden.

### Schichten

**Pages** — vollständige Screens, Use-Case-orientiert.

**Components** — UI-Bausteine, die aus Pages ausgelagert werden, wenn ein Screen zu groß wird oder aus mehreren eigenständigen Blöcken mit eigenen Zuständen besteht. Nicht vorsorglich auslagern — solange eine Page kompakt und übersichtlich bleibt, gehört alles in die Page.

**Stores** — API-nahe Logik pro Fachbereich (Pinia). Minimal nötige Funktionen pro Use Case, nicht alles auf einmal. Stores sollen Requests nicht roh verstreuen, sondern ein gemeinsames API-Composable verwenden.

**Validators** — Vuelidate-Regeln für Formulare. Vuelidate ist für Formularvalidierung verbindlich; ad-hoc-Validierungslogik statt Vuelidate soll vermieden werden. Backend-Validierungsfehler ergänzen die Frontend-Validierung und müssen im UI sichtbar verarbeitet werden.

**API Client / Composables** — zentrale ofetch-Konfiguration. Für Request-Aufrufe in Stores soll ein gemeinsames `useApiFetch()`-Composable für Base-URL, Header und Token-Handling verwendet werden.

### Frontend-Ordnerstruktur

```
src/
  pages/         (auth/, users/, tickets/, customers/, contacts/, categories/)
  components/    (auth/, users/, tickets/, customers/, contacts/, shared/)
  stores/        (auth.store.ts, users.store.ts, tickets.store.ts, ...)
  services/api/  (client.ts)
  composables/
  layouts/
  router/
  utils/
  validators/
```

### UI-Erzeugung: Frontend Design Plugin

Das `frontend-design` Plugin ist der verbindliche Workflow zur UI-Erzeugung. Der Workflow erzeugt UI-Struktur lokal, keine Fachlogik, kein externer Dienst.

### UI-Regeln

Ruhige Admin-, Support- und Prüfoberfläche. Lesbarkeit vor Effekten. Konsistenz vor Einzellösung.

---

### Verbindliches API-Composable

```ts
import { $fetch } from 'ofetch'

const API_BASE = import.meta.env.VITE_API_URL ?? 'http://localhost:8000'

export function useApiFetch() {
  function apiFetch(path, options = {}) {
    const token = localStorage.getItem('auth_token')

    return $fetch(`${API_BASE}${path}`, {
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...(token ? { Authorization: `Bearer ${token}` } : {}),
        ...options.headers,
      },
      ...options,
    })
  }

  return { apiFetch }
}
```

Pinia-Stores sollen dieses Composable verwenden, statt Base-URL-, Header- und Token-Logik mehrfach zu duplizieren.

Formulare sollen mit Tailwind CSS umgesetzt werden. Feldbezogene Backend-Validierungsfehler gehören direkt ans jeweilige Feld, globale Fehler in einen separaten Fehlerbereich.

---

## Pipeline pro Use Case

Jede `by-use-case/` Datei beschreibt die komplette Pipeline in 9 Abschnitten:

### Abschnitt 1: Use Case
Ziel, Akteure, Vorbedingungen, Hauptablauf, Alternativabläufe, Nachbedingungen.

### Abschnitt 2: API-Contract
Endpoint, Methode, Authentifizierung, Request, Success Response, Failed Cases.

### Abschnitt 3: Backend-Architektur
Route, Middleware, FormRequest, Controller, Policy, Action, Model/Datenbasis.

### Abschnitt 4: Backend-QA
Welche Tests der `backend-qa` Subagent ausführen soll: curl-Requests, HTTP-Status, JSON-Struktur, Datenbankfolgen, Audit-Logs, RBAC.

### Abschnitt 5: Frontend-Architektur
Pages, Components, Store-Funktionen, Validators, Projektstruktur.

### Abschnitt 6: Screen-Flow
Screen-Name, sichtbare Hauptbereiche, Benutzeraktionen, erwartete Frontend-Logik, UI-Zustände.

### Abschnitt 7: UI-Regeln
Visuelle Muster für diesen Screen-Bereich.

### Abschnitt 8: Frontend-Design-Prompt
Vorbereiteter Prompt für die UI-Erzeugung über das Frontend Design Plugin.

### Abschnitt 9: Frontend-QA
Welche Tests der `frontend-qa` Subagent über Playwright MCP ausführen soll.

---

## Inhalt einer by-domain/ Datei

Domain-Dateien sind Koordinatoren. Sie enthalten:

1. **Domain-Überblick** — fachlicher Scope, betroffene Hauptobjekte, Abgrenzung
2. **Use-Case-Liste** — Links zu den `by-use-case/` Dateien
3. **Empfohlene Reihenfolge** — in welcher Reihenfolge die Use Cases implementiert werden sollten
4. **Abhängigkeiten** — fachliche und technische Voraussetzungen
5. **Gemeinsame Bausteine** — Backend (Routes, Controller, Actions, Models) und Frontend (Pages, Store, Components)
6. **Session-Bundles** — sinnvolle Gruppierungen für Sessions (z.B. "Auth-Grundlage: UC 36+37", "Vollständige Auth: UC 36–39")
7. **Verweise auf Wahrheitsquellen** — Links zu `domain/`

Domain-Dateien enthalten keine Pipeline. Die Pipeline lebt in den `by-use-case/` Dateien.

---

## Domains und Use Cases

| Domain | Use Cases | Session-Größe |
|--------|-----------|---------------|
| Auth | UC 36–39 | 4 UCs |
| Users | UC 40–43 | 4 UCs |
| Customers | UC 5–10 | 6 UCs |
| Contacts | UC 11–15 | 5 UCs |
| Contracts | UC 16–21 | 6 UCs |
| Tickets | UC 22–30 | 9 UCs |
| Messages | UC 3–4 | 2 UCs |
| Inbound | UC 1–2 | 2 UCs |
| Categories | UC 31–32 | 2 UCs |
| Media | UC 33 | 1 UC |
| System | UC 34–35 | 2 UCs |

---

## Wie eine Session abläuft

### Ganze Domain implementieren

```
1. docs/domain/ lesen (fachliche Grundlage)
2. docs/by-domain/{domain}.md lesen (Koordinator: Reihenfolge, Abhängigkeiten)
3. Für jeden UC in der empfohlenen Reihenfolge:
   a. docs/by-use-case/{uc}.md öffnen
   b. Phase 1: Backend implementieren (Abschnitte 1–3)
   c. Phase 1b: backend-qa Subagent ausführen (Abschnitt 4)
   d. Phase 2: Frontend implementieren (Abschnitte 5–8, UI-Gerüst via Frontend Design Plugin)
   e. Phase 2b: frontend-qa Subagent ausführen (Abschnitt 9)
4. Bei Kontextfülle: /clear und mit nächstem UC weitermachen
```

### Einzelnen Use Case implementieren

```
1. docs/domain/ lesen (fachliche Grundlage)
2. docs/by-use-case/{uc}.md öffnen
3. Pipeline in 9 Abschnitten abarbeiten
```

---

## Verbotene Muster

### Backend
- Fachlogik im Controller
- `protected $fillable` statt `#[Fillable([...])]`
- `Request $request` statt typisierter FormRequest
- Rollenabfragen quer im Code statt in Policies
- `$model->delete()` direkt im Controller
- Audit vergessen bei fachlich relevanten Änderungen
- Fehlende Transaktion bei zusammenhängenden Operationen
- Generische Exception statt fachlicher Exception
- Job mit Fachlogik statt Action-Aufruf

### Frontend
- UI-Screen ohne Use-Case-Bezug
- Business Rules aus dem UI-Gerüst ableiten
- Rollenlogik im UI-Code erfinden
- Statusregeln im Frontend definieren
- Store-Architektur aus Intuition statt aus API-Contracts
- Formulare ohne Vuelidate umsetzen
- Klassisches freies CSS statt Tailwind CSS verwenden, sofern kein dokumentierter Ausnahmefall besteht
- Backend-Validierungsfehler nicht im UI anzeigen
- Base-URL-, Header- oder Token-Handling mehrfach direkt in Stores duplizieren statt ein gemeinsames API-Composable zu verwenden

### Allgemein
- Fachliche Annahmen erfinden, die nicht in den Docs stehen
- Felder, Enum-Werte, Rollen-Zuordnungen oder Status-Transitionen raten
- Monster-Dateien laden statt die passende `by-use-case/` Datei
- Pipeline in `by-domain/` statt in `by-use-case/` beschreiben

---

## Fachliche Wahrheitsquellen

- Felder und Beziehungen → `docs/domain/03_er.md`
- Fachliche Regeln → `docs/domain/02_business-rules.md`
- Domänenwissen → `docs/domain/01_miniworld.md`
- Pipeline pro UC → `docs/by-use-case/{uc}.md`
- Domain-Orchestrierung → `docs/by-domain/{domain}.md`

Die API-Contracts in den `by-use-case/` Dateien sind aus den ursprünglichen Master-Dateien übernommen. Bei Widersprüchen gilt `docs/domain/`.

Nichts erfinden. Nichts ergänzen. Nur implementieren, was dokumentiert ist.
