# Frontend Architecture: Smart Support Desk System

## Zweck

Diese Datei beschreibt die geplante Frontend-Architektur des Smart Support Desk Systems.

Sie verbindet die fachlichen Dokumente des Projekts mit einer sauberen technischen Struktur für das Frontend und definiert, wie Screens, Komponenten, Stores, Validierung und spätere Backend-Anbindung aufgebaut werden sollen.

Diese Datei beschreibt bewusst die Struktur, Verantwortlichkeiten und Schichten des Frontends. Sie ersetzt nicht die Miniworld, Business Rules, das ER-Modell, die Use Cases oder die API-Contracts, sondern baut auf diesen auf.

---

## Referenzdokumente

Diese Datei ist zusammen mit den folgenden Dokumenten zu lesen:

1. `docs/01_domain/01_miniworld.md`
2. `docs/01_domain/02_business-rules.md`
3. `docs/01_domain/03_er.md`
4. `docs/02_use-cases/use-cases.md`
5. `docs/03_backend/api-contracts/api-contracts.md`
6. `docs/04_frontend/architecture/frontend-architecture.md`

Die Frontend-Architektur darf den fachlichen Regeln dieser Dokumente nicht widersprechen.

---

## Ziel der Frontend-Architektur

Die Frontend-Architektur soll sicherstellen, dass:

- fachliche Regeln nicht im UI erfunden werden
- Screens klar aus Use Cases und API-Contracts abgeleitet werden
- UI, Store-Logik und spätere API-Anbindung sauber getrennt bleiben
- Komponenten wiederverwendbar und verständlich bleiben
- Agentic-AI-Workflows mit Stitch und Claude Code gezielt nutzbar sind
- spätere Integration mit Backend-Endpunkten kontrolliert und konsistent erfolgt
- Formulare, Tabellen und Dialoge einheitlich aufgebaut werden
- der Kontext für einzelne Sessions klein gehalten werden kann

---

## Technologiewahl

Das Frontend des Systems basiert auf:

- **Vue.js**
- **Tailwind CSS**
- **Pinia**
- **ofetch**
- **Vuelidate**

### Begründung

#### Vue.js

Vue.js wird verwendet, weil das Projekt viele fachlich strukturierte Admin-, Detail- und CRUD-Screens enthält und Vue dafür eine gut lesbare und ruhige Komponentenstruktur bietet.

#### Tailwind CSS

Tailwind CSS wird verwendet, weil das System eine flexible, eigene UI benötigt und die Screens nicht an ein starres UI-Framework gebunden sein sollen.

#### Pinia

Pinia wird verwendet, um API-nahe Frontend-Logik und zustandsbezogene Operationen pro fachlichem Bereich zentral zu organisieren.

#### ofetch

ofetch wird verwendet, um API-Anfragen an das Backend strukturiert, schlank und konsistent abzuwickeln.

#### Vuelidate

Vuelidate wird verwendet, um Formularvalidierung in Vue-Komponenten klar und kontrolliert umzusetzen.

---

## Grundprinzipien der Frontend-Architektur

### 1. Fachlogik kommt aus den Backend-Dokumenten

Das Frontend darf fachliche Regeln nicht selbst erfinden.

Die verbindliche Fachgrundlage kommt immer aus:

- Miniworld
- Business Rules
- Use Cases
- API-Contracts

Das Frontend setzt diese Regeln sichtbar und technisch um, ist aber nicht die Quelle der fachlichen Wahrheit.

### 2. Das Frontend arbeitet Use-Case-orientiert

Jeder wichtige Screen oder Dialog soll einem oder mehreren klaren Use Cases zugeordnet werden können.

Ein Screen darf nicht „nur hübsch“ sein, sondern muss einen fachlichen Zweck haben.

### 3. UI und API-Logik bleiben getrennt

API-Aufrufe sollen nicht ungeordnet direkt in einzelnen Komponenten verteilt werden.

Stattdessen gilt:

- Komponenten und Pages enthalten UI-Logik
- Stores enthalten API-nahe Anwendungslogik
- fachliche Wahrheit kommt aus Use Cases und API-Contracts

### 4. Erst pure UI, dann Integration

Die bevorzugte Arbeitsweise des Projekts ist:

1. Use Case verstehen
2. API-Contract verstehen
3. minimale Store-Funktionen für diesen Use Case planen
4. UI-Screen erzeugen
5. Vue-Logik ergänzen
6. Validierung ergänzen
7. Store-Funktion anbinden
8. später echte Backend-Integration verfeinern

Das bedeutet: Das Frontend darf bewusst in einer frühen Phase mit statischen oder Mock-Daten beginnen.

### 5. Agentic-AI ist Hilfsmittel, nicht fachliche Quelle

Stitch, Claude Code, Codex oder andere agentische Werkzeuge dürfen beim Erzeugen von Screens und Komponenten helfen.

Sie dürfen aber nicht:

- Business Rules erfinden
- API-Verhalten erfinden
- Rollenlogik erfinden
- Validierungslogik fachlich frei festlegen
- Zustandsübergänge frei interpretieren

---

## Frontend-Schichten

Die Frontend-Architektur ist in mehrere Schichten gedacht.

### A. Screen-Schicht

Verantwortung:

- vollständige Seiten
- Seitenaufbau
- Zusammensetzung größerer UI-Bereiche
- Interaktion zwischen UI-Abschnitten
- Einbindung von Stores und Validierung

Typische Beispiele:

- LoginPage
- UsersListPage
- UserCreatePage
- UserEditPage
- TicketListPage
- TicketDetailPage
- CustomerDetailPage

### B. Komponenten-Schicht

Verantwortung:

- wiederverwendbare UI-Bausteine
- Formularelemente
- Tabellen
- Karten
- Dialoge
- Detailsektionen
- Header-Bereiche
- Status-Badges

Typische Beispiele:

- UserTable
- TicketStatusBadge
- CustomerContactList
- ConfirmDialog
- FormInput
- FormTextarea
- PageHeader

### C. Store-Schicht

Verantwortung:

- API-nahe Aufrufe
- Laden, Speichern, Ändern, Deaktivieren, Reaktivieren
- Laden von Detaildaten
- Verwaltung von Loading-, Error- und Ergebniszuständen
- Gruppierung nach fachlichem Bereich

Stores sollen **nicht** die visuelle Struktur eines Screens definieren.

### D. Validierungs-Schicht

Verantwortung:

- Vuelidate-Regeln für Formulare
- formale und UI-nahe Prüfungen
- Anzeige von Feldfehlern
- Verhinderung unnötiger Requests bei offensichtlich ungültigen Eingaben

### E. API-Client-Schicht

Verantwortung:

- zentrale HTTP-Basis
- ofetch-Konfiguration
- Basis-URL
- Header
- Auth-Token oder Session-bezogene Konfiguration
- zentrale Fehlerbehandlung auf niedriger Ebene

---

## Empfohlene Projektstruktur im Frontend

Eine mögliche Frontend-Struktur könnte so aussehen:

```text
src/
  pages/
    auth/
    users/
    tickets/
    customers/
    contacts/
    categories/

  components/
    auth/
    users/
    tickets/
    customers/
    contacts/
    shared/

  stores/
    auth.store.ts
    users.store.ts
    tickets.store.ts
    customers.store.ts
    contacts.store.ts
    categories.store.ts
    messages.store.ts

  services/
    api/
      client.ts

  composables/
  layouts/
  router/
  utils/
  validators/
```

Diese Struktur ist nicht die einzige mögliche, aber sie passt gut zur fachlichen Struktur des Projekts.

---

## Rolle der Pages

Pages repräsentieren vollständige Screens.

Eine Page soll typischerweise:

- den fachlichen Screen-Zweck abbilden
- die passenden Unterkomponenten zusammensetzen
- den zuständigen Store verwenden
- die UI-Zustände eines Screens verwalten
- Formulare und Tabellen in ihren größeren Zusammenhang einbetten

