# Smart Support Desk System

## Projektbeschreibung

Smart Support Desk System ist eine Laravel-basierte B2B-Support-Desk-Anwendung zur strukturierten Verwaltung und Bearbeitung von Kundenanfragen.

Das System verwaltet Customers, Contacts, Contracts, Tickets, Nachrichten, Inbound-Prüffälle, Medien, Audit-Logs und interne Benutzerkonten. Ziel ist es, einen fachlich sauberen Business-Core aufzubauen, der Authentifizierung, rollenbasierte Berechtigungen, Ticketbearbeitung, Kommunikationshistorie, Soft Delete und nachvollziehbare Auditierung unterstützt.

Der aktuelle Fokus liegt auf Phase 1: der vollständigen und sauberen Umsetzung des klassischen Laravel-Business-Cores. AI-, LLM-, RAG- und Agentic-AI-Erweiterungen sind bewusst nicht Teil der aktuellen Implementierungsphase. Sie können später ergänzt werden, nachdem die fachliche Grundlage stabil umgesetzt und die relevanten LLM-Konzepte verstanden wurden.

---

## Zweck dieser Datei

Diese Datei ist die zentrale Meta- und Arbeitsreferenz des Projekts.

Sie beschreibt:
- den Projektkontext und den aktuellen Entwicklungsfokus
- die Dokumentstruktur
- die Rollen der Dokumentebenen
- die Lesereihenfolge
- die Dokumenthierarchie bei Widersprüchen
- den groben Arbeitsablauf pro Domain und Use Case
- die Phasenroadmap

Fachliche Wahrheit liegt nicht in dieser Datei, sondern in `docs/domain/`.

---

## Technischer Stack

### Backend

- Laravel 13
- PHP 8.4
- MySQL 8.4
- Laravel Sanctum
- Spatie Laravel Permission
- Laravel Queues
- Laravel Storage

### Frontend

- Vue.js
- Tailwind CSS
- FontAwesomeIcon
- Pinia
- ofetch
- Vuelidate

### Infrastruktur

- Docker Compose
- API-Port: `8000`
- App-Port: `5173`
- DB-Port: `3306`

### QA

- Backend-QA über `curl` und `php artisan tinker`
- Frontend-QA als Human-in-the-loop Browserprüfung
- Playwright nur auf ausdrückliche Benutzeranforderung

---

## Aktueller Fokus

Der aktuelle Entwicklungsfokus liegt ausschließlich auf Phase 1:

- Laravel Business-Core
- MySQL-Datenmodell
- Authentifizierung
- Rollen und Berechtigungen
- Customers
- Contacts
- Contracts
- Tickets
- Ticket Messages
- Inbound Review Cases
- Media
- Audit Logs
- Soft Delete
- Service Layer
- Form Requests
- Policies
- Transactions
- Tests

AI-Features werden in dieser Phase nicht implementiert.

---

## Strategische Architekturentscheidung

Der Hauptstack bleibt Laravel + MySQL. Laravel bildet den Business-Core, erzwingt die fachlichen Regeln und persistiert alle fachlich relevanten Daten. MySQL bleibt die relationale Hauptdatenbank für Customers, Contacts, Contracts, Tickets, Messages, Inbound Review Cases, Media, Audit Logs und interne Benutzerkonten.

Vue ist das Frontend für operative Support-Desk-Workflows. Rollen, Berechtigungen, Statuslogik und fachliche Entscheidungen werden nicht im Frontend erfunden, sondern aus Backend-Regeln und dokumentierten Fachregeln abgeleitet.

---

## Phasenroadmap

Phase 1 — Laravel Business-Core: Auth, RBAC, Customers, Contacts, Contracts, Actors, Categories, Tickets, Messages, Status Histories, Inbound Review Cases, Media, Audit Logs, Soft Delete, Policies, Form Requests, Service Layer, Transactions, Tests.

Weitere Phasen werden erst nach Abschluss und Stabilisierung von Phase 1 konkretisiert.

---

## Spätere mögliche Erweiterungen

Nach Abschluss des stabilen Business-Cores kann das System schrittweise um AI-Features erweitert werden, zum Beispiel:

- Ticket-Zusammenfassungen
- Antwortvorschläge
- semantische Suche über Support- oder Vertragsdokumente
- RAG-basierte Unterstützung für Support Agents
- kontrollierte Human-in-the-loop Workflows

Diese Erweiterungen sind bewusst spätere Phasen und werden erst umgesetzt, wenn der klassische Business-Core vollständig verstanden, implementiert und getestet ist.

---

## Portfolio-Ziele

Dieses Projekt demonstriert: Business Domain Modeling, relationales Datenbankdesign, MySQL Constraints und Indexing, Laravel Service-Layer-Architektur, rollenbasierte Autorisierung, Audit Logging, Soft Delete und historische Nachvollziehbarkeit, Queue-basierte Hintergrundverarbeitung, saubere API-Contracts, Form Requests, Policies, Transactions und Tests.

---

## Dokumentstruktur

    docs/
    ├── domain/
    │   ├── 01_miniworld.md
    │   ├── 02_business-rules.md
    │   └── 03_er.md
    ├── README.md
    ├── by-domain/
    │   ├── auth.md
    │   ├── users.md
    │   ├── customers.md
    │   ├── contacts.md
    │   ├── contracts.md
    │   ├── tickets.md
    │   ├── messages.md
    │   ├── inbound.md
    │   ├── categories.md
    │   └── media.md
    ├── by-use-case/
    │   ├── uc36_login.md
    │   ├── uc37_logout.md
    │   ├── uc38_profile_show.md
    │   ├── uc39_profile_update.md
    │   └── ...
    ├── patterns/
    │   ├── backend-laravel.md
    │   └── frontend-vue.md
    ├── design-prompts/
    │   ├── auth/
    │   │   └── ...
    │   ├── users/
    │   │   └── ...
    │   └── ...
    └── design-references/
        ├── app-shell.png
        ├── auth/
        │   ├── uc36_login.png
        │   └── ...
        ├── users/
        │   ├── uc40_users_list.png
        │   ├── uc41_user_create.png
        │   ├── uc42_user_edit.png
        │   └── uc43_user_deactivate.png
        └── ...

---

## Rollen der Dokumentebenen

### `docs/domain/`

Fachliche Wahrheit.

Enthält:
- Miniworld
- Business Rules
- ER-Modell

Diese Dateien definieren:
- fachliche Objekte
- Beziehungen
- Rollen
- Statuslogik
- Constraints
- fachliche Grundprinzipien

### `docs/by-domain/`

Koordinator pro Domain.

Enthält:
- fachlichen Scope der Domain
- zugehörige Use Cases
- empfohlene Reihenfolge
- Abhängigkeiten
- gemeinsame Bausteine
- sinnvolle Session-Bundles

Diese Dateien enthalten keine vollständige Umsetzungs-Pipeline.

### `docs/by-use-case/`

Pipeline pro Use Case.

Jede Datei beschreibt einen vollständigen Use Case in einer festen Struktur:
1. Use Case
2. API-Contract
3. Backend-Architektur
4. Backend-QA
5. Frontend-Architektur
6. Screen-Flow
7. UI-Regeln
8. UI-Referenz
9. Frontend-QA

### `docs/patterns/`

Technische Muster.

Enthält wiederverwendbare technische Standardmuster für:
- Laravel
- Vue
- Pinia
- Vuelidate
- ofetch


Diese Dateien liefern technische Standardstrukturen, aber keine fachliche Wahrheit.

### `docs/design-prompts/`

Design-Prompts pro Domain und Use Case.

Enthält:
- Prompt-Grundlagen für die Erstellung visueller Referenzen
- Domain-spezifische UI-Anforderungen
- Use-Case-spezifische Screen-Vorgaben

Diese Dateien dienen nur als Vorbereitung und Beschreibung für Design-Referenzen. Sie definieren keine fachliche Wahrheit.

### `docs/design-references/`

Visuelle Referenzen für Layout, Navigation und Use-Case-Screens.

Enthält:
- globale Layout- und Navigationsbilder wie `app-shell.png` und `navigation-header.png`
- Domain-Unterordner mit Screen-Referenzen pro Use Case
- z. B. `users/uc40_users_list.png` oder `users/uc41_user_create.png`

