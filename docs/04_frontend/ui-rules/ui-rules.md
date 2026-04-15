# UI Rules: Smart Support Desk System

## Zweck

Diese Datei definiert die UI-Regeln des Smart Support Desk Systems.

Sie beschreibt, wie Screens, Komponenten und wiederverwendbare UI-Bausteine aussehen und sich verhalten sollen, damit das Frontend konsistent, verständlich und langfristig wartbar bleibt.

Diese Datei beschreibt bewusst visuelle und strukturelle UI-Regeln. Sie ersetzt nicht die Fachlogik, Use Cases oder API-Contracts.

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

Die UI-Regeln dürfen den fachlichen Regeln dieser Dokumente nicht widersprechen.

---

## Ziel der UI-Regeln

Die UI-Regeln sollen sicherstellen, dass:

- Screens konsistent aufgebaut werden
- Komponenten wiederverwendbar bleiben
- Formulare verständlich und ruhig wirken
- Tabellen und Listen einheitlich aussehen
- Fehlermeldungen und Status klar dargestellt werden
- Loading-, Empty- und Error-Zustände nicht vergessen werden
- rollenabhängige Bereiche nachvollziehbar sichtbar sind
- Contract-, Inbound- und Medienbereiche visuell konsistent behandelt werden
- Claude Code mit Stitch MCP und Google Labs stitch-skills aus dieser Datei klare UI-Vorgaben ableiten kann

---

## Grundprinzipien der UI

### 1. Ruhige Admin-, Support- und Prüfoberfläche

Das System ist kein Marketing-Frontend, sondern ein Arbeitsfrontend für Support, Verwaltung und Prüfprozesse.

Die Oberfläche soll deshalb:

- ruhig
- klar
- strukturiert
- gut lesbar
- funktional
- nicht überdekoriert

sein.

### 2. Lesbarkeit vor Effekten

Priorität haben:

- klare Hierarchie
- gute Abstände
- erkennbare Interaktionsflächen
- verständliche Texte
- saubere Gruppierung

Nicht Priorität haben:

- übermäßige Animation
- dekorative Effekte
- unnötige visuelle Komplexität
- künstlich wirkende „AI“-Gestaltung

### 3. Konsistenz vor Einzellösung

Neue Screens sollen sich an bestehende UI-Muster halten.

Das Ziel ist nicht, jeden Screen neu zu erfinden, sondern bekannte Muster sauber wiederzuverwenden.

### 4. Nicht nur Happy Path darstellen

Jeder wichtige Screen muss neben dem Normalzustand auch folgende UI-Zustände unterstützen:

- loading
- empty
- error
- disabled
- success
- conflict
- forbidden
- not found

### 5. Fachliche Bereiche visuell sauber trennen

Das UI soll deutlich machen, wenn Inhalte unterschiedlichen fachlichen Charakter haben.

Besonders wichtig sind klare Unterschiede zwischen:

- Customer-Stammdaten
- Contacts
- Contracts
- Ticket-Metadaten
- öffentlicher Kommunikation
- interner Kommunikation
- Inbound-Prüffällen
- administrativen Bereichen

### 6. Tooling dient der UI-Erzeugung, nicht der UI-Wahrheit

Claude Code mit Stitch MCP und Google Labs stitch-skills darf aus diesen Regeln Design-Systeme und Screen-Strukturen ableiten.

Die von dort erzeugten Screens müssen sich aber weiterhin an diese UI-Regeln halten und dürfen keine widersprüchlichen visuellen Muster einführen.

---

## Layout-Regeln

### Seitenaufbau

Ein vollständiger Page-Screen soll möglichst aus diesen Bereichen bestehen:

- Seitenkopf
- Hauptinhalt
- optional Filter- oder Toolbar-Bereich
- Inhaltsbereich
- optionale Sekundärbereiche wie Seitenpanel, Kontextpanel oder Dialoge

### Seitenkopf

Der Seitenkopf enthält typischerweise:

