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
- Die Änderung ist nachvollziehbar protokolliert.

---

## 2. API-Contract

### Endpoint

`PATCH /api/me`

### Auth

Erfordert authentifizierten internen Benutzer.

### Request

```json
{
  "first_name": "Support",
  "last_name": "Agent",
  "username": "support.agent",
  "email": "agent@example.com"
}
```

### Validierung

- Es dürfen nur fachlich erlaubte Profildaten geändert werden.
- E-Mail und Benutzername müssen weiterhin eindeutig bleiben, sofern sie geändert werden.

### Erfolgsantwort

HTTP `200`

```json
{
  "message": "Vorgang erfolgreich.",
  "data": {
    "id": 1,
    "first_name": "Support",
    "last_name": "Agent",
    "username": "support.agent",
    "email": "agent@example.com",
    "is_active": true
  }
}
```

### Fehlerfälle

- HTTP `401`, wenn kein gültiger Authentifizierungszustand besteht.
- HTTP `422`, wenn formale Validierung fehlschlägt.
- HTTP `409`, wenn eine fachliche Eindeutigkeit verletzt wird.

---

## 3. Backend-Architektur

### Route

```php
Route::middleware('auth:sanctum')->patch('/me', UpdateOwnProfileController::class);
```

### Request

`App\Http\Requests\Auth\UpdateOwnProfileRequest`

Aufgabe:
- formale Eingaben validieren
- keine Fachlogik enthalten

### Controller

`App\Http\Controllers\Auth\UpdateOwnProfileController`

Aufgabe:
- authentifizierten Benutzer verwenden
- Request validieren lassen
- Action aufrufen
- JSON Response zurückgeben

### Action

`App\Actions\Auth\UpdateOwnProfileAction`

Aufgabe:
- fachlich erlaubte Profildaten des angemeldeten Benutzers aktualisieren
- keine fremden Benutzerprofile ändern
- Änderung auditieren

### Models

- `InternalUser`
- `AuditLog`

### Audit

Auditpflichtig:
- eigenes Profil geändert

Kontext:
- interner Benutzer

---

## 4. Backend-QA

### Success Case

**Request**
```bash
curl -i -X PATCH http://localhost:8000/api/me \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <TOKEN>" \
  -d '{"first_name":"Support","last_name":"Agent","username":"support.agent","email":"agent@example.com"}'
```

**Erwartung HTTP**
- `200`

**Erwartung JSON**
- aktualisierte Profildaten des angemeldeten Benutzers

**Erwartung Datenbank**
- nur eigenes Benutzerkonto ist geändert
- keine fremden Benutzerprofile sind geändert

**Erwartung Audit**
- eigenes Profil geändert ist protokolliert
- alte und neue Werte werden soweit fachlich sinnvoll gespeichert

### Validation Case

Ungültige oder fehlende formale Eingaben senden.

**Erwartung**
- HTTP `422`
- Feldfehler sichtbar in JSON

### Unauthorized Case

Request ohne gültigen Token ausführen.

**Erwartung**
- HTTP `401`

### Conflict Case

E-Mail oder Benutzername so ändern, dass eine fachliche Eindeutigkeit verletzt wird.

**Erwartung**
- HTTP `409` oder validierungsnahe Ablehnung
- keine inkonsistente Änderung

---

## 5. Frontend-Architektur

### Page

`src/pages/auth/ProfilePage.vue`

### Store

`useAuthStore().updateProfile(payload)`

### API-Aufruf

`PATCH /api/me`

---

## 6. Screen-Flow

1. Benutzer öffnet sein Profil.
2. UI lädt die aktuellen Profildaten.
3. Benutzer ändert erlaubte Felder.
4. Benutzer speichert Änderungen.
5. UI zeigt während des Speicherns einen Loading-/Disabled-Zustand.
6. Bei Erfolg werden aktualisierte Profildaten angezeigt.
7. Bei Fehlern werden Validierungs- oder Konfliktfehler angezeigt.

---

## 7. UI-Regeln

- Formular während `loading` deaktivieren.
- Backend-Validierungsfehler sichtbar anzeigen.
- Konfliktfehler sichtbar anzeigen.
- Success-State nach erfolgreicher Änderung anzeigen.
- Keine fremden Benutzerprofile änderbar machen.
- Keine Rollenlogik im Frontend erfinden.

---

## 8. UI-Referenz

- `docs/design-references/auth/uc39_profile_update.png`
- alternativ global: `docs/design-references/app-shell.png`

Wenn eine Referenz nicht vorhanden ist, gilt sie nicht als fachliche Quelle.

---

## 9. Frontend-QA

### Tests

1. Profiländerung ist nur authentifiziert erreichbar.
2. Aktuelle Profildaten werden vor dem Bearbeiten geladen.
3. Während Speichern ist das Formular deaktiviert oder sichtbar im Loading-Zustand.
4. Bei HTTP `422` werden Backend-Validierungsfehler angezeigt.
5. Bei fachlichem Konflikt wird ein sichtbarer Fehlerzustand angezeigt.
6. Nach erfolgreichem Speichern werden aktualisierte Profildaten angezeigt.
7. Success-State wird sichtbar dargestellt.
