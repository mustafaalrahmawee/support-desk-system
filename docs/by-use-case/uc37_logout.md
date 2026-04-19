# UC 37 – Logout

> Die API-Contracts in diesem Dokument sind aus den ursprünglichen Master-Dateien übernommen. Bei Widersprüchen gilt `docs/domain/`.

## 1. Use Case

### Ziel

Ein authentifizierter interner Benutzer meldet sich vom System ab.

### Akteure

- Interner Benutzer

### Vorbedingungen

- Der Benutzer ist authentifiziert.
- Ein gültiger Authentifizierungskontext besteht.

### Hauptablauf

1. Der authentifizierte interne Benutzer löst den Logout aus.
2. Das System beendet den aktuellen Authentifizierungskontext.
3. Das System protokolliert den Logout fachlich nachvollziehbar.
4. Das System bestätigt den erfolgreichen Logout.

### Alternativabläufe / Fehlerfälle

#### A1: Kein gültiger Authentifizierungskontext vorhanden
- Der Logout kann nicht für einen nicht authentifizierten Benutzer ausgeführt werden.
- Das System liefert `401 Unauthenticated`.

### Nachbedingungen

- Der aktuelle Authentifizierungskontext ist beendet.
- Der Logout ist auditpflichtig behandelt.

---

## 2. API-Contract

### Endpoint

- `POST /api/logout`

### Authentifizierung

- erforderlich

### Request

- kein Body erforderlich

### Success Response

```json
{
  "message": "Logout erfolgreich."
}
```

### Failed Cases

- `401 Unauthenticated`

---

## 3. Backend-Architektur

### Route

- `POST /api/logout`

### Middleware

- `auth:sanctum`

### FormRequest

- kein spezieller Request-Body erforderlich

### Controller

- `AuthController@logout`

### Policy

- keine separate Objekt-Policy notwendig
- Auth-Middleware schützt den Endpoint

### Action

- `LogoutAction`
- Aufgaben:
  - aktuellen Authentifizierungskontext beenden
  - Token oder Session ungültig machen
  - Audit auslösen

### Model / Datenbasis

- optional `personal_access_tokens`
- `audit_logs`

---

## 4. Backend-QA

### Mindestens testen

1. Logout erfolgreich bei authentifiziertem Benutzer
2. Logout ohne Authentifizierung schlägt mit `401` fehl
3. Audit-Eintrag für Logout entsteht
4. Token ist danach nicht mehr verwendbar

---

## 5. Frontend-Architektur

### Pages / UI-Orte

- kein großer eigener Screen nötig
- Logout-Flow typischerweise in Topbar, Sidebar oder Profilmenü

### Store-Funktionen

- `authStore.logout()`

### Komponentenregel

- Logout ist kein eigener Screen — ein Menüeintrag oder Button in Topbar/Sidebar/Profilmenü.
- Keine separate Komponente nötig.

---

## 6. Screen-Flow

### Typische UI-Orte

- Topbar
- Profilmenü
- Sidebar

### Benutzeraktionen

- Logout im Menü anklicken

### Erwartete Frontend-Logik

- `authStore.logout()` aufrufen
- lokalen Auth-Zustand löschen
- zur Login-Seite navigieren

### UI-Zustände

- initial
- disabled
- success
- fallback bei Logout-Fehler

---

## 7. UI-Regeln

- Logout klar auffindbar platzieren
- kurze Sperre während des Requests
- Navigation nach Erfolg konsistent zur Login-Seite
- keine komplexe UI nötig

---

## 8. Stitch-Prompt

```text
Erzeuge mit Claude Code unter Verwendung von Stitch MCP und Google Labs stitch-skills eine kleine Vue.js UI-Struktur mit Tailwind CSS für einen Logout-Flow im Smart Support Desk System.

Wichtig:
- Nur UI
- Keine API-Integration
- Keine Store-Logik
- Keine Auth-Implementierung
- Responsive
- Ruhige Admin-Oberfläche

Kontext:
Logout ist kein eigener großer Screen, sondern ein Menü- oder Navigations-Flow.

Enthält:
- Profilmenü oder Topbar-Ausschnitt
- Logout-Menüpunkt oder Button
- Platzhalter für disabled/loading Zustand
```

---

## 9. Frontend-QA

### Mindestens testen

1. Logout-Eintrag sichtbar
2. Klick löst Flow aus
3. Button / Eintrag kurz gesperrt während Request
4. nach Erfolg Weiterleitung zur Login-Seite
5. lokaler Auth-Zustand wird entfernt
