---
name: session
description: Startet eine neue Implementierungs-Session für eine Domain oder ein Session-Bundle. Verwendung z.B. /session users oder /session auth bundle-a
argument-hint: <domain> [bundle]
disable-model-invocation: true
allowed-tools: Read Grep Glob Bash
---

# Session-Start für Domain: $0

Du startest jetzt eine neue Implementierungs-Session. Halte dich strikt an die folgende Reihenfolge.

---

## Schritt 1: Fachliche Grundlage lesen

Lies diese drei Dateien vollständig:

1. `docs/domain/01_miniworld.md`
2. `docs/domain/02_business-rules.md`
3. `docs/domain/03_er.md`

---

## Schritt 2: Architektur und Konventionen lesen

Lies einmalig:

- `docs/README.md`

---

## Schritt 3: Domain-Koordinator lesen

Lies den Koordinator für die Domain **$0**:

- `docs/by-domain/$0.md`

Entnimm daraus:

- Welche Use Cases gehören zur Domain
- Empfohlene Reihenfolge
- Abhängigkeiten (fachlich und technisch)
- Gemeinsame Bausteine (Backend + Frontend)
- Session-Bundles

---

## Schritt 4: Session-Bundle bestimmen

Falls ein Bundle-Argument übergeben wurde (`$1`), verwende das angegebene Bundle aus dem Koordinator.

Falls kein Bundle angegeben wurde, verwende das vollständige Bundle (alle Use Cases der Domain).

---

## Schritt 5: Plan erstellen und präsentieren

Erstelle einen strukturierten Plan mit:

- **Domain:** $0
- **Bundle:** (gewähltes Bundle mit UCs)
- **Reihenfolge:** UC-Liste in Implementierungsreihenfolge
- **Pro UC:**
  - Datei: `docs/by-use-case/{uc}.md`
  - Backend: Route, Controller, Action, Model
  - Frontend: Page, Store-Funktion
- **Abhängigkeiten:** Was muss vorher existieren
- **Dateien die erzeugt/geändert werden**

---

## Schritt 6: Auf Bestätigung warten

Präsentiere den Plan und warte auf Bestätigung, bevor du mit der Implementierung beginnst.

**Implementiere NICHTS bevor der Plan bestätigt wurde.**

---

## Schritt 7: Pipeline pro UC abarbeiten (erst nach Bestätigung)

Für jeden UC im bestätigten Plan:

1. `docs/by-use-case/{uc}.md` lesen
2. **Backend implementieren** — Abschnitte 1–3
3. **Backend-QA** — Abschnitt 4
4. **Frontend implementieren** — Abschnitte 5–8
5. **Frontend-QA** — Abschnitt 9

Kein Abschnitt überspringen. Bei Kontextfülle: `/clear` und mit dem nächsten UC weitermachen.

---

## Beispielaufrufe

- `/session auth` → Vollständige Auth-Domain (UC 36–39)
- `/session auth bundle-a` → Auth-Grundlage (UC 36 + 37)
- `/session users` → Vollständige Users-Domain (UC 40–43)
- `/session users bundle-b` → Bearbeitung + Deaktivierung (UC 42 + 43)
- `/session tickets` → Vollständige Tickets-Domain (UC 22–30)
