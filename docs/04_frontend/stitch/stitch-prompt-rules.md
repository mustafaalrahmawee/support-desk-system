# Stitch Prompt Rules: Smart Support Desk System

## Zweck

Diese Datei definiert die Regeln für Prompts, die im Smart Support Desk System für Claude Code zusammen mit Stitch MCP und Google Labs stitch-skills verwendet werden.

Sie stellt sicher, dass der Workflow nicht frei aus Intuition UI erzeugt, sondern auf Basis der fachlichen und frontendseitigen Projektdokumentation arbeitet.

Diese Datei beschreibt nicht die Fachlogik des Systems selbst, sondern die Regeln, wie Screens und UI-Komponenten per Claude Code mit Stitch MCP sinnvoll erzeugt werden sollen.

---

## Referenzdokumente

Diese Datei ist zusammen mit den folgenden Dokumenten zu lesen:

1. `docs/01_domain/01_miniworld.md`
2. `docs/01_domain/02_business-rules.md`
3. `docs/01_domain/03_er.md`
4. `docs/02_use-cases/use-cases.md`
5. `docs/03_backend/api-contracts/api-contracts.md`
6. `docs/04_frontend/architecture/frontend-architecture.md`
7. `docs/04_frontend/screen-flows/screen-flows.md`
8. `docs/04_frontend/ui-rules/ui-rules.md`
9. `docs/04_frontend/stitch/stitch-prompt-rules.md`

Die Prompts dürfen den Regeln dieser Dokumente nicht widersprechen.

---

## Ziel der Prompt-Regeln

Die Prompts sollen so formuliert werden, dass:

- Claude Code mit Stitch MCP nur UI und Design-System erzeugt und keine Fachregeln erfindet
- Screens aus konkreten Use Cases, Screen Flows und API-Contracts abgeleitet werden
- Vue.js und Tailwind CSS als verbindlicher Stack genutzt werden
- Screens nicht nur den Happy Path abbilden
- Komponenten und Screens konsistent aussehen
- die erzeugte UI leicht in Vue-Komponenten überführt werden kann
- der Workflow mit Google Labs stitch-skills stabil und reproduzierbar funktioniert

---

## Grundregel

Die wichtigste Regel lautet:

**Claude Code + Stitch MCP erzeugen UI-Struktur und Design-System, nicht Fachlogik.**

Der Workflow darf helfen bei:

- Layout
- Design-System-Synthese
- Komponentenstruktur
- Formularaufbau
- Tabellenaufbau
- Dialogstruktur
- responsivem Screen-Gerüst
- visuellen States

Der Workflow darf nicht die Quelle sein für:

- Business Rules
- API-Logik
- Rollenlogik
- Statusübergänge
- Contact-Auswahlregeln
- Merge-Regeln
- Soft-Delete-Regeln
- Audit-Regeln
- Validierungsregeln als fachliche Wahrheit

---

## Verbindlicher technischer Kontext für Prompts

Jeder Prompt soll den technischen Stack klar nennen.

Verbindlich sind:

- **Claude Code**
- **Stitch MCP**
- **Google Labs stitch-skills**
- **Vue.js**
- **Tailwind CSS**
- **Single File Component Denkweise**
- **nur UI / Design-System / Screen-Struktur**
- **keine direkte API-Implementierung**
- **keine Business-Logik**
- **keine echte Auth-Implementierung**
- **keine Store-Architektur im Prompt erfinden**

Der Workflow soll also Screens erzeugen, die später in:

- `pages/`
- `components/`

übernommen und dort mit Vue-Logik ergänzt werden.

---

## Pflichtbestandteile jedes Prompts

Jeder Prompt soll möglichst diese Bausteine enthalten:

### 1. Screen-Kontext

Was ist der Screen fachlich?

Beispiele:
- Admin-Login-Screen
- Ticket-Detail-Screen
- Contract-Detail-Screen
- Inbound-Review-Listen-Screen
- Internal-Users-Admin-Screen

### 2. Technologiekontext

Immer klar nennen:

- Claude Code mit Stitch MCP
- Google Labs stitch-skills
- Vue.js
- Tailwind CSS
- responsive Layout
- nur UI
- keine API-Integration

### 3. Zielbenutzer oder Rolle

