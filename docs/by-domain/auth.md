# Auth Domain

## Zweck

Diese Datei koordiniert die Auth-Domain des Smart Support Desk Systems.

Die Auth-Domain umfasst ausschließlich dokumentierte Vorgänge rund um:
- Login
- Logout
- eigenes Profil anzeigen
- eigenes Profil ändern

Fachliche Wahrheit bleibt in `docs/domain/`.
Diese Datei dient nur als Domain-Koordinator für die zugehörigen Use Cases.

---

## Fachlicher Scope

Die Auth-Domain behandelt interne Benutzer, die sich am System authentifizieren, vom System abmelden und ihr eigenes Profil einsehen oder ändern.

Interne fachliche Funktionen dürfen nur durch authentifizierte interne Benutzer ausgeführt werden. Login und Logout sind fachlich relevante Systemvorgänge. Ein authentifizierter interner Benutzer darf die eigenen Profildaten einsehen und bearbeiten, sofern keine fachliche Regel dagegen spricht.

---

## Zugehörige Use Cases

| Use Case | Datei | Titel |
|---|---|---|
| UC-36 | `docs/by-use-case/uc36_login.md` | Login |
| UC-37 | `docs/by-use-case/uc37_logout.md` | Logout |
| UC-38 | `docs/by-use-case/uc38_profile_show.md` | Eigenes Profil anzeigen |
| UC-39 | `docs/by-use-case/uc39_profile_update.md` | Eigenes Profil ändern |

---

## Empfohlene Reihenfolge

1. UC-36 Login
2. UC-38 Eigenes Profil anzeigen
3. UC-39 Eigenes Profil ändern
4. UC-37 Logout

Begründung:
- Login schafft die Authentifizierungsgrundlage.
- Profilanzeige und Profiländerung setzen Authentifizierung voraus.
- Logout beendet die bestehende Authentifizierung.

---

## Fachliche Abhängigkeiten

### Gemeinsame fachliche Grundlagen

- Ein internes Benutzerkonto existiert.
- Das Benutzerkonto besitzt einen Aktivstatus.
- Tokenbasierte API-Authentifizierung kann über `personal_access_tokens` erfolgen.
- Login, Logout und eigenes Profil ändern sind auditpflichtig.
- Nicht authentifizierte Benutzer dürfen keine internen Fachfunktionen ausführen.

### Entitäten

- `internal_users`
- `personal_access_tokens`
- `audit_logs`

### Rollen / Berechtigungen

- Authentifizierung ist für interne Benutzer vorgesehen.
- Profilanzeige und Profiländerung beziehen sich ausschließlich auf den angemeldeten Benutzer.
- Administrative Benutzerverwaltung gehört nicht zur Auth-Domain, sondern zur Users-Domain.

---

## Gemeinsame Backend-Bausteine

### Models

- `InternalUser`
-  Laravel Sanctum `PersonalAccessToken`
- `AuditLog`

### Actions

- `LoginAction`
- `LogoutAction`
- `ShowOwnProfileAction`
- `UpdateOwnProfileAction`

### Requests

- `LoginRequest`
- `UpdateOwnProfileRequest`

### Controller

- `LoginController`
- `LogoutController`
- `ShowOwnProfileController`
- `UpdateOwnProfileController`

### Policies

Für eigene Profilfunktionen kann die Autorisierung über Authentifizierung und fachlichen Kontext des angemeldeten Benutzers erfolgen. Administrative Benutzerverwaltung und Rollenpflege liegen außerhalb dieser Domain.

### Routen

- `POST /api/login`
- `POST /api/logout`
- `GET /api/me`
- `PATCH /api/me`

Geschützte Routen verwenden Auth-Middleware.

---

## Gemeinsame Frontend-Bausteine

### Store

- `src/stores/auth.store.ts` oder `src/stores/auth.store.js`

Benötigte Store-Funktionen:
- `login(payload)`
- `logout()`
- `fetchMe()`
- `updateProfile(payload)`

### Pages

- Login-Page
- Eigenes-Profil-Page

### Layouts

- Login nutzt ein öffentliches Layout.
- Profilfunktionen nutzen ein geschütztes Layout.

### UI-Zustände

Bei den Auth-Use-Cases sind mindestens folgende Zustände mitzudenken:

- loading
- error
- disabled
- success
- forbidden
- not found, sofern Backend dies für den konkreten Use Case liefert
- validation error

---

## Session-Bundles

### bundle-a — Auth-Grundlage

Use Cases:
- UC-36 Login
- UC-37 Logout

Ziel:
- Authentifizierung starten und beenden.

### bundle-b — Eigenes Profil

Use Cases:
- UC-38 Eigenes Profil anzeigen
- UC-39 Eigenes Profil ändern

Ziel:
- Profil des angemeldeten Benutzers einsehen und ändern.

### full — vollständige Auth-Domain

Use Cases:
- UC-36 Login
- UC-38 Eigenes Profil anzeigen
- UC-39 Eigenes Profil ändern
- UC-37 Logout

---

## Design-Referenzen

Globale Referenz:
- `docs/design-references/app-shell.png`

Auth-spezifische Referenzen:
- `docs/design-references/auth/uc36_login.png`
- `docs/design-references/auth/uc38_profile_show.png`
- `docs/design-references/auth/uc39_profile_update.png`

Wenn eine Referenzdatei nicht vorhanden ist, darf daraus keine fachliche Regel ergänzt werden.

---

## Abgrenzung

Nicht Teil der Auth-Domain:

- Benutzerkonto anlegen
- Benutzerkonto ändern
- Rollen zuweisen
- Benutzer deaktivieren, soft löschen oder reaktivieren
- Rollenverwaltung
- Passwort-Reset, sofern nicht separat dokumentiert
- Registrierung externer Benutzer

Diese Vorgänge gehören nicht zu den dokumentierten Auth-Use-Cases.