- Titel
- optional Untertitel
- optional kontextbezogene Badges
- optional primäre Aktionen
- optional sekundäre Aktionen

### Inhaltsbreite

Seiten sollen nicht unnötig breit oder chaotisch wirken.

Bereiche sollen so strukturiert sein, dass Inhalte lesbar bleiben und nicht ohne Führung über die gesamte Breite laufen.

### Detailseiten

Detailseiten wie:

- Ticket-Detail
- Customer-Detail
- Contract-Detail
- Inbound-Review-Detail

sollen klar in fachliche Sektionen aufgeteilt sein.

Typische Gliederung:

- Kopfbereich
- Metadatenblock
- Hauptinhaltsbereich
- zugehörige Listen oder Dokumente
- Aktionsbereiche

### Responsive Verhalten

Die UI muss responsiv sein.

Typische Regeln:

- Desktop: mehrspaltige oder größere Layouts möglich
- Tablet: reduzierte Spalten, klarere vertikale Gruppierung
- Mobile: einspaltig oder stark vereinfachte Reihenfolge

---

## Abstandsregeln

Die Oberfläche soll klare und wiederkehrende Abstände nutzen.

### Regeln

- zwischen Sektionen sichtbar Luft lassen
- Formfelder nicht zu eng setzen
- Gruppen klar voneinander trennen
- Aktionen nicht direkt ohne Abstand an Inhalte kleben
- Tabellen, Karten und Panels mit ruhigen Innenabständen gestalten

### Ziel

Die Oberfläche soll nicht dicht und gedrängt wirken.

---

## Typografie-Regeln

### Titel

Seiten-Titel sollen klar erkennbar sein und den Screenzweck sofort sichtbar machen.

### Untertitel

Untertitel sind optional und helfen bei Kontext, wenn der Screen komplexer ist.

### Labels

Form-Labels sollen klar, eindeutig und nahe am Feld stehen.

### Fließtext

Hilfetexte, Fehlertexte und sekundäre Texte sollen ruhig und gut lesbar sein.

### Tabellen- und Listeninhalte

Tabellen sollen nicht mit unnötig kleinen oder überladenen Texten arbeiten.

### Bereichsüberschriften

Sektionen wie:

- Contacts
- Contracts
- Nachrichten
- Dokumente
- Prüfhinweise

sollen klar als Teilbereiche erkennbar sein.

---

## Farb- und Statusregeln

Farben sollen funktional eingesetzt werden.

### Typische Funktionsfarben

- Standard / neutral
- Erfolg
- Warnung
- Fehler
- Info

### Statusdarstellung

Statusfarben sollen konsistent genutzt werden.

Beispiele:

- aktiv
- deaktiviert
- verifiziert
- nicht verifiziert
- open
- in_progress
- waiting_for_customer
- resolved
- closed
- active
- inactive
- pending
- entschieden

### Wichtig

Farbe allein reicht nicht.

Status soll möglichst zusätzlich über:

- Text
- Badge
- Icon
- klare Beschriftung

kenntlich sein.

---

## Rollen- und Berechtigungsdarstellung

Das System verwendet interne Rollen.

### Rollen sollen im UI sichtbar darstellbar sein

Beispiele:

- Support Agent
- Inbound Reviewer
- Contract Manager
- Admin

### Regeln für Rollendarstellung

- Rollen können als Badge, kompakte Liste oder Textgruppe dargestellt werden
- mehrere Rollen eines internen Benutzers sollen lesbar und nicht chaotisch erscheinen
- Rollendarstellung dient der Orientierung, nicht der alleinigen Sicherheitsentscheidung

### Forbidden-Zustand

Wenn ein Benutzer einen Bereich nicht betreten darf:

- klaren Zugriff-verweigert-Zustand anzeigen
- nicht einfach stillschweigend leere Inhalte rendern

---

## Button-Regeln

### Hauptarten von Buttons

Das System soll typischerweise diese Button-Arten verwenden:

