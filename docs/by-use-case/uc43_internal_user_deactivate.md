# UC 43 – Internen Benutzer deaktivieren

## 1. Use Case

### Ziel

Ein Admin deaktiviert einen internen Benutzer (Soft Delete). Dabei werden abhängige Objekte konsistent synchronisiert.

### Akteure

- Admin

### Vorbedingungen

- Der Benutzer ist authentifiziert.
- Der Benutzer besitzt die Rolle Admin.
- Der zu deaktivierende interne Benutzer existiert und ist aktiv.

### Hauptablauf

1. Der Admin löst die Deaktivierung eines internen Benutzers aus.
2. Das System prüft die Berechtigung.
3. Das System prüft, ob der Benutzer aktiv ist.
4. Das System deaktiviert den internen Benutzer (Soft Delete: `is_active = false`, `deleted_at` setzen).
5. Das System deaktiviert oder soft deleted den zugehörigen Actor.
6. Das System hebt bestehende Ticket-Zuweisungen des Benutzers auf (Tickets werden unassigned).
7. Das System protokolliert die Deaktivierung fachlich nachvollziehbar.
8. Das System bestätigt die Deaktivierung.

### Alternativabläufe / Fehlerfälle

#### A1: Benutzer ist nicht authentifiziert
- Das System liefert `401 Unauthenticated`.

#### A2: Benutzer hat keine Admin-Rolle
- Das System liefert `403 Forbidden`.

#### A3: Interner Benutzer nicht gefunden
- Das System liefert `404 Not Found`.

#### A4: Interner Benutzer ist bereits deaktiviert
- Das System liefert `409 Conflict` oder eine passende Fehlermeldung.

#### A5: Admin versucht, sich selbst zu deaktivieren
- Das System verhindert die Selbst-Deaktivierung.
- Das System liefert `409 Conflict` oder `422 Validation Error`.

### Nachbedingungen

- Der interne Benutzer ist deaktiviert und soft deleted.
- Der zugehörige Actor ist deaktiviert oder soft deleted.
- Alle Ticket-Zuweisungen des Benutzers sind aufgehoben.
- Die Deaktivierung ist auditpflichtig behandelt.

---

## 2. API-Contract

### Endpoint

- `DELETE /api/admin/internal-users/{id}`

### Authentifizierung

- erforderlich

### Request

- kein Body erforderlich

### Success Response

#### HTTP Status
- `200 OK`

#### Beispiel Response

```json
{
  "message": "Interner Benutzer erfolgreich deaktiviert."
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict` (bereits deaktiviert oder Selbst-Deaktivierung)

---

## 3. Backend-Architektur

### Route

- `DELETE /api/admin/internal-users/{id}`

### Middleware

- `auth:sanctum`

### Controller

- `InternalUserController@destroy`
- dünn halten: Request entgegennehmen, Policy prüfen, Action aufrufen, Response zurückgeben

### Policy

- `InternalUserPolicy@delete`
- nur Admins dürfen interne Benutzer deaktivieren
- Selbst-Deaktivierung wird in der Action oder Policy verhindert

### Action

- `DeactivateInternalUserAction`
- Aufgaben:
  - prüfen, ob der Benutzer nicht der aktuell authentifizierte Admin ist
  - prüfen, ob der Benutzer aktiv ist
  - `is_active = false` setzen
  - Soft Delete (`deleted_at` setzen)
  - zugehörigen Actor deaktivieren / soft deleten
  - Ticket-Zuweisungen des Benutzers aufheben (`assigned_internal_user_id = null`)
  - Audit auslösen
- `DB::transaction()` für atomare Operation (Benutzer + Actor + Ticket-Zuweisungen)

### Model / Datenbasis

- `internal_users`
- `actors`
- `tickets` (für Zuweisungs-Aufhebung)
- `audit_logs`

### Routing-Hinweis

- Teil der Admin-Gruppe
- nicht Teil der Auth-Routen

---

## 4. Backend-QA

### Mindestens testen

