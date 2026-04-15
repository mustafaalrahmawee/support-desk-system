# Endpoint Groups: Smart Support Desk System

## Zweck

Diese Datei beschreibt die fachliche und technische Gruppierung der API-Endpunkte des Smart Support Desk Systems.

Die Gruppierung dient dazu, die API nicht nur als einzelne Endpunkte zu sehen, sondern als zusammenhängende Funktionsbereiche. Dadurch wird für Entwickler, Tests, Frontend und Agentic-AI klarer, welche Endpunkte zusammengehören und welche Verantwortung ein bestimmter API-Bereich hat.

Diese Datei ist eine Ordnungs- und Strukturdatei. Sie ersetzt nicht die fachlichen Regeln aus `01_miniworld.md`, `02_business-rules.md`, `03_er.md`, `use-cases.md` oder `api-contracts.md`, sondern ergänzt diese.

---

## Referenzdokumente

Diese Datei ist zusammen mit den folgenden Dokumenten zu lesen:

1. `docs/01_domain/01_miniworld.md`
2. `docs/01_domain/02_business-rules.md`
3. `docs/01_domain/03_er.md`
4. `docs/02_use-cases/use-cases.md`
5. `docs/03_backend/api-contracts/api-contracts.md`
6. `docs/03_backend/endpoint-groups/endpoint-groups.md`

Die Gruppierung der Endpunkte darf den fachlichen Regeln dieser Dokumente nicht widersprechen.

---

## Ziel der Endpoint-Gruppen

Die Gruppierung der Endpunkte soll helfen bei:

- Strukturierung der Laravel-Routen
- Strukturierung von Controllern
- Strukturierung von Request-Klassen
- Strukturierung von Policies und Middleware
- Strukturierung von Tests
- Strukturierung von Frontend-API-Aufrufen
- klarer Arbeitsteilung für Agentic-AI-Modelle

Ein Agentic-AI-Modell soll aus dieser Datei erkennen können, welche Endpunkte logisch zusammengehören und in welchem technischen Bereich eine Aufgabe umgesetzt werden soll.

---

## Grundprinzipien der Gruppierung

### Fachliche Kohärenz

Endpunkte sollen nach fachlich zusammengehörigen Bereichen gruppiert werden.

Beispiele:

- Login, Logout und eigenes Profil gehören in dieselbe Gruppe
- Customer und Contact sind eng verwandt, aber fachlich nicht identisch
- Tickets und Nachrichten hängen zusammen, bleiben aber getrennte Verantwortungsbereiche
- Inbound-Verarbeitung und Inbound-Prüffälle sind verwandt, aber nicht identisch
- Contracts und Contract-Medien gehören zusammen, sind aber von Customers fachlich getrennt

### Klare Verantwortungsgrenzen

Eine Endpoint-Gruppe soll eine erkennbare Verantwortung haben.

Beispiele:

- Auth-Gruppe für Anmeldung und eigene Identität
- Internal-Users-Gruppe für interne Benutzer- und Rollenverwaltung
- Ticket-Gruppe für Supportfall-Verwaltung
- Inbound-Gruppe für systemseitige Eingänge
- Inbound-Review-Gruppe für manuelle Prüfung unklarer Eingänge
- Contract-Gruppe für Vertrags-, Produkt- oder Leistungskontexte

### Technische Umsetzbarkeit in Laravel

Die Gruppierung soll so gewählt werden, dass sie sich sinnvoll in Laravel umsetzen lässt, zum Beispiel über:

- Route-Dateien
- Controller-Gruppen
- Form Requests
- Policies
- Services / Actions

### Trennung von internen und systemseitigen Eingängen

Interne Endpunkte für authentifizierte interne Benutzer sind von systemseitigen Eingängen zu unterscheiden.

Beispiele:

- `POST /api/login` ist ein interner Auth-Endpoint
- `POST /api/inbound/messages` ist ein systemseitiger oder öffentlicher Eingangs-Endpoint
- `GET /api/inbound/review-cases` ist wieder ein interner Bearbeitungs-Endpoint

