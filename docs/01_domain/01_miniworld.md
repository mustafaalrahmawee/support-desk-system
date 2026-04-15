# Miniworld: Smart Support Desk System

## 1. Einführung

Das Smart Support Desk System ist eine Anwendung zur Verwaltung und Bearbeitung von Kundenanfragen über verschiedene Kommunikationskanäle.

Das System unterstützt die strukturierte Erfassung, Bearbeitung und Nachverfolgung von Supportfällen. Es ermöglicht die Kommunikation zwischen externen Kunden und internen Mitarbeitern sowie die nachvollziehbare Dokumentation aller fachlich relevanten Vorgänge.

Zentrale fachliche Bestandteile des Systems sind Customers, Contacts, Contracts, Tickets, Nachrichten, Kategorien, Actors, Inbound-Prüffälle, Medien, Audit-Logs und authentifizierte Benutzerkonten.

Das System ist so konzipiert, dass historische Informationen erhalten bleiben und fachliche Vorgänge auch zu einem späteren Zeitpunkt nachvollzogen werden können. Aus diesem Grund verwendet das System grundsätzlich Soft Delete für fachliche Hauptobjekte.

Das System ist fachlich für ein B2B-SaaS- oder IT-Service-Unternehmen gedacht, das digitale Produkte oder Leistungen für Geschäftskunden anbietet und eingehende Supportanfragen strukturiert bearbeitet.

---

## 2. Akteure

### Customer

Customer ist ein externer Kommunikationspartner des Systems. Customer sendet Anfragen über unterstützte Kommunikationskanäle an das System und kommuniziert im Rahmen von Supportfällen mit dem Support. Customer hat keinen direkten Zugriff auf das interne System.

### Interner Benutzer

Interne Benutzer sind Mitarbeiter des Unternehmens, die das System zur Bearbeitung und Verwaltung von Supportvorgängen verwenden.

Interne Benutzer melden sich mit einem Benutzerkonto am System an und führen je nach Rolle unterschiedliche fachliche Aufgaben aus.

Das System kennt mehrere interne Rollen mit unterschiedlichen Verantwortlichkeiten, zum Beispiel operative Support-Bearbeitung, Prüfung unklarer Eingänge, Verwaltung von Vertragskontexten oder administrative Systemverwaltung.

### Actor

Actor ist ein abstrakter Kommunikationsakteur innerhalb des Systems. Actor dient dazu, Kommunikation einheitlich zu modellieren. Ein Actor repräsentiert fachlich entweder einen Customer oder einen internen Benutzer.

---

## 3. Geschäftsobjekte

### Customer

Customer repräsentiert die fachliche Identität eines externen Kommunikationspartners.

Ein Customer kann mehrere Kommunikationswege besitzen und mehrere Supportfälle betreffen.

Ein Customer besitzt eine fachliche Kundennummer (`customer_number`). Diese Kundennummer dient der eindeutigen fachlichen Identifikation des Customers innerhalb des Systems.

Ein Customer kann keinen, einen oder mehrere Contracts besitzen.

### Contact

Ein Contact repräsentiert einen konkreten Kommunikationsweg eines Customers, zum Beispiel eine E-Mail-Adresse, Telefonnummer, einen WhatsApp-Kontakt, einen Web-Kontakt oder einen anderen externen Kanal.

Contacts dienen der Zuordnung eingehender Kommunikation und der Zustellung von Antworten.

### Contract

Ein Contract repräsentiert einen fachlichen Vertrags-, Produkt- oder Leistungskontext eines Customers.

Ein Contract gehört genau zu einem Customer.

Ein Customer kann keinen, einen oder mehrere Contracts besitzen.

Ein Contract dient dazu, geschäftliche Zusammenhänge eines Customers fachlich abzubilden, zum Beispiel einen Vertrag, ein Produktpaket oder ein Leistungspaket.

Ein Contract kann Statusinformationen, Gültigkeitszeiträume und eine fachliche Vertrags- oder Leistungskontextkennung besitzen.

Ein Contract kann zusätzlich fachliche Dokumente oder andere zugehörige Medien besitzen, zum Beispiel Vertragsdokumente, Angebotsunterlagen, Leistungsbeschreibungen oder PDF-Dateien.

### Ticket

Ein Ticket repräsentiert einen fachlichen Supportfall.

Ein Ticket entsteht aus einer Kundenanfrage oder aus einer fortgeführten Kommunikation und bildet den zentralen Bearbeitungsfall im System.

Ein Ticket gehört immer genau zu einem Customer.

