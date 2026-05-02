---
name: review-session
description: Startet eine read-only Review-Session für eine Domain oder ein Session-Bundle. Verwendung z.B. /review-session users oder /review-session auth bundle-a
argument-hint: <domain> [bundle]
---

# Review-Session für Domain: $0

Du startest jetzt eine read-only Review-Session. Halte dich strikt an die folgende Reihenfolge.

Für Review-Regeln und Subagenten-Verhalten gilt `AGENTS.md`.
Für Grundregeln, Leseregeln und Dokumenthierarchie gilt `docs/README.md`.

**Es werden keine Codeänderungen durchgeführt.**

---

## Schritt 1: Session-Kontext bestimmen

Falls ein Bundle-Argument übergeben wurde (`$1`), lies `docs/by-domain/$0.md` und verwende das angegebene Bundle.

Falls kein Bundle angegeben wurde, verwende die vollständige Domain.

---

## Schritt 2: Geänderte Dateien über Git ermitteln

```bash
git diff --name-only
git diff --cached --name-only
git status --short
```

Filtere die Ergebnisse nach:

- **Backend-Dateien:** `api/app/`, `api/routes/`, `api/database/`
- **Frontend-Dateien:** `app/src/`

Falls keine geänderten Dateien gefunden werden, informiere den Benutzer und beende die Session.

---

## Schritt 3: Backend-Review

Subagent `backend-code-review` aufrufen mit:

- Domain: $0
- Liste der geänderten Backend-Dateien

Der Subagent arbeitet nach `.agents/skills/backend-code-review/SKILL.md`.

Backend-Bericht abwarten, bevor der nächste Schritt beginnt.

---

## Schritt 4: Frontend-Review

Subagent `frontend-code-review` aufrufen mit:

- Domain: $0
- Liste der geänderten Frontend-Dateien

Der Subagent arbeitet nach `.agents/skills/frontend-code-review/SKILL.md`.

Frontend-Bericht abwarten.

---

## Schritt 5: Gesamtbericht erstellen

Erstelle einen finalen Bericht mit:

- **Domain:** $0
- **Bundle:** $1, falls angegeben
- **Geprüfte Dateien:** Backend und Frontend getrennt
- **Findings gruppiert nach Bewertungsstufe:**
  - Kritisch — muss korrigiert werden
  - Sollte — sollte korrigiert werden
  - Optional — Verbesserungspotenzial
- **Pro Finding:** Datei, Bereich, Problem, Empfehlung
- **Refactoring-Vorschlag:** konkrete Korrekturschritte, falls Findings vorhanden

---

## Beispielaufrufe

- `/review-session auth` → vollständige Auth-Domain
- `/review-session auth bundle-a` → Auth-Grundlage
- `/review-session users` → vollständige Users-Domain
- `/review-session tickets` → vollständige Tickets-Domain
