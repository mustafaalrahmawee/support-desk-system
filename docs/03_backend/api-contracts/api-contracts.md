# API Contracts: Smart Support Desk System

## Zweck

Diese Datei beschreibt die API-Verträge zwischen Backend und Frontend des Smart Support Desk Systems.

Die API-Contracts bauen auf den fachlichen Dokumenten des Projekts auf und übersetzen Use Cases in konkrete HTTP-Endpunkte, Request-Strukturen, Response-Formate und Fehlerfälle.

Die Datei dient als gemeinsame Referenz für:

- Backend-Implementierung
- Frontend-Integration
- Postman-Tests
- Feature-Tests
- Agentic-AI-Kontext für Codegenerierung und Umsetzung

## Hinweis zur Dokumentstruktur

Diese Datei ist die **Master-Datei** für die API-Contracts des Projekts.

Sie dient für:

- Gesamtüberblick
- Konsistenzprüfung
- Pflege der vollständigen API-Verträge

Für fokussierte Backend-Arbeit werden zusätzlich aufgeteilte Dateien verwendet, insbesondere:

- `docs/03_backend/api-contracts/by-domain/`

Da API-Contracts oft enger an fachlichen Bereichen als an exakt einem einzelnen Use Case hängen, erfolgt die Aufteilung hier primär über fachliche Domains.

Für normale Implementierungs- oder Review-Sessions soll bevorzugt die kleinste passende Dokumenteinheit verwendet werden.

---

## Referenzdokumente

Beim Arbeiten mit dieser Datei sollen die Dokumente in dieser Reihenfolge gelesen werden:

1. `01_miniworld.md`
2. `02_business-rules.md`
3. `03_er.md`
4. `use-cases.md`
5. `api-contracts.md`

Die API-Contracts dürfen den fachlichen Regeln dieser Dokumente nicht widersprechen.

---

## Ziel der API-Contracts

Diese Datei soll klar beschreiben:

- welchen fachlichen Zweck ein Endpoint hat
- welchem Use Case der Endpoint zugeordnet ist
- welche HTTP-Methode und welche URL verwendet wird
- welche Request-Daten erwartet werden
- welche Response bei Erfolg zurückgegeben wird
- welche Fehlerfälle möglich sind
- welche Authentifizierungs- und Autorisierungsregeln gelten
- welche Hinweise für Frontend und Tests wichtig sind

---

## Grundregeln für alle Endpoints

### Authentifizierung

Alle internen fachlichen Endpoints dürfen nur durch authentifizierte interne Benutzer verwendet werden, sofern nicht ausdrücklich etwas anderes dokumentiert ist.

Öffentliche oder systemseitige Eingangs-Endpunkte, zum Beispiel für eingehende Kommunikation oder Webhooks, werden gesondert beschrieben.

### Autorisierung

Die erlaubte Nutzung eines Endpoints hängt von Rolle und Fachkontext ab.

Das System arbeitet mit mehreren internen Rollen, insbesondere:

- Support Agent
- Inbound Reviewer
- Contract Manager
- Admin

Ein interner Benutzer darf nur die Funktionen ausführen, die für die eigene Rolle und den fachlichen Kontext erlaubt sind.

Administrative Endpoints dürfen nur durch entsprechend berechtigte interne Benutzer verwendet werden.

### Konsistenz

Ein Endpoint darf keine fachlich ungültigen Zustände erzeugen.

Wenn ein fachlicher Vorgang mehrere zusammenhängende Änderungen umfasst, muss die Backend-Implementierung diesen Vorgang atomar ausführen.

### Audit

Fachlich relevante Änderungen müssen nachvollziehbar protokolliert werden, sofern dies durch die Business Rules gefordert ist.

### Soft Delete

Wenn ein Endpoint Deaktivierung oder Soft Delete ausführt, müssen die fachlichen Auswirkungen auf abhängige Objekte beachtet werden.

Historische Daten dürfen dadurch nicht unzulässig verloren gehen.

---

## Einheitliche Struktur für Responses

### Erfolgsstruktur

Responses können einheitlich mit `message` und `data` aufgebaut werden.

Beispiel:

```json
{
  "message": "Vorgang erfolgreich.",
  "data": {
    "id": 1
  }
}
```

### Fehlerstruktur

Für Fehler sollte eine konsistente Struktur verwendet werden.

