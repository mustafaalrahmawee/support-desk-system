# UC-37 Logout

## 1. Use Case

**Ziel**  
Ein authentifizierter interner Benutzer meldet sich vom System ab.

**Primäre Akteure**  
Interner Benutzer

**Beteiligte Entitäten**  
Benutzerkonto, Personal Access Token, Audit-Log

**Vorbedingungen**
- Ein interner Benutzer ist authentifiziert.

**Trigger**  
Der Benutzer löst den Logout aus.


**Hauptablauf**
1. Das System beendet die aktuelle Authentifizierung.
2. Zugehörige Personal Access Tokens werden fachlich konsistent entzogen oder beendet.
3. Der Logout wird auditiert.

**Nachbedingungen**
- Der interne Benutzer ist nicht mehr authentifiziert.
- Der Logout ist im Audit-Log nachvollziehbar dokumentiert.

## 2. API-Contract

Umsetzbare Ableitung:

- `POST /api/auth/logout`
- Authentifizierung: erforderlich über `Authorization: Bearer <token>`.
- Request: keine fachlichen Nutzdaten erforderlich.
- Response bei Erfolg: Bestätigung, dass die Authentifizierung des internen Benutzers beendet und alle zugehörigen Personal Access Tokens entzogen wurden.
- Response ohne Authentifizierung: unauthenticated.

## 3. Backend-Architektur

- Route wird über Sanctum-Middleware geschützt.
- Controller ermittelt den aktuellen internen Benutzer aus dem Request-Kontext.
- Alle Personal Access Tokens des aktuellen internen Benutzers werden konsistent entzogen oder beendet.
- Logout wird auditiert, solange der Benutzerkontext vor Token-Entzug noch verfügbar ist.
- Controller enthält keine verstreute Token- oder Audit-Fachlogik; bei mehreren Schritten Service verwenden.

## 4. Backend-QA

- Mit gültigem Auth-Kontext Logout ausführen und erfolgreiche Response prüfen.
- Danach denselben Token gegen einen geschützten Endpunkt verwenden und Ablehnung prüfen.
- Falls mehrere Personal Access Tokens fuer denselben Benutzer existieren, prüfen, dass alle diese Tokens nach Logout abgelehnt werden.
- Ohne Auth-Kontext Logout ausführen und unauthenticated prüfen.
- Audit-Log auf Logout-Eintrag mit internem Benutzerkontext prüfen.

## 5. Frontend-Architektur

- Logout wird aus geschützter UI ausgelöst, z. B. über Header/User-Menü im `AuthenticatedLayout`.
- Auth Store ruft Logout-Endpunkt mit `Authorization: Bearer <token>` auf und leert lokalen Auth-Zustand.
- Nach Erfolg wird auf Login navigiert.
- Bei Fehler wird kein fachlich falscher eingeloggter Zustand angezeigt; lokale Auth-Daten muessen konsistent behandelt werden.

## 6. Screen-Flow

1. Authentifizierter Benutzer löst Logout aus.
2. Frontend sendet Logout-Anfrage.
3. Backend beendet Authentifizierung und auditiert den Vorgang.
4. Frontend leert lokalen Auth-Zustand.
5. Benutzer sieht wieder den öffentlichen Login-Zugang.

## 7. UI-Regeln

- Logout-Aktion klar als Abmelden benennen.
- Logout-Button zeigt während der laufenden Anfrage einen Ladezustand.
- Während laufender Logout-Anfrage keine mehrfachen Abmeldungen auslösen.
- Keine Abfrage zusätzlicher nicht dokumentierter Informationen.
- Nach Logout keine geschützten Benutzerdaten mehr anzeigen.

## 8. UI-Referenz

- Primäre erwartete Referenz: `docs/design-references/auth/uc37_logout.png`
- Globale Layout-Referenz: `docs/design-references/app-shell.png`

Wenn die Use-Case-Referenz noch fehlt, ist der zugehörige Design-Prompt unter `docs/design-prompts/auth/uc37_logout.md` zu verwenden.

## 9. Frontend-QA

- Als authentifizierter Benutzer Logout auslösen.
- Lade-/disabled-Zustand der Logout-Aktion prüfen.
- Weiterleitung auf öffentliche Login-Seite prüfen.
- Browser-Refresh nach Logout ausführen und sicherstellen, dass keine geschützte Seite sichtbar bleibt.
- Prüfen, dass kein Profil- oder Rolleninhalt nach Logout im UI stehen bleibt.
