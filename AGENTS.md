# Smart Support Desk System — Codex Arbeitsanweisung

## Zweck

Diese Datei steuert das Arbeitsverhalten von Codex für das Smart Support Desk System.

Für fachliche Wahrheit, Dokumentstruktur, Leseregeln und Grundregeln gilt `docs/README.md`.

Diese Datei regelt:
- verfügbare Session-Typen
- Subagenten-Verhalten
- Git-Ermittlung

---

## Verfügbare Session-Typen

| Befehl | Skill | Zweck |
|---|---|---|
| `/review-session <domain> [bundle]` | `.codex/skills/review-session/SKILL.md` | Read-only Code-Review nach einer Implementierungs-Session |
| `/domain-doc-generator <domain>` | `.agents/skills/domain-doc-generator/SKILL.md` | Domain-Datei, Use-Case-Dateien und Design-Prompts generieren |

Ablauf und Schritte stehen im jeweiligen Skill.

---

## Subagenten

- `backend-code-review` und `frontend-code-review` sind read-only
- sie ändern keinen Code und schreiben keine Dateien
- sie prüfen Codequalität, Architekturtreue und Refactoring-Potenzial
- der Hauptagent entscheidet über Nachbesserungen basierend auf dem Bericht

---

## Git-Ermittlung

Codex prüft geänderte Dateien über:

```bash
git status --short
git diff --name-only
git diff --stat
git diff --cached --name-only