- Primärbutton
- Sekundärbutton
- Tertiär- oder Ghost-Button
- Destruktiver Button

### Primärbutton

Für die wichtigste Aktion des Screens.

Beispiele:

- Speichern
- Login
- internen Benutzer anlegen
- Customer anlegen
- Contract anlegen
- Nachricht senden
- Prüffall entscheiden

### Sekundärbutton

Für unterstützende, aber sichtbare Aktionen.

Beispiele:

- Abbrechen
- Zurück
- Filter zurücksetzen
- später entscheiden
- Bearbeiten

### Destruktiver Button

Nur für fachlich riskante oder irreversible Vorgänge.

Beispiele:

- Deaktivieren
- Soft Delete
- Merge bestätigen, wenn visuell getrennt notwendig
- Contract deaktivieren
- internen Benutzer soft deleten

### Disabled State

Buttons müssen einen klar erkennbaren Disabled State haben.

---

## Formularregeln

### Aufbau

Formulare sollen klar gegliedert sein.

Mögliche Struktur:

- allgemeine Stammdaten
- Rollen und Aktivstatus
- Contact-Daten
- Contract-Daten
- Dokumente oder Upload-Bereich
- Aktionen

### Felder

Jedes Feld soll nach Möglichkeit enthalten:

- Label
- Input / Select / Textarea / Upload
- optional Hilfetext
- Platz für Fehlermeldung

### Pflichtfelder

Pflichtcharakter soll klar sichtbar sein, aber nicht überladen.

### Fehlerdarstellung

Fehler gehören nah an das Feld.

Bei Feldfehlern soll der Benutzer direkt erkennen können:

- welches Feld betroffen ist
- was falsch ist

### Globale Fehlermeldung

Zusätzlich zu Feldfehlern kann es globale Fehlermeldungen geben.

Beispiele:

- Login fehlgeschlagen
- Konflikt mit bestehender E-Mail
- `customer_number` bereits vergeben
- Contact-Wert bereits vergeben
- Contract-Kontext nicht zulässig
- fachlicher Konflikt beim Merge
- Inbound-Prüffall konnte nicht entschieden werden

### Submit-Bereich

Buttons zum Speichern, Abbrechen oder Zurück sollen klar und konsistent angeordnet sein.

---

## Tabellenregeln

### Wann Tabellen sinnvoll sind

Tabellen sind sinnvoll für:

- Internal-Users-Liste
- Ticket-Liste
- Kategorien-Liste
- Inbound-Review-Liste
- Contact-Liste bei vielen Einträgen
- Contract-Liste

### Typische Bestandteile

- Tabellenkopf
- klare Spalten
- Aktionsspalte
- Pagination, wenn nötig
- Empty State

### Tabellenaktionen

Aktionen sollen nicht chaotisch sein.

Typische Aktionen:

- Anzeigen
- Bearbeiten
- Deaktivieren
- Reaktivieren
- Soft Delete
- primär setzen
- verifizieren
- Contract öffnen
- Prüffall öffnen

### Mobile Verhalten

Tabellen müssen auf kleineren Screens sinnvoll reagieren.

Wenn eine klassische Tabelle zu eng wird, soll entweder:

- horizontales Scrollen kontrolliert möglich sein
- oder eine alternative Karten- oder Listenansicht verwendet werden

---

## Listen- und Kartenregeln

Nicht jeder Bereich braucht eine Tabelle.

Für bestimmte Inhalte sind Karten oder Listen besser, zum Beispiel:

- Contact-Blöcke
- Customer-Detailsektionen
- Contract-Zusammenfassungen
- Nachrichtenverlauf
- Statusinformationen
- Inbound-Prüffall-Details
- Dokumentlisten

Karten sollen:

- klare Sektionen haben
- nicht überladen sein
- ruhig gegliedert sein
- Aktionen sauber integrieren

---

## Dialog- und Modal-Regeln

Dialoge sollen für fokussierte Aktionen genutzt werden.

