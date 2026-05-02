---
name: backend-code-review
description: Read-only Code-Review für Backend-Dateien einer Domain. Prüft Architektur, Datenkonsistenz, API-Contract und fachliche Treue.
---

# backend-code-review

## Zweck

Read-only Code-Review für Backend-Dateien einer Domain nach Abschluss einer Implementierungs-Session.

Für Review-Regeln und Ablauf gilt `AGENTS.md`.
Für fachliche Wahrheit und Dokumenthierarchie gilt `docs/README.md`.
Für technische Muster gilt `docs/patterns/backend-laravel.md`.

---

## Eingabe

Der Hauptagent übergibt:

- Domain oder Bundle
- Liste der geänderten Backend-Dateien (aus Git)

---

## Prüfbereiche

### Architektur

- Fachlogik in Actions, nicht in Controllern
- Autorisierung über Policies, nicht verstreut im Code
- FormRequests nur mit formaler Validierung, keine Fachlogik
- Jobs delegieren an Actions, keine eigene Fachlogik
- Fachliche Exceptions statt generischer Exceptions

### Datenkonsistenz

- `DB::transaction()` bei fachlich zusammenhängenden Änderungen
- Audit bei fachlich relevanten Vorgängen vorhanden
- Soft Delete über Actions, nicht direkt im Controller

### API-Contract

- Response-Struktur entspricht der Use-Case-Dokumentation
- HTTP-Statuscodes sind fachlich korrekt
- Felder in der Response entsprechen dem dokumentierten API-Contract

### Fachliche Treue

- Keine erfundenen Felder, Rollen, Status oder Enum-Werte
- Implementierung entspricht den Regeln aus `docs/domain/`
- Keine fachlichen Ergänzungen ohne Dokumentationsgrundlage

---

## Bewertungsstufen

Jede Finding wird eingeordnet als:

- **Kritisch** — Verstößt gegen Architektur oder fachliche Wahrheit, muss korrigiert werden
- **Sollte** — Weicht vom dokumentierten Muster ab, sollte korrigiert werden
- **Optional** — Verbesserungspotenzial, kein Verstoß

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
- Wenn keine Findings vorhanden sind, ausdrücklich „keine Änderung empfohlen" schreiben
