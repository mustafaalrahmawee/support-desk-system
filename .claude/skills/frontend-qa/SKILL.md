---
name: frontend-qa
description: Detaillierte QA-Anweisungen für Frontend-Tests. Wird vom frontend-qa Subagent via skills-Preload geladen.
---

# Frontend-QA Anweisungen

Für QA-Subagenten-Regeln und Nachbesserungslogik gilt `CLAUDE.md`.

## Ablauf

### 1. Testplan lesen

Lies den Abschnitt **9. Frontend-QA** aus der Use-Case-Datei, die dir vom Hauptagent mitgeteilt wird:

- `docs/by-use-case/{uc}.md`

Entnimm: welche Tests, welche UI-Zustände, welche Benutzerinteraktionen.

### 2. Infrastruktur prüfen (KRITISCH)

Prüfe **beide** Dienste:

```bash
# Backend
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/api/login

# Frontend
curl -s -o /dev/null -w "%{http_code}" http://localhost:5173
```

- Backend nicht erreichbar → `❌ Backend nicht erreichbar (http://localhost:8000)` → stoppe
- Frontend nicht erreichbar → `❌ Frontend-Dev-Server nicht erreichbar (http://localhost:5173)` → stoppe

**Wenn die Infrastruktur nicht steht, ist das KEIN UI-Fehler. Ändere niemals Vue-Komponenten deswegen.**

### 3. Playwright-Tests ausführen

Verwende Playwright MCP für jeden Test aus dem Testplan:

1. Navigiere zur richtigen Seite
2. Warte auf Ladevorgang
3. Prüfe sichtbare Elemente
4. Führe Benutzeraktionen aus
5. Prüfe das Ergebnis

### 4. Fehlerklassifikation

Klassifiziere jeden fehlgeschlagenen Test:

**Typ A: UI-Fehler** (vom Hauptagent behebbar)
- Element nicht sichtbar / nicht vorhanden
- Falscher Text / Label
- Button fehlt oder reagiert nicht
- Loading-State fehlt
- Fehlermeldung wird nicht angezeigt
- Navigation funktioniert nicht
- Formularvalidierung fehlt

**Typ B: Infrastruktur-Fehler** (NICHT durch UI-Änderung behebbar)
- API-Aufruf schlägt fehl weil Backend nicht läuft
- CORS-Fehler
- Netzwerk-Timeout
- 500er vom Backend

**Typ C: Backend-Logik-Fehler** (erfordert Backend-Änderung)
- API gibt falsche Daten zurück
- API gibt falschen HTTP-Status zurück
- Fehlende Felder in der API-Response

Bei Typ B und Typ C dürfen keine Vue-Dateien geändert werden, bevor die eigentliche Ursache geklärt ist.

### 5. Bericht erstellen

Erstelle den Bericht in **genau** diesem Format:

```
frontend-qa Ergebnis für {uc}:

Infrastruktur:
  Backend:  ✓ erreichbar / ❌ nicht erreichbar
  Frontend: ✓ erreichbar / ❌ nicht erreichbar

UI-Tests:
✓ [Beschreibung]
✗ [Beschreibung]
  Typ: [A: UI-Fehler / B: Infrastruktur / C: Backend-Logik]
  Betroffene Datei: [Pfad]
  Erwartetes Verhalten: [was passieren sollte]
  Tatsächliches Verhalten: [was passiert ist]
  Mögliche Ursache: [kurze Analyse]

Zusammenfassung: [X/Y Tests bestanden]
Fehlertypen: [X Typ-A, Y Typ-B, Z Typ-C]
```

## Regeln

- Teste **jeden** Punkt aus dem QA-Abschnitt
- Teste auch negative Fälle: Fehlerzustände, leere Listen, Forbidden-State
- Unterscheide immer zwischen UI-Fehlern (Typ A) und Infrastruktur/Backend-Fehlern (Typ B/C)
- Wenn das Backend nicht läuft, ist ein fehlgeschlagener API-Aufruf im UI **kein UI-Fehler**
