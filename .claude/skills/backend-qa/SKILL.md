---
name: backend-qa
description: Detaillierte QA-Anweisungen für Backend-Tests. Wird vom backend-qa Subagent via skills-Preload geladen.
---

# Backend-QA Anweisungen

## Ablauf

### 1. Testplan lesen

Lies den Abschnitt **4. Backend-QA** aus der Use-Case-Datei, die dir vom Hauptagent mitgeteilt wird:

- `docs/by-use-case/{uc}.md`

Entnimm: welche Tests, welche HTTP-Status-Codes, welche JSON-Strukturen, welche Datenbankfolgen.

### 2. Infrastruktur prüfen

Prüfe ob das Backend erreichbar ist:

```bash
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/api/login
```

Wenn **nicht erreichbar**: stoppe sofort, melde `❌ Backend nicht erreichbar (http://localhost:8000)`. Keine weiteren Tests.

### 3. Testdaten vorbereiten

Verwende `docker exec support_desk_api php artisan tinker --execute="..."` um:
- Testbenutzer und Testrollen zu prüfen oder anzulegen
- Authentifizierungstokens für geschützte Endpunkte zu erzeugen

### 4. Tests systematisch ausführen

Für jeden Test aus dem Testplan:
- `curl -s -w "\n%{http_code}" -H "Authorization: Bearer {token}" -H "Content-Type: application/json" ...`
- Dokumentiere: Beschreibung, curl-Befehl, erwarteter vs. tatsächlicher Status, ✓ oder ✗

Teste immer:
- Happy Path (korrekter Request)
- RBAC (falsche Rolle → 403, keine Auth → 401)
- Validierung (fehlende Felder → 422)
- Audit-Log (wenn UC auditpflichtig)

### 5. Datenbankzustand prüfen

Für Tests mit Datenbankfolgen (Audit-Log, Actor-Erzeugung, Soft Delete):

```bash
docker exec support_desk_api php artisan tinker --execute="..."
```

### 6. Bericht erstellen

Erstelle den Bericht in **genau** diesem Format:

```
backend-qa Ergebnis für {uc}:

Infrastruktur: ✓ Backend erreichbar

Tests:
✓ [Beschreibung] → [HTTP-Status], [Kernaussage]
✗ [Beschreibung] → Erwartet: [X], Erhalten: [Y]
  Betroffene Datei: [Pfad]
  Mögliche Ursache: [kurze Analyse]

Datenbank:
✓ [Prüfung] → [Ergebnis]
✗ [Prüfung] → Erwartet: [X], Erhalten: [Y]
  Betroffene Datei: [Pfad]
  Mögliche Ursache: [kurze Analyse]

Zusammenfassung: [X/Y Tests bestanden]
```

## Regeln

- Du testest nur — du änderst **keinen** Code
- Bei fehlgeschlagenen Tests: nenne die betroffene Datei und mögliche Ursache
- Teste **jeden** Punkt aus dem QA-Abschnitt
- Der Hauptagent entscheidet über Nachbesserungen basierend auf deinem Bericht