Beispiele:

- Bestätigung von Deaktivierung
- Soft Delete bestätigen
- Merge bestätigen
- Contact anlegen im Kontext
- Contract deaktivieren
- internen Benutzer deaktivieren
- Inbound-Prüffall-Entscheidung bestätigen

### Dialoge sollen enthalten

- klaren Titel
- kurze Erklärung
- klare Hauptaktion
- klare Abbrechen-Aktion

### Destruktive Aktionen

Destruktive Dialoge sollen optisch erkennbar sein, aber nicht unnötig dramatisch.

---

## Badge- und Statusregeln

Badges sind sinnvoll für kompakte Statusinformationen.

Beispiele:

- active / inactive
- verified / not verified
- open / in_progress / waiting_for_customer / resolved / closed
- pending / decided
- Support Agent / Inbound Reviewer / Contract Manager / Admin

Badges sollen:

- gut lesbar
- klein, aber klar
- konsistent gefärbt
- textlich eindeutig

sein.

---

## Loading-Regeln

Loading-Zustände sollen immer geplant werden.

### Typische Formen

- Spinner
- Skeleton
- disabled Buttons
- ausgegraute Bereiche

### Regel

Nicht einfach „nichts anzeigen“, wenn Daten laden.

Der Benutzer soll erkennen:

- dass etwas passiert
- welcher Bereich lädt

---

## Empty-State-Regeln

Wenn keine Daten vorhanden sind, soll der Screen nicht leer wirken.

Ein guter Empty State enthält:

- klare Aussage
- kurze Erklärung
- wenn sinnvoll eine passende Primäraktion

Beispiele:

- keine internen Benutzer vorhanden
- keine Tickets gefunden
- keine Contacts vorhanden
- keine Contracts vorhanden
- keine Inbound-Prüffälle vorhanden
- keine Dokumente vorhanden

---

## Error-State-Regeln

Fehler sollen verständlich und möglichst nah am betroffenen Bereich dargestellt werden.

### Arten von Fehlern

- Feldfehler
- globale Screen-Fehler
- Konfliktfehler
- Berechtigungsfehler
- Not Found
- Ladefehler
- Upload-Fehler

### Regel

Fehlermeldungen sollen:

- nicht technisch roh wirken
- nicht zu allgemein sein
- nach Möglichkeit verständlich und nutzerorientiert formuliert sein

---

## Conflict-State-Regeln

Conflict-Zustände sind in diesem Projekt wichtig.

Beispiele:

- E-Mail schon vergeben
- Benutzername schon vergeben
- `customer_number` schon vergeben
- Contact-Wert schon aktiv vergeben
- Contract-Kennung schon vergeben
- Statusübergang nicht erlaubt
- letzter Contact darf nicht entfernt werden
- Merge-Konflikt
- Contract gehört nicht zum Customer des Tickets
- Inbound-Prüffall kann so nicht entschieden werden

Diese Zustände sollen im UI klar von einfachen Feldfehlern unterschieden werden.

---

## Forbidden- und Not-Found-Regeln

### Forbidden

Wenn ein Benutzer einen Bereich nicht betreten darf:

- klaren Zugriff-verweigert-Zustand anzeigen
- nicht stillschweigend leere Seite zeigen

### Not Found

Wenn ein Objekt nicht existiert:

- klaren „nicht gefunden“-Zustand anzeigen
- Navigation zurück oder zur Liste anbieten

---

## Nachrichtendarstellung

Der Nachrichtenbereich im Ticket ist besonders wichtig.

### Öffentliche Nachrichten

Sollen klar von internen Nachrichten unterschieden werden.

### Interne Nachrichten

Sollen sichtbar intern markiert sein.

### Typische Bestandteile

- Autor
- Typ
- Zeit
- Inhalt
- Anhänge

### Ziel

Der Verlauf soll lesbar und nachvollziehbar sein.

---

## Regeln für Contract-Bereiche

