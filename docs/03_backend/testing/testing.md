# Backend Testing: Smart Support Desk System

## Zweck

Diese Datei beschreibt die Teststrategie für das Backend des Smart Support Desk Systems.

Sie definiert, wie fachliche Regeln, Nachbedingungen, API-Verträge und Konsistenzregeln in Tests überprüft werden sollen.

Die Datei ist keine Sammlung einzelner Testfälle, sondern die strukturierte Grundlage dafür, wie Tests im Projekt gedacht, abgeleitet und organisiert werden.

---

## Referenzdokumente

Diese Datei ist zusammen mit den folgenden Dokumenten zu lesen:

1. `docs/01_domain/01_miniworld.md`
2. `docs/01_domain/02_business-rules.md`
3. `docs/01_domain/03_er.md`
4. `docs/02_use-cases/use-cases.md`
5. `docs/03_backend/api-contracts/api-contracts.md`
6. `docs/03_backend/testing/testing.md`

Die Tests dürfen den fachlichen Regeln dieser Dokumente nicht widersprechen.

---

## Ziel der Tests

Die Backend-Tests sollen sicherstellen, dass:

- Use Cases fachlich korrekt umgesetzt sind
- Nachbedingungen tatsächlich erfüllt werden
- fachliche Ungültigkeiten verhindert werden
- API-Contracts eingehalten werden
- Authentifizierung und Autorisierung korrekt wirken
- RBAC und Rollenwechsel korrekt wirken
- Soft Delete und Reaktivierung konsistent ablaufen
- Audit-Logs korrekt erzeugt werden
- historische Daten erhalten bleiben
- atomare Vorgänge wirklich atomar ausgeführt werden
- Inbound-Verarbeitung kontrolliert erfolgt
- Contracts und Ticket-Contract-Kontexte fachlich korrekt behandelt werden
- Medien fachlich korrekt zugeordnet werden

---

## Grundprinzip der Testableitung

Die wichtigste Regel ist:

**Tests werden nicht aus Bauchgefühl geschrieben, sondern aus den fachlichen Dokumenten abgeleitet.**

Die Testableitung folgt dieser Reihenfolge:

1. Miniworld verstehen
2. Business Rules lesen
3. Use Case lesen
4. Nachbedingungen identifizieren
5. API-Contract lesen
6. technische Prüfungen daraus ableiten

---

## Verhältnis zwischen Nachbedingungen und Tests

Nachbedingungen bleiben im Use Case die **fachliche Wahrheit**.

Beispiel:

```md
### Nachbedingungen

- Der Customer ist im System angelegt.
- Der Customer besitzt mindestens einen aktiven Contact.
- Der Customer befindet sich in einem fachlich gültigen Zustand.
- Fachlich relevante Änderungen sind protokolliert.
```

Diese Nachbedingungen werden im Testing in **prüfbare technische Aussagen** übersetzt.

Zum Beispiel:

- ein Datensatz in `customers` existiert
- mindestens ein aktiver Datensatz in `customer_contacts` existiert
- kein fachlich ungültiger Zustand ist entstanden
- ein Audit-Log wurde erzeugt

---

## Testebenen

Das Backend verwendet mehrere Testebenen.

### 1. Feature-Tests

Feature-Tests prüfen das Verhalten der API-Endpunkte aus Sicht eines Clients.

Sie sind die wichtigste Testebene für dieses Projekt.

Typisch geprüft werden:

- HTTP-Status
- JSON-Response
- Authentifizierung
- Autorisierung
- RBAC-Rollenwirkungen
- Auswirkungen auf die Datenbank
- Audit-Logs
- Statushistorie
- Soft-Delete-Verhalten
- Medien-Zuordnung
- Inbound-Prüffall-Verhalten

### 2. Use-Case-nahe Application-Tests

Wenn Actions oder Services komplexe Fachlogik enthalten, kann zusätzlich diese Logik direkt getestet werden.

Beispiele:

- `MergeCustomersAction`
- `HandleInboundMessageAction`
- `ResolveInboundReviewCaseWithExistingCustomerAction`
- `CreateContractAction`
- `SetTicketContractAction`
- `ChangeTicketStatusAction`

Solche Tests sind sinnvoll, wenn:

- viele Fachregeln ohne HTTP-Kontext gelten
- atomare Änderungen geprüft werden sollen
- komplizierte Zustandswechsel kontrolliert werden müssen
- mehrere Hauptobjekte in einem Vorgang koordiniert werden

### 3. Unit-Tests

Unit-Tests sind optional und sollten gezielt für kleine, stabile, isolierte Regeln verwendet werden.

Beispiele:

- Enum-Mapping
- kleine Value-Objects
- Hilfsklassen
- pure Domain-Regeln ohne DB-Zugriff
- kleinere Rollen- oder Berechtigungshelfer

