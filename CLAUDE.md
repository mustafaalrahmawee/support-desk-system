# Smart Support Desk System — Claude Code Arbeitsanweisung

## Zweck

Diese Datei steuert das Arbeitsverhalten von Claude Code für das Smart Support Desk System.

Für fachliche Wahrheit, Dokumentstruktur, Leseregeln und Grundregeln gilt `docs/README.md`.

Diese Datei regelt:
- verfügbare Session-Typen
- Subagenten-Verhalten
- technische Arbeitsregeln

---

## Verfügbare Session-Typen

| Befehl | Skill | Zweck |
|---|---|---|
| `/review-session <domain> [bundle]` | `.claude/skills/review-session/SKILL.md` | Read-only Code-Review nach einer Implementierungs-Session |

Ablauf und Schritte stehen im jeweiligen Skill.

---

## Subagenten

### Code-Review-Subagenten

- `backend-code-review` und `frontend-code-review` sind read-only
- sie ändern keinen Code und schreiben keine Dateien
- sie prüfen Codequalität, Architekturtreue und Refactoring-Potenzial
- der Hauptagent entscheidet über Nachbesserungen basierend auf dem Bericht

---

## Technische Arbeitsregeln

Zusätzlich zu den Grundregeln in `docs/README.md` gelten:

- Keine Fachlogik im Controller
- Keine Rollenabfragen verstreut im Controller
- Keine fehlende Transaktion bei atomaren Fachvorgängen
- Audit bei fachlich relevanten Änderungen nicht vergessen
- Keine Rollen- oder Statuslogik im Frontend erfinden

---

## Workflow-Hinweis

Implementierung und QA werden von Codex ausgeführt (siehe `AGENTS.md`).
Claude Code übernimmt nach der Implementierung das Code-Review über `/review-session`.

Bei Kontextfülle: `/clear` und mit dem nächsten Schritt weitermachen.
