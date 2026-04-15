# Stitch Workflow: Smart Support Desk System

## Zweck

Diese Datei beschreibt, wie Claude Code zusammen mit Stitch MCP und Google Labs stitch-skills im Frontend-Workflow des Smart Support Desk Systems verwendet wird.

Sie legt fest, welche Rolle dieser Workflow im Projekt hat, wie ein Screen vorbereitet wird, wie der erzeugte Output weiterverarbeitet wird und welche Grenzen dieser Workflow im Projekt hat.

Diese Datei beschreibt bewusst den Arbeitsablauf mit Claude Code, Stitch MCP und stitch-skills. Sie ersetzt nicht die fachlichen Dokumente oder die Frontend-Architektur.

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
10. `docs/04_frontend/stitch/stitch-workflow.md`

Der Stitch-Workflow darf den Regeln dieser Dokumente nicht widersprechen.

---

## Rolle von Claude Code + Stitch MCP im Projekt

Claude Code mit Stitch MCP und Google Labs stitch-skills ist in diesem Projekt der **verbindliche Workflow zur UI-Erzeugung**.

Dieser Workflow wird verwendet für:

- Design-System-Synthese
- Screen-Grundstruktur
- Layout-Vorschläge
- Form-Struktur
- Tabellen-Struktur
- Dialog-Grundlagen
- responsive UI-Vorlagen
- visuelle Screens für Admin-, Support- und Prüfoberflächen

Der Workflow wird **nicht** verwendet als Quelle für:

- Business Rules
- API-Verhalten
- Rollenmodell
- Berechtigungsregeln
- Statusregeln
- Contact-Regeln
- Merge-Regeln
- Audit-Regeln
- Backend-Architektur

---

## Grundregel

Die wichtigste Regel lautet:

**Claude Code + Stitch MCP erzeugen UI-Struktur, aber die fachliche Wahrheit kommt immer aus den Projektdokumenten.**

---

## Verbindlicher Projektworkflow

Im Projekt ist die Nutzung von Claude Code zusammen mit Stitch MCP und Google Labs stitch-skills der Standardweg für die Vorbereitung und Erzeugung neuer Screens.

Das bedeutet:

- UI-Screens werden nicht frei aus Intuition gebaut
- UI-Screens werden nicht direkt nur manuell improvisiert
- Screens werden aus fachlichem Kontext vorbereitet und anschließend über Claude Code mit Stitch MCP erzeugt
- der erzeugte Output wird danach in die Vue-Projektstruktur überführt

Manuelles Nacharbeiten bleibt erlaubt und notwendig, aber die **Erststruktur** eines Screens soll standardmäßig aus diesem Workflow kommen.

---

## Bevorzugter Workflow pro Use Case

Der bevorzugte Ablauf im Projekt ist:

### 1. Use Case auswählen

Ein konkreter Use Case wird gewählt.

Beispiele:

- Use Case 36: Login
- Use Case 40: Internen Benutzer anlegen
- Use Case 24: Ticket einem internen Benutzer zuweisen
- Use Case 16: Contract für Customer anlegen
- Use Case 2: Inbound-Prüffall prüfen und entscheiden

### 2. API-Contract lesen

Der passende Abschnitt aus `api-contracts.md` wird gelesen.

Dabei wird geklärt:

- welche Endpunkte betroffen sind
- welche Requests später gesendet werden
- welche Success Cases existieren
- welche Failed Cases existieren
- welche UI-Reaktionen notwendig sind

### 3. Screen Flow lesen

Der passende Screen Flow wird gelesen.

Dabei wird geklärt:

- welche Hauptbereiche der Screen braucht
- welche Benutzeraktionen stattfinden
- welche UI-Zustände abgebildet werden müssen

### 4. UI-Regeln lesen

Die relevanten UI-Regeln werden gelesen.

Dabei wird geklärt:

- welche visuellen Muster verwendet werden sollen
- wie Formulare, Tabellen, Dialoge und Statusdarstellungen aussehen sollen
- welche wiederverwendbaren UI-Strukturen bevorzugt werden

### 5. Minimale Store-Funktionen planen

Für den konkreten Use Case werden die minimal nötigen Pinia-Store-Funktionen geplant.

Beispiele:

- `login(payload)`
- `fetchInternalUsers(params)`
- `createInternalUser(payload)`
- `fetchReviewCases(params)`
- `assignExistingCustomer(id, payload)`
- `createContract(customerId, payload)`
- `setContract(id, payload)`

Wichtig:
Nicht alle theoretisch möglichen Funktionen des gesamten Projekts vorher bauen, sondern nur die für diesen konkreten Screen nötigen Funktionen.

### 6. Projektkontext für Claude Code vorbereiten

Dann wird der relevante Kontext für die UI-Erzeugung vorbereitet.

