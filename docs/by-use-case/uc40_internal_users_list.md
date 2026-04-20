# UC 40 – Interne Benutzer auflisten

## 1. Use Case

### Ziel

Ein Admin ruft eine Liste aller internen Benutzer ab.

### Akteure

- Admin

### Vorbedingungen

- Der Benutzer ist authentifiziert.
- Der Benutzer besitzt die Rolle Admin.

### Hauptablauf

1. Der Admin öffnet die Benutzerübersicht.
2. Das System prüft die Berechtigung des authentifizierten Benutzers.
3. Das System lädt die Liste der internen Benutzer mit Stammdaten und Rollen.
4. Das System gibt die Benutzerliste zurück.
5. Die Liste wird angezeigt.

### Alternativabläufe / Fehlerfälle

#### A1: Benutzer ist nicht authentifiziert
- Das System liefert `401 Unauthenticated`.

#### A2: Benutzer hat keine Admin-Rolle
- Das System liefert `403 Forbidden`.

### Nachbedingungen

- Die Benutzerliste ist sichtbar.
- Es werden nur Daten angezeigt, die für die administrative Verwaltung relevant sind.

---

## 2. API-Contract

### Endpoint

- `GET /api/admin/internal-users`

### Authentifizierung

- erforderlich

### Query-Parameter (optional)

- `search` — Suche über Name, Benutzername, E-Mail
- `is_active` — Filter nach Aktivstatus (`true` / `false`)
- `role` — Filter nach Rollenname
- `page` — Seitenzahl (Pagination)
- `per_page` — Einträge pro Seite

### Success Response

#### HTTP Status
- `200 OK`

#### Beispiel Response

```json
{
  "data": [
    {
      "id": 1,
      "first_name": "Anna",
      "last_name": "Beispiel",
      "username": "anna.beispiel",
      "email": "admin@example.com",
      "is_active": true,
      "roles": ["admin"],
      "created_at": "2026-04-01T10:00:00Z",
      "updated_at": "2026-04-12T12:00:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 42
  }
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`

---

## 3. Backend-Architektur

### Route

- `GET /api/admin/internal-users`

### Middleware

- `auth:sanctum`

### Controller

- `InternalUserController@index`
- dünn halten: Request entgegennehmen, Policy prüfen, Action aufrufen, Response zurückgeben

### Policy

- `InternalUserPolicy@viewAny`
- nur Admins dürfen die Benutzerliste abrufen

### Action

- `ListInternalUsersAction`
- Aufgaben:
  - Query mit optionalen Filtern und Suche aufbauen
  - Rollenbeziehungen eager-loaden
  - Pagination anwenden
  - Ergebnis zurückgeben

### Model / Datenbasis

- `internal_users`
- `roles`
- `internal_user_roles`

### Routing-Hinweis

- Teil der Admin-Gruppe
- nicht Teil der Auth-Routen

---

## 4. Backend-QA

### Mindestens testen

1. Liste abrufen bei authentifiziertem Admin erfolgreich
2. Liste abrufen ohne Authentifizierung schlägt mit `401` fehl
3. Liste abrufen ohne Admin-Rolle schlägt mit `403` fehl
4. Response enthält Rollen der Benutzer
5. Pagination funktioniert korrekt
6. Suchfilter liefert erwartete Ergebnisse
7. Aktivstatus-Filter funktioniert

### Typische Prüfungen

- HTTP-Status 200 / 401 / 403
- JSON-Struktur enthält `data` als Array und `meta` mit Pagination
- Jeder Eintrag enthält `id`, `first_name`, `last_name`, `username`, `email`, `is_active`, `roles`

---

## 5. Frontend-Architektur

### Pages

- `src/pages/users/InternalUsersListPage.vue`

### Komponentenregel

- Liste ist ein kompakter Screen — Tabelle direkt in `InternalUsersListPage.vue` belassen.
- Nur auslagern, wenn die Page zu unübersichtlich wird.

### Store-Funktionen

- `usersStore.fetchInternalUsers(params)`

### Projektstruktur

- Benutzer-Logik im `users.store.ts`
- API-Kommunikation über zentralen Client
- keine API-Logik direkt in Komponenten

---

## 6. Screen-Flow

### Typischer Screen-Name

- `InternalUsersListPage.vue`

### Sichtbare Hauptbereiche

- Seitentitel
- Suchfeld
- Filter (Aktivstatus, Rolle)
- Benutzertabelle mit Spalten: Name, Benutzername, E-Mail, Rollen, Aktivstatus, Aktionen
- Pagination
- Button „Neuen Benutzer anlegen"

### Benutzeraktionen

- Suchen
- Filtern
- Benutzer anklicken → zur Bearbeitung navigieren
- „Neuen Benutzer anlegen" klicken
- Seite wechseln (Pagination)

### Erwartete Frontend-Logik

- beim Öffnen `usersStore.fetchInternalUsers()` aufrufen
- bei Suche oder Filteränderung neu laden
- Pagination-Steuerung
- Navigation zu Detail-/Bearbeitungsseite

### UI-Zustände

- loading
- success (Liste mit Daten)
- empty (Liste leer)
- error
- unauthenticated
- forbidden

---

## 7. UI-Regeln

- ruhige Admin-Oberfläche
- Tabelle mit klarer Spaltenstruktur
- Rollen als Badges
- Aktivstatus visuell klar erkennbar (z. B. farbiger Indikator)
- Aktionsbuttons dezent
- Suchfeld und Filter oberhalb der Tabelle
- responsive, aber auf Desktop-Nutzung optimiert

---

## 8. Frontend-Design-Prompt

```text
Erzeuge mit dem Frontend Design Plugin eine Vue.js Screen-Komponente mit Tailwind CSS.

Kontext:
Dies ist die Benutzerübersicht für die administrative Verwaltung interner Benutzer im Smart Support Desk System.

Wichtig:
- Nur UI
- Keine API-Integration
- Keine Store-Logik
- Keine Business-Logik
- Keine Berechtigungslogik erfinden
- Responsive Layout
- Moderne, ruhige Admin-Oberfläche

Benutzer:
Admin

Screen-Ziel:
Übersicht aller internen Benutzer mit Such-, Filter- und Paginationsfunktion.

Enthält:
- Seitentitel
- Suchfeld
- Filter für Aktivstatus und Rolle
- Tabelle mit Spalten: Name, Benutzername, E-Mail, Rollen (Badges), Aktivstatus, Aktionen
- Pagination
- Button „Neuen Benutzer anlegen"
- Platzhalter für Loading-, Empty- und Error-State

Berücksichtige UI-Zustände:
- loading
- empty
- error
- success
```

---

## 9. Frontend-QA

### Mindestens testen

1. Benutzertabelle wird geladen und angezeigt
2. Rollen als Badges sichtbar
3. Aktivstatus visuell erkennbar
4. Suchfeld filtert Ergebnisse
5. Pagination funktioniert
6. Button „Neuen Benutzer anlegen" vorhanden und navigiert
7. Klick auf Benutzerzeile navigiert zur Bearbeitung
8. Forbidden-State bei fehlender Berechtigung
9. Unauthenticated führt zur Login-Seite
