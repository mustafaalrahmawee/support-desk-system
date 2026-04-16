# Docs README: Smart Support Desk System

## Zweck

Diese Datei beschreibt die Dokumentstruktur des Projekts und wie die Dokumente gelesen, gepflegt und für neue Chats, Claude Code, Codex oder andere Agenten verwendet werden sollen.

Sie dient als zentrale Orientierung für die Dokument-Topologie des Projekts.

---

## Grundidee der Dokumentstruktur

Das Projekt verwendet bewusst **zwei Ebenen von Dokumentation**:

1. **Master-Dateien**
2. **aufgeteilte Arbeitsdateien**

### Master-Dateien

Master-Dateien sind die vollständigen, zusammenhängenden Hauptdokumente eines Bereichs.

Sie dienen für:

- Gesamtüberblick
- Review
- Konsistenzprüfung
- fachliche Gesamtpflege

Beispiele:

- `docs/02_use-cases/use-cases.md`
- `docs/03_backend/api-contracts/api-contracts.md`
- `docs/04_frontend/screen-flows/screen-flows.md`

### Aufgeteilte Arbeitsdateien

Zusätzlich zu den Master-Dateien gibt es kleinere, fokussierte Dateien in Unterordnern wie:

- `by-domain/`
- `by-use-case/`

Diese dienen für:

- kleinere Kontexte pro Session
- gezielte Arbeit mit Claude Code oder Codex
- fokussierte Frontend- oder Backend-Implementierung
- gezielte Review- oder Testarbeit

---

## Verbindliche Regel

Die wichtigste Regel lautet:

**Master-Datei = Gesamtüberblick und kanonische Hauptreferenz**  
**by-domain = bevorzugter Session-Kontext**  
**by-use-case = atomare oder eng zugeschnittene Arbeitseinheit**

Das bedeutet:

- Master-Dateien bleiben erhalten
- große Dateien werden nicht einfach ersetzt
- kleinere Dateien ergänzen die Hauptdateien
- neue Chats und Agenten sollen möglichst mit der kleinsten passenden Dokumenteinheit arbeiten

---

## Leselogik im Projekt

### Für Überblick oder fachliche Gesamtprüfung

Zuerst die Master-Dateien lesen.

### Für fokussierte Implementierungssessions

Nach dem Überblick bevorzugt die passende `by-domain/` Datei lesen.

### Für sehr kleine, atomare Aufgaben

Zusätzlich die passende `by-use-case/` Datei lesen, wenn vorhanden und sinnvoll.

---

## Verbindliche Dokumentreihenfolge

Bei einer neuen Aufgabe gilt grundsätzlich diese Reihenfolge:

1. `docs/01_domain/01_miniworld.md`
2. `docs/01_domain/02_business-rules.md`
3. `docs/01_domain/03_er.md`
4. `docs/02_use-cases/use-cases.md`
5. `docs/03_backend/api-contracts/api-contracts.md`
6. `docs/03_backend/architecture/architecture.md`
7. `docs/03_backend/endpoint-groups/endpoint-groups.md`
8. `docs/03_backend/testing/testing.md`
9. `docs/04_frontend/architecture/frontend-architecture.md`
10. `docs/04_frontend/screen-flows/screen-flows.md`
11. `docs/04_frontend/ui-rules/ui-rules.md`
12. `docs/04_frontend/stitch/stitch-prompt-rules.md`
13. `docs/04_frontend/stitch/stitch-workflow.md`

Diese Reihenfolge bleibt die Gesamtlogik des Projekts.

Danach soll für die eigentliche Arbeit die kleinere passende Einheit gewählt werden.

---

## Struktur der Unterordner

### `by-domain/`

`by-domain/` enthält thematisch zusammenhängende Dateien für einen fachlichen Bereich.

Beispiele:

- auth
- internal-users
- customers
- contacts
- contracts
- tickets
- inbound
- categories
- media

`by-domain/` ist im Projekt der **bevorzugte Arbeitskontext für Sessions**.

### `by-use-case/`

`by-use-case/` enthält kleine, gezielte Einheiten für einzelne Use Cases oder eng zusammenhängende Umsetzungsflows.

Diese Dateien sind besonders nützlich für:

- kleine Aufgaben
- gezielte Agentenarbeit
- einzelne Commands
- UI-Erzeugung
- atomare Review- oder Testarbeit