Ein Ticket kann zusätzlich einem Contract des zugehörigen Customers zugeordnet sein, wenn der Supportfall einen konkreten Vertrags-, Produkt- oder Leistungskontext hat. Diese Zuordnung ist fachlich optional.

### Nachricht

Eine Nachricht ist ein einzelner Kommunikationseintrag innerhalb eines Tickets.

Nachrichten bilden die eigentliche Kommunikationshistorie zwischen Customer und Support sowie interne Notizen innerhalb der Ticketbearbeitung ab.

Eine Nachricht kann neben Text auch fachlich relevante Anhänge enthalten, zum Beispiel Screenshots, Fotos, PDF-Dokumente oder andere Dateien, die zur Beschreibung, Prüfung oder Nachvollziehbarkeit eines Supportfalls dienen.

### Kategorie

Eine Kategorie dient der fachlichen Einordnung eines Tickets. Sie unterstützt die Strukturierung und spätere Auswertung von Supportfällen.

### Actor

Ein Actor repräsentiert den fachlichen Autor einer Nachricht. Dadurch kann Kommunikation unabhängig davon modelliert werden, ob sie von einem Customer oder von einem internen Benutzer stammt.

### Inbound-Prüffall

Ein Inbound-Prüffall repräsentiert eine eingehende Kundenanfrage, die nicht sicher automatisch einem bestehenden Customer, Contact oder fachlichen Kontext zugeordnet werden kann.

Ein Inbound-Prüffall dient dazu, unklare Eingänge kontrolliert zu erhalten, sichtbar zu machen und durch interne Benutzer fachlich prüfen zu lassen.

### Medium / Datei

Ein Medium repräsentiert einen Dateianhang oder eine andere gespeicherte Datei, die einem fachlichen Objekt zugeordnet werden kann.

Medien können zum Beispiel an Nachrichten, Tickets oder Contracts angehängt werden, sofern dies fachlich vorgesehen ist.

Zu den möglichen Medien gehören insbesondere Screenshots, Fotos, PDF-Dokumente und andere fachlich relevante Dateien.

### Audit-Log

Ein Audit-Log dokumentiert fachlich relevante Änderungen im System. Audit-Logs dienen der Nachvollziehbarkeit und der historischen Rekonstruktion wichtiger Vorgänge.

### Benutzerkonto

Ein Benutzerkonto repräsentiert einen internen Systemzugang. Über das Benutzerkonto erfolgen Anmeldung, Abmeldung, Authentifizierung und die Ausführung interner fachlicher Funktionen entsprechend der zugeordneten Rolle oder Berechtigung.

---

## 4. Geschäftsprozesse

### Eingang einer Kundenanfrage

Eine Kundenanfrage kann über verschiedene unterstützte Kommunikationskanäle in das System gelangen, zum Beispiel per E-Mail, Telefon, Web, WhatsApp oder andere externe Kanäle.

Das System verarbeitet den Eingang fachlich als neue oder fortgeführte Kommunikation.

Eine eingehende Kundenanfrage kann neben Text auch Anhänge oder andere Medien enthalten, zum Beispiel Screenshots, Fotos oder PDF-Dokumente. Solche Anhänge werden im System gespeichert und der zugehörigen Kommunikation oder dem betroffenen Fachobjekt fachlich zugeordnet.

### Zuordnung oder Erstellung eines Customers

Beim Eingang einer neuen Kommunikation prüft das System, ob der zugrunde liegende Contact bereits einem bestehenden Customer zugeordnet werden kann.

Wenn die eingehende Nachricht zusätzlich eine `customer_number` enthält, darf das System diese als vorrangigen Identifikationshinweis prüfen.

Kann über die `customer_number` genau ein aktiver Customer sicher gefunden werden, wird dieser Customer verwendet.

Danach prüft das System weiterhin den erkannten Contact fachlich. Es prüft, ob der Contact bereits zu diesem Customer gehört, ob es sich um einen neuen zusätzlichen Contact dieses Customers handelt oder ob ein fachlicher Konflikt vorliegt.

Wenn keine `customer_number` vorhanden ist, bleibt die Contact-basierte Zuordnung bestehen.

Wenn `customer_number` und Contact widersprüchlich sind, darf das System keine blinde automatische Zuordnung erzwingen. Der Vorgang muss in einen fachlich kontrollierten Prüfprozess oder Konfliktfall überführt werden.

Ist keine sichere Customer-Zuordnung möglich, kann das System einen neuen Customer anlegen und den Contact diesem Customer zuordnen, sofern die verfügbaren Informationen für eine fachlich vertretbare Neuanlage ausreichen.

### Umgang mit unklaren eingehenden Anfragen