---

## Übersicht der Endpoint-Gruppen

Das System verwendet die folgenden Hauptgruppen:

1. Auth
2. Internal Users
3. Customers
4. Contacts
5. Contracts
6. Tickets
7. Messages
8. Inbound / System Inputs
9. Inbound Review Cases
10. Categories
11. Media

---

# 1. Auth-Gruppe

## Zweck

Die Auth-Gruppe enthält Endpunkte für Authentifizierung und die Verwaltung der eigenen Benutzeridentität.

## Fachlicher Bereich

- Login
- Logout
- eigenes Profil anzeigen
- eigenes Profil bearbeiten

## Zugehörige Use Cases

- Use Case 36: Login
- Use Case 37: Logout
- Use Case 38: Eigenes Profil anzeigen
- Use Case 39: Eigenes Profil bearbeiten

## Typische Endpunkte

- `POST /api/login`
- `POST /api/logout`
- `GET /api/me`
- `PATCH /api/me`

## Typische Laravel-Zuordnung

- `AuthController`
- `ProfileController`

## Typische Middleware

- `auth`
- optional Token- oder Session-basierte Middleware

## Typische Besonderheiten

- Login ist der Einstieg in alle geschützten Bereiche
- Logout beendet den aktuellen Authentifizierungskontext
- Profil-Endpunkte betreffen nur den aktuell eingeloggten internen Benutzer
- diese Gruppe ist keine administrative Benutzerverwaltung

---

# 2. Internal-Users-Gruppe

## Zweck

Die Internal-Users-Gruppe enthält administrative Endpunkte für die Verwaltung interner Benutzerkonten und deren Rollen.

## Fachlicher Bereich

- Liste interner Benutzer
- Benutzer-Detail
- internen Benutzer anlegen
- internen Benutzer bearbeiten
- Rollen zuweisen oder ändern
- internen Benutzer deaktivieren
- internen Benutzer reaktivieren
- internen Benutzer soft deleten

## Zugehörige Use Cases

- Use Case 40: Internen Benutzer durch Admin anlegen
- Use Case 41: Liste interner Benutzer anzeigen
- Use Case 42: Internen Benutzer durch Admin bearbeiten
- Use Case 43: Internen Benutzer durch Admin deaktivieren oder soft delete ausführen

## Typische Endpunkte

- `GET /api/admin/internal-users`
- `GET /api/admin/internal-users/{id}`
- `POST /api/admin/internal-users`
- `PATCH /api/admin/internal-users/{id}`
- `PATCH /api/admin/internal-users/{id}/deactivate`
- `PATCH /api/admin/internal-users/{id}/reactivate`
- `DELETE /api/admin/internal-users/{id}`

## Typische Laravel-Zuordnung

- `Admin/InternalUserController`
- optional `Admin/InternalUserRoleController`, wenn Rollen später separat gepflegt werden

## Typische Middleware

- `auth`
- administrative Berechtigungsprüfung
- alternativ `auth` + Policy / Gate

## Typische Besonderheiten

- ausschließlich für entsprechend berechtigte interne Benutzer
- Änderungen sind auditpflichtig
- Actor-Synchronisation und Ticket-Zuweisungsfolgen müssen beachtet werden
- dieser Bereich ist klar getrennt von `GET /api/me` und `PATCH /api/me`

---

# 3. Customers-Gruppe

## Zweck

Die Customers-Gruppe enthält Endpunkte für die fachliche Identität externer Kommunikationspartner.

## Fachlicher Bereich

- Customer-Liste
- Customer-Detail
- Customer manuell anlegen
- Customer bearbeiten
- Customer deaktivieren
- Customer reaktivieren
- Customer soft deleten
- Customer merge

## Zugehörige Use Cases