Für dieses Projekt sind **Feature-Tests wichtiger** als viele Unit-Tests.

---

## Testkategorien

### Authentifizierungstests

Prüfen:

- Login erfolgreich
- Login mit falschen Daten
- Login mit deaktiviertem internen Benutzer
- Logout erfolgreich
- Zugriff ohne Authentifizierung

### Autorisierungstests

Prüfen:

- unberechtigte interne Benutzer dürfen Admin-Endpunkte nicht aufrufen
- nur berechtigte interne Benutzer dürfen Inbound-Prüffälle bearbeiten
- nur berechtigte interne Benutzer dürfen Contracts verwalten
- unberechtigte Rollen bekommen `403 Forbidden`

### RBAC-Tests

Prüfen:

- Rollen werden korrekt zugewiesen
- Benutzer mit mehreren Rollen erhalten die korrekten Rechte
- Rollenänderungen wirken auf Endpunktzugriffe
- fehlende Rolle führt zu `403 Forbidden`
- Rollenzuweisungen werden korrekt persistiert

### Validierungstests

Prüfen:

- Pflichtfelder
- Wertebereiche
- Eindeutigkeit
- fachlich unzulässige Eingaben
- Datei-Validierung bei Medien-Uploads

### Fachregeltests

Prüfen:

- nur erlaubte Status-Transitionen
- letzter Contact darf nicht unzulässig entfernt werden
- kein Ticket auf deaktivierten internen Benutzer zuweisen
- keine neue Nachricht auf geschlossenem Ticket
- Ticket darf nur Contract desselben Customers referenzieren
- `customer_number` ist eindeutig
- Contact-Wert bleibt im aktiven Zustand eindeutig
- unklare Inbound-Eingänge werden nicht blind automatisch zugeordnet

### Soft-Delete- und Reaktivierungstests

Prüfen:

- Soft Delete erzeugt keinen Hard Delete
- abhängige Objekte werden konsistent mitsynchronisiert
- historische Daten bleiben erhalten
- Reaktivierung erzeugt wieder gültigen Zustand
- Contract- und Medienbezüge bleiben historisch konsistent

### Audit-Tests

Prüfen:

- Audit-Log wird bei relevanten Aktionen geschrieben
- korrekter Kontext ist gesetzt
- alte und neue Werte werden gespeichert, sofern fachlich sinnvoll
- Inbound-Prüffall-Entscheidungen werden auditierbar protokolliert
- Rollenänderungen werden auditierbar protokolliert

### Atomaritätstests

Prüfen:

- bei Fehler wird keine halbe Fachoperation gespeichert
- insbesondere bei:
  - Customer + erster Contact
  - Customer-Merge
  - Soft Delete mit abhängigen Objekten
  - Reaktivierung mit abhängigen Objekten
  - Contract-Anlage mit Medien, wenn im selben Vorgang gedacht
  - Inbound-Prüffall-Entscheidung
  - Inbound-Message-Verarbeitung

### Medien- und Datei-Tests

Prüfen:

- Medium wird korrekt gespeichert
- Medium wird dem richtigen Fachobjekt zugeordnet
- unzulässige Dateien werden abgelehnt
- Medien an Contract, Ticket und Nachricht funktionieren korrekt
- historische Medien-Zuordnung bleibt erhalten

---

## Teststruktur nach Fachbereichen

Die Teststruktur soll die fachlichen Bereiche des Systems widerspiegeln.

Typische Testgruppen sind:

- Auth
- Internal Users
- RBAC / Roles
- Customers
- Contacts
- Contracts
- Tickets
- Messages
- Inbound / System Inputs
- Inbound Review Cases
- Categories
- Media

So entsteht eine klare Verbindung zwischen:

- Endpoint Group
- API Contract
- Testdateien

---

## Testableitung aus Use Cases

Für jeden wichtigen Use Case sollen mindestens diese Dinge abgeleitet werden:

### 1. Success Case

Was muss bei erfolgreicher Ausführung wahr sein?

### 2. Failed Cases

Welche fachlichen oder technischen Fehlerfälle gibt es?

### 3. Datenbankfolgen

Welche Datensätze müssen erstellt, geändert, deaktiviert, soft deleted oder reaktiviert werden?

### 4. Historische Folgen

Welche Daten müssen erhalten bleiben?

### 5. Audit-Folgen

Welches Audit muss entstehen?

---

## Beispiel: Testableitung aus einem Use Case

### Beispiel Use Case

Customer manuell anlegen

### Fachliche Nachbedingungen

- Der Customer ist im System angelegt.
- Der Customer besitzt mindestens einen aktiven Contact.
- Der Customer befindet sich in einem fachlich gültigen Zustand.
- Fachlich relevante Änderungen sind protokolliert.

