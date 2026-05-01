# AI-assisted Smart Support Desk — Codex Arbeitsanweisung

## Zweck

Diese Datei steuert das Arbeitsverhalten von Codex für das AI-assisted Smart Support Desk System.

Für fachliche Wahrheit, Dokumentstruktur, Leseregeln und Grundregeln gilt `docs/README.md`.

Diese Datei regelt:
- verfügbare Session-Typen
- Subagenten-Verhalten
- QA-Regeln
- Git-Ermittlung

---

## Verfügbare Session-Typen

| Befehl | Skill | Zweck |
|---|---|---|
| `/session <domain> [bundle]` | `.agents/skills/session/SKILL.md` | Implementierungs-Session für eine Domain oder ein Bundle |
| `/domain-doc-generator <domain>` | `.agents/skills/domain-doc-generator/SKILL.md` | Domain-Datei, Use-Case-Dateien und Design-Prompts generieren |

Ablauf und Schritte stehen im jeweiligen Skill.

---

## Subagenten

### QA-Subagenten

- `backend-qa` und `frontend-qa` sind code-read-only — sie ändern keinen Anwendungscode
- sie dürfen QA-Dateien unter `docs/qa/` erzeugen und aktualisieren
- sie erzeugen QA-Pläne und werten belegte Benutzerergebnisse aus
- sie führen keine fachlichen Acceptance-Tests selbst aus
- sie dürfen technische Infrastruktur-Checks vorbereiten oder beschreiben, aber nicht als bestandene Fachtests werten

Nach dem Erzeugen eines QA-Plans stoppt der Agent und wartet auf dokumentierte Benutzerergebnisse.
Playwright darf nur verwendet werden, wenn der Benutzer es ausdrücklich für diese QA-Ausführung anfordert.

Wenn QA Fehler meldet:
1. Bericht analysieren
2. gezielt korrigieren
3. QA-Plan aktualisieren oder Benutzerergebnisse erneut auswerten
4. maximal 3 gezielte Nachbesserungsversuche

Nach 3 erfolglosen Versuchen:
- stoppen
- Benutzer informieren
- offenen Fehler, Ursache und bisherige Fixes dokumentieren

---

## Frontend-QA Fehlerklassen

- Typ A: UI-Fehler → Frontend darf angepasst werden
- Typ B: Infrastruktur → keine Vue-Änderung, zuerst Ursache klären
- Typ C: Backend-Logik → Backend korrigieren, nicht Frontend

---

## Technische Arbeitsregeln

Zusätzlich zu den Grundregeln in `docs/README.md` gelten:

- Keine Fachlogik im Controller
- Rollen und Permissions über Spatie-Middleware oder Services prüfen, nicht verstreut im Controller
- Keine fehlende Transaktion bei atomaren Fachvorgängen
- Audit bei fachlich relevanten Änderungen nicht vergessen
- Keine Rollen- oder Statuslogik im Frontend erfinden

---

## Session-Start (Pflicht)

Jede Implementierungs-Session startet im Plan-Mode.

Vor jeder Implementierung gilt:
1. Plan erstellen
2. Plan bestätigen lassen
3. Erst danach implementieren

---

## Git-Ermittlung

Codex prüft geänderte Dateien über:

```bash
git status --short
git diff --name-only
git diff --stat
git diff --cached --name-only
```