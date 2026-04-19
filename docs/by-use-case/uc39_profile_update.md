# UC 39 – Profil bearbeiten

> Die API-Contracts in diesem Dokument sind aus den ursprünglichen Master-Dateien übernommen. Bei Widersprüchen gilt `docs/domain/`.

## 1. Use Case

### Ziel

Ein authentifizierter interner Benutzer ändert die eigenen erlaubten Profildaten.

### Akteure

- Interner Benutzer

### Vorbedingungen

- Der Benutzer ist authentifiziert.
- Der Benutzer bearbeitet das eigene Profil.
- Änderbare Felder sind fachlich erlaubt.

### Hauptablauf

1. Der authentifizierte interne Benutzer öffnet den Bearbeitungsmodus des eigenen Profils.
2. Das System zeigt die aktuellen Profilwerte an.
3. Der Benutzer ändert erlaubte Felder.
4. Das System validiert die formalen Eingaben.
5. Das System speichert die Änderungen.
6. Das System protokolliert die Profiländerung fachlich nachvollziehbar.
7. Das System zeigt die aktualisierten Profildaten bzw. den Erfolg an.

### Alternativabläufe / Fehlerfälle

#### A1: Benutzer ist nicht authentifiziert
- Das System liefert `401 Unauthenticated`.

#### A2: Formale Eingabe ungültig
- Das System liefert Validierungsfehler.

#### A3: Fachlicher Konflikt
- Zum Beispiel bereits vergebener Benutzername.
- Das UI muss einen Konfliktzustand darstellen.

### Nachbedingungen

- Erlaubte eigene Profildaten sind aktualisiert.
- Die Änderung ist auditierbar behandelt.

---

## 2. API-Contract

### Endpoint

- `PATCH /api/me`

### Authentifizierung

- erforderlich

### Request

```json
{
  "first_name": "Anna",
  "last_name": "Beispiel",
  "username": "anna.beispiel"
}
```

### Success Response

- Für diesen Endpoint ist in der Quell-Dokumentation für diesen Endpoint kein vollständiges Beispiel-Response-Objekt ausformuliert.
- Es gilt mindestens der projektweite Response-Standard mit erfolgreicher Rückmeldung.

### Failed Cases

- `401 Unauthenticated`

Zusätzliche UI-relevante Fehlerfälle aus den Master-Dateien:
- Validation Error
- Conflict State, z. B. Benutzername bereits vergeben

---

## 3. Backend-Architektur

### Route

- `PATCH /api/me`

### Middleware

- `auth:sanctum`

### FormRequest

- `UpdateOwnProfileRequest`
- nur formale Validierung
- relevante Felder laut API-Contract:
  - `first_name`
  - `last_name`
  - `username`

### Controller

- `ProfileController@update`

### Policy

- Endpoint betrifft nur das eigene Profil des aktuell authentifizierten Benutzers
- keine Bearbeitung fremder Benutzerkonten über diesen Use Case

### Action

- `UpdateOwnProfileAction`
- Aufgaben:
  - aktuell authentifizierten Benutzer laden
  - erlaubte Felder aktualisieren
  - fachliche Konflikte behandeln
  - Audit auslösen
  - aktualisierte Daten oder Erfolg zurückgeben

### Model / Datenbasis

- `internal_users`
- `audit_logs`

---

## 4. Backend-QA

### Mindestens testen

1. Profiländerung erfolgreich bei authentifiziertem Benutzer
2. Profiländerung ohne Authentifizierung schlägt mit `401` fehl
3. Benutzername-Konflikt wird fachlich korrekt behandelt
4. Audit-Eintrag für Profiländerung entsteht
5. geänderte Felder werden persistiert

---

## 5. Frontend-Architektur

### Pages

- `src/pages/auth/ProfileEditPage.vue`
- oder Edit-Modus innerhalb von `ProfilePage.vue`

### Komponentenregel

- Profil bearbeiten ist ein kompaktes Formular — direkt in der Page belassen.
- Wenn das Formular merklich wächst (viele Felder, komplexe Validierung), `ProfileEditForm.vue` auslagern.
- Nicht vorsorglich auslagern.

### Store-Funktionen

- `authStore.updateProfile(payload)`
- optional danach `authStore.fetchMe()`

### Validators

- Vuelidate für editierbare Felder

---

## 6. Screen-Flow

### Typischer Screen-Name

- `ProfileEditPage.vue`
- oder Profilseite mit eingebettetem Edit-Modus

### Sichtbare Hauptbereiche

- Formularfelder für erlaubte Profildaten
- Speichern-Button
- Abbrechen-Button
- Bereich für Feldfehler
- Bereich für globale Konflikt- oder Erfolgsnachricht

### Benutzeraktionen

- Felder bearbeiten
- speichern
- abbrechen

### Erwartete Frontend-Logik

- Vuelidate für Felder
- `authStore.updateProfile(payload)` aufrufen
- bei Erfolg Daten aktualisieren
- bei Konflikten passende Meldung zeigen

### UI-Zustände

- initial
- loading
- success
- validation error
- conflict
- unauthenticated

---

## 7. UI-Regeln

- bestehende Werte im Formular vorbelegen
- Speichern und Abbrechen klar trennen
- Feldfehler nah am Feld anzeigen
- globale Konfliktmeldung separat sichtbar halten
- ruhige, konsistente Formularoberfläche

---

## 8. Stitch-Prompt

```text
Erzeuge mit Claude Code unter Verwendung von Stitch MCP und Google Labs stitch-skills eine Vue.js Screen-Komponente mit Tailwind CSS.

Kontext:
Dies ist ein Screen zum Bearbeiten des eigenen Profils im Smart Support Desk System.

Wichtig:
- Nur UI
- Keine API-Integration
- Keine Store-Logik
- Keine Business-Logik
- Keine Berechtigungslogik erfinden
- Keine echte Formularvalidierung
- Responsive Layout
- Ruhige Admin-Oberfläche

Enthält:
- Formular mit vorbelegten Werten
- Felder für erlaubte Profildaten
- Speichern-Button
- Abbrechen-Button
- Bereich für Feldfehler
- Bereich für globale Konflikt- oder Erfolgsnachricht

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

1. Formular zeigt bestehende Werte an
2. Speichern löst Loading-State aus
3. Feldfehler werden sichtbar dargestellt
4. Konfliktmeldung wird sichtbar dargestellt
5. Erfolgsmeldung und aktualisierte Daten erscheinen
6. ohne Authentifizierung Weiterleitung zur Login-Seite