Pages sollen möglichst nicht zu viele kleine Details selbst rendern, wenn dafür wiederverwendbare Komponenten sinnvoll sind.

---

## Rolle der Komponenten

Komponenten sollen wiederverwendbare oder klar abgegrenzte UI-Teile sein.

Komponenten sind sinnvoll für:

- Formfelder
- Formsektionen
- Tabellen
- Listeneinträge
- Statusanzeigen
- Modals / Dialoge
- Karten / Panels
- Toolbar-Bereiche
- Filterleisten

Komponenten sollen möglichst keine chaotische fachliche API-Logik enthalten.

Sie dürfen:

- Props empfangen
- Emits auslösen
- lokale UI-Zustände haben
- Slots verwenden

Sie sollen nicht der Hauptort für fachliche Backend-Kommunikation sein.

---

## Rolle der Stores

Stores sind ein zentraler Teil der Frontend-Architektur.

### Grundregel

Stores enthalten die **minimal nötigen Funktionen pro Use Case oder fachlichem Bereich**, nicht eine überladene Sammlung aller theoretisch möglichen Funktionen des gesamten Projekts.

### Aufgaben der Stores

Stores sollen typischerweise enthalten:

- API-Aufrufe an das Backend
- Speichern geladener Daten
- Loading-State
- Error-State
- Reset-Mechanismen
- klare, fachlich benannte Funktionen

### Beispiele für Store-Funktionen

#### Auth Store

- `login(payload)`
- `logout()`
- `fetchMe()`

#### Users Store

- `fetchUsers(params)`
- `fetchUser(id)`
- `createUser(payload)`
- `updateUser(id, payload)`
- `deactivateUser(id)`
- `reactivateUser(id)`

#### Tickets Store

- `fetchTickets(params)`
- `fetchTicket(id)`
- `createTicket(payload)`
- `assignTicket(id, payload)`
- `changeStatus(id, payload)`
- `resolveTicket(id)`
- `closeTicket(id)`

### Was Stores nicht sein sollen

Stores sollen nicht:

- die gesamte Bildschirmstruktur definieren
- komplexe Template-Logik enthalten
- Fachregeln erfinden, die nicht in den Dokumenten stehen
- als Ersatz für jede Formkomponente dienen

---

## Rolle von ofetch

ofetch ist die bevorzugte technische Basis für API-Anfragen.

### ofetch soll genutzt werden für:

- zentrale Konfiguration der Backend-Basis-URL
- zentrale Request-Optionen
- zentrale Header-Konfiguration
- zentrale Fehlerbehandlung auf niedriger Ebene
- saubere Nutzung in Stores

### Empfohlene Struktur

Eine zentrale Client-Datei ist sinnvoll, zum Beispiel:

- `src/services/api/client.ts`

Diese Datei kann enthalten:

- Base URL
- JSON-Defaults
- Auth Header, falls nötig
- Response / Error Interceptors, falls erforderlich

Die eigentlichen fachlichen Aufrufe sollen dann in den Stores erfolgen.

---

## Rolle von Vuelidate

Vuelidate wird für Formularvalidierung verwendet.

### Vuelidate soll genutzt werden für:

- Pflichtfelder
- formale Feldvalidierung
- einfache UI-nahe Eingabeprüfungen
- bestätigende Passwörter
- E-Mail-Formate
- minimale Längenregeln
- Anzeige von Feldfehlern

### Wichtige Grenze

Vuelidate ist **nicht** die einzige fachliche Wahrheit.

Backend-Validierung bleibt weiterhin notwendig, weil:

- API-Contracts verbindlich sind
- Business Rules nicht nur im Frontend gelten
- Server-seitige Validierung immer maßgeblich bleibt

Das Frontend nutzt Vuelidate für eine bessere Benutzererfahrung, nicht als Ersatz für Backend-Regeln.

---

## Bevorzugter Entwicklungsworkflow

Der bevorzugte Frontend-Workflow des Projekts ist:

### 1. Use Case lesen

Der konkrete fachliche Use Case wird zuerst verstanden.

### 2. API-Contract lesen

Dann wird der passende Backend-Contract gelesen.

### 3. Minimale Store-Funktionen planen

Nur die für diesen Screen nötigen Store-Funktionen werden vorbereitet.

### 4. Stitch-Workflow vorbereiten

Danach wird der relevante Projektkontext für Claude Code mit Stitch MCP und Google Labs stitch-skills vorbereitet.

Dazu gehören insbesondere:

- der fachliche Use Case
- der relevante API-Contract
- die nötigen UI-Ziele
- vorhandene UI-Regeln
- relevante Screen-Flows
- gegebenenfalls Design- oder Kontextinformationen für den Screen

### 5. Screen über Claude Code + Stitch MCP erzeugen

Die erste UI-Struktur des Screens wird im Projekt standardmäßig über Claude Code mit Stitch MCP und Google Labs stitch-skills erzeugt.

Dabei kann Claude Code auf vorbereiteten Projektkontext zugreifen, daraus ein konsistentes Design-System oder Screen-Ziel ableiten und anschließend die passende UI-Struktur mit Stitch erzeugen.

### 6. Vue-Logik ergänzen

Reactive State, Events, Props, lokale Logik werden eingebaut.

### 7. Vuelidate ergänzen

Formulare erhalten Validierungsregeln.

### 8. Store anbinden

Dann ruft die Page oder Komponente die Store-Funktionen auf.

### 9. UI-Zustände ergänzen

Loading, Error, Empty, Disabled und Success States werden ergänzt.

---

## Rolle von Stitch im Frontend

Stitch ist ein **optionales Hilfsmittel** innerhalb des Frontend-Workflows.

Stitch kann im Projekt genutzt werden, um aus vorbereitetem Projektkontext, UI-Regeln und klar beschriebenen Screen-Zielen ein konsistentes Design-System sowie daraus abgeleitete UI-Screens zu erzeugen.

Der vorbereitete Kontext kann zum Beispiel enthalten:

- Use Cases
- Screen Flows
- API-Contracts
- UI-Regeln
- bestehende Screens oder Referenzen
- Brand- oder Design-Hinweise
- projektspezifische Notizen zu Layout, Tonalität und Oberfläche

Ein möglicher Workflow mit Stitch ist:

1. fachlichen Screen-Zweck aus den Projektdokumenten bestimmen
2. relevanten Projektkontext vorbereiten
3. daraus ein konsistentes UI- oder Design-System ableiten
4. auf dieser Grundlage Screens oder Komponenten per Stitch erzeugen
5. den erzeugten Output in die Projektstruktur überführen
6. anschließend Vue-Logik, Pinia, ofetch und Vuelidate ergänzen

Stitch dient dabei als Werkzeug für:

- Design-System-Synthese
- Screen-Grundstruktur
- Layout-Vorschläge
- Formularlayout
- Tabellenlayout
- visuelle Komponentengerüste
- responsive UI-Grundstruktur

Stitch ist **nicht** die Quelle für:

- Fachregeln
- API-Logik
- RBAC-Logik
- Business Rules
- Backend-Validierung
- Statusregeln
- Merge-Regeln
- Soft-Delete-Regeln
- Inbound-Entscheidungslogik
- Contract-Regeln

Die mit Stitch erzeugten Screens sind daher **UI-Vorlagen**, nicht fachlich endgültige Implementierungen.

Nach der Screen-Erzeugung müssen die Ergebnisse in die Projektarchitektur überführt und fachlich angepasst werden, insbesondere durch:

- saubere Vue-Struktur
- Komponentenaufteilung
- Pinia-Store-Anbindung
- ofetch-Integration
- Vuelidate-Validierung
- Loading-, Error-, Empty-, Forbidden- und Conflict-States

### Wichtige Regel

Stitch erzeugt im Projekt Design-System und UI-Struktur auf Basis vorbereiteten Kontexts.  
Die eigentliche fachliche Wahrheit bleibt in Miniworld, Business Rules, Use Cases und API-Contracts.