1. Deaktivierung erfolgreich bei aktivem Benutzer
2. Deaktivierung ohne Authentifizierung schlägt mit `401` fehl
3. Deaktivierung ohne Admin-Rolle schlägt mit `403` fehl
4. Deaktivierung eines nicht existierenden Benutzers liefert `404`
5. Deaktivierung eines bereits deaktivierten Benutzers liefert `409`
6. Selbst-Deaktivierung wird verhindert (`409` oder `422`)
7. `is_active` wird auf `false` gesetzt
8. `deleted_at` wird gesetzt (Soft Delete)
9. Zugehöriger Actor wird deaktiviert / soft deleted
10. Ticket-Zuweisungen des Benutzers werden aufgehoben
11. Audit-Eintrag für Deaktivierung entsteht
12. Atomarität: bei Fehler in einem Schritt wird alles zurückgerollt

### Typische Prüfungen

- HTTP-Status 200 / 401 / 403 / 404 / 409
- JSON-Struktur enthält `message`
- Datenbank: `internal_users.is_active = false`, `deleted_at IS NOT NULL`
- Datenbank: `actors.deleted_at IS NOT NULL` für zugehörigen Actor
- Datenbank: keine `tickets.assigned_internal_user_id` mehr auf diesen Benutzer
- Audit-Log wurde erzeugt

---

## 5. Frontend-Architektur

### Pages / UI-Orte

- kein eigener Screen nötig
- Deaktivierung wird aus der Benutzerliste oder der Bearbeitungsseite ausgelöst
- Bestätigungsdialog (Modal oder Confirm-Dialog)

### Store-Funktionen

- `usersStore.deactivateInternalUser(id)`

### Komponentenregel

- Deaktivierung ist kein eigener Screen — ein Aktionsbutton in der Liste oder Bearbeitungsseite mit Bestätigungsdialog.
- Bestätigungsdialog als einfache Komponente oder Browser-Confirm.

---

## 6. Screen-Flow

### Typische UI-Orte

- Benutzerliste (Aktionsspalte)
- Bearbeitungsseite (Aktionsbereich)

### Benutzeraktionen

- „Deaktivieren"-Button klicken
- Bestätigungsdialog bestätigen oder abbrechen

### Erwartete Frontend-Logik

- Bestätigungsdialog anzeigen mit klarem Hinweis auf Konsequenzen
- bei Bestätigung `usersStore.deactivateInternalUser(id)` aufrufen
- bei Erfolg Benutzerliste aktualisieren
- bei Fehler passende Meldung zeigen

### UI-Zustände

- initial
- confirm-dialog sichtbar
- loading (Deaktivierung läuft)
- success
- error (z. B. Selbst-Deaktivierung, bereits deaktiviert)

---

## 7. UI-Regeln

- Deaktivierung erfordert Bestätigung
- klarer Hinweis auf Konsequenzen im Bestätigungsdialog (Actor, Ticket-Zuweisungen)
- Button visuell als destruktive Aktion erkennbar (z. B. rot)
- Button im Loading-State deaktivieren
- Erfolgsmeldung nach Deaktivierung
- keine Deaktivierung des eigenen Kontos ermöglichen (Button ausblenden oder deaktivieren)

---

## 8. Frontend-Design-Prompt

```text
Erzeuge mit dem Frontend Design Plugin eine Vue.js Bestätigungsdialog-Komponente mit Tailwind CSS.

Kontext:
Dies ist ein Bestätigungsdialog für die Deaktivierung eines internen Benutzers im Smart Support Desk System.

Wichtig:
- Nur UI
- Keine API-Integration
- Keine Store-Logik
- Keine Business-Logik
- Responsive
- Ruhige Admin-Oberfläche

Enthält:
- Modal oder Confirm-Dialog
- Titel: Benutzer deaktivieren
- Hinweis auf Konsequenzen (Actor wird deaktiviert, Ticket-Zuweisungen werden aufgehoben)
- Name des betroffenen Benutzers
- Bestätigen-Button (destruktiv / rot)
- Abbrechen-Button
- Loading-State im Bestätigen-Button

Berücksichtige UI-Zustände:
- initial (Dialog offen)
- loading
- error
```

---

## 9. Frontend-QA

### Mindestens testen

1. Deaktivieren-Button in der Benutzerliste sichtbar
2. Klick öffnet Bestätigungsdialog
3. Bestätigungsdialog zeigt Konsequenzen
4. Abbrechen schließt Dialog ohne Aktion
5. Bestätigen löst Loading-State aus
6. Erfolg aktualisiert die Benutzerliste
7. Fehler bei Selbst-Deaktivierung wird angezeigt
8. Fehler bei bereits deaktiviertem Benutzer wird angezeigt
9. Deaktivieren-Button ist für eigenes Konto nicht sichtbar oder deaktiviert