---

## Wichtige Sonderregel für Screen Flows

Bei `docs/04_frontend/screen-flows/` gilt eine besondere Regel:

### Master-Datei

- `screen-flows.md` bleibt die vollständige Übersichtsdatei

### `by-domain/`

- enthält Screen-Flows pro fachlichem Bereich

### `by-use-case/`

- wird **screen-orientiert** interpretiert
- nicht zwingend als 1 Datei pro Backend-Use-Case
- wenn mehrere Backend-Use-Cases denselben Screen betreffen, sollen sie in einer gemeinsamen Screen-Flow-Datei zusammengefasst werden

Beispiel:

Ein Ticket-Detail-Screen kann mehrere Use Cases abdecken, etwa:

- Ticket anzeigen
- Ticket zuweisen
- Ticketstatus ändern
- Kategorie ändern
- Priorität ändern
- Contract setzen

Dann soll dafür **eine gemeinsame Screen-Flow-Datei** verwendet werden, statt künstlich viele kleine Dateien zu erzeugen.

---

## Wichtige Sonderregel für Use Cases

Bei `docs/02_use-cases/` gilt:

- `use-cases.md` bleibt die vollständige Master-Datei
- `by-domain/` gruppiert mehrere fachlich zusammenhängende Use Cases
- `by-use-case/` enthält atomare Use-Case-Dateien

Hier bleibt die Einheit fachlich tatsächlich der Use Case.

Das bedeutet:

- ein Use Case bleibt ein Use Case
- auch wenn mehrere Use Cases später denselben Frontend-Screen verwenden

---

## Wichtige Sonderregel für API Contracts

Bei `docs/03_backend/api-contracts/` gilt:

- `api-contracts.md` bleibt die vollständige Master-Datei
- die Aufteilung erfolgt primär über `by-domain/`

Grund:

API-Contracts hängen oft enger an fachlichen Bereichen als an exakt einem einzelnen Use Case.

---

## Wichtige Sonderregel für Querschnittsdokumente

Nicht alle Dokumente sollen gleich stark atomisiert werden.

Besonders bei querschnittlichen Dateien wie:

- `architecture.md`
- `testing.md`
- `ui-rules.md`

gilt:

- Master-Datei bleibt sehr wichtig
- `by-domain/` ist sinnvoll
- zu starke Zerstückelung soll vermieden werden

Grund:

Diese Dokumente enthalten viele bereichsübergreifende Regeln und verlieren an Qualität, wenn sie zu kleinteilig werden.

---

## Wie neue Chats oder Agenten arbeiten sollen

Bei neuen Chats, Claude Code, Codex oder ähnlichen Agenten gilt:

### 1. Erst Überblick
Zuerst die relevante Master-Datei und die Domain-Dokumente lesen.

### 2. Dann Kontext verkleinern
Danach möglichst auf die passende `by-domain/` Datei wechseln.

### 3. Wenn nötig weiter zuschneiden
Für sehr kleine Aufgaben zusätzlich `by-use-case/` verwenden.

### 4. Nicht blind ganze Monster-Dateien laden
Wenn es eine passende kleinere Datei gibt, soll diese bevorzugt verwendet werden.

---

## Pflege-Regeln für die Doku

Wenn Inhalte geändert werden, gilt:

- zuerst auf Konsistenz mit der Master-Datei achten
- kleinere Dateien dürfen der Hauptlogik nicht widersprechen
- Unterdateien sollen die Master-Dokumente ergänzen, nicht davon fachlich abweichen
- bei strukturellen Änderungen soll auch diese `docs/README.md` angepasst werden

---

## Ziel dieser Struktur

Die Dokumentstruktur soll gleichzeitig ermöglichen:

- fachliche Konsistenz
- gute Lesbarkeit für Menschen
- fokussierte Arbeit mit Agenten
- kleine Session-Kontexte
- klare Trennung zwischen Überblick und Umsetzungseinheit

---

## Kurzfassung

Die wichtigste Kurzfassung lautet:

- **Master-Dateien bleiben**
- **by-domain ist der bevorzugte Session-Kontext**
- **by-use-case ist die kleine Arbeitseinheit**
- **screen-flows/by-use-case ist screen-orientiert**
- **querschnittliche Dokumente werden nicht blind überatomisiert**