Nicht jede eingehende Nachricht enthält eine `customer_number` oder einen sicher erkennbaren Contact.

Wenn eine eingehende Nachricht weder sicher über `customer_number` noch über einen eindeutig erkennbaren Contact einem Customer zugeordnet werden kann, darf das System die Nachricht nicht ignorieren und nicht blind automatisch einem bestehenden Customer zuordnen.

In diesem Fall überführt das System den Eingang in einen kontrollierten Prüfprozess.

Der Eingang wird als Inbound-Prüffall erhalten und für interne Benutzer sichtbar gemacht.

Ein interner Benutzer mit passender fachlicher Zuständigkeit kann den Fall anschließend prüfen und entscheiden, ob die Nachricht einem bestehenden Customer zugeordnet wird, ob ein neuer Customer angelegt wird oder ob weitere Klärung erforderlich ist.

### Zuordnung oder Erkennung eines Contracts

Sobald der Customer fachlich sicher bestimmt ist, kann das System zusätzlich prüfen, ob die eingehende Kommunikation einem bestehenden Contract dieses Customers zugeordnet werden kann.

Dies ist zum Beispiel möglich, wenn eine `contract_number`, ein eindeutiger Produktbezug, ein Leistungspaket oder ein anderer fachlich klarer Kontext in der Kommunikation enthalten ist.

Kann ein Contract sicher erkannt werden, darf das System den Supportfall diesem Contract-Kontext zuordnen.

Kann kein Contract sicher erkannt werden, bleibt die Ticket-Erstellung zunächst ohne Contract-Zuordnung möglich. Die fachliche Contract-Zuordnung kann später durch einen internen Benutzer ergänzt werden.

### Erstellung oder Fortführung eines Tickets

Nach der Customer-Zuordnung prüft das System, ob die eingehende Kommunikation zu einem bestehenden offenen Ticket gehört oder ob ein neuer Supportfall entsteht.

Im ersten Fall wird ein bestehendes Ticket fortgeführt.

Andernfalls wird ein neues Ticket angelegt.

Ein neues oder fortgeführtes Ticket kann zusätzlich einen Contract-Kontext besitzen, wenn dieser fachlich bekannt und sicher zuordenbar ist.

### Kommunikation im Ticket

Innerhalb eines Tickets werden Nachrichten zwischen Customer und Support ausgetauscht.

Öffentliche Nachrichten dienen der Kommunikation mit dem Customer.

Interne Nachrichten dienen der internen Bearbeitung und Abstimmung.

### Bearbeitung eines Tickets

Ein authentifizierter interner Benutzer kann ein Ticket sichten, bearbeiten, fachlich einordnen, den Bearbeitungsstatus verändern und bei Bedarf einen fachlich passenden Contract-Kontext setzen oder ändern.

Je nach Rolle können zusätzlich weitere Funktionen wie Zuweisung, Prüfung unklarer Eingänge, Vertragsverwaltung oder administrative Verwaltung möglich sein.

Die Bearbeitung erfolgt entlang des fachlichen Ticket-Lebenszyklus.

### Warten auf Rückmeldung des Customers

Benötigt der Support zusätzliche Informationen, kann die Bearbeitung eines Tickets vorübergehend pausiert werden, bis der Customer eine relevante Rückmeldung gibt. Danach wird die Bearbeitung fortgesetzt.

### Abschluss eines Supportfalls

Wurde ein Supportfall fachlich gelöst, wird das Ticket in einen abgeschlossenen Zustand überführt. Die historischen Informationen des Tickets und seiner Kommunikation bleiben weiterhin erhalten.

### Contracts verwalten

Authentifizierte interne Benutzer mit entsprechender fachlicher Zuständigkeit können zu einem bestehenden Customer Contracts anlegen, anzeigen, ändern und fachlich verwalten.

Contracts bilden den geschäftlichen Kontext des Customers ab und können für Supportfälle, Zuordnungen und spätere Auswertungen relevant sein.

### Dokumente zu Contracts speichern

Für einen Contract können fachlich relevante Dokumente oder andere Dateien im System gespeichert werden. Dazu gehören zum Beispiel Vertragsdokumente, Angebotsunterlagen oder Leistungsbeschreibungen.

Die Dokumente bleiben dem zugehörigen Contract historisch zugeordnet.

### Benutzeranmeldung und interne Rollensteuerung

Interne Benutzer melden sich mit einem Benutzerkonto am System an.

Nach erfolgreicher Authentifizierung können sie je nach Rolle und Berechtigung fachliche Funktionen ausführen.

Das System unterscheidet dabei fachlich zwischen verschiedenen internen Zuständigkeiten, etwa operativer Supportbearbeitung, Prüfung unklarer Eingänge, Vertragsverwaltung und administrativer Verwaltung.

