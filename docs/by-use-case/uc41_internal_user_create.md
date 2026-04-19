# UC 41 – Internen Benutzer anlegen

> Die API-Contracts in diesem Dokument sind aus den ursprünglichen Master-Dateien abgeleitet. Bei Widersprüchen gilt `docs/domain/`.

## 1. Use Case

### Ziel

Ein Admin legt einen neuen internen Benutzer mit Stammdaten und Rollenzuordnung an.

### Akteure

- Admin

### Vorbedingungen

- Der Benutzer ist authentifiziert.
- Der Benutzer besitzt die Rolle Admin.

### Hauptablauf

1. Der Admin öffnet das Formular zur Neuanlage eines internen Benutzers.
2. Der Admin gibt Stammdaten ein: Vorname, Nachname, Benutzername, E-Mail, Passwort.
3. Der Admin wählt eine oder mehrere Rollen aus.
4. Das System validiert die formalen Eingaben.
5. Das System prüft Eindeutigkeit von Benutzername und E-Mail.
6. Das System legt den internen Benutzer an.
7. Das System erzeugt den zugehörigen Actor.
8. Das System ordnet die gewählten Rollen zu.
9. Das System protokolliert die Anlage fachlich nachvollziehbar.
10. Das System gibt den angelegten Benutzer zurück.

### Alternativabläufe / Fehlerfälle

#### A1: Benutzer ist nicht authentifiziert
- Das System liefert `401 Unauthenticated`.

#### A2: Benutzer hat keine Admin-Rolle
- Das System liefert `403 Forbidden`.

#### A3: Formale Eingabe ungültig
- Das System liefert Validierungsfehler (`422`).

#### A4: Benutzername oder E-Mail bereits vergeben
- Das System liefert einen Validierungs- oder Konfliktfehler.

### Nachbedingungen

- Ein neuer interner Benutzer ist angelegt.
- Der Benutzer besitzt die zugeordneten Rollen.
- Ein zugehöriger Actor ist erzeugt.
- Die Anlage ist auditpflichtig behandelt.

---

## 2. API-Contract

### Endpoint

- `POST /api/admin/internal-users`

### Authentifizierung

- erforderlich

### Request

```json
{
  "first_name": "Max",
  "last_name": "Mustermann",
  "username": "max.mustermann",
  "email": "max@example.com",
  "password": "sicheres-passwort",
  "password_confirmation": "sicheres-passwort",
  "roles": ["support_agent"]
}
```

### Success Response

#### HTTP Status
- `201 Created`

#### Beispiel Response

