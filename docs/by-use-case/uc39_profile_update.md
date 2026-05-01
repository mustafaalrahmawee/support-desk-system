# UC-39 Eigenes Profil ändern

## 1. Use Case

**Ziel**  
Ein authentifizierter interner Benutzer aktualisiert seine eigenen Profildaten.

**Primäre Akteure**  
Interner Benutzer

**Beteiligte Entitäten**  
Benutzerkonto, Audit-Log

**Vorbedingungen**
- Der Benutzer ist authentifiziert.

**Trigger**  
Der Benutzer speichert Änderungen an seinem Profil.

**Hauptablauf**
1. Der Benutzer ändert fachlich erlaubte Profildaten.
2. Das System validiert die Änderungen.
3. Das System speichert die Änderungen.
4. Der Vorgang wird auditiert.

**Nachbedingungen**
- Das eigene Profil ist aktualisiert.
- Die Profiländerung ist im Audit-Log nachvollziehbar dokumentiert.

## 2. API-Contract

Umsetzbare Ableitung:

- `PATCH /api/auth/me` oder ein projektspezifischer Profil-Update-Endpunkt.
- Authentifizierung: erforderlich über `Authorization: Bearer <token>`.
- Request: nur fachlich erlaubte Profilfelder.
- Response bei Erfolg: aktualisierte eigene Profildaten.
- Response bei Validierungsfehler: feldbezogene Validierungsfehler.
- Response ohne Authentifizierung: unauthenticated.

Platzhalter, da nicht fachlich weiter spezifiziert:

- Konkrete änderbare Felder sind nicht in `docs/domain/04_use-cases.md` definiert und muessen bei Implementierung aus bestehendem Datenmodell oder ergänzter Fachentscheidung abgeleitet werden.

## 3. Backend-Architektur

- Route wird über Sanctum-Middleware geschützt.
- Geschützte Profil-Update-Anfragen erwarten den Personal Access Token im Bearer-Header.
- Form Request erlaubt ausschließlich dokumentierte oder technisch bestehende, fachlich freigegebene Profilfelder.
- Controller aktualisiert nur den aktuell authentifizierten Benutzer.
- Profiländerung wird auditiert.
- Wenn mehrere fachliche Schritte zusammenhaengen, wird die Änderung in einer Transaktion über einen Service ausgeführt.
- Keine Rollen-, Permission-, Aktivstatus- oder Admin-Feldänderungen in diesem Use Case.

## 4. Backend-QA

- Mit gültigem Auth-Kontext erlaubte Profilfelder aktualisieren und Response prüfen.
- Ohne Auth-Kontext Update ausführen und unauthenticated prüfen.
- Ungültige Daten senden und feldbezogene Validierungsfehler prüfen.
- Versuch, nicht erlaubte Felder wie Rollen, Permissions oder Aktivstatus zu ändern, muss abgelehnt oder ignoriert werden.
- Prüfen, dass ein Audit-Eintrag fuer eigenes Profil geändert erzeugt wird.

## 5. Frontend-Architektur

- Profilbearbeitung als geschützte Page oder Formularzustand innerhalb der Profilseite umsetzen.
- Initiale Werte aus dem eigenen Profil laden.
- Formularvalidierung mit Vuelidate.
- API-Aufruf über gemeinsames `useApiFetch()`-Composable und Store-nahe Logik mit Bearer Token.
- Backend-Validierungsfehler feldnah anzeigen.
- Nach Erfolg lokalen Benutzerkontext aktualisieren.

## 6. Screen-Flow

1. Benutzer öffnet Profilbearbeitung.
2. Frontend lädt aktuelle eigene Profildaten.
3. Benutzer ändert erlaubte Felder.
4. Frontend validiert lokal und sendet Update.
5. Backend validiert, speichert und auditiert.
6. Frontend zeigt Erfolg und aktualisierte Profildaten.

## 7. UI-Regeln

- Nur fachlich erlaubte Profilfelder als editierbar anzeigen.
- Nicht änderbare Informationen, falls sichtbar, klar als read-only darstellen.
- Submit während laufender Anfrage deaktivieren.
- Erfolg, Validierungsfehler und globale Fehler sichtbar anzeigen.
- Keine Felder fuer Rollen, Permissions, Aktivstatus, Admin-Notizen oder fremde Benutzer ergänzen.

## 8. UI-Referenz

- Primäre erwartete Referenz: `docs/design-references/auth/uc39_profile_update.png`
- Globale Layout-Referenz: `docs/design-references/app-shell.png`

Wenn die Use-Case-Referenz noch fehlt, ist der zugehörige Design-Prompt unter `docs/design-prompts/auth/uc39_profile_update.md` zu verwenden.

## 9. Frontend-QA

- Profilbearbeitung als authentifizierter Benutzer öffnen.
- Erlaubte Felder ändern und erfolgreiche Speicherung prüfen.
- Ungültige Eingaben erzeugen und feldnahe Fehler prüfen.
- Nicht dokumentierte Felder im UI ausschließen.
- Browser-Refresh nach erfolgreicher Änderung ausführen und aktualisierte Werte prüfen.
- Ohne gültigen Auth-Kontext unauthenticated-Verhalten prüfen.
