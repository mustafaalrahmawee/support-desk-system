# Backend Architecture: Smart Support Desk System

## Zweck

Diese Datei beschreibt die geplante Backend-Architektur des Smart Support Desk Systems.

Sie verbindet die fachlichen Dokumente mit einer sauberen technischen Struktur für Laravel und soll helfen, dass Implementierung, Tests und Agentic-AI-Arbeit nicht aus Intuition, sondern aus einer klaren Architektur heraus erfolgen.

Diese Datei beschreibt bewusst die Struktur, Verantwortlichkeiten und Schichten des Backends. Sie ersetzt nicht die Miniworld, Business Rules, ER-Modell, Use Cases oder API-Contracts.

---

## Referenzdokumente

Diese Datei ist zusammen mit den folgenden Dokumenten zu lesen:

1. `docs/01_domain/01_miniworld.md`
2. `docs/01_domain/02_business-rules.md`
3. `docs/01_domain/03_er.md`
4. `docs/02_use-cases/use-cases.md`
5. `docs/03_backend/api-contracts/api-contracts.md`
6. `docs/03_backend/architecture/architecture.md`

Die Architektur darf den fachlichen Regeln dieser Dokumente nicht widersprechen.

---

## Ziel der Architektur

Die Architektur soll sicherstellen, dass:

- fachliche Regeln nicht im Frontend versteckt werden
- fachliche Regeln nicht ungeordnet in Controller wandern
- atomare Fachoperationen sauber umgesetzt werden
- Änderungen auditierbar bleiben
- Soft Delete und Historie konsistent bleiben
- komplexe Use Cases testbar bleiben
- Rollen und Berechtigungen sauber strukturiert sind
- unklare eingehende Anfragen kontrolliert verarbeitet werden
- Contracts, Medien und Ticket-Kontext fachlich korrekt behandelt werden
- Agentic-AI zielgerichtet im richtigen Bereich arbeiten kann

---

## Architekturprinzipien

### 1. Fachlogik gehört in den Application Layer

Controller sollen nicht die eigentliche Fachlogik enthalten.

Controller sollen primär:

- Requests entgegennehmen
- Requests validieren oder validieren lassen
- Autorisierung anstoßen
- den passenden Use-Case-Service oder die passende Action aufrufen
- Response zurückgeben

Die fachlich relevante Logik gehört in Actions oder Services.

### 2. Validierung und Fachlogik sind getrennt

Formale Eingabeprüfung gehört in Request-Klassen.

Beispiele:

- Pflichtfelder
- Formate
- erlaubte Enum-Werte
- einfache Längenregeln
- einfache Exists-Regeln
- einfache Unique-Regeln, soweit fachlich passend

Komplexe Fachprüfungen gehören in den Application Layer.

Beispiele:

- letzter aktiver Contact darf nicht gelöscht werden
- Status-Transition ist nicht erlaubt
- deaktivierter interner Benutzer darf kein Ticket-Bearbeiter sein
- Ticket darf nur Contract desselben Customers referenzieren
- Inbound-Prüffall darf nicht blind automatisch entschieden werden
- Customer-Merge ist nur unter bestimmten Konfliktbedingungen zulässig

### 3. Fachlich zusammenhängende Änderungen müssen atomar sein

Wenn ein fachlicher Vorgang mehrere zusammenhängende Änderungen umfasst, muss er atomar ausgeführt werden.

Das bedeutet in Laravel in der Regel:

- `DB::transaction()`

Wichtige Beispiele:

- Customer mit erstem Contact anlegen
- Customer-Merge
- internen Benutzer deaktivieren und Actor mitsynchronisieren
- Customer deaktivieren und Contacts mitsynchronisieren
- Contract mit erstem Dokument anlegen, wenn dies in einem zusammenhängenden Vorgang erfolgt
- Inbound-Prüffall entscheiden und daraus Customer, Ticket und Nachricht erzeugen
- eingehende Nachricht Customer und Ticket zuordnen

### 4. Historische Daten haben hohen Wert

Tickets, Nachrichten, Contract-Dokumente und Audit-Logs sind historisch relevante Objekte.