### Nachvollziehbarkeit und Historie

Fachlich relevante Änderungen und Kommunikationsvorgänge werden im System nachvollziehbar dokumentiert.

Historische Daten bleiben erhalten, damit Supportfälle, Contract-Bezüge, Dokumente und Systemaktionen später rekonstruiert und ausgewertet werden können.

---

## 5. Fachliche Grundprinzipien

### Identität und Kommunikation

Ein Customer repräsentiert die fachliche Identität eines externen Kommunikationspartners.

Ein Contact repräsentiert dagegen nur einen konkreten Kommunikationsweg.

Mehrere Contacts können zu demselben Customer gehören.

Die `customer_number` ist eine eindeutige fachliche Kennung des Customers und stärkt die Identität eines Customers zusätzlich zur Contact-basierten Kommunikation.

### Omnichannel-Unterstützung

Das System ist darauf ausgelegt, Kundenanfragen über verschiedene Kommunikationskanäle einheitlich zu verarbeiten.

Unterschiedliche Eingangskanäle ändern nicht die fachliche Identität des Customers, sondern nur den jeweiligen Kommunikationsweg.

### Trennung von Fall, Kommunikation und Geschäftskontext

Ein Ticket repräsentiert den fachlichen Supportfall.

Die eigentliche Kommunikationshistorie wird über die Nachrichten innerhalb des Tickets abgebildet.

Ein Contract repräsentiert dagegen den geschäftlichen Kontext eines Customers, zum Beispiel einen Vertrag, ein Produktpaket oder ein Leistungspaket.

Ein Ticket kann optional einem Contract zugeordnet sein, wenn der Supportfall fachlich einen solchen konkreten Kontext besitzt.

### Sichere Zuordnung vor automatischer Entscheidung

Das System darf eingehende Kommunikation nicht blind automatisch zuordnen, wenn die fachliche Identität oder der fachliche Kontext nicht sicher bestimmt werden kann.

Dies gilt sowohl für die Customer-Zuordnung als auch für die Contract-Zuordnung.

Widersprüche zwischen `customer_number`, Contact und möglichem Contract-Kontext müssen kontrolliert behandelt werden.

### Kontrollierte Behandlung unklarer Eingänge

Unklare eingehende Nachrichten dürfen nicht verworfen werden.

Wenn eine sichere automatische Customer-Zuordnung nicht möglich ist, muss das System den Eingang in einen kontrollierten Prüfprozess überführen.

Die fachliche Entscheidung über die weitere Zuordnung erfolgt anschließend durch intern zuständige Benutzer.

### Rollenbasierte interne Verantwortung

Nicht alle internen Benutzer haben dieselben fachlichen Aufgaben.

Das System kennt mehrere interne Rollen oder Verantwortungsbereiche, damit Supportbearbeitung, Prüfung unklarer Eingänge, Vertragsverwaltung und administrative Funktionen kontrolliert und nachvollziehbar ausgeführt werden können.

### Contracts als Customer-Kontext

Ein Customer kann keinen, einen oder mehrere Contracts besitzen.

Contracts dienen dazu, geschäftliche Beziehungen und fachliche Leistungskontexte eines Customers strukturiert abzubilden.

Dadurch kann das System Supportfälle, Auswertungen und fachliche Zusammenhänge genauer einem konkreten Vertrags-, Produkt- oder Leistungskontext zuordnen.

### Historie und Nachvollziehbarkeit

Fachlich relevante Vorgänge müssen auch zu einem späteren Zeitpunkt nachvollziehbar bleiben.

Deshalb bleiben Tickets, Nachrichten, Contracts, zugehörige Dokumente und Audit-Informationen historisch erhalten, soweit ihre fachliche Bedeutung dies erfordert.

### Soft Delete statt Hard Delete im normalen Fachbetrieb

Das System verwendet Soft Delete, um fachliche Objekte logisch zu entfernen, ohne ihre historische Bedeutung im normalen Fachbetrieb zu verlieren.

Dadurch bleiben Identität, Kommunikation, Verträge, Dokumente und Nachvollziehbarkeit erhalten.

### Erweiterbarkeit

Das System ist so konzipiert, dass zusätzliche Kommunikationskanäle und künftige Erweiterungen integriert werden können, ohne die grundlegende Fachlogik des Systems zu verändern.

Dies gilt auch für künftige Erweiterungen im Bereich Vertragslogik, Produktbezug, Leistungspakete, feinere Rollen- und Berechtigungsmodelle, SLA-Regeln und KI-gestützte Unterstützung.