- Use Case 5: Customer manuell anlegen
- Use Case 6: Customer-Details anzeigen
- Use Case 7: Customer bearbeiten
- Use Case 8: Customer deaktivieren oder soft delete ausführen
- Use Case 9: Customer reaktivieren
- Use Case 10: Customer-Dubletten zusammenführen

## Typische Endpunkte

- `GET /api/customers`
- `GET /api/customers/{id}`
- `POST /api/customers`
- `PATCH /api/customers/{id}`
- `PATCH /api/customers/{id}/deactivate`
- `PATCH /api/customers/{id}/reactivate`
- `DELETE /api/customers/{id}`
- `POST /api/customers/merge`

## Typische Laravel-Zuordnung

- `CustomerController`
- `CustomerLifecycleController`
- `CustomerMergeController`

## Typische Middleware

- `auth`

## Typische Besonderheiten

- Customer ist Identität, nicht Contact
- `customer_number` ist fachlich zentrale Kennung
- Merge ist eine spezielle, atomare Fachoperation
- Contacts, Contracts und Actor sind eng an den Customer-Lebenszyklus gebunden
- historische Tickets und Nachrichten bleiben erhalten

---

# 4. Contacts-Gruppe

## Zweck

Die Contacts-Gruppe enthält Endpunkte für Kommunikationswege eines Customers.

## Fachlicher Bereich

- Contacts eines Customers anzeigen
- Contact-Detail
- Contact anlegen
- Contact bearbeiten
- Contact als primär setzen
- Contact verifizieren
- Contact deaktivieren
- Contact reaktivieren
- Contact soft deleten

## Zugehörige Use Cases

- Use Case 11: Customer-Contact anlegen
- Use Case 12: Contact als primär setzen
- Use Case 13: Contact verifizieren
- Use Case 14: Contact deaktivieren oder soft delete ausführen
- Use Case 15: Contact reaktivieren

## Typische Endpunkte

- `GET /api/customers/{customerId}/contacts`
- `GET /api/contacts/{id}`
- `POST /api/customers/{customerId}/contacts`
- `PATCH /api/contacts/{id}`
- `PATCH /api/contacts/{id}/set-primary`
- `PATCH /api/contacts/{id}/verify`
- `PATCH /api/contacts/{id}/deactivate`
- `PATCH /api/contacts/{id}/reactivate`
- `DELETE /api/contacts/{id}`

## Typische Laravel-Zuordnung

- `CustomerContactController`
- `ContactLifecycleController`

## Typische Middleware

- `auth`

## Typische Besonderheiten

- primärer Contact ist fachlich besonders wichtig
- Eindeutigkeit von `type + value` im aktiven Zustand
- letzter aktiver Contact eines aktiven Customers darf nicht ohne Gesamtoperation entfernt werden

---

# 5. Contracts-Gruppe

## Zweck

Die Contracts-Gruppe enthält Endpunkte für Vertrags-, Produkt- oder Leistungskontexte eines Customers.

## Fachlicher Bereich

- Contracts eines Customers anzeigen
- Contract-Detail
- Contract anlegen
- Contract bearbeiten
- Contract deaktivieren
- Contract reaktivieren
- Contract soft deleten
- Contract-Medien verwalten

## Zugehörige Use Cases

- Use Case 16: Contract für Customer anlegen
- Use Case 17: Contracts eines Customers anzeigen
- Use Case 18: Contract-Details anzeigen
- Use Case 19: Contract bearbeiten
- Use Case 20: Contract deaktivieren oder soft delete ausführen
- Use Case 21: Dokument an Contract anhängen

## Typische Endpunkte

- `GET /api/customers/{customerId}/contracts`
- `GET /api/contracts/{id}`
- `POST /api/customers/{customerId}/contracts`
- `PATCH /api/contracts/{id}`
- `PATCH /api/contracts/{id}/deactivate`
- `PATCH /api/contracts/{id}/reactivate`
- `DELETE /api/contracts/{id}`
- `POST /api/contracts/{id}/media`
- `GET /api/contracts/{id}/media`

## Typische Laravel-Zuordnung