```json
{
  "message": "Interner Benutzer erfolgreich angelegt.",
  "data": {
    "id": 5,
    "first_name": "Max",
    "last_name": "Mustermann",
    "username": "max.mustermann",
    "email": "max@example.com",
    "is_active": true,
    "roles": ["support_agent"],
    "created_at": "2026-04-18T10:00:00Z",
    "updated_at": "2026-04-18T10:00:00Z"
  }
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `422 Validation Error`

---

## 3. Backend-Architektur

### Route

- `POST /api/admin/internal-users`

### Middleware

- `auth:sanctum`

### FormRequest

- `CreateInternalUserRequest`
- nur formale Validierung
- erforderliche Felder:
  - `first_name`
  - `last_name`
  - `username` (unique)
  - `email` (unique)
  - `password`
  - `password_confirmation`
  - `roles` (Array, jeder Wert muss in `roles.name` existieren)

### Controller

- `InternalUserController@store`
- dünn halten: Request entgegennehmen, Policy prüfen, Action aufrufen, Response zurückgeben

### Policy

- `InternalUserPolicy@create`
- nur Admins dürfen interne Benutzer anlegen

### Action

- `CreateInternalUserAction`
- Aufgaben:
  - internen Benutzer anlegen
  - Passwort hashen
  - Rollen zuordnen
  - Actor erzeugen
  - Audit auslösen
  - angelegten Benutzer mit Rollen zurückgeben
- `DB::transaction()` für atomare Operation (Benutzer + Actor + Rollen)

### Model / Datenbasis

- `internal_users`
- `roles`
- `internal_user_roles`
- `actors`
- `audit_logs`

### Routing-Hinweis

- Teil der Admin-Gruppe
- nicht Teil der Auth-Routen

---

## 4. Backend-QA

### Mindestens testen

1. Anlage erfolgreich mit gültigen Daten und Rolle
2. Anlage ohne Authentifizierung schlägt mit `401` fehl
3. Anlage ohne Admin-Rolle schlägt mit `403` fehl
4. Validierungsfehler bei fehlenden Pflichtfeldern
5. Validierungsfehler bei bereits vergebenem Benutzernamen
6. Validierungsfehler bei bereits vergebener E-Mail
7. Validierungsfehler bei ungültiger Rolle
8. Passwort wird gehasht gespeichert
9. Actor wird automatisch erzeugt
10. Rollenzuordnung wird korrekt gespeichert
11. Audit-Eintrag für Benutzeranlage entsteht

### Typische Prüfungen

- HTTP-Status 201 / 401 / 403 / 422
- JSON-Struktur enthält `message` und `data`
- `data` enthält angelegten Benutzer mit Rollen
- Datenbank enthält neuen `internal_users`-Eintrag
- Datenbank enthält neuen `actors`-Eintrag
- Datenbank enthält `internal_user_roles`-Einträge
- Audit-Log wurde erzeugt

---

## 5. Frontend-Architektur

### Pages

- `src/pages/users/InternalUserCreatePage.vue`

### Komponentenregel

- Anlageformular ist ein kompakter Screen — Formular direkt in der Page belassen.
- Nur auslagern, wenn die Page zu unübersichtlich wird.

### Store-Funktionen

- `usersStore.createInternalUser(payload)`
- `usersStore.fetchRoles()` (für Rollenauswahl)

### Validators

- Vuelidate für:
  - Vorname Pflichtfeld
  - Nachname Pflichtfeld
  - Benutzername Pflichtfeld
  - E-Mail Pflichtfeld, E-Mail-Format
  - Passwort Pflichtfeld, Mindestlänge
  - Passwort-Bestätigung muss übereinstimmen
  - mindestens eine Rolle ausgewählt

### Projektstruktur

- Benutzer-Logik im `users.store.ts`
- API-Kommunikation über zentralen Client
- keine API-Logik direkt in Komponenten

---

## 6. Screen-Flow

### Typischer Screen-Name

- `InternalUserCreatePage.vue`

### Sichtbare Hauptbereiche

- Seitentitel
- Formularfelder: Vorname, Nachname, Benutzername, E-Mail, Passwort, Passwort-Bestätigung
- Rollenauswahl (Checkboxen oder Multi-Select)
- Speichern-Button
- Abbrechen-Button
- Bereich für Feldfehler
- Bereich für globale Fehlermeldung

### Benutzeraktionen

- Felder ausfüllen
- Rollen auswählen
- Speichern
- Abbrechen (zurück zur Liste)

### Erwartete Frontend-Logik

- Rollen beim Öffnen laden
- Formular mit Vuelidate prüfen
- bei gültiger Eingabe `usersStore.createInternalUser(payload)` aufrufen
- bei Erfolg zur Benutzerliste navigieren oder Erfolgsmeldung zeigen
- bei Validierungsfehler oder Konflikten Meldung anzeigen

### UI-Zustände

- initial
- loading
- success
- validation error
- conflict (Benutzername/E-Mail vergeben)
- forbidden
- unauthenticated

---

## 7. UI-Regeln

- ruhige Admin-Oberfläche
- klare Formularstruktur
- Platz für Feldfehler nah am jeweiligen Feld
- globale Fehlermeldung separat sichtbar
- Rollenauswahl klar und übersichtlich
- Button im Loading-State deaktivieren
- Speichern und Abbrechen klar trennen
- keine fachliche Logik im UI erfinden

---

## 8. Stitch-Prompt

```text
Erzeuge mit Claude Code unter Verwendung von Stitch MCP und Google Labs stitch-skills eine Vue.js Screen-Komponente mit Tailwind CSS.

Kontext:
Dies ist ein Formular zur Neuanlage eines internen Benutzers im Smart Support Desk System.

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
Neuen internen Benutzer mit Stammdaten und Rollenzuordnung anlegen.

Enthält:
- Seitentitel
- Felder: Vorname, Nachname, Benutzername, E-Mail, Passwort, Passwort-Bestätigung
- Rollenauswahl (Checkboxen oder Multi-Select)
- Speichern-Button
- Abbrechen-Button
- Bereich für Feldfehler
- Bereich für globale Fehlermeldung

Berücksichtige UI-Zustände:
- loading
- validation error
- conflict
- success
- disabled
```

---

## 9. Frontend-QA

### Mindestens testen

1. Formular ist sichtbar mit allen Feldern
2. Rollenauswahl wird geladen und angezeigt
3. Pflichtfelder werden im UI abgefangen
4. Passwort-Bestätigung wird geprüft
5. Loading-State beim Absenden sichtbar
6. Validierungsfehler bei Feldern sichtbar
7. Konfliktmeldung bei bestehendem Benutzernamen oder E-Mail sichtbar
8. Erfolg führt zur Benutzerliste oder zeigt Erfolgsmeldung
9. Abbrechen navigiert zur Benutzerliste
10. Forbidden-State bei fehlender Berechtigung
