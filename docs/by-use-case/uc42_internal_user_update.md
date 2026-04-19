# UC 42 – Internen Benutzer bearbeiten

> Die API-Contracts in diesem Dokument sind aus den ursprünglichen Master-Dateien abgeleitet. Bei Widersprüchen gilt `docs/domain/`.

## 1. Use Case

### Ziel

Ein Admin bearbeitet die Stammdaten und Rollenzuordnung eines bestehenden internen Benutzers.

### Akteure

- Admin

### Vorbedingungen

- Der Benutzer ist authentifiziert.
- Der Benutzer besitzt die Rolle Admin.
- Der zu bearbeitende interne Benutzer existiert und ist nicht soft deleted.

### Hauptablauf

1. Der Admin öffnet die Bearbeitungsansicht eines internen Benutzers.
2. Das System lädt die aktuellen Stammdaten und Rollen des Benutzers.
3. Der Admin ändert erlaubte Felder (Vorname, Nachname, Benutzername, E-Mail, optional Passwort, Rollen).
4. Das System validiert die formalen Eingaben.
5. Das System prüft Eindeutigkeit von Benutzername und E-Mail.
6. Das System speichert die Änderungen.
7. Das System aktualisiert die Rollenzuordnung, falls geändert.
8. Das System protokolliert die Änderung fachlich nachvollziehbar.
9. Das System gibt den aktualisierten Benutzer zurück.

### Alternativabläufe / Fehlerfälle

#### A1: Benutzer ist nicht authentifiziert
- Das System liefert `401 Unauthenticated`.

#### A2: Benutzer hat keine Admin-Rolle
- Das System liefert `403 Forbidden`.

#### A3: Interner Benutzer nicht gefunden
- Das System liefert `404 Not Found`.

#### A4: Formale Eingabe ungültig
- Das System liefert Validierungsfehler (`422`).

#### A5: Benutzername oder E-Mail bereits vergeben
- Das System liefert einen Validierungs- oder Konfliktfehler.

### Nachbedingungen

- Die Stammdaten des internen Benutzers sind aktualisiert.
- Die Rollenzuordnung ist bei Bedarf aktualisiert.
- Die Änderung ist auditpflichtig behandelt.

---

## 2. API-Contract

### Endpoint

- `PATCH /api/admin/internal-users/{id}`

### Authentifizierung

- erforderlich

### Request

```json
{
  "first_name": "Max",
  "last_name": "Mustermann",
  "username": "max.mustermann",
  "email": "max@example.com",
  "password": "neues-passwort",
  "password_confirmation": "neues-passwort",
  "roles": ["support_agent", "contract_manager"]
}
```

Alle Felder sind optional (PATCH-Semantik). Nur übermittelte Felder werden geändert. Wird `password` übermittelt, ist `password_confirmation` erforderlich. Wird `roles` übermittelt, wird die Rollenzuordnung vollständig ersetzt (Sync).

### Success Response

#### HTTP Status
- `200 OK`

#### Beispiel Response

