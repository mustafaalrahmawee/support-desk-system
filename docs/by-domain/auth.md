# Auth Domain

## Domain-Überblick

Die Domain **Auth** umfasst die Anmeldung, Abmeldung und die Verwaltung der **eigenen** Benutzeridentität eines internen Benutzers.

Fachlicher Scope:
- Login
- Logout
- eigenes Profil anzeigen
- eigenes Profil bearbeiten

Betroffene Hauptobjekte:
- `internal_users`
- `roles`
- `internal_user_roles`
- optional `personal_access_tokens`
- `audit_logs`

Nicht Teil dieser Domain:
- administrative Verwaltung anderer interner Benutzerkonten
- Rollenvergabe an andere Benutzer
- allgemeine Internal-Users-Administration

---

## Use-Case-Liste

- [UC 36 – Login](../by-use-case/uc36_login.md)
- [UC 37 – Logout](../by-use-case/uc37_logout.md)
- [UC 38 – Profil anzeigen](../by-use-case/uc38_profile_show.md)
- [UC 39 – Profil bearbeiten](../by-use-case/uc39_profile_update.md)

---

## Empfohlene Reihenfolge

1. UC 36 – Login
2. UC 37 – Logout
3. UC 38 – Profil anzeigen
4. UC 39 – Profil bearbeiten

Begründung:
- Login und Logout definieren den Authentifizierungskontext.
- Profil-Endpunkte betreffen den aktuell authentifizierten internen Benutzer.
- Profil anzeigen ist Grundlage für Profil bearbeiten.

---

## Abhängigkeiten

### Fachliche Abhängigkeiten

- Nur **aktive interne Benutzer** dürfen sich anmelden.
- Interne fachliche Funktionen setzen erfolgreiche Authentifizierung voraus.
- Ein Benutzer darf nur das **eigene Profil** anzeigen und bearbeiten.
- Login und Logout sind auditpflichtig.
- Profiländerungen sind fachlich relevante Änderungen und sollen auditierbar sein.

### Technische Abhängigkeiten

- Authentifizierung via Laravel Sanctum.
- Auth-Middleware für `POST /api/logout`, `GET /api/me`, `PATCH /api/me`.
- Zugriff auf `internal_users`, Rollenbeziehungen und optional Tokens.
- Audit-Erzeugung innerhalb der fachlich relevanten Actions.

---

## Gemeinsame Bausteine

### Backend

- Route-Gruppe: Auth
- Typische Endpunkte:
  - `POST /api/login`
  - `POST /api/logout`
  - `GET /api/me`
  - `PATCH /api/me`
- Typische Controller-Zuordnung:
  - `AuthController`
  - `ProfileController`
- Mögliche Actions:
  - `LoginAction`
  - `LogoutAction`
  - `ShowOwnProfileAction`
  - `UpdateOwnProfileAction`
- Relevante Models:
  - `InternalUser`
  - `Role`
  - `PersonalAccessToken` (optional)
  - `AuditLog`

### Frontend

- Pages:
  - `src/pages/auth/LoginPage.vue`
  - `src/pages/auth/ProfilePage.vue`
  - `src/pages/auth/ProfileEditPage.vue` oder eingebetteter Edit-Modus
- Store:
  - `src/stores/auth.store.ts`
- Mögliche Store-Funktionen:
  - `login(payload)`
  - `logout()`
  - `fetchMe()`
  - `updateProfile(payload)`
- Komponentenregel:
  - Solange ein Screen unter ~200 Zeilen bleibt und nur einen fachlichen Block hat, bleibt alles in der Page.
  - Nur auslagern, wenn die Page aus mehreren eigenständigen Blöcken mit eigenen Zuständen besteht oder zu unübersichtlich wird.
  - Nicht vorsorglich auslagern, nur weil man es theoretisch könnte.

---

## Session-Bundles

### Bundle A: Auth-Grundlage

- UC 36 – Login
- UC 37 – Logout

Geeignet für:
- Aufbau des Auth-Flows
- Sanctum-Anbindung
- Auth-Middleware und Token-Handling
- erste Backend- und Frontend-Integration

### Bundle B: Eigene Identität

- UC 38 – Profil anzeigen
- UC 39 – Profil bearbeiten

Geeignet für:
- geschützte Self-Service-Endpunkte
- `authStore.fetchMe()`
- Profilscreen und Profilformular

### Bundle C: Vollständige Auth-Domain

- UC 36–39 gemeinsam

Geeignet für:
- zusammenhängende Implementierung der gesamten Auth-Domain
- integrierte QA von Anmeldung bis Profilpflege

---

## Verweise auf Wahrheitsquellen

- Domänenwissen: `docs/domain/01_miniworld.md`
- Fachliche Regeln: `docs/domain/02_business-rules.md`
- Entitäten / Felder / Beziehungen: `docs/domain/03_er.md`

---

## Hinweise zur Umsetzung

- Diese Domain-Datei dient der Orientierung und Orchestrierung.
- Die eigentliche Implementierungs-, Review- und QA-Arbeit soll über die einzelnen `by-use-case/` Dateien erfolgen.
- Es dürfen keine zusätzlichen Rollenregeln, Response-Felder oder Profilfelder erfunden werden, die in den Quellen nicht dokumentiert sind.
- Die API-Contracts in den `by-use-case/` Dateien sind aus den ursprünglichen Master-Dateien übernommen. Bei Widersprüchen gilt `docs/domain/`.