Beispiel:

```json
{
  "message": "Die eingegebenen Daten sind ungültig.",
  "errors": {
    "email": ["Das Feld E-Mail ist erforderlich."]
  }
}
```

Bei nicht feldspezifischen Fehlern kann `errors` entfallen.

---

## Einheitliche Fehlerkategorien

### 401 Unauthenticated

Der Benutzer ist nicht angemeldet oder die Authentifizierung ist ungültig.

### 403 Forbidden

Der Benutzer ist angemeldet, darf diese Funktion aber fachlich oder rollenbasiert nicht ausführen.

### 404 Not Found

Das angeforderte Fachobjekt existiert nicht oder ist in diesem Kontext nicht verfügbar.

### 409 Conflict

Der gewünschte Vorgang ist fachlich nicht möglich, weil ein Konflikt mit bestehendem Zustand vorliegt.

### 422 Validation Error

Die Eingabedaten sind formal oder fachlich unzulässig.

---

## Beziehung zu Frontend und Postman

Diese Datei ist die Brücke zwischen Backend und Frontend.

Sie beschreibt nicht nur technische Requests und Responses, sondern auch die erwartbaren fachlichen Erfolgs- und Fehlerfälle.

Postman wird verwendet, um diese Verträge technisch und fachlich zu prüfen.

Das Frontend verwendet dieselben Contracts, um UI-Verhalten für:

- Erfolg
- Fehler
- leere Zustände
- Zugriff verweigert
- erneute Anmeldung
- Konfliktfälle

sauber umzusetzen.

---

# Abschnitt A: Authentifizierung und eigenes Profil

## Zugehörige Use Cases

- Use Case 36: Login
- Use Case 37: Logout
- Use Case 38: Eigenes Profil anzeigen
- Use Case 39: Eigenes Profil bearbeiten

## Allgemeine Regeln

- Authentifizierung ist Voraussetzung für interne fachliche Funktionen.
- Nur aktive interne Benutzer dürfen sich anmelden.
- Logout beendet die aktuelle Authentifizierung.
- Ein Benutzer darf nur das eigene Profil anzeigen und bearbeiten, sofern keine separate Admin-Funktion verwendet wird.
- Login und Logout sind auditpflichtige Vorgänge.
- Profiländerungen sind fachlich relevante Änderungen und sollen auditierbar sein.

---

## Endpoint A1: Login

### Zweck

Ein interner Benutzer meldet sich am System an.

### Methode und URL

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

## Endpoint A2: Logout

### Zweck

Ein authentifizierter interner Benutzer meldet sich vom System ab.

### Methode und URL

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

## Endpoint A3: Eigenes Profil anzeigen

### Zweck

Ein authentifizierter interner Benutzer ruft die eigenen Profildaten ab.

### Methode und URL

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

## Endpoint A4: Eigenes Profil bearbeiten

### Zweck

Ein authentifizierter interner Benutzer ändert die eigenen erlaubten Profildaten.

### Methode und URL

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

### Failed Cases

- `401 Unauthenticated`
- `409 Conflict`
- `422 Validation Error`

---

# Abschnitt B: Benutzer- und Rollenverwaltung

## Zugehörige Use Cases

- Use Case 40: Internen Benutzer durch Admin anlegen
- Use Case 41: Liste interner Benutzer anzeigen
- Use Case 42: Internen Benutzer durch Admin bearbeiten
- Use Case 43: Internen Benutzer durch Admin deaktivieren oder soft delete ausführen

## Allgemeine Regeln

- Alle Endpunkte in diesem Bereich erfordern Authentifizierung.
- Diese Endpunkte erfordern administrative Berechtigung.
- Rollen werden dem internen Benutzer ausdrücklich zugewiesen.
- Änderungen an Benutzerkonten oder Rollen sind auditpflichtig.

---

## Endpoint B1: Liste interner Benutzer anzeigen

### Methode und URL

- `GET /api/admin/internal-users`

### Authentifizierung

- erforderlich
- Admin erforderlich

### Query-Parameter

Optional:

- `search`
- `is_active`
- `role`
- `page`
- `per_page`

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`

---

## Endpoint B2: Einzelnen internen Benutzer anzeigen

### Methode und URL

- `GET /api/admin/internal-users/{id}`

### Authentifizierung

- erforderlich
- Admin erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`

---