Dieser Kontext kann enthalten:

- Use Case
- API-Contract
- Screen Flow
- UI-Regeln
- fachliche Begriffe des Screens
- Design-Hinweise
- bestehende Komponenten oder Referenzscreens
- projektspezifische Notizen

### 7. Prompt gemäß Stitch-Prompt-Regeln vorbereiten

Dann wird ein Prompt gemäß `stitch-prompt-rules.md` erstellt oder durch Claude Code mit den vorhandenen Kontextdateien strukturiert vorbereitet.

### 8. Claude Code mit Stitch MCP und stitch-skills ausführen

Claude Code nutzt den vorbereiteten Kontext, Stitch MCP und die stitch-skills, um:

- ein konsistentes Design-System oder Screen-Ziel abzuleiten
- die passende UI-Struktur zu erzeugen
- bei Bedarf Design- oder Layout-Entscheidungen aus dem bereitgestellten Kontext zu harmonisieren

### 9. Output importieren

Der erzeugte Output wird in das Vue-Projekt übernommen.

### 10. Vue-Struktur ergänzen

Dann wird der Output in eine saubere Vue-Struktur überführt:

- `template`
- `script setup`
- Props
- Emits
- Reactive State
- Komponentenaufteilung

### 11. Vuelidate ergänzen

Falls der Screen Formulare enthält, werden passende Vuelidate-Regeln ergänzt.

### 12. Store anbinden

Jetzt werden die vorbereiteten Store-Funktionen im Screen aufgerufen.

### 13. UI-Zustände fertig ergänzen

Dann werden alle relevanten UI-Zustände ergänzt:

- loading
- empty
- error
- disabled
- success
- forbidden
- not found
- conflict

---

## Kurzform des bevorzugten Workflows

Die bevorzugte Reihenfolge lautet:

**Use Case → API-Contract → Screen Flow → UI-Regeln → minimale Store-Funktionen → vorbereiteter Kontext → Claude Code + Stitch MCP + stitch-skills → Vue-Logik → Vuelidate → Store-Aufruf → UI-Zustände**

---

## Was Claude Code + Stitch MCP im Projekt erzeugen dürfen

Der Workflow darf im Projekt erzeugen:

- Seitenlayout
- Karten, Panels und Sektionen
- Form-Grundstruktur
- Tabellen-Grundstruktur
- Dialog-Grundstruktur
- Such- und Filterleisten
- Header-Bereiche
- Responsive-Anordnung
- Platzhalter für Fehlermeldungen
- Platzhalter für Empty States
- visuelle Statusindikatoren
- Design-System-nahe UI-Vorgaben für den Screen-Kontext

---

## Was Claude Code + Stitch MCP nicht erzeugen dürfen

Der Workflow soll nicht die Quelle sein für:

- echte API-Aufrufe als fachliche Wahrheit
- Pinia-Store-Architektur aus Intuition
- fachliche Validierungslogik
- Auth- und Rollenlogik
- Statusregeln
- Merge-Logik
- Contact-Auswahlregeln
- Soft-Delete-Folgen
- Reaktivierungslogik
- Audit-Regeln
- Inbound-Entscheidungslogik
- Contract-Zuordnungsregeln

Auch wenn Claude Code oder Stitch Vorschläge dafür machen können, dürfen diese nicht ohne Abgleich mit den Projektdokumenten als korrekt übernommen werden.

---

## Rolle von Design-System und Kontextdateien

Im Projekt darf Claude Code mit Stitch MCP nicht nur einen einzelnen Screen erzeugen, sondern auch aus vorbereitetem Kontext ein konsistentes UI- oder Design-System ableiten.

Das kann zum Beispiel enthalten:

- Farben
- Typografie
- Abstände
- Formularmuster
- Tabellenmuster
- Card- und Panel-Strukturen
- Dialogmuster
- Statusdarstellungen

Wichtig ist:

Dieses Design-System ist Teil der **UI-Konsistenz**, nicht Teil der fachlichen Wahrheit.

Es darf also:

- UI vereinheitlichen
- visuelle Entscheidungen vorbereiten
- die Arbeit an Screens beschleunigen

Es darf nicht:

- Business Rules ersetzen
- Fachlogik definieren
- Rollen- oder Statusregeln frei festlegen

---

## Wie Output weiterverarbeitet wird

Der Output aus Claude Code + Stitch MCP wird im Projekt nicht direkt als finale Wahrheit verwendet.

Stattdessen gilt:

### 1. Output prüfen

Zuerst wird geprüft:

- passt der Screen zum Use Case?
- passen die Hauptbereiche?
- fehlen wichtige Zustände?
- enthält der Output unnötige Logik?
- ist die Struktur für Vue brauchbar?
- widerspricht etwas den UI-Regeln oder Screen-Flows?

