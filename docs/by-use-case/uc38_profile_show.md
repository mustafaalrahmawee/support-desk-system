# UC 38 – Profil anzeigen

> Die API-Contracts in diesem Dokument sind aus den ursprünglichen Master-Dateien übernommen. Bei Widersprüchen gilt `docs/domain/`.

## 1. Use Case

### Ziel

Ein authentifizierter interner Benutzer ruft die eigenen Profildaten ab und sieht diese im System an.

### Akteure

- Interner Benutzer

### Vorbedingungen

- Der Benutzer ist authentifiziert.
- Der Benutzer besitzt ein internes Benutzerkonto.

### Hauptablauf

1. Der authentifizierte interne Benutzer öffnet die Profilansicht.
2. Das System lädt die Profildaten des aktuell eingeloggten Benutzers.
3. Das System gibt die eigenen Stammdaten und Rollen zurück.
4. Das Profil wird angezeigt.

### Alternativabläufe / Fehlerfälle

#### A1: Benutzer ist nicht authentifiziert
- Der Zugriff wird verweigert.
- Das System liefert `401 Unauthenticated`.

### Nachbedingungen

- Die eigenen Profildaten sind sichtbar.
- Es werden nur Daten des aktuell authentifizierten internen Benutzers angezeigt.

---

## 2. API-Contract

### Endpoint

- `GET /api/me`

### Authentifizierung

- erforderlich

### Success Response

```json
{
  "data": {
    "id": 1,
    "first_name": "Anna",
    "last_name": "Beispiel",
    "username": "anna.beispiel",
    "email": "agent@example.com",
    "is_active": true,
    "roles": ["support_agent"],
    "created_at": "2026-04-12T10:00:00Z",
    "updated_at": "2026-04-12T12:00:00Z"
  }
}
```

### Failed Cases

- `401 Unauthenticated`

---

## 3. Backend-Architektur

### Route

- `GET /api/me`

### Middleware

- `auth:sanctum`

### Controller

- `ProfileController@show`

### Policy

- keine freie Benutzer-ID im Request
- der Endpoint betrifft nur den aktuell authentifizierten internen Benutzer

### Action

- `ShowOwnProfileAction`
- Aufgaben:
  - aktuell authentifizierten Benutzer laden
  - Rollenbeziehungen laden
  - Profil-Daten als Response zurückgeben

### Model / Datenbasis

- `internal_users`
- `roles`
- `internal_user_roles`

---

## 4. Backend-QA

### Mindestens testen

1. Profil abrufen bei authentifiziertem Benutzer erfolgreich
2. Profilabruf ohne Authentifizierung schlägt mit `401` fehl
3. Response enthält Rollen des Benutzers
4. nur eigene Daten werden zurückgegeben

---

## 5. Frontend-Architektur

### Pages

- `src/pages/auth/ProfilePage.vue`

### Komponentenregel

- Profil anzeigen ist ein kompakter Screen — Detailansicht direkt in `ProfilePage.vue` belassen.
- Nur auslagern, wenn die Page aus mehreren eigenständigen Blöcken besteht.

### Store-Funktionen

- `authStore.fetchMe()`

---

## 6. Screen-Flow

### Typischer Screen-Name

- `ProfilePage.vue`

### Sichtbare Hauptbereiche

- Seitentitel
- Name
- Benutzername
- E-Mail
- Aktivstatus
- Rollen
- Button zum Bearbeiten des Profils

### Benutzeraktionen

- Profildaten ansehen
- zur Bearbeitung wechseln

### Erwartete Frontend-Logik

- beim Öffnen `authStore.fetchMe()` aufrufen
- Daten anzeigen
- bei Fehlern passend reagieren

### UI-Zustände

- loading
- success
- error
- unauthenticated

---

## 7. UI-Regeln

- Profildaten lesbar als ruhige Detailansicht darstellen
- Rollen als klare Badges oder Liste
- Edit-Aktion sichtbar, aber nicht dominant
- Fehler- und Retry-Zustand klar sichtbar

---

## 8. Stitch-Prompt

```text
Erzeuge mit Claude Code unter Verwendung von Stitch MCP und Google Labs stitch-skills eine Vue.js Screen-Komponente mit Tailwind CSS.

Kontext:
Dies ist die Profilansicht des aktuell eingeloggten internen Benutzers im Smart Support Desk System.

Wichtig:
- Nur UI
- Keine API-Integration
- Keine Store-Logik
- Keine Berechtigungslogik erfinden
- Keine Business-Logik
- Responsive Layout
- Ruhige Admin-Oberfläche

Enthält:
- Seitentitel
- Name
- Benutzername
- E-Mail
- Aktivstatus
- Rollenliste oder Rollen-Badges
- Button zum Bearbeiten des Profils
- Platzhalter für Loading- und Error-State
```

---

## 9. Frontend-QA

### Mindestens testen

1. Profilinhalte werden geladen und angezeigt
2. Rollen sichtbar dargestellt
3. Bearbeiten-Button vorhanden
4. Unauthenticated führt zur Login-Seite
5. Fehlerzustand mit Retry sichtbar