Wer nutzt den Screen?

Beispiele:
- Support Agent
- Inbound Reviewer
- Contract Manager
- Admin

### 4. Wichtige UI-Bereiche

Welche Bereiche muss der Screen enthalten?

Beispiele:
- Suchleiste
- Filter
- Tabelle
- Formular
- Detailpanel
- Nachrichtenbereich
- Aktionsbuttons
- Dokumente-Sektion
- Rollen-Badges

### 5. Zustände

Welche UI-Zustände müssen sichtbar gemacht werden?

Mindestens relevant sind oft:

- loading
- empty
- error
- disabled
- success
- forbidden
- not found
- conflict

### 6. Stilhinweise

Zum Beispiel:
- modernes Admin-Layout
- klare Abstände
- ruhige Oberfläche
- responsive
- gut lesbare Formstruktur
- konsistente Support- und Prüfoberfläche

### 7. Kontextquellen

Wenn möglich, soll der Prompt klar machen, aus welchem Projektkontext die UI abgeleitet wurde.

Zum Beispiel:

- Use Case
- API-Contract
- Screen Flow
- UI-Regeln
- Design- oder Brand-Hinweise

---

## Was Prompts immer ausdrücklich sagen sollen

Jeder Prompt soll möglichst klar sagen:

- **Nur UI**
- **Keine API-Calls**
- **Keine Store-Logik**
- **Keine Business-Logik**
- **Keine Auth-Implementierung**
- **Keine Rollenlogik im Code**
- **Keine echte Formularvalidierung**
- **Responsive Layout**
- **Geeignet für spätere Überführung in Vue-Komponenten**
- **Stitch-Output ist UI-Vorlage, nicht finale Fachimplementierung**

---

## Was Prompts vermeiden sollen

Ein Prompt soll vermeiden:

- unklare Fachbegriffe ohne Kontext
- „mach einfach ein Dashboard“
- zu viel technische Implementierungslogik
- zu allgemeine Bitten ohne Use-Case-Bezug
- implizite Annahmen über Rollen oder Rechte
- implizite Annahmen über API-Verhalten
- implizite Annahmen über Fehlermeldungen
- implizite Annahmen über Statuswechsel
- implizite Annahmen über Contract-, Contact- oder Inbound-Logik

---

## Ableitung eines Prompts aus dem Projekt

Ein Prompt soll idealerweise in dieser Reihenfolge vorbereitet werden:

### 1. Use Case lesen
Was soll der Screen fachlich tun?

### 2. Screen Flow lesen
Wie verhält sich der Screen im UI?

### 3. API-Contract lesen
Welche Success- und Failed-Cases sind später relevant?

### 4. UI Rules lesen
Wie soll der Screen visuell aufgebaut sein?

### 5. Kontext für Claude Code vorbereiten
Welche Projektdaten, Referenzen oder Design-Hinweise sollen einfließen?

### 6. Prompt formulieren
Erst jetzt wird der Prompt erstellt.

---

## Standardform eines Prompts

Ein guter Standardprompt hat ungefähr diese Form:

```text
Erzeuge mit Claude Code unter Verwendung von Stitch MCP und Google Labs stitch-skills eine Vue.js Screen-Komponente mit Tailwind CSS.

Kontext:
Dies ist ein Screen für das Smart Support Desk System.

Wichtig:
- Nur UI
- Keine API-Integration
- Keine Store-Logik
- Keine Business-Logik
- Keine Auth-Implementierung
- Keine Rollenlogik im Code
- Keine echte Formularvalidierung
- Responsive Layout
- Moderne, ruhige Admin- oder Support-Oberfläche
- Geeignet für spätere Überführung in Vue-Komponenten

Benutzer:
[Support Agent / Inbound Reviewer / Contract Manager / Admin]

Screen-Ziel:
[fachlicher Zweck]

Enthält:
[Liste der UI-Bereiche]

Berücksichtige UI-Zustände:
- loading
- empty
- error
- disabled
- success
- forbidden / not found / conflict wenn relevant
```

---

## Beispiel: Login Prompt

