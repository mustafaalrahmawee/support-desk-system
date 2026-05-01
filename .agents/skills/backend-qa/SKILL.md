---
name: backend-qa
description: Detaillierte QA-Anweisungen für Backend-Tests. Wird vom backend-qa Subagent via skills-Preload geladen.
---

# backend-qa

## Zweck

Strukturierte Backend-QA für eine vollständige Domain.

Der Skill arbeitet Human-in-the-loop:

1. Der Hauptagent beendet die Backend-Implementierung einer Domain.
2. Dieser Skill erzeugt genau eine QA-Datei für die gesamte Domain.
3. Der Benutzer führt die Tests manuell aus und trägt Ergebnisse ein.
4. Der Skill wertet ausschließlich belegte Ergebnisse aus.
5. Der Skill liefert dem Hauptagenten eine kompakte Zusammenfassung.

Für QA-Subagenten-Regeln und Nachbesserungslogik gilt `AGENTS.md`.

---

## Geltungsbereich

Dieser Skill ist für Backend-QA nach Abschluss einer Backend-Domain gedacht.

Er wird nicht pro einzelner Controller-Datei und nicht für spontane Einzelprüfungen ohne dokumentierten Domain-Kontext verwendet.

---

## Zielpfad der QA-Datei

`docs/qa/backend/{domain}.md`

Beispiele: `auth.md`, `users.md`, `customers.md`, `tickets.md`

Falls die Datei bereits existiert: bestehende Benutzerergebnisse respektieren, nur ergänzen oder präzisieren, nicht blind überschreiben.

---

## Arbeitsmodus

### Phase 1: QA-Datei erzeugen

Nach Abschluss einer Backend-Domain:

1. `docs/domain/01_miniworld.md`, `02_business-rules.md`, `03_er.md` lesen
2. `docs/by-domain/{domain}.md` lesen
3. alle zur Domain gehörenden `docs/by-use-case/{uc}.md` lesen
4. daraus genau eine QA-Datei für die Domain erzeugen

### Phase 2: Benutzerergebnisse auswerten

Nachdem der Benutzer getestet und Ergebnisse eingetragen hat:

1. nur die vom Benutzer dokumentierten Ergebnisse lesen
2. jeden Testpunkt bewerten:
   - `bestanden` — durch Benutzerbeleg klar erfüllt
   - `fehlgeschlagen` — durch Benutzerbeleg klar verletzt
   - `blockiert` — Benutzer konnte den Test nicht ausführen
   - `offen` — kein Ergebnis eingetragen
3. Soll/Ist-Abweichungen benennen
4. betroffene Schicht als begründete Vermutung einordnen
5. kompakten Bericht an den Hauptagenten liefern

Fehlende Informationen dürfen niemals als Erfolg interpretiert werden.

---

## Aufbau der QA-Datei

Die Datei unter `docs/qa/backend/{domain}.md` wird in dieser Struktur erzeugt:

# Backend QA – <Domain>

## Metadaten

- Domain: <domain>
- Status: vorbereitet
- Erstellt von: backend-qa
- Datum: <YYYY-MM-DD>

## Quellen

- `docs/domain/01_miniworld.md`
- `docs/domain/02_business-rules.md`
- `docs/domain/03_er.md`
- `docs/by-domain/{domain}.md`
- `docs/by-use-case/{uc}.md`

## Vorbedingungen

- API läuft
- Datenbank läuft
- benötigte Seed-Daten vorhanden
- benötigte Rollen vorhanden
- benötigte Testdaten vorhanden

## Use Cases

### <Use-Case-ID> – <Titel>

**Ziel**
<Beschreibung>

**Request / Aktion**
    <curl oder Testbeschreibung>

**Erwartung HTTP**
- <Status>

**Erwartung JSON**
- <Punkt>

**Erwartung Datenbank**
- <Punkt>

**Erwartung Audit**
- <Punkt>

**Erwartung RBAC**
- <Punkt>

**Benutzerergebnis**
- Status: offen
- Notiz: noch nicht getestet

**CLI / Tinker Ergebnis**
    noch nicht getestet

**Skill-Auswertung**
- noch nicht ausgewertet

---

## Typische Prüfblöcke pro Use Case

Je nach Use Case sollen insbesondere diese Backend-Aspekte vorbereitet werden:

- Success Case
- Validation Case
- Unauthorized
- Forbidden / RBAC
- Not Found
- fachlicher Konflikt
- Datenbankfolge
- Audit-Log
- atomare Konsistenz
- Soft Delete oder Reaktivierung, falls fachlich relevant

Nicht jeder Use Case braucht alle Prüfblöcke. Es dürfen nur die fachlich dokumentierten und technisch sinnvollen Prüfblöcke aufgenommen werden.

---

## Ergebnisbericht an den Hauptagenten

Nach Auswertung liefert der Skill einen kompakten Bericht mit:

- Domain
- geprüfte Use Cases
- bestandene, fehlgeschlagene, blockierte Punkte
- wichtigste Soll/Ist-Abweichungen
- vermutete betroffene Schicht (z. B. Middleware, Spatie Permission, FormRequest, Controller, Service, Model, Response-Struktur, Audit-Service, Transaktion)
- empfohlene Korrekturrichtung

Der Bericht ist knapp, strukturiert und ausschließlich belegbasiert.

---

## Harte Regeln

- Keine Testergebnisse erfinden
- Keine fehlenden Ergebnisse als erfolgreich interpretieren
- Keine zusätzlichen fachlichen Testfälle erfinden, die nicht dokumentiert sind
- Keine fachliche Wahrheit aus Vermutungen ableiten
