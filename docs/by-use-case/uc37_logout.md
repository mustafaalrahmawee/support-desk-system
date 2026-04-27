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
2. Falls verwendet, werden zugehörige Token fachlich konsistent entzogen oder beendet.
3. Der Logout wird auditiert.

**Nachbedingungen**
- Der interne Benutzer ist nicht mehr authentifiziert.
- Der Logout ist nachvollziehbar protokolliert.

---

## 2. API-Contract

### Endpoint

`POST /api/logout`

### Auth

Erfordert authentifizierten internen Benutzer.

### Request

Kein Body erforderlich.

### Erfolgsantwort

HTTP `200`

```json
{
  "message": "Vorgang erfolgreich.",
  "data": null
}
```

### Fehlerfälle

- HTTP `401`, wenn kein gültiger Authentifizierungszustand besteht.

---

## 3. Backend-Architektur

### Route

```php
Route::middleware('auth:sanctum')->post('/logout', LogoutController::class);
```

### Controller

`App\Http\Controllers\Auth\LogoutController`

Aufgabe:
- authentifizierten Benutzer verwenden
- Action aufrufen
- JSON Response zurückgeben

### Action

`App\Actions\Auth\LogoutAction`

Aufgabe:
- aktuelle Authentifizierung beenden
- Token fachlich konsistent entziehen oder beenden, falls verwendet
- Logout auditieren

### Models

- `InternalUser`
- `AuditLog`
- optional Laravel Sanctum `PersonalAccessToken`

### Audit

Auditpflichtig:
- Logout ausgelöst

Kontext:
- interner Benutzer

---

## 4. Backend-QA

### Success Case

**Request**
```bash
curl -i -X POST http://localhost:8000/api/logout \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <TOKEN>"
```

**Erwartung HTTP**
- `200`

**Erwartung JSON**
- `message`
- `data` ist `null` oder fachlich leer

**Erwartung Datenbank**
- verwendeter Token ist fachlich konsistent beendet oder entzogen, falls tokenbasierte API-Authentifizierung verwendet wird

**Erwartung Audit**
- Logout ist protokolliert

### Unauthorized Case

Request ohne gültigen Token ausführen.

**Erwartung**
- HTTP `401`

---

## 5. Frontend-Architektur

### Store

`useAuthStore().logout()`

### API-Aufruf

`POST /api/logout`

### Nach Erfolg oder fachlich konsistenter Beendigung

- lokale Auth-Daten entfernen
- Benutzer zurück in den öffentlichen Auth-Bereich führen

---

## 6. Screen-Flow

1. Authentifizierter Benutzer löst Logout aus.
2. UI führt Logout-Request aus.
3. UI zeigt während des Requests einen Loading- oder Disabled-Zustand.
4. Lokaler Auth-Zustand wird beendet.
5. Benutzer sieht keinen geschützten Bereich mehr.

---

## 7. UI-Regeln

- Logout-Aktion während laufendem Request deaktivieren.
- Lokale Auth-Daten nach Logout entfernen.
- Nicht erfolgreich authentifizierte Benutzer dürfen geschützte Bereiche nicht weiter sehen.
- Keine Rollenlogik im Frontend erfinden.

---

## 8. UI-Referenz

- `docs/design-references/app-shell.png`

Wenn die Referenz nicht vorhanden ist, gilt sie nicht als fachliche Quelle.

---

## 9. Frontend-QA

### Tests

1. Authentifizierter Benutzer kann Logout auslösen.
2. Während Logout ist die Logout-Aktion deaktiviert oder sichtbar im Loading-Zustand.
3. Nach Logout sind lokale Auth-Daten entfernt.
4. Nach Logout ist der geschützte Bereich nicht mehr zugänglich.
5. Bei HTTP `401` wird der lokale Auth-Zustand ebenfalls fachlich konsistent beendet.