```text
Erzeuge mit Claude Code unter Verwendung von Stitch MCP und Google Labs stitch-skills eine Vue.js Screen-Komponente mit Tailwind CSS.

Kontext:
Dies ist ein Login-Screen für das Smart Support Desk System.

Wichtig:
- Nur UI
- Keine API-Integration
- Keine Store-Logik
- Keine Business-Logik
- Keine Auth-Implementierung
- Keine echte Formularvalidierung
- Responsive Layout
- Moderne, ruhige Admin-Oberfläche

Benutzer:
Interner Benutzer

Screen-Ziel:
Anmeldung am System mit E-Mail und Passwort.

Enthält:
- Seitentitel
- E-Mail-Feld
- Passwort-Feld
- Login-Button
- Bereich für globale Fehlermeldung
- Platzhalter für Feldfehler

Berücksichtige UI-Zustände:
- loading
- error
- disabled
```

---

## Beispiel: Internal-Users-Liste Prompt

```text
Erzeuge mit Claude Code unter Verwendung von Stitch MCP und Google Labs stitch-skills eine Vue.js Screen-Komponente mit Tailwind CSS.

Kontext:
Dies ist ein Admin-Screen für die Verwaltung interner Benutzer im Smart Support Desk System.

Wichtig:
- Nur UI
- Keine API-Integration
- Keine Store-Logik
- Keine Business-Logik
- Keine Rollenlogik im Code
- Responsive Layout
- Moderne, ruhige Admin-Oberfläche

Benutzer:
Admin

Screen-Ziel:
Liste interner Benutzerkonten mit Such- und Verwaltungsaktionen.

Enthält:
- Seitentitel
- Suchfeld
- Filter für Rolle und Aktivstatus
- Tabelle mit Spalten für Name, E-Mail, Rollen, Status, Aktionen
- Button „Neuen internen Benutzer anlegen“
- Pagination
- Empty State
- Fehlerbereich

Berücksichtige UI-Zustände:
- loading
- empty
- error
- disabled
- success
- forbidden
```

---

## Beispiel: Ticket-Detail Prompt

```text
Erzeuge mit Claude Code unter Verwendung von Stitch MCP und Google Labs stitch-skills eine Vue.js Screen-Komponente mit Tailwind CSS.

Kontext:
Dies ist ein Ticket-Detail-Screen für das Smart Support Desk System.

Wichtig:
- Nur UI
- Keine API-Integration
- Keine Store-Logik
- Keine Business-Logik
- Keine Statuslogik im Code
- Keine Contract-Logik im Code
- Responsive Layout
- Moderne Support-Oberfläche

Benutzer:
Support Agent

Screen-Ziel:
Darstellung eines Tickets mit Metadaten, Customer-Bezug, optionalem Contract-Kontext, Nachrichtenverlauf und Aktionsbereichen.

Enthält:
- Ticketkopf mit Subject, Status, Priorität, Kanal, Kategorie
- Customer-Bereich
- Contract-Bereich
- Bearbeiter-Bereich
- Nachrichtenverlauf
- Bereich für öffentliche Antwort
- Bereich für interne Nachricht
- Bereich für Statusaktionen
- Bereich für Kategorie-, Prioritäts- und Contract-Änderung

Berücksichtige UI-Zustände:
- loading
- error
- not found
- disabled
- closed state
- conflict
```

---

## Beispiel: Inbound-Review-Detail Prompt

```text
Erzeuge mit Claude Code unter Verwendung von Stitch MCP und Google Labs stitch-skills eine Vue.js Screen-Komponente mit Tailwind CSS.

Kontext:
Dies ist ein Detail-Screen für einen Inbound-Prüffall im Smart Support Desk System.

Wichtig:
- Nur UI
- Keine API-Integration
- Keine Store-Logik
- Keine Business-Logik
- Keine Entscheidungslogik im Code
- Responsive Layout
- Ruhige Prüf- und Triage-Oberfläche

Benutzer:
Inbound Reviewer

Screen-Ziel:
Prüfung eines unklaren Eingangs und Darstellung möglicher Entscheidungswege.

Enthält:
- Bereich mit Eingangsdaten
- erkannte Hinweise wie Contact, customer_number, contract_number, Name
- Nachrichtentext
- Bereich für vorhandene Anhänge
- Aktionsbereich für Zuordnung zu bestehendem Customer
- Aktionsbereich für Anlage eines neuen Customers
- Bereich zum Markieren als pending
- Eingabebereich für Entscheidungshinweis

Berücksichtige UI-Zustände:
- loading
- error
- not found
- disabled
- success
- forbidden
- conflict
```

