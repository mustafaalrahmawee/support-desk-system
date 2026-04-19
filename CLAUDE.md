# Smart Support Desk System — Claude Code Anweisungen

## Session-Start (PFLICHT)

Jede Implementierungs-Session wird über den Skill `/session` gestartet:

```
/session <domain>              → Vollständige Domain implementieren
/session <domain> <bundle>     → Bestimmtes Session-Bundle implementieren
```

Beispiele:
- `/session users` → Users-Domain (UC 40–43)
- `/session auth bundle-a` → Auth-Grundlage (UC 36 + 37)
- `/session tickets bundle-b` → Ticket-Bearbeitung

Der Skill führt automatisch die folgenden Schritte aus:

1. **Fachliche Grundlage lesen** — `docs/domain/01_miniworld.md`, `02_business-rules.md`, `03_er.md`
2. **Architektur lesen** — `docs/README.md`
3. **Domain-Koordinator lesen** — `docs/by-domain/{domain}.md`
4. **Plan erstellen** — UCs, Reihenfolge, Abhängigkeiten, betroffene Dateien
5. **Plan bestätigen lassen** — Nichts implementieren bevor Bestätigung vorliegt
6. **Pipeline abarbeiten** — Pro UC: Backend → Backend-QA → Frontend → Frontend-QA

Falls kein `/session`-Aufruf erfolgt, gelten die Schritte trotzdem als Pflicht — der Skill automatisiert sie lediglich.

---

## Pipeline pro Use Case

Für jeden Use Case wird die zugehörige `docs/by-use-case/{uc}.md` Datei gelesen und die Pipeline in dieser Reihenfolge abgearbeitet:

1. **Backend implementieren** — Abschnitte 1–3 (Use Case, API-Contract, Backend-Architektur)
2. **Backend-QA** — Abschnitt 4 (Tests über curl und tinker)
3. **Frontend implementieren** — Abschnitte 5–8 (Frontend-Architektur, Screen-Flow, UI-Regeln, Stitch-Prompt)
4. **Frontend-QA** — Abschnitt 9 (Tests über Playwright MCP)

Kein Abschnitt darf übersprungen werden. Bei Kontextfülle: `/clear` und mit dem nächsten UC weitermachen.

---

## Domains und Use Cases

| Domain | Use Cases | Datei |
|--------|-----------|-------|
| Auth | UC 36–39 | `docs/by-domain/auth.md` |
| Users | UC 40–43 | `docs/by-domain/users.md` |
| Customers | UC 5–10 | `docs/by-domain/customers.md` |
| Contacts | UC 11–15 | `docs/by-domain/contacts.md` |
| Contracts | UC 16–21 | `docs/by-domain/contracts.md` |
| Tickets | UC 22–30 | `docs/by-domain/tickets.md` |
| Messages | UC 3–4 | `docs/by-domain/messages.md` |
| Inbound | UC 1–2 | `docs/by-domain/inbound.md` |
| Categories | UC 31–32 | `docs/by-domain/categories.md` |
| Media | UC 33 | `docs/by-domain/media.md` |
| System | UC 34–35 | `docs/by-domain/system.md` |

---

## Technischer Stack

- **Backend:** Laravel 13, PHP 8.4, PostgreSQL 16, Sanctum
- **Frontend:** Vue.js, Tailwind CSS, Pinia, ofetch, Vuelidate
- **Infrastruktur:** Docker Compose (API :8000, App :5173, DB :5432)
- **UI-Erzeugung:** Claude Code + Stitch MCP + Google Labs stitch-skills

---

## Architektur-Regeln

### Backend

- Request-Lifecycle: `Route → Middleware → FormRequest → Controller → Policy → Action → Model → Response`
- Controller dünn halten — keine Fachlogik
- Eine Action pro Use Case mit `execute()` als einzige öffentliche Methode
- `DB::transaction()` für atomare Operationen
- Laravel 13 Attribute: `#[Fillable([...])]`, `#[Hidden([...])]`, `#[Table('...')]`
- Fachliche Exceptions statt generischer
- Audit innerhalb der Action auslösen

### Frontend

- Fachlogik kommt aus dem Backend, nicht aus dem UI
- Pages = vollständige Screens, Use-Case-orientiert
- Stores = API-nahe Logik pro Fachbereich (Pinia)
- Komponenten nur auslagern, wenn Page aus mehreren eigenständigen Blöcken besteht
- Keine Rollen- oder Statuslogik im Frontend erfinden

---

## Verbotene Muster

- Fachlogik im Controller
- `protected $fillable` statt Attribute
- `Request $request` statt typisierter FormRequest
- Rollenabfragen quer im Code statt in Policies
- Audit vergessen bei fachlich relevanten Änderungen
- Fehlende Transaktion bei zusammenhängenden Operationen
- UI-Screen ohne Use-Case-Bezug
- Fachliche Annahmen erfinden, die nicht in den Docs stehen
- Felder, Enum-Werte oder Rollen-Zuordnungen raten

---

## Fachliche Wahrheitsquellen

Bei Widersprüchen gilt diese Hierarchie:

1. `docs/domain/` (höchste Autorität)
2. `docs/by-use-case/` (API-Contracts, Pipeline)
3. `docs/by-domain/` (Orchestrierung)

Nichts erfinden. Nichts ergänzen. Nur implementieren, was dokumentiert ist.
