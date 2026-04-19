# Users Domain

## Domain-Überblick

Die Domain **Users** umfasst die administrative Verwaltung interner Benutzerkonten durch berechtigte Administratoren.

Fachlicher Scope:
- Interne Benutzer auflisten
- Internen Benutzer anlegen
- Internen Benutzer bearbeiten (Stammdaten und Rollen)
- Internen Benutzer deaktivieren / Soft Delete

Betroffene Hauptobjekte:
- `internal_users`
- `roles`
- `internal_user_roles`
- `actors`
- `audit_logs`

Nicht Teil dieser Domain:
- Login, Logout, eigenes Profil (→ Auth-Domain)
- Customer-Verwaltung
- Ticket-Zuweisung (wird durch Ticket-Domain abgedeckt, aber Deaktivierung eines Benutzers hat Auswirkungen auf bestehende Zuweisungen)

---

## Use-Case-Liste

- [UC 40 – Interne Benutzer auflisten](../by-use-case/uc40_internal_users_list.md)
- [UC 41 – Internen Benutzer anlegen](../by-use-case/uc41_internal_user_create.md)
- [UC 42 – Internen Benutzer bearbeiten](../by-use-case/uc42_internal_user_update.md)
- [UC 43 – Internen Benutzer deaktivieren](../by-use-case/uc43_internal_user_deactivate.md)

---

## Empfohlene Reihenfolge

1. UC 40 – Interne Benutzer auflisten
2. UC 41 – Internen Benutzer anlegen
3. UC 42 – Internen Benutzer bearbeiten
4. UC 43 – Internen Benutzer deaktivieren

Begründung:
- Die Liste ist Grundlage für Navigation und Kontextverständnis.
- Anlegen definiert das Datenmodell und die Rollenstruktur.
- Bearbeiten baut auf der Anlage-Logik auf und erweitert sie.
- Deaktivieren ist der komplexeste Use Case (Actor-Synchronisation, Ticket-Zuweisungen) und setzt Verständnis der vorherigen Use Cases voraus.

---

## Abhängigkeiten

### Fachliche Abhängigkeiten

- Nur **Admins** dürfen interne Benutzerkonten verwalten.
- Ein interner Benutzer kann eine oder mehrere Rollen besitzen.
- Beim Anlegen wird ein zugehöriger **Actor** erzeugt.
- Beim Deaktivieren oder Soft Delete muss der zugehörige **Actor** synchron deaktiviert oder soft deleted werden.
- Beim Deaktivieren müssen bestehende **Ticket-Zuweisungen** des Benutzers aufgehoben oder neu vergeben werden.
- Alle Verwaltungsaktionen sind auditpflichtig.
- Reaktivierung eines Benutzers reaktiviert auch den zugehörigen Actor.

### Technische Abhängigkeiten

- Authentifizierung via Laravel Sanctum.
- Auth-Middleware für alle Endpoints.
- Admin-Policy für Autorisierung.
- Zugriff auf `internal_users`, `roles`, `internal_user_roles`, `actors`.
- Atomare Transaktionen bei Anlegen (mit Actor), Deaktivieren (mit Actor und Ticket-Zuweisungen) und Reaktivierung.
- Audit-Erzeugung innerhalb der fachlich relevanten Actions.

---

## Gemeinsame Bausteine

### Backend

- Route-Gruppe: Admin / InternalUsers
- Typische Endpunkte:
  - `GET /api/admin/internal-users`
  - `POST /api/admin/internal-users`
  - `PATCH /api/admin/internal-users/{id}`
  - `DELETE /api/admin/internal-users/{id}`
- Typische Controller-Zuordnung:
  - `InternalUserController`
- Mögliche Actions:
  - `ListInternalUsersAction`
  - `CreateInternalUserAction`
  - `UpdateInternalUserAction`
  - `DeactivateInternalUserAction`
- Relevante Models:
  - `InternalUser`
  - `Role`
  - `Actor`
  - `AuditLog`
- Policy:
  - `InternalUserPolicy`

### Frontend

- Pages:
  - `src/pages/users/InternalUsersListPage.vue`
  - `src/pages/users/InternalUserCreatePage.vue`
  - `src/pages/users/InternalUserEditPage.vue`
- Store:
  - `src/stores/users.store.ts`
- Mögliche Store-Funktionen:
  - `fetchInternalUsers(params)`
  - `createInternalUser(payload)`
  - `updateInternalUser(id, payload)`
  - `deactivateInternalUser(id)`
- Komponentenregel:
  - Solange ein Screen unter ~200 Zeilen bleibt und nur einen fachlichen Block hat, bleibt alles in der Page.
  - Nur auslagern, wenn die Page aus mehreren eigenständigen Blöcken mit eigenen Zuständen besteht oder zu unübersichtlich wird.
  - Nicht vorsorglich auslagern, nur weil man es theoretisch könnte.

---

## Session-Bundles

### Bundle A: Benutzerübersicht und Anlage

- UC 40 – Interne Benutzer auflisten
- UC 41 – Internen Benutzer anlegen

Geeignet für:
- CRUD-Grundstruktur für interne Benutzer
- Admin-Policy und -Routing
- Rollenauswahl beim Anlegen
- Actor-Erzeugung
- erste Backend- und Frontend-Integration

### Bundle B: Bearbeitung und Deaktivierung

- UC 42 – Internen Benutzer bearbeiten
- UC 43 – Internen Benutzer deaktivieren

Geeignet für:
- Stammdaten- und Rollenänderung
- Soft Delete mit Actor-Synchronisation
- Ticket-Zuweisungs-Handling bei Deaktivierung
- Audit-Vollständigkeit

### Bundle C: Vollständige Users-Domain

- UC 40–43 gemeinsam

Geeignet für:
- zusammenhängende Implementierung der gesamten Users-Domain
- integrierte QA von Liste bis Deaktivierung

---

## Verweise auf Wahrheitsquellen

- Domänenwissen: `docs/domain/01_miniworld.md`
- Fachliche Regeln: `docs/domain/02_business-rules.md`
- Entitäten / Felder / Beziehungen: `docs/domain/03_er.md`

---

## Hinweise zur Umsetzung

- Diese Domain-Datei dient der Orientierung und Orchestrierung.
- Die eigentliche Implementierungs-, Review- und QA-Arbeit soll über die einzelnen `by-use-case/` Dateien erfolgen.
- Es dürfen keine zusätzlichen Rollenregeln, Response-Felder oder Benutzerfelder erfunden werden, die in den Quellen nicht dokumentiert sind.
- Die API-Contracts in den `by-use-case/` Dateien sind aus den ursprünglichen Master-Dateien abgeleitet. Bei Widersprüchen gilt `docs/domain/`.