### Praktische Projektregel

Stitch darf im Projekt helfen, schneller zu konsistenten und hochwertigen Screens zu kommen.

Stitch darf aber nicht dazu führen, dass:

- Screens fachlich frei interpretiert werden
- Rollen oder Rechte erfunden werden
- API-Verhalten angenommen wird, das nicht dokumentiert ist
- Validierungs- oder Zustandslogik ungeprüft aus UI-Vorschlägen übernommen wird

---

## UI-Zustände, die immer berücksichtigt werden müssen

Jeder relevante Screen soll nicht nur den Happy Path enthalten.

Mindestens diese UI-Zustände sind mitzudenken:

- Loading
- Empty
- Error
- Disabled
- Success
- Not Found, wenn relevant
- Forbidden, wenn relevant

Diese Zustände müssen aus den API-Contracts und Screen-Flows abgeleitet werden.

---

## Verbindung zwischen Frontend und API-Contracts

Das Frontend darf keine Endpunkte erfinden.

Jeder wichtige Backend-Aufruf im Frontend soll sich auf einen dokumentierten API-Contract stützen.

Die Verbindung ist:

- Use Case beschreibt den fachlichen Ablauf
- API-Contract beschreibt Request und Response
- Frontend setzt daraus Screen-Verhalten um
- Store ruft den passenden Endpoint auf

---

## Verbindung zwischen Frontend und Use Cases

Jeder wichtige Screen soll in irgendeiner Form einem Use Case zugeordnet sein.

Beispiele:

- Login Screen → Use Case 25
- Profile Page → Use Case 27 und 28
- User Create Page → Use Case 29
- Ticket Detail Screen → Use Case 5 bis 10
- Customer Detail Screen → Use Case 11 bis 18

Dadurch wird verhindert, dass das Frontend aus losen Bildschirmen ohne fachliche Grundlage besteht.

---

## Verbindung zu Agentic AI

Ein Agentic-AI-Modell soll aus dieser Datei verstehen:

- wie das Frontend grundsätzlich aufgebaut ist
- dass Vue.js + Tailwind CSS verwendet wird
- dass Pinia, ofetch und Vuelidate verbindlich vorgesehen sind
- dass Store-Funktionen pro Use Case minimal und gezielt geplant werden
- dass Claude Code mit Stitch MCP und Google Labs stitch-skills der verbindliche Workflow für die UI-Erzeugung im Projekt ist
- dass Stitch und Claude Code Design-System und UI-Struktur erzeugen, aber nicht die fachliche Wahrheit liefern
- dass Screens aus Use Cases, API-Contracts, Screen-Flows und UI-Regeln abgeleitet werden
- dass das Frontend keine Fachlogik erfinden darf

---

## Wichtigste Architekturregel

Die wichtigste Regel dieser Frontend-Architektur lautet:

**Use Case → API-Contract → minimale Store-Funktionen → vorbereiteter Stitch-Kontext → Claude Code + Stitch MCP + stitch-skills → UI-Screen → Vue-Logik → Vuelidate → Store-Aufruf**

Diese Reihenfolge ist die bevorzugte und verbindliche Arbeitsweise des Projekts.

---

## Zusammenfassung

Das Frontend des Smart Support Desk Systems soll:

- fachlich aus den Dokumenten abgeleitet werden
- technisch mit Vue.js + Tailwind CSS umgesetzt werden
- API-nahe Logik in Pinia Stores kapseln
- ofetch für HTTP verwenden
- Vuelidate für Formularvalidierung verwenden
- UI-Struktur standardmäßig über Claude Code mit Stitch MCP und Google Labs stitch-skills erzeugen
- Screens aus klaren Use Cases, API-Contracts, Screen-Flows und UI-Regeln ableiten
- Happy Path, Error, Empty, Forbidden und Conflict sauber berücksichtigen
- langfristig wartbar, testbar und agentenfreundlich bleiben