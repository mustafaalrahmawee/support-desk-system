# UC-38 Eigenes Profil anzeigen

## 1. Use Case

**Ziel**  
Ein authentifizierter interner Benutzer sieht seine eigenen Profildaten.

**Primäre Akteure**  
Interner Benutzer

**Beteiligte Entitäten**  
Benutzerkonto

**Vorbedingungen**
- Der Benutzer ist authentifiziert.

**Trigger**  
Der Benutzer öffnet sein eigenes Profil.

**Hauptablauf**
1. Das System lädt die Profildaten des angemeldeten Benutzers.
2. Das System zeigt die eigenen Benutzerinformationen an.

**Nachbedingungen**
- Das eigene Profil ist sichtbar.

## 2. API-Contract

Umsetzbare Ableitung:

- `GET /api/auth/me` oder ein projektspezifischer Profil-Endpunkt.
- Authentifizierung: erforderlich über `Authorization: Bearer <token>`.
- Response bei Erfolg: eigene Profildaten des authentifizierten internen Benutzers.
- Response ohne Authentifizierung: unauthenticated.

Platzhalter, da nicht fachlich weiter spezifiziert:

- Die konkreten sichtbaren Profilfelder muessen aus bestehendem `internal_users`-Modell und Implementierung abgeleitet werden. Es duerfen keine zusätzlichen Profilfelder erfunden werden.

## 3. Backend-Architektur

- Route wird über Sanctum-Middleware geschützt.
- Geschützte Profil-Anfragen erwarten den Personal Access Token im Bearer-Header.
- Controller liest nur den aktuellen Benutzer aus dem Request-Kontext.
- Es werden ausschließlich eigene Profildaten zurückgegeben.
- Keine administrative Benutzerabfrage und keine fremden Benutzerprofile in diesem Use Case.
- Rollen oder Permissions nur zurückgeben, wenn dies bereits technisch oder fachlich für den aktuellen Benutzerkontext vorgesehen ist.

## 4. Backend-QA

- Mit gültigem Auth-Kontext Profil abrufen und eigene Benutzer-ID prüfen.
- Ohne Auth-Kontext Profil abrufen und unauthenticated prüfen.
- Prüfen, dass keine fremden Benutzerprofile über Parameter geladen werden können.
- Prüfen, dass keine nicht vorgesehenen sensiblen Felder in der Response enthalten sind.

## 5. Frontend-Architektur

- Profilanzeige als geschützte Page im `AuthenticatedLayout`.
- Daten über Auth Store oder Profil Store aus geschütztem API-Endpunkt mit Bearer Token laden.
- Loading-, Error- und Success-Zustände sichtbar abbilden.
- Keine Rollen- oder Statuslogik im Frontend erfinden.
- Bearbeiten-Aktion nur auf UC-39 verlinken, wenn diese Route umgesetzt ist.

## 6. Screen-Flow

1. Benutzer öffnet sein Profil.
2. Frontend lädt aktuelle Profildaten.
3. Während des Ladens wird ein neutraler Ladezustand angezeigt.
4. Bei Erfolg werden die eigenen Benutzerinformationen angezeigt.
5. Bei Auth-Fehler wird der Benutzer aus dem geschützten Bereich geführt.

## 7. UI-Regeln

- Eigene Profildaten klar und ruhig als geschützte Kontoinformationen darstellen.
- Keine administrativen Bearbeitungsfunktionen anzeigen.
- Keine nicht dokumentierten Felder, KPIs, Tabs oder Rollenverwaltungsbereiche ergänzen.
- Fehlerzustände müssen sichtbar und verständlich sein.

## 8. UI-Referenz

- Primäre erwartete Referenz: `docs/design-references/auth/uc38_profile_show.png`
- Globale Layout-Referenz: `docs/design-references/app-shell.png`

Wenn die Use-Case-Referenz noch fehlt, ist der zugehörige Design-Prompt unter `docs/design-prompts/auth/uc38_profile_show.md` zu verwenden.

## 9. Frontend-QA

- Profilseite als authentifizierter Benutzer öffnen.
- Ladezustand und erfolgreiche Datenanzeige prüfen.
- Browser-Refresh auf Profilseite prüfen.
- Auth-Token entfernen oder ablaufen lassen und unauthenticated-Verhalten prüfen.
- Sicherstellen, dass keine fremden Benutzer oder Admin-Funktionen sichtbar sind.