- `CustomerContractController`
- `ContractController`
- `ContractLifecycleController`
- optional `ContractMediaController`

## Typische Middleware

- `auth`

## Typische Besonderheiten

- Contract gehört genau zu einem Customer
- Contract ist fachlich nicht identisch mit Customer oder Ticket
- Contract kann Dokumente und andere Medien besitzen
- Contract kann später für Ticket-Kontext, SLA oder Auswertung wichtig sein

---

# 6. Tickets-Gruppe

## Zweck

Die Tickets-Gruppe enthält Endpunkte für die Verwaltung und Bearbeitung fachlicher Supportfälle.

## Fachlicher Bereich

- Ticket-Liste
- Ticket-Detail
- manuelle Ticket-Anlage
- Bearbeiter-Zuweisung
- Statusänderung
- resolved
- close
- Kategorie setzen
- Priorität setzen
- Contract-Kontext setzen

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

## Typische Endpunkte

- `GET /api/tickets`
- `GET /api/tickets/{id}`
- `POST /api/tickets`
- `PATCH /api/tickets/{id}/assignment`
- `PATCH /api/tickets/{id}/status`
- `PATCH /api/tickets/{id}/resolve`
- `PATCH /api/tickets/{id}/close`
- `PATCH /api/tickets/{id}/category`
- `PATCH /api/tickets/{id}/priority`
- `PATCH /api/tickets/{id}/contract`

## Typische Laravel-Zuordnung

- `TicketController`
- `TicketAssignmentController`
- `TicketStatusController`
- `TicketContractController`

## Typische Middleware

- `auth`

## Typische Besonderheiten

- geschlossene Tickets sind fachlich besonders geschützt
- Status-Transitionen müssen strikt kontrolliert werden
- Statushistorie und Audit gehören eng zu diesem Bereich
- ein Ticket kann optional genau einem Contract desselben Customers zugeordnet sein
- Tickets sind Hauptobjekte mit historischer Bedeutung

---

# 7. Messages-Gruppe

## Zweck

Die Messages-Gruppe enthält Endpunkte für Kommunikationshistorie innerhalb von Tickets.

## Fachlicher Bereich

- Nachrichten eines Tickets anzeigen
- öffentliche Nachricht erstellen
- interne Nachricht erstellen

## Zugehörige Use Cases

- Use Case 1: Eingehende Kundenanfrage verarbeiten
- Use Case 3: Öffentliche Antwort an Customer senden
- Use Case 4: Interne Nachricht im Ticket erstellen

## Typische Endpunkte

- `GET /api/tickets/{ticketId}/messages`
- `POST /api/tickets/{ticketId}/messages/public`
- `POST /api/tickets/{ticketId}/messages/internal`

## Typische Laravel-Zuordnung

- `TicketMessageController`

## Typische Middleware

- `auth`

## Typische Besonderheiten

- interne Nachrichten nur von internen Benutzern
- öffentliche Nachrichten mit Contact- und Zustelllogik verknüpft
- geschlossene Tickets dürfen keine neuen Nachrichten erhalten
- Nachrichten können Medien oder Anhänge besitzen

---

# 8. Inbound- / System-Inputs-Gruppe

## Zweck

Diese Gruppe enthält systemseitige oder öffentliche Eingänge, die nicht wie normale interne Benutzer-Endpunkte funktionieren.

## Fachlicher Bereich

- eingehende Kundenkommunikation
- Webhooks
- externe Kanal-Eingänge
- automatische Erstverarbeitung eingehender Anfragen

## Zugehörige Use Cases

- Use Case 1: Eingehende Kundenanfrage verarbeiten

## Typische Endpunkte

- `POST /api/inbound/messages`

## Typische Laravel-Zuordnung

- `InboundMessageController`
- `WebhookController` je nach Kanal

## Typische Middleware / Absicherung

- keine normale interne Benutzer-Auth
- stattdessen:
  - Signaturprüfung
  - API-Key
  - Kanal-Token
  - Systemkontext