### Technische Testableitung

Daraus folgen mindestens diese Prüfungen:

- `customers` enthält den neuen Customer
- `customer_contacts` enthält mindestens einen aktiven Contact
- kein Customer ohne aktiven Contact wurde erzeugt
- `audit_logs` enthält einen Eintrag zur Customer-Anlage

---

## Beispielhafte Laravel-Assertions

Typische technische Prüfungen in Laravel wären:

```php
$this->assertDatabaseHas('customers', [
    'id' => $customerId,
]);

$this->assertDatabaseHas('customer_contacts', [
    'customer_id' => $customerId,
    'deleted_at' => null,
]);

$this->assertDatabaseHas('audit_logs', [
    'action' => 'customer_created',
]);
```

Diese Assertions sind nicht die fachliche Regel selbst, sondern die technische Überprüfung der fachlichen Nachbedingungen.

---

## Wichtige Testregeln pro Bereich

### Auth

Mindestens testen:

- Login erfolgreich
- Login mit falschem Passwort
- Login mit deaktiviertem internen Benutzer
- Logout erfolgreich
- Profil abrufen nur authentifiziert
- Profil ändern nur authentifiziert

### Internal Users / RBAC

Mindestens testen:

- nur berechtigte interne Benutzer dürfen Internal-User-Verwaltung nutzen
- internen Benutzer anlegen
- internen Benutzer bearbeiten
- Rollen zuweisen und ändern
- internen Benutzer deaktivieren
- internen Benutzer reaktivieren
- internen Benutzer soft deleten
- Actor-Synchronisation
- Ticket-Zuweisungen fachlich korrekt behandeln
- Benutzer mit Rolle `inbound_reviewer` darf Inbound-Prüffälle bearbeiten
- Benutzer ohne passende Rolle darf Contract-Verwaltung nicht ausführen

### Customers

Mindestens testen:

- Customer manuell anlegen
- `customer_number` wird gespeichert und bleibt eindeutig
- Customer bearbeiten
- Customer deaktivieren
- Customer reaktivieren
- Customer soft deleten
- Merge
- Contacts, Contracts und Actor konsistent behandeln
- historische Tickets und Nachrichten erhalten

### Contacts

Mindestens testen:

- Contact anlegen
- Contact bearbeiten
- primär setzen
- verifizieren
- deaktivieren
- reaktivieren
- soft deleten
- letzten aktiven Contact korrekt schützen
- Eindeutigkeit von Typ und Wert prüfen

### Contracts

Mindestens testen:

- Contract anlegen
- Contracts eines Customers anzeigen
- Contract-Detail anzeigen
- Contract bearbeiten
- Contract deaktivieren
- Contract reaktivieren
- Contract soft deleten
- Contract gehört genau zu einem Customer
- Contract-Medien korrekt zuordnen
- fachliche Konflikte bei doppelter Contract-Kennung prüfen, sofern vorgesehen

### Tickets

Mindestens testen:

- Ticket anlegen
- Ticket zuweisen
- Zuweisung entfernen
- Status ändern
- verbotene Status-Transitionen ablehnen
- resolved setzen
- schließen
- Kategorie ändern
- Priorität ändern
- Contract-Kontext setzen
- Contract-Kontext entfernen
- Ticket darf nicht Contract eines anderen Customers referenzieren
- keine Änderungen an geschlossenem Ticket zulassen

### Messages

Mindestens testen:

- öffentliche Nachricht anlegen
- interne Nachricht anlegen
- keine neue Nachricht auf geschlossenem Ticket
- interne Nachricht nur von internem Benutzer
- korrekter Actor
- Audit-Log
- Medien an Nachricht anhängen

### Inbound / System Inputs

Mindestens testen:

- eingehende Nachricht erfolgreich verarbeiten
- Customer-Zuordnung über `customer_number`
- Contact-basierte Customer-Zuordnung
- Contract-Erkennung, wenn sicher möglich
- neues Ticket bei Bedarf
- unsichere Zuordnung nicht blind erzwingen
- Inbound-Prüffall wird korrekt erzeugt
- Anhänge eingehender Kommunikation werden korrekt behandelt
- atomare Verarbeitung

### Inbound Review Cases

Mindestens testen:

- Prüffall-Liste anzeigen
- Prüffall-Detail anzeigen
- Prüffall einem bestehenden Customer zuordnen
- Prüffall durch neuen Customer lösen
- Prüffall pending markieren
- nur berechtigte Rolle darf Prüffälle entscheiden
- Audit bei Entscheidung
- aus Prüffall entstehen Customer, Ticket und Nachricht korrekt, wenn fachlich vorgesehen

### Categories

Mindestens testen:

