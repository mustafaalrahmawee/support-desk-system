---
name: session
description: Startet eine neue Implementierungs-Session für eine Domain oder ein Session-Bundle. Verwendung z.B. /session users oder /session auth bundle-a
argument-hint: <domain> [bundle]
disable-model-invocation: true
allowed-tools: Read Grep Glob Bash
---

# Session-Start für Domain: $0

Du startest jetzt eine neue Implementierungs-Session. Halte dich strikt an die folgende Reihenfolge.

Für QA-Regeln, Fehlerklassen und technische Arbeitsregeln gilt `CLAUDE.md`.
Für Grundregeln, Leseregeln und Dokumenthierarchie gilt `docs/README.md`.

---

## Schritt 1: Fachliche Grundlage lesen

Lies diese Dateien vollständig:

1. `docs/domain/01_miniworld.md`
2. `docs/domain/02_business-rules.md`
3. `docs/domain/03_er.md`

---

## Schritt 2: Meta- und Arbeitsreferenz lesen

Lies `docs/README.md`.

---

## Schritt 3: Domain-Koordinator lesen

Lies `docs/by-domain/$0.md`.

Entnimm daraus:

- Welche Use Cases gehören zur Domain
- Empfohlene Reihenfolge
- Abhängigkeiten fachlich und technisch
- Gemeinsame Bausteine Backend und Frontend
- Session-Bundles
- Welche Design-Referenzen für die Domain vorbereitet sein sollen

---

## Schritt 4: Session-Bundle bestimmen

Falls ein Bundle-Argument übergeben wurde (`$1`), verwende das angegebene Bundle aus dem Koordinator.

Falls kein Bundle angegeben wurde, verwende das vollständige Bundle aller Use Cases der Domain.

---

## Schritt 5: Plan erstellen und präsentieren

Erstelle einen strukturierten Plan mit:

- **Domain:** $0
- **Bundle:** gewähltes Bundle mit Use Cases
- **Reihenfolge:** UC-Liste in Implementierungsreihenfolge
- **Pro UC:**
  - Datei: `docs/by-use-case/{uc}.md`
  - Backend: Route, Controller, Action, Model
  - Frontend: Page, Store-Funktion, benötigte Design-Referenz
- **Abhängigkeiten:** Was vorher existieren muss
- **Dateien die erzeugt oder geändert werden**
- **Design-Referenzen:** Welche globalen und UC-spezifischen Bilder verwendet werden

---

## Schritt 6: Auf Bestätigung warten

Präsentiere den Plan und warte auf Bestätigung, bevor du mit der Implementierung beginnst.

**Implementiere nichts bevor der Plan bestätigt wurde.**

---

## Schritt 7: Pipeline abarbeiten nach Bestätigung

### Phase A: Backend für alle UCs des Bundles umsetzen

Für jeden UC im bestätigten Plan:

1. `docs/by-use-case/{uc}.md` lesen
2. Backend auf Basis der Abschnitte 1 bis 4 umsetzen
3. `docs/patterns/backend-laravel.md` nur als technisches Muster verwenden
4. Bei Widersprüchen gilt die Dokumenthierarchie aus `docs/README.md`

Kein Frontend und kein Backend-QA dazwischen mischen.

### Phase B: Backend-QA für Domain oder Bundle

Nach Abschluss der Backend-Implementierung für alle UCs des bestätigten Bundles:

1. Subagent `backend-qa` aufrufen
2. Kontext ist die Domain oder das Bundle, nicht ein einzelner Use Case
3. Der Subagent arbeitet nach `.claude/skills/backend-qa/SKILL.md`
4. Der Subagent erzeugt oder aktualisiert genau eine gemeinsame QA-Datei unter `docs/qa/backend/{domain}.md`
5. Der Subagent bereitet die Testpunkte für die relevanten Use Cases der Domain vor
6. Danach werden belegte Benutzerergebnisse ausgewertet
7. Der Subagent liefert einen kompakten Bericht an den Hauptagenten

Bei Fehlern gelten die Nachbesserungsregeln aus `CLAUDE.md`.

### Phase C: Frontend pro UC umsetzen

Erst nach erfolgreicher Backend-QA oder akzeptiertem Backend-Stand.

Für jeden UC im bestätigten Plan:

1. `docs/by-use-case/{uc}.md` lesen
2. globale Design-Referenzen prüfen:
  - `docs/design-references/app-shell.png`
3. passende UC-Referenz prüfen:
   - `docs/design-references/{domain}/{uc}_*.png` oder entsprechend dokumentierte Datei
4. `docs/patterns/frontend-vue.md` als technisches Muster verwenden
5. Frontend auf Basis der Use-Case-Dokumentation, Patterns und Design-Referenzen umsetzen
6. Page, Store, Validierung und Events anschließen
7. Subagent `frontend-qa` für den einzelnen UC aufrufen
8. Bericht auswerten
9. Falls nötig nachbessern — Fehlerklassen und Nachbesserungsregeln gelten aus `CLAUDE.md`

---

## Beispielaufrufe

- `/session auth` → vollständige Auth-Domain
- `/session auth bundle-a` → Auth-Grundlage
- `/session users` → vollständige Users-Domain
- `/session users bundle-b` → bestimmtes Users-Bundle
- `/session tickets` → vollständige Tickets-Domain