## Typische Besonderheiten

- keine normale interne Session
- Customer- und Ticket-Zuordnung müssen kontrolliert erfolgen
- Contract-Kontext kann optional erkannt werden
- unsichere automatische Zuordnungen dürfen nicht blind erzwungen werden
- ein Inbound-Prüffall kann entstehen
- dieser Bereich ist stark service-orientiert

---

# 9. Inbound-Review-Cases-Gruppe

## Zweck

Die Inbound-Review-Cases-Gruppe enthält Endpunkte für die manuelle Prüfung unklarer eingehender Anfragen.

## Fachlicher Bereich

- Prüffall-Liste
- Prüffall-Detail
- bestehendem Customer zuordnen
- durch neuen Customer lösen
- offen oder pending markieren

## Zugehörige Use Cases

- Use Case 2: Unklaren Inbound-Prüffall prüfen und entscheiden

## Typische Endpunkte

- `GET /api/inbound/review-cases`
- `GET /api/inbound/review-cases/{id}`
- `POST /api/inbound/review-cases/{id}/assign-customer`
- `POST /api/inbound/review-cases/{id}/create-customer`
- `PATCH /api/inbound/review-cases/{id}/mark-pending`

## Typische Laravel-Zuordnung

- `InboundReviewCaseController`

## Typische Middleware

- `auth`
- zusätzliche rollen- oder policy-basierte Prüfung für Inbound Reviewer oder gleichwertige Berechtigung

## Typische Besonderheiten

- unklare Eingänge dürfen nicht ignoriert werden
- diese Gruppe ist fachlich ein Prüf- und Triage-Bereich
- Entscheidungen zu Prüffällen sind auditpflichtig
- aus einem Prüffall kann Customer, Ticket und Nachricht hervorgehen

---

# 10. Categories-Gruppe

## Zweck

Die Categories-Gruppe enthält Endpunkte für die fachliche Einordnung von Tickets.

## Fachlicher Bereich

- Kategorie-Liste
- Kategorie-Detail
- Kategorie anlegen
- Kategorie bearbeiten
- Kategorie deaktivieren
- Kategorie reaktivieren
- Kategorie soft deleten

## Zugehörige Use Cases

- Use Case 31: Kategorie anlegen oder ändern
- Use Case 32: Kategorie deaktivieren oder soft delete ausführen

## Typische Endpunkte

- `GET /api/categories`
- `GET /api/categories/{id}`
- `POST /api/categories`
- `PATCH /api/categories/{id}`
- `PATCH /api/categories/{id}/deactivate`
- `PATCH /api/categories/{id}/reactivate`
- `DELETE /api/categories/{id}`

## Typische Laravel-Zuordnung

- `CategoryController`
- `CategoryLifecycleController`

## Typische Middleware

- `auth`

## Typische Besonderheiten

- deaktivierte Kategorien bleiben historisch an bestehenden Tickets erhalten
- neue Ticket-Zuweisungen auf deaktivierte Kategorien sind verboten

---

# 11. Media-Gruppe

## Zweck

Die Media-Gruppe enthält Endpunkte für Anhänge und andere Dateien an fachlichen Objekten.

## Fachlicher Bereich

- Medium an Ticket anhängen
- Medium an Nachricht anhängen
- Medium an Contract anhängen
- Medien zu Fachobjekten anzeigen

## Zugehörige Use Cases

- Use Case 21: Dokument an Contract anhängen
- Use Case 33: Datei an Nachricht, Ticket oder Contract anhängen

## Typische Endpunkte

- `POST /api/tickets/{ticketId}/media`
- `POST /api/messages/{messageId}/media`
- `POST /api/contracts/{id}/media`
- `GET /api/contracts/{id}/media`

## Typische Laravel-Zuordnung

- `TicketMediaController`
- `MessageMediaController`
- `ContractMediaController`

## Typische Middleware

- `auth`

## Typische Besonderheiten

