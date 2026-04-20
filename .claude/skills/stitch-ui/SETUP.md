# Stitch MCP — Einrichtungsanleitung

Offizielle Dokumentation: https://stitch.withgoogle.com/docs/mcp/setup

## Übersicht

Stitch MCP ist Googles offizieller Remote-MCP-Server für UI-Erzeugung. Er läuft als gehosteter Dienst unter `https://stitch.googleapis.com/mcp`.

## Voraussetzungen

1. Zugriff auf Stitch
2. Ein gültiger Stitch- oder Google-API-Key für den MCP-Zugriff
3. Sichere Ablage des API-Keys außerhalb versionierter Dateien

## Schritt 1: API-Key sicher ablegen

Lege den API-Key als Umgebungsvariable ab, statt ihn direkt in Dateien einzuchecken.

Beispiel für `~/.zshrc` oder `~/.bashrc`:

```bash
export STITCH_API_KEY="dein_api_key"
```

Dann Shell neu laden:

```bash
source ~/.zshrc
```

## Schritt 2: MCP-Konfiguration prüfen

Die Datei `.mcp.json` im Projektroot sollte den Key **nicht im Klartext** enthalten, sondern über die Umgebungsvariable referenzieren:

```json
{
  "mcpServers": {
    "stitch": {
      "url": "https://stitch.googleapis.com/mcp",
      "headers": {
        "X-Goog-Api-Key": "${STITCH_API_KEY}"
      }
    }
  }
}
```

## Schritt 3: Stitch-Skills installieren

Die Google Labs stitch-skills erweitern den Workflow mit zusätzlichen Fähigkeiten:

```bash
# Alle verfügbaren Skills auflisten
npx skills add google-labs-code/stitch-skills --list

# Enhance-Prompt-Skill (verbessert Stitch-Prompts automatisch)
npx skills add google-labs-code/stitch-skills --skill enhance-prompt --global

# Stitch-Design-Skill (Design-System-Analyse)
npx skills add google-labs-code/stitch-skills --skill stitch-design --global
```

Quelle: https://github.com/google-labs-code/stitch-skills

## Schritt 4: Verbindung testen

Starte Claude Code im Projektverzeichnis und prüfe:

```text
/stitch-ui uc36
```

Wenn die Stitch-MCP-Tools erkannt werden, ist die Einrichtung erfolgreich.

## Verfügbare Stitch-MCP-Tools

| Tool | Funktion |
|------|----------|
| `create_project` | Neues Stitch-Projekt anlegen |
| `generate_screen_from_text` | Screen aus Text-Prompt generieren |
| `get_screen` | Screen-Details abrufen |
| `get_screen_code` | HTML/CSS-Code eines Screens abrufen |
| `get_screen_image` | Screenshot eines Screens (base64) |
| `list_projects` | Alle Stitch-Projekte auflisten |
| `list_screens` | Alle Screens eines Projekts auflisten |

## Fehlerbehebung

### "Stitch MCP Server nicht verfügbar"
- `.mcp.json` im Projektroot vorhanden?
- `STITCH_API_KEY` gesetzt?
- Claude Code nach Änderung neu gestartet?

### MCP-Änderungen werden nicht erkannt
- Claude Code neu starten (MCP-Config wird beim Start gecached)
- In VS Code: `Developer: Reload Window`

### Fallback: Lokaler Proxy (alternative Methode)
Falls der Remote-Endpoint nicht funktioniert, kann alternativ der Community-Proxy verwendet werden:

```json
{
  "mcpServers": {
    "stitch": {
      "command": "npx",
      "args": ["-y", "@_davideast/stitch-mcp", "proxy"]
    }
  }
}
```

Dieser benötigt `gcloud auth application-default login` statt eines API-Keys. Quelle: https://github.com/davideast/stitch-mcp