Die Architektur muss sicherstellen, dass historische Daten nicht unzulässig verloren gehen.

### 5. Soft Delete ist fachlich relevant

Soft Delete ist nicht nur eine technische ORM-Funktion, sondern Teil der Fachlogik.

Die Architektur muss daher definieren, wo Soft Delete fachlich verarbeitet wird und welche abhängigen Objekte mitbetroffen sind.

### 6. Rollen und Autorisierung müssen sauber gekapselt sein

Das System verwendet ein rollenbasiertes Berechtigungsmodell für interne Benutzer.

Autorisierung darf nicht ungeordnet durch den Code verteilt werden.

Feingranulare Entscheidungen sollen über Policies, Gates oder klar strukturierte Autorisierungsdienste erfolgen.

Direkte, verstreute Rollenabfragen in Controllern oder Views sollen vermieden werden.

### 7. Inbound-Verarbeitung ist ein eigener Fachbereich

Die Verarbeitung eingehender Kommunikation ist fachlich nicht dasselbe wie normale Ticketbearbeitung durch interne Benutzer.

Unklare Eingänge, automatische Zuordnung, Prüfwarteschlange und manuelle Inbound-Entscheidungen gehören zu einem eigenen fachlichen Bereich mit eigener technischer Struktur.

---

## Architekturschichten

Das Backend ist in diese Schichten gedacht:

### A. HTTP-Schicht

Verantwortung:

- Routing
- Controller
- Form Requests
- JSON Responses
- Middleware
- Policy- oder Gate-gebundene Zugriffe

Typische Elemente:

- `routes/api.php`
- Controller
- Request-Klassen
- Middleware
- Policies / Gates

### B. Application Layer

Verantwortung:

- Umsetzung eines Use Cases
- Koordination mehrerer Modelle
- fachliche Konsistenz
- atomare Operationen
- Auslösen von Audit
- Aufruf weiterer Services
- kontrollierte Behandlung fachlicher Konflikte

Typische Elemente:

- Actions
- Use-Case-Services
- Orchestrierungslogik

Diese Schicht ist die wichtigste Schicht für die eigentliche Fachlogik.

### C. Domain-nahe Modelle / Eloquent Models

Verantwortung:

- Datenrepräsentation
- Beziehungen
- einfache modelnahe Hilfslogik
- Scopes
- Soft Delete Verhalten
- Casts

Die Models sollen nicht alle komplexe Fachlogik selbst tragen, aber durchaus kleine, stabile fachliche Hilfen besitzen.

### D. Infrastruktur-Schicht

Verantwortung:

- Persistence
- Dateispeicherung
- externe Eingänge
- Auth-Mechanismus
- Sanctum oder anderer Token-Mechanismus
- Mail / WhatsApp / Webhook-Anbindung
- Audit-Speicherung

---

## Empfohlene Laravel-Struktur

Eine mögliche Struktur wäre:

```text
app/
  Actions/
    Auth/
    InternalUsers/
    Roles/
    Tickets/
    Customers/
    Contacts/
    Contracts/
    Categories/
    Messages/
    Inbound/
    Media/

  Http/
    Controllers/
      Auth/
      Admin/
      Tickets/
      Customers/
      Contacts/
      Contracts/
      Categories/
      Messages/
      Inbound/
      Media/

    Requests/
      Auth/
      InternalUsers/
      Tickets/
      Customers/
      Contacts/
      Contracts/
      Categories/
      Messages/
      Inbound/
      Media/

    Middleware/

  Models/

  Policies/

  Services/
    Audit/
    Inbound/
    Media/
    Notifications/
    Authorization/

  Support/
    Enums/
    Exceptions/
    Helpers/
```

Diese Struktur ist nicht die einzige mögliche, aber sie passt gut zur fachlichen Gruppierung des Projekts.

---

## Rolle der Controller

Controller sollen bewusst schlank bleiben.

Ein Controller soll typischerweise:

1. Request entgegennehmen
2. validierte Eingaben holen
3. Autorisierung oder Policy-Aufruf durchführen
4. Action oder Service aufrufen
5. Response zurückgeben