- Medien sind fachlich relevante Anhänge
- typische Inhalte sind Screenshots, Fotos, PDFs und andere Dateien
- Medien werden polymorph an Fachobjekte gebunden
- Upload, Zuordnung und Historie müssen konsistent behandelt werden

---

## Empfohlene Route-Organisation in Laravel

Eine sinnvolle Route-Aufteilung könnte so aussehen:

- `routes/api.php` als Haupteinstieg
- interne Gruppierung nach Präfix und Middleware
- optionale Auslagerung in Teil-Dateien

Beispielhafte fachliche Gruppen:

- Auth-Routen
- Admin-Internal-User-Routen
- Customer-Routen
- Contact-Routen
- Contract-Routen
- Ticket-Routen
- Message-Routen
- Inbound-Routen
- Inbound-Review-Routen
- Category-Routen
- Media-Routen

---

## Empfohlene Controller-Gruppierung

Die Controller-Struktur soll die Endpoint-Gruppen klar widerspiegeln.

Beispiel:

- `AuthController`
- `ProfileController`
- `Admin/InternalUserController`
- `CustomerController`
- `CustomerMergeController`
- `CustomerContactController`
- `CustomerContractController`
- `ContractController`
- `ContractLifecycleController`
- `TicketController`
- `TicketAssignmentController`
- `TicketStatusController`
- `TicketContractController`
- `TicketMessageController`
- `InboundMessageController`
- `InboundReviewCaseController`
- `CategoryController`
- `TicketMediaController`
- `MessageMediaController`
- `ContractMediaController`

---

## Empfohlene Service- / Action-Gruppierung

Komplexe Fachlogik soll nicht direkt im Controller liegen.

Typische Actions oder Services wären zum Beispiel:

- `LoginInternalUserAction`
- `LogoutInternalUserAction`
- `UpdateOwnProfileAction`
- `CreateInternalUserAction`
- `UpdateInternalUserAction`
- `DeactivateInternalUserAction`
- `AssignRolesToInternalUserAction`
- `CreateCustomerAction`
- `UpdateCustomerAction`
- `MergeCustomersAction`
- `CreateCustomerContactAction`
- `SetPrimaryContactAction`
- `VerifyContactAction`
- `CreateContractAction`
- `UpdateContractAction`
- `DeactivateContractAction`
- `AttachContractMediaAction`
- `CreateTicketAction`
- `AssignTicketAction`
- `ChangeTicketStatusAction`
- `ResolveTicketAction`
- `CloseTicketAction`
- `SetTicketContractAction`
- `CreatePublicTicketMessageAction`
- `CreateInternalTicketMessageAction`
- `HandleInboundMessageAction`
- `CreateInboundReviewCaseAction`
- `ResolveInboundReviewCaseWithExistingCustomerAction`
- `ResolveInboundReviewCaseByCreatingCustomerAction`
- `AttachTicketMediaAction`
- `AttachMessageMediaAction`

---

## Nutzen für Tests

Die Endpoint-Gruppen helfen auch bei der Teststruktur.

Beispielhafte Testgruppen:

- Auth-Tests
- Internal-User-Tests
- Customer-Tests
- Contact-Tests
- Contract-Tests
- Ticket-Tests
- Message-Tests
- Inbound-Tests
- Inbound-Review-Tests
- Category-Tests
- Media-Tests

So kann jede Testgruppe genau einen klaren fachlichen Bereich prüfen.

---

## Nutzen für Agentic AI

Ein Agentic-AI-Modell soll aus dieser Datei erkennen:

- in welchem API-Bereich eine Aufgabe liegt
- welche Endpunkte typischerweise zusammengehören
- welche Controller wahrscheinlich betroffen sind
- welche Services betroffen sind
- welche Tests betroffen sind
- welche Middleware oder Policies relevant sind

Diese Datei soll helfen, dass API-Aufgaben nicht unsortiert über mehrere fachliche Bereiche verstreut umgesetzt werden.