---

## Beziehung zwischen Prompt und Vue-Umsetzung

Claude Code mit Stitch MCP liefert die UI-Vorlage.

Danach wird der Screen in Vue weiterverarbeitet:

- Aufteilung in `template`
- Ergänzung mit `script setup`
- Props und Emits
- lokale Reactive-States
- Vuelidate
- Pinia Store Anbindung
- API-Aufrufe über Store

Deshalb sollen Prompts immer so geschrieben sein, dass der Output leicht in Vue strukturiert werden kann.

---

## Rolle von Design-System-Synthese

Der Workflow darf aus vorbereitetem Projektkontext auch ein konsistentes Design-System oder Design-Ziel ableiten.

Das kann helfen bei:

- Farben
- Typografie
- Spacing
- Formmustern
- Tabellenmustern
- Dialogmustern
- Statusdarstellungen

Wichtig ist:

Dieses Design-System ist Teil der UI-Konsistenz, nicht Teil der Fachlogik.

---

## Rolle von Mock-Daten

Mock-Daten oder statische Platzhalter sind in Prompts erlaubt und sinnvoll.

Beispiele:

- Beispiel-Benutzer in einer Tabelle
- Beispiel-Ticketstatus
- Beispiel-Contract-Daten
- Beispiel-Nachrichtenverlauf
- Beispiel-Inbound-Prüffall

Wichtig ist nur:

**Mock-Daten dienen der visuellen Struktur, nicht als fachliche Wahrheit.**

---

## Umgang mit Listen und Tabellen

Wenn ein Prompt Tabellen oder Listen enthält, soll er möglichst nennen:

- welche Spalten wichtig sind
- welche Aktionen pro Zeile existieren
- ob Empty State notwendig ist
- ob Pagination vorgesehen ist
- ob Filter vorgesehen sind

Das reduziert unpassende oder zufällige Tabellenlayouts.

---

## Umgang mit Formularen

Wenn ein Prompt Formulare betrifft, soll er möglichst nennen:

- welche Felder sichtbar sind
- welche Felder Pflichtcharakter haben
- welche Aktionsbuttons es gibt
- ob Feldfehler und globale Fehlermeldungen Platzhalter brauchen
- ob es Success- oder Conflict-Meldungen geben muss

---

## Umgang mit Dialogen

Wenn der Screen Confirm- oder Aktionsdialoge braucht, soll der Prompt dies ausdrücklich nennen.

Zum Beispiel:

- Confirm Deactivate Internal User
- Confirm Soft Delete Contact
- Confirm Contract Deactivate
- Customer Merge Dialog

So verhindert man, dass der Dialog später nur improvisiert ergänzt wird.

---

## Grenzen des Workflows im Projekt

Claude Code + Stitch MCP + stitch-skills sind Hilfsmittel für UI, nicht für Fachdesign.

Deshalb gilt:

- kein API-Contract aus Prompt-Output ableiten
- keine Business Rule aus Prompt-Output ableiten
- kein Rollenmodell aus Prompt-Output ableiten
- keine echte Zustandslogik aus Prompt-Output ableiten

Die Quelle der Wahrheit bleibt immer in den Projektdokumenten.

---

## Nutzen für Agentic AI

Ein Agentic-AI-Modell soll aus dieser Datei verstehen:

- wie Prompts in diesem Projekt formuliert werden
- welche Regeln im Prompt immer genannt werden müssen
- welche Informationen ein Prompt enthalten soll
- was Claude Code + Stitch MCP erzeugen dürfen und was nicht
- dass die UI aus fachlichen Dokumenten abgeleitet werden muss
- dass der Workflow verbindlich im Projekt vorgesehen ist

---

## Wichtigste Prompt-Regel

Die wichtigste Regel lautet:

**Der Prompt beschreibt nur die UI-Struktur eines klar definierten Screens auf Basis der Projektdokumente. Fachlogik, API-Logik und Berechtigungslogik werden nicht aus dem Prompt heraus erfunden.**