Ein Controller soll nicht selbst:

- komplexe Merge-Logik schreiben
- Soft-Delete-Synchronisation steuern
- Status-Transition-Regeln vollständig prüfen
- Contract-Zuordnung fachlich validieren
- Inbound-Prüffälle manuell entscheiden
- große Entscheidungsbäume enthalten

---

## Rolle der Form Requests

Form Requests sollen formale und einfache Eingaberegeln kapseln.

Geeignet für Form Requests:

- required
- string
- email
- max-Länge
- Enum-Werte
- einfache Exists-Regeln
- einfache Unique-Regeln, soweit fachlich passend
- Datei-Validierung
- Basis-Validierung für Arrays wie `role_names`

Nicht geeignet als alleinige Schicht für:

- komplexe Use-Case-Regeln
- RBAC-Entscheidungen
- Soft-Delete-Folgelogik
- atomare Operationen
- mehrstufige Fachentscheidungen
- Konfliktbehandlung bei Inbound-Prüffällen

---

## Rolle der Policies und Autorisierung

Policies oder Gates sollen prüfen, ob ein interner Benutzer eine bestimmte fachliche Aktion ausführen darf.

Beispiele:

- darf internen Benutzer verwalten
- darf Ticket bearbeiten
- darf Contract verwalten
- darf Inbound-Prüffälle prüfen
- darf Kategorie verwalten
- darf Customer mergen

Autorisierung soll nicht quer verteilt im Code versteckt sein.

Für feinere Fachentscheidungen ist eine Kombination aus:

- Middleware für grobe Grenzen
- Policies/Gates für kontextbezogene Freigaben

bevorzugt.

### Empfohlene grobe Trennung

Middleware eignet sich besonders für:

- `auth`
- aktive Sitzung oder gültigen Token
- allgemeine Systemzugangskontrolle

Policies oder Gates eignen sich besonders für:

- Ticketaktionen
- Customer-Merge
- Contract-Verwaltung
- Inbound-Prüffall-Entscheidungen
- Rollen- und Benutzerverwaltung im Fachkontext

---

## Rolle der Actions / Use-Case-Services

Actions oder Use-Case-Services bilden den Kern der Umsetzung.

Eine Action soll möglichst genau einen fachlichen Anwendungsfall oder eine klar abgegrenzte Operation abbilden.

Beispiele:

- `LoginInternalUserAction`
- `LogoutInternalUserAction`
- `CreateInternalUserAction`
- `UpdateInternalUserAction`
- `DeactivateInternalUserAction`
- `AssignRolesToInternalUserAction`
- `CreateTicketAction`
- `AssignTicketAction`
- `ChangeTicketStatusAction`
- `ResolveTicketAction`
- `CloseTicketAction`
- `SetTicketContractAction`
- `CreateCustomerAction`
- `UpdateCustomerAction`
- `MergeCustomersAction`
- `CreateCustomerContactAction`
- `SetPrimaryContactAction`
- `VerifyContactAction`
- `CreateContractAction`
- `UpdateContractAction`
- `AttachContractMediaAction`
- `HandleInboundMessageAction`
- `CreateInboundReviewCaseAction`
- `ResolveInboundReviewCaseWithExistingCustomerAction`
- `ResolveInboundReviewCaseByCreatingCustomerAction`

---

## Umgang mit Eloquent Models

Eloquent bleibt das Persistenzmodell des Projekts.

Die Models dürfen enthalten:

- Beziehungen
- Scopes
- Casts
- Soft Delete
- kleinere Hilfsmethoden

Beispiele sinnvoller Model-Hilfen:

- `isClosed()`
- `isActive()`
- `isCustomerActor()`
- `isInternalUserActor()`
- `hasRole(string $roleName)`
- `belongsToCustomer(Customer $customer)` auf Contract-Ebene
- `hasSameCustomerAs(Ticket $ticket)` als kleine Hilfslogik

Was eher nicht in Models gehört:

