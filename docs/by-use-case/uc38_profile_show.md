# UC-38 Eigenes Profil anzeigen

## 1. Use Case

**Ziel**  
Ein authentifizierter interner Benutzer sieht seine eigenen Profildaten.

**Primäre Akteure**  
Interner Benutzer

**Beteiligte Entitäten**  
Benutzerkonto

**Vorbedingungen**
- Der Benutzer ist authentifiziert.

**Trigger**  
Der Benutzer öffnet sein eigenes Profil.

**Hauptablauf**
1. Das System lädt die Profildaten des angemeldeten Benutzers.
2. Das System zeigt die eigenen Benutzerinformationen an.

**Nachbedingungen**
- Das eigene Profil ist sichtbar.

---

## 2. API-Contract

### Endpoint

`GET /api/me`

### Auth

Erfordert authentifizierten internen Benutzer.

### Request

Kein Body erforderlich.

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

---

## 3. Backend-Architektur

### Route

```php
Route::middleware('auth:sanctum')->get('/me', ShowOwnProfileController::class);
```

### Controller

`App\Http\Controllers\Auth\ShowOwnProfileController`

Aufgabe:
- authentifizierten Benutzer verwenden
- Action aufrufen
- JSON Response zurückgeben

### Action

`App\Actions\Auth\ShowOwnProfileAction`

Aufgabe:
- Profildaten des angemeldeten internen Benutzers laden
- keine fremden Benutzerprofile laden

### Models

- `InternalUser`

### Audit

Für das reine Anzeigen des eigenen Profils ist in den dokumentierten auditpflichtigen Ereignissen kein eigener Auditpunkt genannt.

---

## 4. Backend-QA

### Success Case

**Request**
```bash
curl -i -X GET http://localhost:8000/api/me \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <TOKEN>"
```

**Erwartung HTTP**
- `200`

**Erwartung JSON**
- Profildaten des angemeldeten Benutzers
- keine fremden Benutzerprofile

**Erwartung Datenbank**
- keine fachliche Änderung

**Erwartung Audit**
- kein zusätzlicher Audit-Eintrag erforderlich, sofern nur angezeigt wird

### Unauthorized Case

Request ohne gültigen Token ausführen.

**Erwartung**
- HTTP `401`

---

## 5. Frontend-Architektur

### Page

`src/pages/auth/ProfilePage.vue`

### Store

`useAuthStore().fetchMe()`

### API-Aufruf

`GET /api/me`

---

## 6. Screen-Flow

1. Benutzer öffnet sein Profil.
2. UI lädt Profildaten.
3. UI zeigt Loading-Zustand.
4. Bei Erfolg werden Profildaten angezeigt.
5. Bei Fehler wird ein Fehlerzustand angezeigt.

---

## 7. UI-Regeln

- Loading-State anzeigen.
- Error-State anzeigen.
- Empty-State anzeigen, falls keine Profildaten geladen werden können.
- Unauthorized-Zustand fachlich konsistent behandeln.
- Keine fremden Benutzerprofile anzeigen.
- Keine Rollenlogik im Frontend erfinden.

---

## 8. UI-Referenz

- `docs/design-references/auth/uc38_profile_show.png`
- alternativ global: `docs/design-references/app-shell.png`

Wenn eine Referenz nicht vorhanden ist, gilt sie nicht als fachliche Quelle.

---

## 9. Frontend-QA

### Tests

1. Profilseite ist nur authentifiziert erreichbar.
2. Beim Laden wird ein Loading-State angezeigt.
3. Bei erfolgreicher Antwort werden eigene Profildaten angezeigt.
4. Bei HTTP `401` wird der Benutzerzustand konsistent als nicht authentifiziert behandelt.
5. Fehlerzustand wird sichtbar dargestellt.