Diese Dateien dienen nur als visuelle Vorlage für:
- Layout
- Hierarchie
- Abstände
- Kartenstruktur
- Header
- Navigation
- Formdarstellung
- allgemeine UI-Konsistenz

Diese Dateien definieren keine fachliche Wahrheit.

### `docs/README.md`

Zentrale Meta- und Arbeitsreferenz.

Diese Datei steuert nicht die fachliche Wahrheit, sondern die Struktur, Leselogik und den groben Arbeitsablauf.

---

## Leseregeln

- `docs/domain/` immer zuerst lesen
- `docs/README.md` für Arbeitslogik und Dokumentstruktur lesen
- `docs/by-domain/` lesen, um eine Domain-Session zu planen
- `docs/by-use-case/` lesen, um einen konkreten Use Case umzusetzen
- `docs/patterns/` nur für technische Muster verwenden
- `docs/design-prompts/` nur für die Vorbereitung visueller Referenzen verwenden
- `docs/design-references/` nur als visuelle Vorlage verwenden
- keine große Datei laden, wenn eine kleinere passende Datei ausreicht

---

## Dokumenthierarchie bei Widersprüchen

Bei Widersprüchen gilt diese Reihenfolge:

1. `docs/domain/`
2. `docs/by-use-case/`
3. `docs/by-domain/`
4. `docs/README.md`
5. `docs/patterns/`
6. `docs/design-prompts/`
7. `docs/design-references/`

`docs/patterns/` liefern technische Muster, aber keine fachlichen Regeln.

`docs/design-prompts/` liefern Vorgaben für visuelle Referenzen, aber keine fachlichen Regeln.

`docs/design-references/` liefern visuelle Vorlagen, aber keine fachlichen Regeln.

---

## Arbeitsablauf

### Vor einer Domain-Session

1. `docs/domain/01_miniworld.md` lesen
2. `docs/domain/02_business-rules.md` lesen
3. `docs/domain/03_er.md` lesen
4. `docs/README.md` lesen
5. `docs/by-domain/{domain}.md` lesen

### Vor einer Frontend-Domain-Session

Vor Beginn der Frontend-Umsetzung werden die Design-Prompts und visuellen Referenzen der Domain vorbereitet.

Dazu gehören:
- Design-Prompts pro Domain und Use Case unter `docs/design-prompts/{domain}/`
- globale Layout- und Navigationsbilder in `docs/design-references/app-shell.png`
- getrennte Screen-Bilder pro Use Case unter `docs/design-references/{domain}/`

Während der Frontend-Umsetzung dienen diese Dateien als Prompt-Grundlage und visuelle Vorlage.
Die fachliche Umsetzung bleibt weiterhin vollständig an die dokumentierten Projektdateien gebunden.

### Pro Use Case

1. `docs/by-use-case/{uc}.md` lesen
2. Backend anhand der Use-Case-Dokumentation umsetzen
3. Backend-QA-Plan erzeugen, manuelle Benutzerergebnisse auswerten
4. visuelle Referenzen prüfen
5. Frontend anhand der Use-Case-Dokumentation, der Pattern-Dateien und der Design-Referenzen umsetzen
6. Frontend-QA-Plan erzeugen, manuelle Benutzerergebnisse auswerten

---

## Grundregeln

- Nichts erfinden
- Nichts fachlich ergänzen
- Keine Felder, Rollen, Status oder Regeln raten
- Fachliche Wahrheit immer aus `docs/domain/` ableiten
- `docs/patterns/` nur für technische Standardstrukturen nutzen
- `docs/design-references/` nur für visuelle Orientierung nutzen

---

## Verweise

- Fachliche Wahrheit: `docs/domain/`
- Domain-Koordination: `docs/by-domain/`
- Use-Case-Pipeline: `docs/by-use-case/`
- Backend-Muster: `docs/patterns/backend-laravel.md`
- Frontend-Muster: `docs/patterns/frontend-vue.md`
- Design-Prompts: `docs/design-prompts/`
- Visuelle Referenzen: `docs/design-references/`
