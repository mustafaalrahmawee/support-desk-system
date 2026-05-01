# Auth Domain

## Zweck

Die Auth Domain beschreibt Anmeldung, Abmeldung und die Verwaltung des eigenen Profils fuer interne Benutzer des Smart Support Desk Systems.

Sie stellt sicher, dass interne fachliche Funktionen nur nach erfolgreicher Authentifizierung erreichbar sind und dass Login, Logout sowie Profiländerungen nachvollziehbar auditiert werden.

## Fachlicher Scope

Im Scope liegen:

- Authentifizierung interner Benutzer
- Beenden einer bestehenden Authentifizierung
- Anzeige eigener Profildaten
- Änderung fachlich erlaubter eigener Profildaten
- Auditierung von Login, Logout und Profiländerungen
- tokenbasierte API-Authentifizierung ueber Laravel Sanctum Personal Access Tokens

Nicht im Scope liegen:

- administrative Benutzerverwaltung
- Rollen- und Berechtigungszuordnung
- Aktivierung, Deaktivierung, Soft Delete oder Reaktivierung interner Benutzer
- Passwort-Reset oder Registrierung, sofern nicht in den dokumentierten Use Cases ergänzt

## Zugehörige Use Cases

- UC-36 Login: Ein interner Benutzer authentifiziert sich erfolgreich am System.
- UC-37 Logout: Ein authentifizierter interner Benutzer meldet sich vom System ab.
- UC-38 Eigenes Profil anzeigen: Ein authentifizierter interner Benutzer sieht seine eigenen Profildaten.
- UC-39 Eigenes Profil ändern: Ein authentifizierter interner Benutzer aktualisiert seine eigenen Profildaten.

## Empfohlene Reihenfolge

1. UC-36 Login
2. UC-38 Eigenes Profil anzeigen
3. UC-39 Eigenes Profil ändern
4. UC-37 Logout

Diese Reihenfolge macht die Authentifizierung zuerst verfuegbar, prueft danach den geschuetzten Benutzerkontext und ergänzt zuletzt Profiländerung und Abmeldung.

## Fachliche Abhängigkeiten

- `internal_users` repraesentieren interne Systembenutzer.
- Ein internes Benutzerkonto muss fuer UC-36 existieren und aktiv sein.
- Interne fachliche Funktionen duerfen nur durch authentifizierte interne Benutzer ausgeführt werden.
- Login, Logout und eigenes Profil geändert sind auditpflichtige Ereignisse.
- Tokens sind bei API-Authentifizierung polymorph an das authentifizierbare Modell gebunden, typischerweise an `internal_users`.
- Rollen und Permissions werden fachlich ueber Spatie Laravel Permission modelliert, aber in dieser Domain nicht administrativ verwaltet.

## Gemeinsame Backend-Bausteine

- Laravel Sanctum fuer geschuetzte API-Zugriffe mit Personal Access Tokens
- Auth-Routen fuer Login, Logout und aktuellen Benutzerkontext
- Form Requests fuer Login- und Profiländerungsvalidierung
- dünne Controller ohne verstreute Fachlogik
- Service fuer atomare Profiländerung mit Audit, falls mehrere fachliche Schritte zusammenhaengen
- Audit-Service oder gleichwertiger zentraler Baustein fuer Login, Logout und Profiländerung
- Sanctum-Middleware fuer geschuetzte Profil- und Logout-Endpunkte

## Gemeinsame Frontend-Bausteine

- `PublicLayout` fuer den Login-Screen
- `AuthenticatedLayout` fuer Profil- und Logout-nahe geschuetzte Bedienung
- Pinia Auth Store fuer Login, Logout und aktuellen Benutzer
- gemeinsames `useApiFetch()`-Composable fuer API-Aufrufe mit `Authorization: Bearer <token>` auf geschuetzten Requests
- Vuelidate fuer Login- und Profilformulare
- sichtbare Darstellung von Backend-Validierungsfehlern und globalen Auth-Fehlern
- keine erfundenen Rollen-, Status- oder Berechtigungsentscheidungen im Frontend

## Session-Bundles

- Bundle A: UC-36 Login und UC-38 Eigenes Profil anzeigen
- Bundle B: UC-39 Eigenes Profil ändern und UC-37 Logout

## Design-Referenzen

Globale Referenz:

- `docs/design-references/app-shell.png`

Erwartete Auth-Referenzen:

- `docs/design-references/auth/uc36_login.png`
- `docs/design-references/auth/uc37_logout.png`
- `docs/design-references/auth/uc38_profile_show.png`
- `docs/design-references/auth/uc39_profile_update.png`

Diese Bilder dienen nur als visuelle Referenz fuer Layout, Hierarchie, Abstände, Kartenstruktur, Header, Navigation und Formdarstellung. Fachliche Wahrheit bleibt in `docs/domain/`.

## Abgrenzung

Die Auth Domain erfindet keine zusätzlichen Auth-Funktionen wie Registrierung, Passwort vergessen, MFA, Session-Management-Uebersichten oder Admin-Benutzerverwaltung. Solche Funktionen duerfen nur umgesetzt oder gestaltet werden, wenn sie fachlich dokumentiert sind.