- große Orchestrierungslogik
- mehrstufige Merge-Logik
- komplexe Cross-Entity-Operationen
- umfangreiche Statusprozesslogik
- Inbound-Prüfentscheidungen
- Medienverarbeitungsabläufe mit fachlicher Orchestrierung

---

## Umgang mit Rollenmodell und RBAC

Das Projekt verwendet ein RBAC-fähiges Rollenmodell.

### Grundidee

- `internal_users` repräsentieren interne Systembenutzer
- `roles` repräsentieren fachliche Rollen
- `internal_user_roles` ordnen Rollen internen Benutzern zu

### Ziel

Die Architektur soll von Anfang an so gestaltet werden, dass ein interner Benutzer eine oder mehrere Rollen besitzen kann.

### Wichtige Regeln

- Fachlogik soll nicht an einem einzelnen `role`-String im gesamten Code hängen
- Rollenabfragen sollen zentralisiert werden
- Policies und Gates sollen auf Rollen oder Berechtigungen aufbauen
- spätere Erweiterungen zu feineren Berechtigungsmodellen sollen ohne Grundumbau möglich bleiben

### Technische Folgerung

Das RBAC-Modell ist Teil der technischen Autorisierungsschicht, nicht der eigentlichen Fachlogik einzelner Use Cases.

---

## Umgang mit Exceptions

Fachliche Fehler sollten nicht als zufällige technische Exceptions behandelt werden.

Sinnvoll ist eine klare Exception-Struktur, zum Beispiel:

- `BusinessRuleViolationException`
- `UnauthorizedBusinessActionException`
- `InvalidStatusTransitionException`
- `MergeConflictException`
- `LastActiveContactRemovalException`
- `InboundResolutionConflictException`
- `CrossCustomerContractAssignmentException`

Diese Exceptions können dann auf passende API-Responses abgebildet werden, oft `409` oder `422`.

---

## Umgang mit Audit

Audit ist ein eigener fachlicher Bereich.

Die Architektur soll sicherstellen, dass relevante Änderungen nicht vergessen werden.

Sinnvoll ist ein zentraler Audit-Service oder eine klar definierte Audit-Aktion.

Beispiele:

- `AuditLogger`
- `WriteAuditLogAction`

Wichtig ist, dass Audit nicht zufällig verteilt, sondern konsistent ausgelöst wird.

Besonders auditrelevant sind in diesem Projekt:

- Rollen- oder Benutzeränderungen
- Inbound-Prüffall-Entscheidungen
- Contract-Anlage und Contract-Änderungen
- Ticket-Contract-Kontextwechsel
- Medien an Contract oder Nachricht
- Customer-Merge
- Soft Delete und Reaktivierung

---

## Umgang mit Soft Delete und Reaktivierung

Soft Delete und Reaktivierung sollen nicht nur direkt über Model-Methoden im Controller aufgerufen werden.

Stattdessen sollen fachlich relevante Vorgänge wie:

- internen Benutzer deaktivieren
- Customer deaktivieren
- Contact deaktivieren
- Contract deaktivieren
- Customer reaktivieren
- Contact reaktivieren
- Contract reaktivieren

über Actions oder Services laufen, damit:

- abhängige Objekte mitsynchronisiert werden
- Audit erzeugt wird
- Konflikte abgefangen werden
- Tests klare Use-Case-Einstiege haben

---

## Umgang mit Medien und Dateien

Das Projekt verwendet eine polymorphe Medienstruktur.

### Bedeutung für die Architektur

Medien sind keine bloß technische Zusatzfunktion, sondern fachlich relevante Anhänge.

Sie können insbesondere an:

- Nachrichten
- Tickets
- Contracts

gehängt werden.

### Architekturelle Folgen

- Datei-Upload und Medien-Zuordnung sollen über klar strukturierte Actions oder Services laufen
- Medien mit fachlichem Dokumentationswert dürfen nicht unzulässig verloren gehen
- Medienverarbeitung soll fachlich korrekt auditierbar sein, wenn der Vorgang fachlich relevant ist
- Controller sollen Upload-Logik nicht selbst orchestrieren

---

## Umgang mit eingehenden Systemnachrichten

Der Inbound-Bereich ist architektonisch besonders.