## Endpoint B3: Internen Benutzer anlegen

### Methode und URL

- `POST /api/admin/internal-users`

### Authentifizierung

- erforderlich
- Admin erforderlich

### Request

```json
{
  "first_name": "Anna",
  "last_name": "Beispiel",
  "username": "anna.beispiel",
  "email": "anna@example.com",
  "password": "secret-password",
  "password_confirmation": "secret-password",
  "is_active": true,
  "role_names": ["support_agent"]
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
    "id": 2,
    "first_name": "Anna",
    "last_name": "Beispiel",
    "username": "anna.beispiel",
    "email": "anna@example.com",
    "is_active": true,
    "roles": ["support_agent"]
  }
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint B4: Internen Benutzer bearbeiten

### Methode und URL

- `PATCH /api/admin/internal-users/{id}`

### Authentifizierung

- erforderlich
- Admin erforderlich

### Request

```json
{
  "first_name": "Anna",
  "last_name": "Muster",
  "username": "anna.muster",
  "email": "anna.muster@example.com",
  "is_active": true,
  "role_names": ["support_agent", "inbound_reviewer"]
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint B5: Internen Benutzer deaktivieren

### Methode und URL

- `PATCH /api/admin/internal-users/{id}/deactivate`

### Authentifizierung

- erforderlich
- Admin erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint B6: Internen Benutzer reaktivieren

### Methode und URL

- `PATCH /api/admin/internal-users/{id}/reactivate`

### Authentifizierung

- erforderlich
- Admin erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint B7: Internen Benutzer soft deleten

### Methode und URL

- `DELETE /api/admin/internal-users/{id}`

### Authentifizierung

- erforderlich
- Admin erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

# Abschnitt C: Customers

## Zugehörige Use Cases

- Use Case 5: Customer manuell anlegen
- Use Case 6: Customer-Details anzeigen
- Use Case 7: Customer bearbeiten
- Use Case 8: Customer deaktivieren oder soft delete ausführen
- Use Case 9: Customer reaktivieren
- Use Case 10: Customer-Dubletten zusammenführen

## Allgemeine Regeln

- Alle Customer-Endpunkte erfordern Authentifizierung.
- Customer repräsentiert die fachliche Identität externer Kommunikationspartner.
- Ein Customer besitzt genau eine eindeutige `customer_number`.
- Ein aktiver Customer muss mindestens einen aktiven Contact besitzen.
- Mehrere Customer-Datensätze derselben Identität dürfen nur über einen kontrollierten Merge zusammengeführt werden.

---

## Endpoint C1: Customer-Liste anzeigen

### Methode und URL

- `GET /api/customers`

### Authentifizierung

- erforderlich

### Query-Parameter

Optional:

- `search`
- `customer_number`
- `is_active`
- `page`
- `per_page`

### Failed Cases

- `401 Unauthenticated`

---

## Endpoint C2: Einzelnen Customer anzeigen

### Methode und URL

- `GET /api/customers/{id}`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `404 Not Found`

---

## Endpoint C3: Customer manuell anlegen

### Methode und URL

- `POST /api/customers`

### Authentifizierung

- erforderlich

### Request

```json
{
  "customer_number": "CUST-10023",
  "first_name": null,
  "last_name": null,
  "display_name": "Meyer Logistik GmbH",
  "company_name": "Meyer Logistik GmbH",
  "is_active": true,
  "primary_contact": {
    "type": "email",
    "value": "support@meyer-logistik.de",
    "is_verified": false
  }
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint C4: Customer bearbeiten

### Methode und URL

- `PATCH /api/customers/{id}`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint C5: Customer deaktivieren

### Methode und URL

- `PATCH /api/customers/{id}/deactivate`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint C6: Customer reaktivieren

### Methode und URL

- `PATCH /api/customers/{id}/reactivate`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint C7: Customer soft deleten

### Methode und URL

- `DELETE /api/customers/{id}`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint C8: Customer-Dubletten zusammenführen

### Methode und URL

- `POST /api/customers/merge`

### Authentifizierung

- erforderlich

### Request

```json
{
  "target_customer_id": 12,
  "source_customer_ids": [18, 21]
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

# Abschnitt D: Contacts

## Zugehörige Use Cases

- Use Case 11: Customer-Contact anlegen
- Use Case 12: Contact als primär setzen
- Use Case 13: Contact verifizieren
- Use Case 14: Contact deaktivieren oder soft delete ausführen
- Use Case 15: Contact reaktivieren

## Allgemeine Regeln

- Alle Contact-Endpunkte erfordern Authentifizierung.
- Ein Contact gehört genau einem Customer.
- Contact-Werte müssen innerhalb ihres Typs eindeutig sein, sofern sie aktiv und nicht soft deleted sind.
- Pro Customer darf höchstens ein aktiver Contact als primär markiert sein.
- Ein Contact darf nicht unabhängig entfernt werden, wenn dadurch ein aktiver Customer keinen aktiven Contact mehr hätte.
- Contact-Änderungen sind auditpflichtig.

---

## Endpoint D1: Contacts eines Customers anzeigen

### Methode und URL

- `GET /api/customers/{customerId}/contacts`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `404 Not Found`

---

## Endpoint D2: Einzelnen Contact anzeigen

### Methode und URL

- `GET /api/contacts/{id}`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `404 Not Found`

---

## Endpoint D3: Contact anlegen

### Methode und URL

- `POST /api/customers/{customerId}/contacts`

### Authentifizierung

- erforderlich

### Request

```json
{
  "type": "email",
  "value": "kunde@example.com",
  "is_primary": false,
  "is_verified": false
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint D4: Contact bearbeiten

### Methode und URL

- `PATCH /api/contacts/{id}`

### Authentifizierung

- erforderlich

### Request

```json
{
  "value": "neue-adresse@example.com",
  "is_verified": true
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint D5: Contact als primär setzen

### Methode und URL

- `PATCH /api/contacts/{id}/set-primary`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint D6: Contact verifizieren

### Methode und URL

- `PATCH /api/contacts/{id}/verify`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint D7: Contact deaktivieren

### Methode und URL

- `PATCH /api/contacts/{id}/deactivate`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint D8: Contact reaktivieren

### Methode und URL

- `PATCH /api/contacts/{id}/reactivate`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint D9: Contact soft deleten

### Methode und URL

- `DELETE /api/contacts/{id}`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

# Abschnitt E: Contracts

## Zugehörige Use Cases

- Use Case 16: Contract für Customer anlegen
- Use Case 17: Contracts eines Customers anzeigen
- Use Case 18: Contract-Details anzeigen
- Use Case 19: Contract bearbeiten
- Use Case 20: Contract deaktivieren oder soft delete ausführen
- Use Case 21: Dokument an Contract anhängen

## Allgemeine Regeln

- Alle Contract-Endpunkte erfordern Authentifizierung.
- Contract-Verwaltung erfordert passende fachliche Berechtigung.
- Ein Contract gehört genau zu einem Customer.
- Ein Contract kann Dokumente oder andere Medien besitzen.
- Contract-Änderungen sind auditpflichtig.

---

## Endpoint E1: Contracts eines Customers anzeigen

### Methode und URL

- `GET /api/customers/{customerId}/contracts`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `404 Not Found`

---

## Endpoint E2: Einzelnen Contract anzeigen

### Methode und URL

- `GET /api/contracts/{id}`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `404 Not Found`

---

## Endpoint E3: Contract anlegen

### Methode und URL

- `POST /api/customers/{customerId}/contracts`

### Authentifizierung

- erforderlich

### Request

```json
{
  "contract_number": "CTR-2026-0007",
  "name": "Analytics Enterprise Paket",
  "type": "product_package",
  "status": "active",
  "valid_from": "2026-01-01",
  "valid_to": "2026-12-31"
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint E4: Contract bearbeiten

### Methode und URL

- `PATCH /api/contracts/{id}`

### Authentifizierung

- erforderlich

### Request

```json
{
  "name": "Analytics Enterprise Paket",
  "status": "inactive",
  "valid_to": "2026-10-31"
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint E5: Contract deaktivieren

### Methode und URL

- `PATCH /api/contracts/{id}/deactivate`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint E6: Contract reaktivieren

### Methode und URL

- `PATCH /api/contracts/{id}/reactivate`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint E7: Contract soft deleten

### Methode und URL

- `DELETE /api/contracts/{id}`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint E8: Dokument an Contract anhängen

### Methode und URL

- `POST /api/contracts/{id}/media`

### Authentifizierung

- erforderlich

### Request

- `multipart/form-data`

Felder:

- `file`
- `collection_name` optional

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `422 Validation Error`

---

## Endpoint E9: Medien eines Contracts anzeigen

### Methode und URL

- `GET /api/contracts/{id}/media`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `404 Not Found`

---

# Abschnitt F: Tickets

## Zugehörige Use Cases

- Use Case 22: Ticket-Liste anzeigen
- Use Case 23: Ticket-Details anzeigen
- Use Case 24: Ticket einem internen Benutzer zuweisen oder Zuweisung ändern
- Use Case 25: Ticket-Status ändern
- Use Case 26: Ticket als resolved markieren
- Use Case 27: Ticket schließen
- Use Case 28: Kategorie eines Tickets setzen oder ändern
- Use Case 29: Priorität eines Tickets setzen oder ändern
- Use Case 30: Contract-Kontext eines Tickets setzen oder ändern

## Allgemeine Regeln

- Alle Ticket-Endpunkte erfordern Authentifizierung.
- Ein geschlossenes Ticket darf nicht weiter bearbeitet, nicht neu zugewiesen und nicht durch neue Nachrichten fortgeführt werden, sofern keine Reopen-Regel definiert ist.
- Ein Ticket gehört genau einem Customer und genau einer Kategorie.
- Ein Ticket kann optional genau einem Contract desselben Customers zugeordnet sein.
- Ein Ticket kann genau einem internen Benutzer zugewiesen sein oder unassigned bleiben.
- Ein Ticket darf keinem deaktivierten oder soft deleted internen Benutzer zugewiesen sein.
- Statuswechsel dürfen nur über fachlich erlaubte Transitionen erfolgen.
- Ticket-Änderungen sind auditpflichtig.

---

## Endpoint F1: Ticket-Liste anzeigen

### Methode und URL

- `GET /api/tickets`

### Authentifizierung

- erforderlich

### Query-Parameter

Optional:

- `status`
- `priority`
- `channel`
- `category_id`
- `assigned_internal_user_id`
- `customer_id`
- `contract_id`
- `search`
- `page`
- `per_page`

### Failed Cases

- `401 Unauthenticated`

---

## Endpoint F2: Einzelnes Ticket anzeigen

### Methode und URL

- `GET /api/tickets/{id}`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `404 Not Found`

---

## Endpoint F3: Ticket manuell anlegen

### Methode und URL

- `POST /api/tickets`

### Authentifizierung

- erforderlich

### Request

```json
{
  "subject": "Neuer Supportfall",
  "description": "Der Kunde meldet ein Problem mit dem Analytics-Modul.",
  "status": "open",
  "priority": "medium",
  "channel": "phone",
  "category_id": 3,
  "customer_id": 12,
  "contract_id": 7,
  "assigned_internal_user_id": 2
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint F4: Ticket zuweisen oder Zuweisung ändern

### Methode und URL

- `PATCH /api/tickets/{id}/assignment`

### Authentifizierung

- erforderlich

### Request

```json
{
  "assigned_internal_user_id": 2
}
```

oder

```json
{
  "assigned_internal_user_id": null
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint F5: Ticket-Status ändern

### Methode und URL

- `PATCH /api/tickets/{id}/status`

### Authentifizierung

- erforderlich

### Request

```json
{
  "status": "in_progress"
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint F6: Ticket als resolved markieren

### Methode und URL

- `PATCH /api/tickets/{id}/resolve`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint F7: Ticket schließen

### Methode und URL

- `PATCH /api/tickets/{id}/close`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint F8: Kategorie eines Tickets setzen oder ändern

### Methode und URL

- `PATCH /api/tickets/{id}/category`

### Authentifizierung

- erforderlich

### Request

```json
{
  "category_id": 3
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint F9: Priorität eines Tickets setzen oder ändern

### Methode und URL

- `PATCH /api/tickets/{id}/priority`

### Authentifizierung

- erforderlich

### Request

```json
{
  "priority": "high"
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `422 Validation Error`

---

## Endpoint F10: Contract-Kontext eines Tickets setzen oder ändern

### Methode und URL

- `PATCH /api/tickets/{id}/contract`

### Authentifizierung

- erforderlich

### Request

```json
{
  "contract_id": 7
}
```

oder

```json
{
  "contract_id": null
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

# Abschnitt G: Messages und Ticket-Medien

## Zugehörige Use Cases

- Use Case 1: Eingehende Kundenanfrage verarbeiten
- Use Case 3: Öffentliche Antwort an Customer senden
- Use Case 4: Interne Nachricht im Ticket erstellen
- Use Case 33: Datei an Nachricht, Ticket oder Contract anhängen

## Allgemeine Regeln

- Alle internen Message-Endpunkte erfordern Authentifizierung, sofern sie nicht ausdrücklich als öffentlicher oder systemseitiger Eingang beschrieben sind.
- Eine Nachricht gehört genau zu einem Ticket.
- Eine Nachricht hat genau einen Actor als Autor.
- `public`-Nachrichten dürfen von internen Benutzern oder Customers stammen.
- `internal`-Nachrichten dürfen nur von internen Benutzern stammen.
- Für geschlossene Tickets dürfen keine neuen Nachrichten erstellt werden, sofern keine Reopen-Regel definiert ist.
- Message-Änderungen sind auditpflichtig.

---

## Endpoint G1: Nachrichten eines Tickets anzeigen

### Methode und URL

- `GET /api/tickets/{ticketId}/messages`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `404 Not Found`

---

## Endpoint G2: Öffentliche Nachricht in Ticket erstellen

### Methode und URL

- `POST /api/tickets/{ticketId}/messages/public`

### Authentifizierung

- erforderlich

### Request

```json
{
  "content": "Bitte senden Sie uns einen Screenshot des Problems."
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint G3: Interne Nachricht in Ticket erstellen

### Methode und URL

- `POST /api/tickets/{ticketId}/messages/internal`

### Authentifizierung

- erforderlich

### Request

```json
{
  "content": "Bitte Vertragskontext prüfen."
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint G4: Medium an Ticket anhängen

### Methode und URL

- `POST /api/tickets/{ticketId}/media`

### Authentifizierung

- erforderlich

### Request

- `multipart/form-data`

Felder:

- `file`
- `collection_name` optional

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `422 Validation Error`

---

## Endpoint G5: Medium an Nachricht anhängen

### Methode und URL

- `POST /api/messages/{messageId}/media`

### Authentifizierung

- erforderlich

### Request

- `multipart/form-data`

Felder:

- `file`
- `collection_name` optional

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `422 Validation Error`

---

## Endpoint G6: Öffentliche eingehende Nachricht verarbeiten

### Zweck

Ein systemseitiger oder öffentlicher Eingang verarbeitet eine neue eingehende Customer-Nachricht und ordnet sie einem Customer und Ticket zu oder erzeugt diese.

### Methode und URL

- `POST /api/inbound/messages`

### Authentifizierung

- gesondert je nach Eingangskanal
- keine klassische interne Benutzer-Authentifizierung
- stattdessen zum Beispiel Webhook-Signatur, Kanal-Token oder Systemkontext

### Request

```json
{
  "channel": "email",
  "contact_type": "email",
  "contact_value": "kunde@example.com",
  "customer_number": "CUST-10023",
  "contract_number": "CTR-2026-0007",
  "sender_name": "Thomas Becker",
  "subject": "Ich kann mich nicht anmelden",
  "content": "Seit heute Morgen funktioniert mein Login nicht mehr."
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `409 Conflict`
- `422 Validation Error`

### Fachliche Hinweise

- Dieser Endpoint ist systemseitig und kein normaler interner Bearbeitungsendpoint.
- Die Verarbeitung kann zu Ticket und Nachricht oder zu einem Inbound-Prüffall führen.
- Eingehende Nachrichten können Anhänge oder Medien enthalten.
- Unsichere Zuordnungen dürfen nicht blind automatisch erzwungen werden.

---

# Abschnitt H: Inbound-Prüffälle

## Zugehörige Use Cases

- Use Case 2: Unklaren Inbound-Prüffall prüfen und entscheiden

## Allgemeine Regeln

- Inbound-Prüffälle erfordern Authentifizierung für interne Bearbeitung.
- Die Bearbeitung erfordert passende fachliche Berechtigung, typischerweise Inbound Reviewer.
- Ein Prüffall darf nicht stillschweigend verworfen werden.
- Entscheidungen zu Prüffällen sind auditpflichtig.

---

## Endpoint H1: Liste der Inbound-Prüffälle anzeigen

### Methode und URL

- `GET /api/inbound/review-cases`

### Authentifizierung

- erforderlich

### Query-Parameter

Optional:

- `status`
- `channel`
- `search`
- `page`
- `per_page`

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`

---

## Endpoint H2: Einzelnen Inbound-Prüffall anzeigen

### Methode und URL

- `GET /api/inbound/review-cases/{id}`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`

---

## Endpoint H3: Inbound-Prüffall einem bestehenden Customer zuordnen

### Methode und URL

- `POST /api/inbound/review-cases/{id}/assign-customer`

### Authentifizierung

- erforderlich

### Request

```json
{
  "customer_id": 12
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint H4: Inbound-Prüffall durch neuen Customer lösen

### Methode und URL

- `POST /api/inbound/review-cases/{id}/create-customer`

### Authentifizierung

- erforderlich

### Request

```json
{
  "customer_number": "CUST-10099",
  "display_name": "Neue Kundenfirma GmbH",
  "company_name": "Neue Kundenfirma GmbH",
  "primary_contact": {
    "type": "email",
    "value": "kontakt@neuefirma.de",
    "is_verified": false
  }
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint H5: Inbound-Prüffall als weiterhin offen markieren

### Methode und URL

- `PATCH /api/inbound/review-cases/{id}/mark-pending`

### Authentifizierung

- erforderlich

### Request

```json
{
  "decision_note": "Weitere Klärung mit Kunde erforderlich."
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `422 Validation Error`

---

# Abschnitt I: Kategorien

## Zugehörige Use Cases

- Use Case 31: Kategorie anlegen oder ändern
- Use Case 32: Kategorie deaktivieren oder soft delete ausführen

## Allgemeine Regeln

- Alle Kategorie-Endpunkte erfordern Authentifizierung.
- Kategorien dürfen nur von berechtigten internen Benutzern verwaltet werden.
- Eine deaktivierte oder soft deleted Kategorie darf keinen neuen Tickets mehr zugewiesen werden.
- Bestehende Tickets behalten ihre historische Kategorie.
- Kategorie-Änderungen sind auditpflichtig.

---

## Endpoint I1: Kategorien anzeigen

### Methode und URL

- `GET /api/categories`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`

---

## Endpoint I2: Einzelne Kategorie anzeigen

### Methode und URL

- `GET /api/categories/{id}`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `404 Not Found`

---

## Endpoint I3: Kategorie anlegen

### Methode und URL

- `POST /api/categories`

### Authentifizierung

- erforderlich

### Request

```json
{
  "name": "Zugangsdaten",
  "is_active": true
}
```

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint I4: Kategorie bearbeiten

### Methode und URL

- `PATCH /api/categories/{id}`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`
- `422 Validation Error`

---

## Endpoint I5: Kategorie deaktivieren

### Methode und URL

- `PATCH /api/categories/{id}/deactivate`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint I6: Kategorie reaktivieren

### Methode und URL

- `PATCH /api/categories/{id}/reactivate`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Endpoint I7: Kategorie soft deleten

### Methode und URL

- `DELETE /api/categories/{id}`

### Authentifizierung

- erforderlich

### Failed Cases

- `401 Unauthenticated`
- `403 Forbidden`
- `404 Not Found`
- `409 Conflict`

---

## Hinweise für Laravel-Umsetzung

- Interne Endpunkte sollen hinter Auth-Middleware liegen.
- Autorisierung soll über Policies, Gates oder passende Middleware sauber strukturiert werden.
- Controller sollen schlank bleiben.
- Formale Validierung gehört in Form Requests.
- Fachlogik gehört in Actions oder Services im Application Layer.
- Atomare Vorgänge sollen mit `DB::transaction()` umgesetzt werden, wenn mehrere fachlich zusammenhängende Änderungen betroffen sind.
- Audit-Logging soll konsistent an zentraler Stelle ausgelöst werden.

---

## Hinweise für Tests

- Erfolgsfall und Failed Cases pro Endpoint prüfen
- Autorisierung pro Rollen- und Fachkontext prüfen
- Datenbankfolgen prüfen
- Audit-Logs prüfen
- Soft-Delete- und Reaktivierungsfolgen prüfen
- Contract-Bezug am Ticket prüfen
- Inbound-Prüffall-Entscheidungen prüfen
- Anhänge und Medien-Zuordnung prüfen
- atomare Vorgänge prüfen
