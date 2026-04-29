---
name: domain-doc-generator
description: Generiert oder aktualisiert für eine Domain die Koordinator-Datei unter docs/by-domain, die Use-Case-Dateien unter docs/by-use-case und die Design-Prompt-Dateien unter docs/design-prompts. Verwendung z.B. /domain-doc-generator auth oder /domain-doc-generator users.
argument-hint: <domain>
---

# Domain-Dokumente und Design-Prompts für Domain: $0

Dieser Skill erzeugt oder aktualisiert die Dokumente für genau eine Domain.

Zielartefakte:
- `docs/by-domain/$0.md`
- `docs/by-use-case/*.md` für alle Use Cases der Domain
- `docs/design-prompts/$0/*.md` für alle Use Cases der Domain

Nicht-Ziel:
- keine Designbilder generieren
- keine Dateien unter `docs/design-references/$0/*.png` erzeugen oder ersetzen

Nach Abschluss dieses Skills kopiert der Benutzer die Design-Prompts manuell in ein externes Bild- oder UI-Generierungstool, lädt die erzeugten Bilder herunter und legt sie selbst unter `docs/design-references/$0/` ab.

Für Grundregeln, Leseregeln und Dokumenthierarchie gilt `docs/README.md`.
Für Arbeitsregeln und Projektverhalten gilt `AGENTS.md`.

---

## Pflichtquellen

Lies in dieser Reihenfolge:
1. `docs/README.md`
2. `docs/domain/04_use-cases.md` — finde den Abschnitt `# $0 Domain` und entnimm alle zugehörigen Use Cases
3. bei Bedarf zusätzlich `docs/domain/01_miniworld.md`, `docs/domain/02_business-rules.md`, `docs/domain/03_er.md`
4. bestehende Dateien unter `docs/by-domain/`, `docs/by-use-case/`, `docs/design-prompts/` und `docs/design-references/`, falls vorhanden

Dateinamen für Use-Case-Dateien: `uc<nummer>_<slug>.md` (z. B. `uc36_login.md`).
Wenn bereits passende Dateien existieren, übernimm deren Dateinamen.

---

## Schritt 1: Domain-Datei erzeugen

Erzeuge oder aktualisiere `docs/by-domain/$0.md`.

Struktur:
- `# <Domain> Domain`
- `## Zweck`
- `## Fachlicher Scope`
- `## Zugehörige Use Cases`
- `## Empfohlene Reihenfolge`
- `## Fachliche Abhängigkeiten`
- `## Gemeinsame Backend-Bausteine`
- `## Gemeinsame Frontend-Bausteine`
- `## Session-Bundles`
- `## Design-Referenzen`
- `## Abgrenzung`

Regeln:
- Scope und Use Cases aus `docs/domain/04_use-cases.md` ableiten
- Session-Bundles pragmatisch gruppieren, aber nur aus dokumentierten Use Cases
- unter `## Design-Referenzen` auf bestehende oder erwartete Bilddateien unter `docs/design-references/$0/` verweisen

---

## Schritt 2: Use-Case-Dateien erzeugen

Erzeuge oder aktualisiere für jeden Use Case der Domain genau eine Datei unter `docs/by-use-case/`.

Zielstruktur pro Datei:
1. `## 1. Use Case`
2. `## 2. API-Contract`
3. `## 3. Backend-Architektur`
4. `## 4. Backend-QA`
5. `## 5. Frontend-Architektur`
6. `## 6. Screen-Flow`
7. `## 7. UI-Regeln`
8. `## 8. UI-Referenz`
9. `## 9. Frontend-QA`

Regeln:
- Abschnitt `1. Use Case` strikt aus `docs/domain/04_use-cases.md` ableiten
- technische Abschnitte nur als umsetzbare Ableitung formulieren, nicht als erfundene Fachwahrheit
- wenn fachliche Details nicht dokumentiert sind, als Annahme oder Platzhalter klar markieren
- bestehende Projektmuster aus `docs/patterns/frontend-vue.md` und `docs/patterns/backend-laravel.md` berücksichtigen
- QA-Schritte konkret und lokal testbar formulieren

---

## Schritt 3: Design-Prompt-Dateien erzeugen

Erzeuge oder aktualisiere für jeden Use Case genau eine Datei unter:

`docs/design-prompts/$0/<use-case-dateiname>`


Struktur pro Datei:
- `# Design Prompt — UC-XX ...`
- `## Ziel`
- `## Quellen`
- `## Prompt`
- `## UI-Zustände`
- `## Nicht erfinden`

Der Prompt selbst soll:
- auf `docs/by-domain/$0.md` und der konkreten Use-Case-Datei basieren
- Desktop und Mobile nennen
- bestehende UI-Sprache des Projekts respektieren
- klare Layout-, Inhalts- und Zustandsanforderungen nennen
- ausdrücklich verbieten, nicht dokumentierte Felder, Tabs, KPIs oder Admin-Funktionen zu ergänzen
- so formuliert sein, dass er direkt in einem Bild- oder UI-Generator verwendet werden kann

---

## Schritt 4: Abschluss

Nach dem Erzeugen oder Aktualisieren der Design-Prompt-Dateien stoppt der Skill.

Der Skill darf keine Designbilder generieren.

Er soll dem Benutzer am Ende knapp nennen:
- welche Design-Prompt-Dateien erzeugt oder aktualisiert wurden
- dass diese Prompts extern zur Bildgenerierung verwendet werden
- dass die fertigen PNG-Dateien anschließend manuell unter `docs/design-references/$0/` abgelegt werden sollen

---

## Beispielaufrufe

- `/domain-doc-generator auth`
- `/domain-doc-generator users`
- `/domain-doc-generator tickets`