Contracts sind ein eigener fachlicher Kontext und sollen im UI nicht wie bloße Notizfelder wirken.

### Contract-Darstellung soll typischerweise enthalten

- Contract-Nummer
- Name
- Typ
- Status
- Laufzeit
- Customer-Bezug
- Dokumente
- Aktionen

### Ticket-Contract-Kontext

Wenn ein Ticket einen Contract hat, soll dieser im Ticket sichtbar und klar abgegrenzt angezeigt werden.

Wenn kein Contract zugeordnet ist, soll das ebenfalls klar sichtbar sein.

---

## Regeln für Inbound-Review-Bereiche

Inbound-Prüffälle sind fachlich ein Prüf- und Triage-Bereich.

Die UI soll deshalb ruhiger, strukturierter und entscheidungsorientiert aufgebaut sein.

### Inbound-Review-Screens sollen typischerweise enthalten

- Eingangsmetadaten
- erkannte Hinweise
- Nachrichtentext
- Anhänge
- mögliche Zuordnungsaktionen
- Bereich für Entscheidungshinweise

### Ziel

Benutzer sollen unklare Eingänge effizient und nachvollziehbar prüfen können, ohne zwischen zu vielen ungeordneten UI-Elementen wechseln zu müssen.

---

## Regeln für Medien- und Upload-Bereiche

Dateiuploads und Dokumentlisten sind fachlich relevant.

### Upload-Bereiche sollen enthalten

- klaren Upload-Auslöser
- zulässige Dateitypen oder Hilfetexte, wenn sinnvoll
- Platz für Upload-Fehler
- sichtbaren Uploading-State
- nach Upload sichtbare Zuordnung zum Fachobjekt

### Dokumentlisten sollen enthalten

- Dateiname
- optional Typ oder Collection
- ggf. Upload-Zeitpunkt oder Uploader
- Download- oder Anzeigeaktion, wenn vorgesehen

---

## UI-Regeln für wiederverwendbare Komponenten

Wiederverwendbare Komponenten sollen:

- klar benannt sein
- nur eine erkennbare Verantwortung haben
- nicht unnötig viele Sonderfälle verstecken
- über Props steuerbar sein
- sich an bestehende Muster halten

Besonders sinnvolle Shared-Komponenten im Projekt sind:

- PageHeader
- StatusBadge
- RoleBadge
- EmptyState
- ErrorState
- ConfirmDialog
- FormFieldWrapper
- MediaUploadField
- DataTable
- SectionCard

---

## Ziel für Claude Code + Stitch MCP

UI-Regeln sollen auch bei der agentischen UI-Erzeugung helfen.

Claude Code mit Stitch MCP und Google Labs stitch-skills soll aus dieser Datei verstehen:

- wie ruhig und funktional die UI sein soll
- welche Zustände immer sichtbar geplant werden müssen
- wie Formulare, Tabellen und Dialoge aussehen sollen
- wie Rollen, Contracts, Inbound-Prüffälle und Medien visuell eingeordnet werden
- dass Konsistenz wichtiger ist als Einzelfall-Design

---

## Zusammenfassung

Die UI des Smart Support Desk Systems soll:

- ruhig und funktional sein
- konsistent über Screens hinweg wirken
- Support-, Prüf- und Verwaltungsarbeit gut unterstützen
- Formulare, Tabellen und Dialoge klar strukturieren
- Status deutlich zeigen
- Loading-, Empty-, Error- und Conflict-Zustände sauber abbilden
- RBAC-Sichtbarkeit verständlich machen
- Contracts, Inbound-Prüffälle und Medien als echte fachliche Bereiche behandeln
- gut mit Vue.js, Tailwind CSS und dem verbindlichen Claude-Code-plus-Stitch-Workflow zusammenarbeiten

---

## Wichtigste UI-Regel

Die wichtigste Regel lautet:

**Die UI soll klar, ruhig, konsistent und zustandsorientiert sein und darf keine Fachlogik aus Designentscheidungen heraus erfinden.**