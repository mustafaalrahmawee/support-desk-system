# UC 36 – Login

## 1. Use Case

### Ziel

Ein interner Benutzer meldet sich am System an.

### Akteure

- Interner Benutzer

### Vorbedingungen

- Der Benutzer besitzt ein internes Benutzerkonto.
- Der interne Benutzer ist aktiv.
- Die für den Login erforderlichen Zugangsdaten liegen vor.

### Hauptablauf

1. Der interne Benutzer öffnet den Login-Screen.
2. Der Benutzer gibt E-Mail und Passwort ein.
3. Das System validiert die formalen Eingaben.
4. Das System prüft die Zugangsdaten.
5. Das System prüft, ob der interne Benutzer aktiv ist.
6. Das System erzeugt den Authentifizierungskontext.
7. Das System gibt den authentifizierten Benutzerkontext zurück.
8. Das System protokolliert den Login fachlich nachvollziehbar.

### Alternativabläufe / Fehlerfälle

#### A1: Formale Eingabe ungültig

- Der Login wird nicht ausgeführt.
- Das System liefert einen Validierungsfehler.

#### A2: Zugangsdaten ungültig

- Der Login schlägt fehl.
- Das System liefert `401 Unauthenticated`.

#### A3: Benutzer deaktiviert oder nicht aktiv

- Die Anmeldung wird verweigert.
- Das System liefert `403 Forbidden`.

### Nachbedingungen

- Ein gültiger Authentifizierungskontext ist erzeugt.
- Der angemeldete interne Benutzer ist im Response enthalten.
- Der Login ist auditpflichtig behandelt.

---

## 2. API-Contract

### Endpoint

- `POST /api/login`

### Authentifizierung

- keine

### Request

```json
{
  "email": "agent@example.com",
  "password": "secret-password"
}
```

### Success Response

#### HTTP Status

- `200 OK`

#### Beispiel Response

```json
{
  "message": "Login erfolgreich.",
  "data": {
    "internal_user": {
      "id": 1,
      "first_name": "Anna",
      "last_name": "Beispiel",
      "email": "agent@example.com",
      "is_active": true,
      "roles": ["support_agent"]
    },
    "token": "plain-text-token-or-session-indicator"
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

- `POST /api/login`

### Middleware

- keine Auth-Middleware auf diesem Endpoint

### FormRequest

- `LoginRequest`
- nur formale Validierung
- erforderliche Felder:
  - `email`
  - `password`

### Controller

- `AuthController@login`
- dünn halten: Request entgegennehmen, Action aufrufen, Response zurückgeben

### Policy

- keine klassische Objekt-Policy für den Login-Endpunkt notwendig
- fachliche Zugangsprüfung erfolgt in der Action anhand des internen Benutzerkontos und Aktivstatus

### Action

- `LoginAction`
- Aufgaben:
  - internen Benutzer über Zugangsdaten ermitteln
  - Aktivstatus prüfen
  - Authentifizierungskontext / Token erzeugen
  - Audit auslösen
  - Response-Daten zurückgeben

### Model / Datenbasis

- `internal_users`
- `roles`
- `internal_user_roles`
- optional `personal_access_tokens`
- `audit_logs`

### Routing-Hinweis

- Teil der Auth-Gruppe
- keine Administrative-User-Route

---

## 4. Backend-QA

### Mindestens testen

1. Login erfolgreich mit gültigen Zugangsdaten
2. Login mit falschem Passwort
3. Login mit deaktiviertem internem Benutzer
4. Validierungsfehler bei fehlender E-Mail
5. Validierungsfehler bei fehlendem Passwort
6. Audit-Eintrag für erfolgreichen Login

### Typische Prüfungen

- HTTP-Status 200 / 401 / 403 / 422
- JSON-Struktur enthält `message` und `data`
- `data.internal_user` vorhanden bei Erfolg
- Token oder Session-Indikator vorhanden bei Erfolg
- Audit-Log wurde erzeugt

---

## 5. Frontend-Architektur

### Pages

- `src/pages/auth/LoginPage.vue`

### Komponentenregel

- Login ist ein kompakter Screen — Formular direkt in `LoginPage.vue` belassen.
- Nur auslagern, wenn die Page zu unübersichtlich wird.

### Store-Funktionen

- `authStore.login(payload)`

### Validators

- Vuelidate für:
  - E-Mail Pflichtfeld
  - Passwort Pflichtfeld

### Projektstruktur

- Auth-nahe Logik bleibt im `auth.store.ts`
- API-Kommunikation über zentralen Client
- keine API-Logik direkt ungeordnet in Komponenten

---

## 6. Screen-Flow

### Typischer Screen-Name

- `LoginPage.vue`

### Sichtbare Hauptbereiche

- Titel oder Branding
- Eingabefeld für E-Mail
- Eingabefeld für Passwort
- Login-Button
- Bereich für globale Fehlermeldung

### Benutzeraktionen

- E-Mail eingeben
- Passwort eingeben
- Login auslösen

### Erwartete Frontend-Logik

- Formular mit Vuelidate prüfen
- bei gültiger Eingabe `authStore.login(payload)` aufrufen
- bei Erfolg Auth-Zustand setzen
- Rolleninformationen laden oder aus Login-Response übernehmen
- anschließend in den geschützten Bereich navigieren

### UI-Zustände

- initial
- loading
- success
- validation error
- authentication error
- forbidden

---

## 7. UI-Regeln

- ruhige Admin-Oberfläche
- klare Formularstruktur
- Platz für Feldfehler und globale Fehlermeldung
- Button im Loading-State deaktivieren
- keine fachliche Auth-Logik im UI erfinden
- responsive, aber einfach und fokussiert

---

## 8. Frontend-Design-Prompt

```text
Erzeuge mit dem Frontend Design Plugin eine Vue.js Screen-Komponente mit Tailwind CSS.

Kontext:
Dies ist ein Login-Screen für das Smart Support Desk System.

Wichtig:
- Nur UI
- Keine API-Integration
- Keine Store-Logik
- Keine Business-Logik
- Keine Auth-Implementierung
- Keine echte Formularvalidierung
- Responsive Layout
- Moderne, ruhige Admin-Oberfläche

Benutzer:
Interner Benutzer

Screen-Ziel:
Anmeldung am System mit E-Mail und Passwort.

Enthält:
- Seitentitel
- E-Mail-Feld
- Passwort-Feld
- Login-Button
- Bereich für globale Fehlermeldung
- Platzhalter für Feldfehler

Berücksichtige UI-Zustände:
- loading
- error
- disabled
```

---

## 9. Frontend-QA

### Mindestens testen

1. Login-Formular sichtbar
2. Pflichtfelder werden im UI abgefangen
3. Loading-State beim Absenden sichtbar
4. globale Fehlermeldung bei Auth-Fehler sichtbar
5. Hinweis bei deaktiviertem Konto sichtbar
6. Navigation in geschützten Bereich bei Erfolg
