# Smart Support Desk System

## Zweck

Diese Datei ist die zentrale Meta- und Arbeitsreferenz des Projekts.

Sie beschreibt:
- den Projektkontext
- die Dokumentstruktur
- die Rollen der Dokumentebenen
- die Lesereihenfolge
- die Dokumenthierarchie bei Widersprüchen
- den groben Arbeitsablauf pro Domain und Use Case

Fachliche Wahrheit liegt nicht in dieser Datei, sondern in `docs/domain/`.

---

## Technischer Stack

### Backend

- Laravel 13
- PHP 8.4
- PostgreSQL 16
- Laravel Sanctum

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
- DB-Port: `5432`

### QA

- Backend-QA über `curl` und `php artisan tinker`
- Frontend-QA als Human-in-the-loop Browserprüfung
- Playwright nur auf ausdrückliche Benutzeranforderung

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
    └── design-references/
        ├── app-shell.png
        ├── navigation-header.png
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
6. `docs/design-references/`

`docs/patterns/` liefern technische Muster, aber keine fachlichen Regeln.

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

Vor Beginn der Frontend-Umsetzung werden die visuellen Referenzen der Domain vorbereitet.

Dazu gehören:
- globale Layout- und Navigationsbilder in `docs/design-references/`
- getrennte Screen-Bilder pro Use Case unter `docs/design-references/{domain}/`

Während der Frontend-Umsetzung dienen diese Dateien als visuelle Vorlage.
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
- Visuelle Referenzen: `docs/design-references/`