### 2. Output bereinigen

Dann wird entfernt oder angepasst:

- unnötige Dummy-Logik
- unpassende Texte
- irrelevante Platzhalter
- unklare UI-Elemente
- fachlich falsche Annahmen
- unnötige implizite Rollen- oder API-Logik

### 3. Output in Projektstruktur überführen

Dann wird die UI in die Projektstruktur überführt:

- `pages/`
- `components/`
- `shared/` Komponenten
- ggf. Modals oder Formbereiche separieren

### 4. Vue-Logik ergänzen

Erst danach wird echte Vue-Logik ergänzt.

---

## Typische Arbeitsteilung im Projekt

### Claude Code + Stitch MCP + stitch-skills
liefern:
- Design-System-Synthese
- visuelle Struktur
- Layout
- Screen-Komposition
- Form- und Tabellen-Grundform

### Pinia Store
liefert:
- API-Aufrufe
- Loading- und Error-Status
- Daten für den Screen
- Ergebnisverarbeitung

### Vuelidate
liefert:
- Frontend-nahe Formularvalidierung

### Vue-Komponenten
liefern:
- Anzeige
- Benutzerinteraktion
- Props / Emits
- Event-Handling
- Integration von Store und Validierung

---

## Welche Screens sich besonders gut für den Workflow eignen

Der Workflow eignet sich besonders gut für:

- Login Screen
- Profile Screen
- Internal-Users-Liste
- Internal-User-Formular
- Ticket-Liste
- Ticket-Detail-Grundlayout
- Customer-Detail-Grundlayout
- Contact-Verwaltung
- Contract-Detail und Contract-Formulare
- Inbound-Review-Liste
- Inbound-Review-Detail
- Kategorie-Verwaltung
- Confirm-Dialoge
- Filter- und Tabellenoberflächen

---

## Welche Dinge mehr Nacharbeit brauchen

Stärker nachzuarbeiten sind vor allem:

- komplexe Ticket-Aktionslogik
- Merge-Dialoge mit vielen Fachkonflikten
- feine Rollen- und Statuslogik
- stark API-abhängige UI-Interaktionen
- komplizierte Inbound-Entscheidungsoberflächen
- Upload- und Dokumentkontexte mit mehreren Zuständen
- Screens mit vielen abhängigen Fachzuständen

Diese Screens dürfen trotzdem über Claude Code + Stitch MCP vorbereitet werden, brauchen aber fast immer mehr manuelle oder agentische Nacharbeit.

---

## Verbindliche technische Regeln für erzeugten Output

Der erzeugte Output soll im Projekt immer an diese Regeln angepasst werden:

- Vue.js-kompatible Struktur
- Tailwind CSS statt zufälliger Stilstruktur
- klare Komponentenaufteilung
- keine direkte Business-Logik
- keine versteckten API-Annahmen
- gute Lesbarkeit
- responsive Verhalten
- konsistente UI mit `ui-rules.md`

---

## Wann der Workflow nicht übersprungen werden soll

Da Claude Code + Stitch MCP im Projekt der verbindliche UI-Workflow ist, soll dieser Workflow grundsätzlich verwendet werden.

Ein direkt rein manuelles Bauen ohne diesen Workflow ist nur dann sinnvoll, wenn:

- eine sehr kleine Anpassung an bestehender UI erfolgt
- eine bestehende Komponente minimal erweitert wird
- der Screen bereits vollständig über diesen Workflow vorbereitet wurde und nur noch technische Ergänzung nötig ist
- ein rein technischer Fix ohne neue UI-Struktur umgesetzt wird

Das ändert nichts daran, dass die **Erststruktur** neuer Screens im Projekt standardmäßig aus diesem Workflow kommen soll.

---

## Nutzen für Agentic AI

Ein Agentic-AI-Modell soll aus dieser Datei verstehen:

- dass Claude Code + Stitch MCP + stitch-skills der verbindliche UI-Workflow des Projekts sind
- in welcher Reihenfolge ein Screen erarbeitet wird
- dass Store-Funktionen vor der finalen UI-Anbindung geplant werden
- dass Design-System und UI-Struktur aus vorbereitetem Kontext erzeugt werden dürfen
- dass fachliche Wahrheit aus den Projektdokumenten kommt
- dass der erzeugte Output immer nachbearbeitet und an die Projektarchitektur angepasst werden muss

---

## Wichtigste Workflow-Regel

Die wichtigste Regel lautet:

**Claude Code + Stitch MCP + stitch-skills werden im Projekt nach Use Case, API-Contract, Screen Flow, UI-Regeln und minimaler Store-Planung eingesetzt und liefern die UI-Vorlage für die spätere Vue-Integration.**