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
4. Falls tokenbasierte API-Authentifizierung verwendet wird, wird ein Token erzeugt oder verwendet.
5. Der Login wird auditiert.

**Alternativen / Ausnahmen**
- Nicht authentifizierte Benutzer dürfen keine internen Fachfunktionen ausführen.
- Ist das Benutzerkonto nicht aktiv, darf kein Zugriff auf interne Funktionen gewährt werden.

**Nachbedingungen**
- Der interne Benutzer ist authentifiziert.
- Der Login ist nachvollziehbar protokolliert.

---

## 2. API-Contract

### Endpoint

`POST /api/login`

### Auth

Öffentlich, da der Benutzer noch nicht authentifiziert ist.

### Request

```json
{
  "email": "agent@example.com",
  "password": "secret-password"
}
```

### Validierung

- `email` ist erforderlich.
- `password` ist erforderlich.

### Erfolgsantwort

HTTP `200`

```json
{
  "message": "Vorgang erfolgreich.",
  "data": {
    "internal_user": {
      "id": 1,
      "first_name": "Support",
      "last_name": "Agent",
      "username": "support.agent",
      "email": "agent@example.com",
      "is_active": true
    },
    "token": "plain-text-token"
  }
}
```

### Fehlerfälle

- HTTP `422`, wenn formale Eingaben fehlen oder ungültig sind.
- HTTP `401`, wenn die Authentifizierung fehlschlägt.
- HTTP `403`, wenn das Benutzerkonto nicht aktiv ist.

---

## 3. Backend-Architektur

### Route

```php
Route::post('/login', LoginController::class);
```

### Request

`App\Http\Requests\Auth\LoginRequest`

Aufgabe:
- formale Eingaben validieren
- keine Fachlogik enthalten

### Controller

`App\Http\Controllers\Auth\LoginController`

Aufgabe:
- Request validieren lassen
- Action aufrufen
- JSON Response zurückgeben

### Action

`App\Actions\Auth\LoginAction`

Aufgabe:
- Authentifizierung prüfen
- aktiven Benutzerzustand berücksichtigen
- bei tokenbasierter API-Authentifizierung Token erzeugen oder verwenden
- Login auditieren

### Models

- `InternalUser`
- `AuditLog`
- optional Laravel Sanctum `PersonalAccessToken`

### Audit

Auditpflichtig:
- Login ausgelöst

Kontext:
- interner Benutzer, wenn Authentifizierung erfolgreich einem Benutzer zugeordnet werden kann

---

## 4. Backend-QA

### Success Case

**Request**
```bash
curl -i -X POST http://localhost:8000/api/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"email":"agent@example.com","password":"secret-password"}'
```

**Erwartung HTTP**
- `200`

**Erwartung JSON**
- `message`
- `data.internal_user`
- `data.token`, falls tokenbasierte API-Authentifizierung verwendet wird

**Erwartung Datenbank**
- bei tokenbasierter API-Authentifizierung ist ein Token fachlich konsistent erzeugt oder verwendet

**Erwartung Audit**
- Login ist als auditpflichtiger Vorgang protokolliert

### Validation Case

Leere oder unvollständige Zugangsdaten senden.

**Erwartung**
- HTTP `422`
- Feldfehler für fehlende Pflichtfelder

### Unauthorized Case

Falsche Zugangsdaten senden.

**Erwartung**
- HTTP `401`
- kein Zugriff auf interne Funktionen

### Forbidden Case

Login mit deaktiviertem Benutzerkonto ausführen.

**Erwartung**
- HTTP `403`
- kein Zugriff auf interne Funktionen

---

## 5. Frontend-Architektur

### Page

`src/pages/auth/LoginPage.vue`

### Store

`useAuthStore().login(payload)`

### API-Aufruf

`POST /api/login`

### Nach Erfolg

- Auth-Daten speichern
- geschützte interne Anwendung erreichbar machen

---

## 6. Screen-Flow

1. Benutzer öffnet Login-Seite.
2. Benutzer gibt E-Mail und Passwort ein.
3. Benutzer sendet Formular.
4. UI zeigt während des Requests einen Loading-Zustand.
5. Bei Erfolg wird der Benutzer in den geschützten Bereich geführt.
6. Bei Fehler werden Validierungs- oder Authentifizierungsfehler angezeigt.

---

## 7. UI-Regeln

- Eingaben während `loading` deaktivieren.
- Backend-Validierungsfehler sichtbar anzeigen.
- Fehlgeschlagene Authentifizierung sichtbar anzeigen.
- Deaktiviertes Konto sichtbar als Fehlerzustand behandeln.
- Keine Rollenlogik im Frontend erfinden.

---

## 8. UI-Referenz

- `docs/design-references/auth/uc36_login.png`

Wenn die Referenz nicht vorhanden ist, gilt sie nicht als fachliche Quelle.

---

## 9. Frontend-QA

### Tests

1. Login-Seite zeigt E-Mail-Feld, Passwort-Feld und Submit-Button.
2. Leeres Formular zeigt Validierungsfehler.
3. Während Submit sind Formularfelder und Button deaktiviert.
4. Bei HTTP `422` werden Backend-Validierungsfehler angezeigt.
5. Bei HTTP `401` wird ein Authentifizierungsfehler angezeigt.
6. Bei HTTP `403` wird ein deaktiviertes Konto als Fehler angezeigt.
7. Bei erfolgreichem Login wird der Benutzer in den geschützten Bereich geführt.