- Kategorie anlegen
- Kategorie ändern
- Kategorie deaktivieren
- Kategorie reaktivieren
- Kategorie soft deleten
- bestehende Tickets behalten historische Kategorie
- neue Zuweisung auf deaktivierte Kategorie verhindern

### Media

Mindestens testen:

- Medium an Ticket anhängen
- Medium an Nachricht anhängen
- Medium an Contract anhängen
- unzulässiger Upload wird abgelehnt
- Medium bleibt historisch erhalten, wenn Fachobjekt deaktiviert oder soft deleted wird, sofern fachlich vorgesehen

---

## Postman und automatische Tests

Postman ist hilfreich für:

- frühe manuelle API-Prüfungen
- schnelles Testen von Requests und Responses
- Verständnis der API-Verträge

Aber Postman ersetzt nicht die automatisierten Backend-Tests.

Die eigentliche fachliche Sicherheit soll über automatisierte Tests im Projekt entstehen.

Die sinnvolle Reihenfolge ist:

1. API-Contract definieren
2. Endpoint mit Postman manuell prüfen
3. Feature-Test schreiben
4. Implementation festigen

---

## Testdaten und Fabriken

Für ein sauberes Testsystem sollten Fabriken und Test-Helfer genutzt werden.

Sinnvoll sind zum Beispiel:

- InternalUserFactory
- RoleFactory
- CustomerFactory
- ContactFactory
- ContractFactory
- TicketFactory
- CategoryFactory
- MessageFactory
- InboundReviewCaseFactory
- MediaFactory

Zusätzlich hilfreich:

- States für aktive / deaktivierte / soft deleted Objekte
- States für Rollen oder Mehrfachrollen
- Hilfsmethoden für Auth mit unterschiedlichen Rollen
- Hilfsmethoden für Standard-Setup
- Hilfsmethoden für Inbound-Testpayloads

---

## Testnamensgebung

Testnamen sollen fachlich verständlich sein.

Gute Beispiele:

- `admin_can_create_internal_user`
- `support_agent_cannot_manage_internal_users`
- `inbound_reviewer_can_resolve_inbound_review_case_with_existing_customer`
- `ticket_cannot_be_assigned_to_deactivated_internal_user`
- `ticket_contract_must_belong_to_same_customer`
- `last_active_contact_of_active_customer_cannot_be_deleted`
- `contract_manager_can_attach_media_to_contract`
- `inbound_message_without_safe_customer_match_creates_review_case`

Die Namen sollen möglichst aus fachlichen Regeln und Use Cases ableitbar sein.

---

## Wann eine DB-Transaction im Test wichtig ist

Tests sollen besonders auf atomare Vorgänge achten, wenn:

- mehrere Tabellen betroffen sind
- abhängige Objekte synchronisiert werden
- bei Fehlern kein halber Zustand entstehen darf

Wichtige Kandidaten:

- Customer-Anlage mit Contact
- Customer-Merge
- internen Benutzer deaktivieren mit Actor-Synchronisation
- Customer-Reaktivierung mit Contact-Reaktivierung
- Contract-Anlage mit weiteren abhängigen Objekten
- Inbound-Message-Verarbeitung
- Inbound-Prüffall-Entscheidung

---

## Teststrategische Besonderheiten für RBAC

Da das Projekt ein RBAC-fähiges Rollenmodell verwendet, sollen Tests nicht nur auf Authentifizierung prüfen, sondern auch auf fachlich passende Rollenwirkungen.

Das bedeutet insbesondere:

- Tests dürfen nicht nur zwischen „eingeloggt“ und „nicht eingeloggt“ unterscheiden
- fachlich relevante Funktionen sollen mindestens mit erlaubter und nicht erlaubter Rolle geprüft werden
- Mehrfachrollen müssen korrekt berücksichtigt werden
- Rollenänderungen sollen sich in Folgezugriffen korrekt auswirken

RBAC-Tests sollen bevorzugt auf API-Ebene erfolgen, damit echte Policies, Gates und Middleware mitgeprüft werden.

---

## Nutzen für Agentic AI

Ein Agentic-AI-Modell soll aus dieser Datei erkennen:

- wie Tests aus Use Cases abgeleitet werden
- dass Nachbedingungen technisch überprüfbar sein müssen
- welche Testebenen wichtig sind
- welche Bereiche besonders kritisch sind
- dass Tests nicht nur Statuscodes, sondern auch Datenbank- und Konsistenzfolgen prüfen sollen
- dass RBAC, Inbound-Prüffälle, Contracts und Medien zentrale Testbereiche des Systems sind

Die wichtigste Instruktion ist:

**Implementiere nicht nur den Happy Path.**  
**Leite aus Nachbedingungen, Failed Cases, Rollenwirkungen und Konsistenzregeln konkrete technische Tests ab.**