```json
{
  "message": "Interner Benutzer erfolgreich aktualisiert.",
  "data": {
    "id": 5,
    "first_name": "Max",
    "last_name": "Mustermann",
    "username": "max.mustermann",
    "email": "max@example.com",
    "is_active": true,
    "roles": ["support_agent", "contract_manager"],
    "created_at": "2026-04-18T10:00:00Z",
    "updated_at": "2026-04-18T14:00:00Z"
  }
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `422 Validation Error`

---

## 3. Backend-Architektur

### Route

- `PATCH /api/admin/internal-users/{id}`

### Middleware

- `auth:sanctum`

### FormRequest

- `UpdateInternalUserRequest`
- nur formale Validierung
- alle Felder optional (PATCH-Semantik)
- relevante Felder:
  - `first_name`
  - `last_name`
  - `username` (unique, ignore own id)
  - `email` (unique, ignore own id)
  - `password` (optional, mit `password_confirmation`)
  - `roles` (Array, jeder Wert muss in `roles.name` existieren)

### Controller

- `InternalUserController@update`
- dünn halten: Request entgegennehmen, Policy prüfen, Action aufrufen, Response zurückgeben

### Policy

- `InternalUserPolicy@update`
- nur Admins dürfen interne Benutzer bearbeiten

### Action

- `UpdateInternalUserAction`
- Aufgaben:
  - internen Benutzer laden
  - erlaubte Felder aktualisieren
  - Passwort hashen, falls übermittelt
  - Rollenzuordnung synchronisieren, falls übermittelt
  - Audit auslösen (mit alten und neuen Werten)
  - aktualisierten Benutzer mit Rollen zurückgeben

### Model / Datenbasis

- `internal_users`
- `roles`
- `internal_user_roles`
- `audit_logs`

### Routing-Hinweis

- Teil der Admin-Gruppe
- nicht Teil der Auth-Routen

---

## 4. Backend-QA

### Mindestens testen

1. Bearbeitung erfolgreich mit gültigen Daten
2. Bearbeitung ohne Authentifizierung schlägt mit `401` fehl
3. Bearbeitung ohne Admin-Rolle schlägt mit `403` fehl
4. Bearbeitung eines nicht existierenden Benutzers liefert `404`
5. Validierungsfehler bei bereits vergebenem Benutzernamen (anderer Benutzer)
6. Validierungsfehler bei bereits vergebener E-Mail (anderer Benutzer)
7. Benutzername bleibt erlaubt, wenn er dem eigenen Benutzer gehört
8. Passwort wird gehasht gespeichert, wenn übermittelt
9. Rollenzuordnung wird korrekt synchronisiert
10. Audit-Eintrag für Benutzeränderung entsteht (mit alten und neuen Werten)
11. Nur übermittelte Felder werden geändert (PATCH-Semantik)

### Typische Prüfungen

- HTTP-Status 200 / 401 / 403 / 404 / 422
- JSON-Struktur enthält `message` und `data`
- `data` enthält aktualisierten Benutzer mit Rollen
- Datenbank enthält aktualisierte Werte
- Audit-Log enthält alte und neue Werte

---

## 5. Frontend-Architektur

### Pages

- `src/pages/users/InternalUserEditPage.vue`

### Komponentenregel

- Bearbeitungsformular ist ein kompakter Screen — Formular direkt in der Page belassen.
- Nur auslagern, wenn die Page zu unübersichtlich wird.

### Store-Funktionen

- `usersStore.fetchInternalUser(id)`
- `usersStore.updateInternalUser(id, payload)`
- `usersStore.fetchRoles()` (für Rollenauswahl)

### Validators

- Vuelidate für editierbare Felder
- Passwort-Felder nur validieren, wenn ausgefüllt

### Projektstruktur

- Benutzer-Logik im `users.store.ts`
- API-Kommunikation über zentralen Client
- keine API-Logik direkt in Komponenten

---

## 6. Screen-Flow

### Typischer Screen-Name

- `InternalUserEditPage.vue`

### Sichtbare Hauptbereiche

- Seitentitel (mit Benutzername oder Name)
- Formularfelder: Vorname, Nachname, Benutzername, E-Mail
- optionale Passwort-Felder (Passwort, Passwort-Bestätigung)
- Rollenauswahl (Checkboxen oder Multi-Select, vorbelegt)
- Aktivstatus-Anzeige (nicht editierbar hier, Deaktivierung über UC 43)
- Speichern-Button
- Abbrechen-Button
- Bereich für Feldfehler
- Bereich für globale Fehlermeldung oder Erfolgsmeldung

### Benutzeraktionen

- Felder bearbeiten
- Rollen ändern
- optional neues Passwort setzen
- Speichern
- Abbrechen (zurück zur Liste)

### Erwartete Frontend-Logik

- beim Öffnen `usersStore.fetchInternalUser(id)` und `usersStore.fetchRoles()` aufrufen
- bestehende Werte in Formular vorbelegen
- Vuelidate für Felder
- `usersStore.updateInternalUser(id, payload)` aufrufen
- bei Erfolg Daten aktualisieren und Erfolgsmeldung zeigen
- bei Konflikten passende Meldung zeigen

### UI-Zustände

- loading (Daten laden)
- initial (Formular bereit)
- saving (Speichervorgang)
- success
- validation error
- conflict
- not found
- forbidden
- unauthenticated

---

## 7. UI-Regeln

- bestehende Werte im Formular vorbelegen
- Passwort-Felder leer lassen — nur füllen, wenn Passwort geändert werden soll
- Speichern und Abbrechen klar trennen
- Feldfehler nah am Feld anzeigen
- globale Fehlermeldung separat sichtbar halten
- Aktivstatus anzeigen, aber nicht über dieses Formular änderbar
- ruhige, konsistente Formularoberfläche
- Button im Loading-State deaktivieren

---

## 8. Stitch-Prompt

```text
Erzeuge mit Claude Code unter Verwendung von Stitch MCP und Google Labs stitch-skills eine Vue.js Screen-Komponente mit Tailwind CSS.

Kontext:
Dies ist ein Formular zur Bearbeitung eines bestehenden internen Benutzers im Smart Support Desk System.

Wichtig:
- Nur UI
- Keine API-Integration
- Keine Store-Logik
- Keine Business-Logik
- Keine Berechtigungslogik erfinden
- Keine echte Formularvalidierung
- Responsive Layout
- Moderne, ruhige Admin-Oberfläche

Benutzer:
Admin

Screen-Ziel:
Stammdaten und Rollenzuordnung eines bestehenden internen Benutzers bearbeiten.

Enthält:
- Seitentitel mit Benutzername
- Felder: Vorname, Nachname, Benutzername, E-Mail (vorbelegt)
- Optionale Passwort-Felder (Passwort, Passwort-Bestätigung)
- Rollenauswahl (Checkboxen, vorbelegt)
- Aktivstatus-Anzeige (nicht editierbar)
- Speichern-Button
- Abbrechen-Button
- Bereich für Feldfehler
- Bereich für globale Fehlermeldung oder Erfolgsmeldung

Berücksichtige UI-Zustände:
- loading
- validation error
- conflict
- success
- not found
- disabled
```

---

## 9. Frontend-QA

### Mindestens testen

1. Formular zeigt bestehende Werte korrekt vorbelegt an
2. Rollen sind vorbelegt dargestellt
3. Speichern löst Loading-State aus
4. Feldfehler werden sichtbar dargestellt
5. Konfliktmeldung bei bestehendem Benutzernamen oder E-Mail sichtbar
6. Erfolgsmeldung und aktualisierte Daten erscheinen
7. Passwort-Felder optional — leere Felder ändern Passwort nicht
8. Abbrechen navigiert zur Benutzerliste
9. Not-Found-State bei nicht existierendem Benutzer
10. Forbidden-State bei fehlender Berechtigung
