---
name: stitch-ui
description: Erzeugt ein Vue.js + Tailwind CSS Komponentengerüst über Google Stitch MCP. Wird in der Pipeline nach den Frontend-Abschnitten (5–7) und vor Frontend-QA aufgerufen. Nimmt den Stitch-Prompt aus Abschnitt 8 der Use-Case-Datei.
argument-hint: <uc-nummer> (z.B. uc36, uc40)
disable-model-invocation: true
---

# Stitch-UI-Erzeugung für Use Case: $0

Du erzeugst jetzt das **Vue.js + Tailwind CSS Komponentengerüst** für diesen Use Case über Google Stitch MCP.

---

## Schritt 1: Stitch-Prompt lesen

Lies den **Abschnitt 8 (Stitch-Prompt)** aus der Use-Case-Datei:

- `docs/by-use-case/$0*.md`

Der Stitch-Prompt ist ein vorbereiteter Text, der genau beschreibt, was erzeugt werden soll. Verwende ihn als Grundlage.

---

## Schritt 2: Stitch-MCP-Verfügbarkeit prüfen

Prüfe ob der Stitch MCP Server verfügbar ist. Wenn die Stitch-Tools (`generate_screen_from_text`, `get_screen_code`) nicht als MCP-Tools erkannt werden:

- Stoppe
- Melde: `❌ Stitch MCP Server nicht verfügbar. Setup prüfen: .mcp.json und STITCH_API_KEY Umgebungsvariable. Anleitung: .claude/skills/stitch-ui/SETUP.md`
- Fahre **nicht** mit einem manuellen Fallback fort

---

## Schritt 3: Projekt und Screen erzeugen

### 3a. Projekt erstellen (falls noch nicht vorhanden)

Verwende `create_project` um ein Stitch-Projekt für die aktuelle Domain anzulegen, falls noch keines existiert.

Projektname: `support-desk-{domain}` (z.B. `support-desk-auth`, `support-desk-users`)

### 3b. Screen generieren

Verwende `generate_screen_from_text` mit dem Stitch-Prompt aus Abschnitt 8.

Ergänze den Prompt um diese Rahmenbedingungen:
- Framework: Vue.js 3 mit Tailwind CSS
- Nur UI-Struktur, keine Business-Logik
- Keine echte API-Anbindung
- Responsive Layout
- Admin-Oberfläche: ruhig, klar, professionell

### 3c. Code abrufen

Verwende `get_screen_code` um den generierten HTML/CSS-Code abzurufen.

---

## Schritt 4: In Vue-Komponente umwandeln

Der Stitch-Output ist HTML + Tailwind CSS. Wandle ihn in eine Vue.js SFC um:

1. **Template:** Übernimm die HTML-Struktur aus dem Stitch-Output als `<template>`-Block
2. **Script:** Erstelle einen leeren `<script setup lang="ts">` mit:
   - Platzhalter-Props oder reactive State für die UI-Zustände aus Abschnitt 6
   - Kommentare wie `// TODO: Store-Anbindung` an den Stellen, wo API-Logik hinkommt
3. **Style:** Kein `<style>`-Block nötig — Tailwind-Klassen reichen

### Dateiname und Pfad

Entnimm den Zielpfad aus **Abschnitt 5 (Frontend-Architektur)** der Use-Case-Datei:
- z.B. `src/pages/auth/LoginPage.vue`
- z.B. `src/pages/users/InternalUsersListPage.vue`

---

## Schritt 5: Gerüst-Datei schreiben

Schreibe die Vue-Komponente an den vorgesehenen Pfad.

Die Datei enthält:
- Vollständige Tailwind-basierte UI-Struktur aus Stitch
- Platzhalter für alle UI-Zustände (loading, error, empty, success, etc.)
- `// TODO`-Kommentare für:
  - Store-Import und -Anbindung
  - Vuelidate-Integration
  - Event-Handler
  - Navigation

---

## Schritt 6: Ergebnis melden

Melde dem Hauptagenten:

```
stitch-ui Ergebnis für $0:

Stitch-Projekt: support-desk-{domain}
Erzeugter Screen: [Screen-Name]
Zieldatei: [Dateipfad]
Status: ✓ Gerüst geschrieben

Nächster Schritt: Store-Anbindung, Validierung und Event-Handler ergänzen (manuell durch Hauptagent)
```

---

## Wichtige Regeln

- Verwende **immer** den Stitch-Prompt aus Abschnitt 8 als Basis
- Erzeuge **nur** das UI-Gerüst — keine Store-Logik, keine API-Calls, keine Validierung
- Der Hauptagent ergänzt anschließend die Fachlogik basierend auf den Abschnitten 5–7
- Wenn Stitch einen unbrauchbaren Output liefert (z.B. falsches Framework, zu wenig Struktur): melde es und schlage vor, den Prompt anzupassen
- Überschreibe **keine** bestehende Datei, ohne vorher zu fragen
- Respektiere die Ordnerstruktur aus `docs/README.md`