Warum?

Weil hier:

- kein normaler interner Benutzer agiert
- externe oder systemseitige Inputs ankommen
- Customer-Zuordnung und Ticket-Zuordnung automatisch oder halbautomatisch erfolgen
- Unsicherheit fachlich relevant sein kann
- ein Inbound-Prüffall entstehen kann

Deshalb sollte der Inbound-Bereich nicht in normalen UI-nahen Ticket-Controllern versteckt sein, sondern einen eigenen Controller und eigene Actions haben.

### Typische Inbound-Bausteine

- `InboundMessageController`
- `HandleInboundMessageAction`
- `InboundResolverService`
- `CreateInboundReviewCaseAction`
- `ResolveInboundReviewCase...` Actions

---

## Authentifizierung im Backend

Für ein API-orientiertes Laravel-Projekt ist ein sauberer Auth-Mechanismus wichtig.

Sinnvolle Optionen:

- Laravel Sanctum für API-Tokens oder SPA-nahe Auth
- Session-basierte Auth, wenn die Architektur das vorsieht

### In diesem Projekt besonders relevant

- `personal_access_tokens` sind optional und technisch, nicht fachliches Kernobjekt
- Tokens sind polymorph an das authentifizierbare Modell gebunden
- im aktuellen Projekt zeigen Tokens typischerweise auf `internal_users`

Wichtig ist:

- die fachliche Auth-Regel bleibt gleich
- nur authentifizierte interne Benutzer dürfen interne Endpunkte verwenden
- administrative Endpunkte benötigen zusätzliche Autorisierung

---

## Verbindung zwischen Use Cases und Architektur

Jeder Use Case soll in der Architektur eine klare technische Heimat haben.

Beispiele:

### Customer manuell anlegen

- Route
- Controller
- Form Request
- `CreateCustomerAction`
- Model-Zugriffe
- Audit
- Response

### Inbound-Prüffall entscheiden

- Route
- `InboundReviewCaseController`
- Form Request
- passende Resolve-Action
- Customer- und Ticket-Services
- Audit
- Response

### Contract anlegen

- Route
- `ContractController`
- Form Request
- `CreateContractAction`
- Model-Zugriffe
- Audit
- Response

Diese Zuordnung hilft, dass ein Use Case nicht verstreut und zufällig umgesetzt wird.

---

## Beziehung zur Testarchitektur

Die Architektur ist eng mit der Teststrategie verbunden.

Weil Actions / Services klar abgegrenzte Fachoperationen sind, können sie:

- sauber durch Feature-Tests geprüft werden
- zusätzlich use-case-nah getestet werden
- durch Agentic-AI leichter implementiert und überprüft werden

Besonders testkritische Bereiche in diesem Projekt sind:

- RBAC und Autorisierung
- Inbound-Verarbeitung
- Customer-Zuordnung
- Ticket-Contract-Kontext
- Contract-Verwaltung
- Soft Delete mit abhängigen Objekten
- Medien-Zuordnung
- Audit

Eine chaotische Architektur führt fast immer zu chaotischen Tests.

---

## Nutzen für Agentic AI

Ein Agentic-AI-Modell soll aus dieser Datei verstehen:

- wo Fachlogik hingehört
- dass Controller schlank bleiben sollen
- dass Use Cases über Actions / Services umgesetzt werden sollen
- dass atomare Vorgänge Transaktionen brauchen
- dass Soft Delete, Audit und Historie zentrale Architekturthemen sind
- dass Rollen und Autorisierung sauber gekapselt sein müssen
- dass Inbound-Verarbeitung ein eigener Fachbereich ist
- dass Medien und Contracts keine Nebenobjekte, sondern fachlich relevante Teile des Systems sind
- dass nicht alles in Models oder Requests gepackt werden soll

Die wichtigste Architektur-Instruktion lautet:

**Implementiere fachliche Use Cases im Application Layer.**  
**Halte Controller schlank.**  
**Nutze Requests für formale Validierung.**  
**Nutze Policies und Gates für Autorisierung.**  
**Nutze Transaktionen für atomare Fachvorgänge.**