---
name: frontend-code-review
description: Read-only Code-Review für Frontend-Dateien einer Domain. Prüft Architektur, Validierung, Styling, Listenverhalten und fachliche Treue.
---

# frontend-code-review

## Zweck

Read-only Code-Review für Frontend-Dateien eines Use Cases oder einer Domain nach Abschluss einer Implementierungs-Session.

Für Review-Regeln und Ablauf gilt `AGENTS.md`.
Für fachliche Wahrheit und Dokumenthierarchie gilt `docs/README.md`.
Für technische Muster gilt `docs/patterns/frontend-vue.md`.

---

## Eingabe

Der Hauptagent übergibt:

- Domain oder Bundle
- Liste der geänderten Frontend-Dateien (aus Git)

---

## Prüfbereiche

### Architektur

- Pages sind Use-Case-orientierte vollständige Screens
- Components sind UI-Bausteine, keine Träger fachlicher Wahrheit
- Stores enthalten API-nahe Logik, keine Fachlogik
- Geschützte Pages verwenden `AuthenticatedLayout`, öffentliche Pages verwenden `PublicLayout`
- Request-Aufrufe laufen über `useApiFetch()`, kein rohes Request-Handling

### Formular und Validierung

- Formulare verwenden Vuelidate
- Backend-Validierungsfehler werden im UI sichtbar dargestellt
- Validierungsregeln entsprechen der Use-Case-Dokumentation

### Styling und Konsistenz

- Tailwind CSS für Layout und Komponenten, kein freies CSS ohne Ausnahmefall
- UI-Struktur orientiert sich an den Design-Referenzen aus `docs/design-references/`
- Konsistente Abstände, Hierarchie und Kartenstruktur

### Listenverhalten

- Listen werden nach CRUD möglichst lokal aktualisiert
- Kompletter Re-Fetch nur wenn fachlich nötig

### Fachliche Treue

- Keine erfundenen Rollen-, Status- oder Fachlogik im Frontend
- Keine Felder oder Enum-Werte geraten
- Keine API-Contracts aus UI-Struktur abgeleitet
- Implementierung entspricht den Regeln aus `docs/domain/`

### UI-States

- loading, empty, error, disabled, success, forbidden, not found und conflict werden berücksichtigt, sofern für den Use Case relevant

---

## Bewertungsstufen

Jede Finding wird eingeordnet als:

- **Kritisch** — Verstößt gegen Architektur oder fachliche Wahrheit, muss korrigiert werden
- **Sollte** — Weicht vom dokumentierten Muster ab, sollte korrigiert werden
- **Optional** — Verbesserungspotenzial, kein Verstoß
- **Nicht ändern** — Umsetzung ist passend, kein Refactoring empfohlen

---

## Bericht

Der Bericht enthält:

- Domain
- geprüfte Dateien
- Findings gruppiert nach Bewertungsstufe
- pro Finding: Datei, Zeile/Bereich, Problem, Empfehlung
- Zusammenfassung mit Gesamtbewertung

---

## Regeln

- Keine Codeänderungen durchführen
- Keine Dateien schreiben
- Keine fachlichen Annahmen treffen
- Nur dokumentierte Muster und Regeln als Prüfgrundlage verwenden
