# Smart Support Desk System — Codex Arbeitsanweisung

## Zweck

Diese Datei steuert das Arbeitsverhalten von Codex in Review- und Refactoring-Sessions.

Für fachliche Wahrheit, Dokumentstruktur, Leseregeln und Grundregeln gilt `docs/README.md`.

Diese Datei regelt nur:
- Review-Start
- Code-Review-Modus
- Refactoring-Empfehlungen
- zusätzliche technische Review-Regeln

---

## Review-Start

Verwendung:
- `/review-session <domain>`
- `/review-session <domain> <bundle>`

Jede Review-Session ist read-only.

Ablauf:
1. Session-Kontext bestimmen
2. geänderte Dateien über Git ermitteln
3. Backend-Review durchführen
4. Frontend-Review durchführen
5. finalen Bericht erstellen
6. keine Codeänderungen durchführen

---

## Review-Subagenten

- `backend-code-review` und `frontend-code-review` sind read-only
- sie prüfen Codequalität, Architekturtreue und Refactoring-Potenzial
- sie testen nicht funktional, außer der Benutzer fordert es ausdrücklich
- sie ändern keinen Code
- sie schreiben keine Dateien
- sie refactoren nicht selbst

Reihenfolge:
1. `backend-code-review` ausführen
2. Backend-Bericht abwarten
3. `frontend-code-review` ausführen
4. Frontend-Bericht abwarten
5. Gesamtbericht für den Benutzer erstellen

Wenn ein Review Fehler oder Verbesserungen meldet:
1. Bericht analysieren
2. Empfehlungen nach Kritisch / Sollte / Optional einordnen
3. keine Änderung ohne Benutzerentscheidung durchführen
4. finalen Refactoring-Vorschlag für Claude Code formulieren

Nach dem Review:
- Codex beendet die Session mit einem Bericht
- Claude Code oder der Benutzer entscheidet über Umsetzung
- Codex implementiert keine Refactorings in Review-Sessions

---

## Git-Ermittlung

Codex prüft geänderte Dateien über:

```bash
git status --short
git diff --name-only
git diff --stat
git diff --cached --name-only