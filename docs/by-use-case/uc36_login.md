# UC-36 Login

## 1. Use Case

**Ziel**  
Ein interner Benutzer authentifiziert sich erfolgreich am System.

**Primäre Akteure**  
Interner Benutzer

**Beteiligte Entitäten**  
Benutzerkonto, Personal Access Token, Audit-Log

**Vorbedingungen**
- Ein internes Benutzerkonto existiert.
- Das Benutzerkonto ist aktiv.

**Trigger**  
Ein interner Benutzer sendet seine Anmeldedaten.

**Hauptablauf**
1. Der Benutzer gibt seine Zugangsdaten ein.
2. Das System prüft die Authentifizierung.
3. Das System gewährt Zugriff auf interne Funktionen.
4. Das System erzeugt oder verwendet einen Personal Access Token für die API-Authentifizierung.
5. Der Login wird auditiert.

**Alternativen / Ausnahmen**
- Nicht authentifizierte Benutzer dürfen keine internen Fachfunktionen ausführen.

**Nachbedingungen**
- Der interne Benutzer ist authentifiziert.
- Der Login ist im Audit-Log nachvollziehbar dokumentiert.

## 2. API-Contract

Umsetzbare Ableitung:

- `POST /api/auth/login`
- Request: Zugangsdaten des internen Benutzers.
- Response bei Erfolg: authentifizierter Benutzerkontext und Token-Information für geschützte API-Anfragen.
- Response bei Fehler: generische Authentifizierungsfehlermeldung ohne Offenlegung, ob Benutzerkonto oder Passwort falsch war.

Platzhalter, da nicht fachlich weiter spezifiziert:

- Konkrete Feldnamen wie `email` und `password` sind technische Annahmen aus üblichen Laravel-Auth-Mustern und muessen bei Implementierung gegen bestehende Models/Migrations geprüft werden.

## 3. Backend-Architektur

- Route bleibt öffentlich erreichbar, erzeugt aber nur bei gültiger Authentifizierung einen internen Benutzerkontext.
- Controller bleibt dünn und delegiert Validierung an einen Form Request.
- Authentifizierung erfolgt über Laravel/Sanctum-kompatible Mechanismen.
- Nur aktive interne Benutzer duerfen erfolgreich authentifiziert werden.
- Login wird als auditpflichtiges Ereignis protokolliert.
- Der Personal Access Token wird fachlich konsistent dem internen Benutzer zugeordnet.

## 4. Backend-QA

- Mit gültigen Zugangsdaten eines aktiven internen Benutzers `POST /api/auth/login` ausführen und erfolgreichen Auth-Kontext prüfen.
- Mit falschem Passwort prüfen, dass kein Auth-Kontext entsteht.
- Mit nicht vorhandenem Benutzer prüfen, dass keine fachliche Detailinformation geleakt wird.
- Mit deaktiviertem Benutzer prüfen, dass Login nicht möglich ist.
- Mit soft-deleted Benutzer prüfen, dass Login nicht möglich ist.
- Nach erfolgreichem Login prüfen, dass ein Audit-Eintrag fuer Login existiert.
- Prüfen, dass der Token als `Authorization: Bearer <token>` für geschützte Endpunkte akzeptiert wird.

## 5. Frontend-Architektur

- Login als öffentliche Page mit `PublicLayout` umsetzen.
- Formularzustand lokal in der Page halten oder über Auth Store orchestrieren.
- API-Aufruf über Auth Store und gemeinsames `useApiFetch()`-Composable ausführen.
- Erfolgreich erhaltenen Personal Access Token im Auth Store für geschützte Requests bereitstellen.
- Formale Validierung mit Vuelidate abbilden.
- Backend-Validierungs- und Authentifizierungsfehler sichtbar im Formular anzeigen.
- Nach Erfolg zur Tickets-Seite navigieren, ohne fachliche Rollenlogik im Frontend zu erfinden.

## 6. Screen-Flow

1. Benutzer öffnet Login-Seite.
2. Benutzer gibt Zugangsdaten ein.
3. Frontend validiert Pflichtfelder.
4. Frontend sendet Login-Anfrage.
5. Bei Erfolg wird der Benutzer zur Tickets-Seite weitergeleitet.
6. Bei Fehler bleibt der Benutzer auf der Login-Seite und sieht eine Fehlermeldung.

## 7. UI-Regeln

- Pflichtfelder eindeutig kennzeichnen.
- Ladezustand am Submit-Button sichtbar machen.
- Submit während laufender Anfrage deaktivieren.
- Backend-Fehler oberhalb oder direkt an den betroffenen Feldern sichtbar anzeigen.
- Keine Links oder Bereiche fuer nicht dokumentierte Auth-Funktionen ergänzen.

## 8. UI-Referenz

- Primäre erwartete Referenz: `docs/design-references/auth/uc36_login.png`
- Globale Layout-Referenz: `docs/design-references/app-shell.png`

Wenn die Use-Case-Referenz noch fehlt, ist der zugehörige Design-Prompt unter `docs/design-prompts/auth/uc36_login.md` zu verwenden.

## 9. Frontend-QA

- Login-Seite auf Desktop und Mobile öffnen.
- Leeres Formular absenden und Pflichtfeldfehler prüfen.
- Falsche Zugangsdaten verwenden und globale Fehlermeldung prüfen.
- Gültige Zugangsdaten verwenden und Weiterleitung zur Tickets-Seite prüfen.
- Während des Requests Ladezustand und deaktivierten Submit prüfen.
- Sicherstellen, dass keine nicht dokumentierten Felder, Tabs oder Auth-Funktionen sichtbar sind.
