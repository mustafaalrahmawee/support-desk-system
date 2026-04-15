# Business Rules: Smart Support Desk System

## 1. Rollen und interne Verantwortungsbereiche

### Customer

Customer ist ein externer Kommunikationspartner.

Customer kann über externe Kanäle mit dem System kommunizieren und Anfragen an das System senden.

Customer hat keinen direkten Zugriff auf das interne System.

### Support Agent

Support Agent ist ein interner Benutzer mit operativer Zuständigkeit für die Bearbeitung von Supportfällen.

Support Agents bearbeiten Tickets, kommunizieren mit Customers, dokumentieren fachlich relevante Informationen, erstellen Nachrichten und steuern den Bearbeitungsprozess innerhalb des fachlichen Ticket-Lebenszyklus.

### Inbound Reviewer

Inbound Reviewer ist ein interner Benutzer mit Zuständigkeit für die fachliche Prüfung unklarer eingehender Anfragen.

Inbound Reviewers bearbeiten Inbound-Prüffälle und entscheiden, ob ein Eingang einem bestehenden Customer zugeordnet werden kann, ob ein neuer Customer angelegt werden soll oder ob weitere Klärung erforderlich ist.

### Contract Manager

Contract Manager ist ein interner Benutzer mit Zuständigkeit für die Verwaltung des Vertrags-, Produkt- oder Leistungskontexts eines Customers.

Contract Managers legen Contracts an, ändern diese, pflegen zugehörige Informationen und verwalten fachlich relevante Vertragsdokumente oder andere zugehörige Medien.

### Admin

Admin ist ein interner Benutzer mit erweiterten administrativen Rechten.

Admin verwaltet Benutzerkonten, Rollen, Aktivstatus und weitere administrative Bereiche des Systems.

Admin kann zusätzlich fachlich berechtigte Funktionen ausführen, sofern dies durch die Rollen- und Berechtigungsstruktur des Systems vorgesehen ist.

### Interne Rollenstruktur

Das System kennt mehrere interne Rollen mit unterschiedlichen fachlichen Verantwortlichkeiten.

Ein interner Benutzer kann je nach Rollenmodell eine oder mehrere Rollen besitzen.

Autorisierungsentscheidungen dürfen nicht nur aus allgemeiner Systemzugehörigkeit, sondern müssen aus der zugeordneten Rolle und dem fachlichen Kontext abgeleitet werden.

---

## 2. Actor-Regeln

Ein Actor repräsentiert genau einen Kommunikationsakteur.

Ein Actor ist genau einem internen Benutzer oder genau einem Customer zugeordnet.

Ein Actor darf niemals gleichzeitig einem internen Benutzer und einem Customer zugeordnet sein.

Für jeden internen Benutzer existiert höchstens ein Actor.

Für jeden Customer existiert höchstens ein Actor.

Der Lebenszyklus eines Actors ist an den zugeordneten internen Benutzer oder Customer gebunden.

Wird ein interner Benutzer oder Customer soft deleted oder deaktiviert, muss der zugehörige Actor durch den Application Layer ebenfalls deaktiviert oder soft deleted werden.

Wird ein zuvor deaktivierter oder soft deleted interner Benutzer oder Customer reaktiviert, muss der zugehörige Actor durch den Application Layer ebenfalls reaktiviert werden, sofern keine fachliche Regel dagegen spricht.

Ein Actor darf nicht unabhängig von seinem zugeordneten internen Benutzer oder Customer gelöscht, deaktiviert oder reaktiviert werden.

---

## 3. Customer-Regeln

Customer repräsentiert die fachliche Identität eines externen Kommunikationspartners.

Ein Customer besitzt genau eine fachliche Kundennummer (`customer_number`).

`customer_number` muss systemweit eindeutig sein.

Ein Customer kann mehrere Contact-Einträge besitzen.

Ein Customer kann keinen, einen oder mehrere Contracts besitzen.

Ein Customer muss mindestens einen aktiven, nicht soft deleted Contact besitzen, sofern der Customer selbst aktiv ist.

Ein Contact repräsentiert einen Kommunikationsweg eines Customers und ist nicht selbst die Identität des Customers.

Ein neuer eingehender Contact darf nicht automatisch als neuer Customer interpretiert werden.

Das System muss zunächst prüfen, ob der Contact einem bestehenden Customer zugeordnet werden kann oder als zusätzlicher Contact zu einem bestehenden Customer gehört.

Wenn eine eingehende Nachricht eine `customer_number` enthält, darf das System diese als vorrangigen Identifikationshinweis prüfen.

Kann über die `customer_number` genau ein aktiver Customer sicher gefunden werden, wird dieser Customer verwendet.

Danach muss das System weiterhin prüfen, ob der erkannte Contact bereits zu diesem Customer gehört, als neuer zusätzlicher Contact zugeordnet werden kann oder einen fachlichen Konflikt erzeugt.

Wenn `customer_number` und Contact widersprüchlich sind, darf das System keine blinde automatische Zuordnung erzwingen.

In diesem Fall muss der Eingang in einen kontrollierten Prüfprozess oder Konfliktfall überführt werden.

Wenn keine `customer_number` vorhanden ist, bleibt die Contact-basierte Zuordnung zulässig.

Werden mehrere Customer-Datensätze erkannt, die tatsächlich dieselbe fachliche Identität repräsentieren, müssen diese über den Application Layer zu einem Customer zusammengeführt werden.

Beim Merge bleibt genau ein Ziel-Customer aktiv.

Alle zugehörigen aktiven Contacts, Tickets, Contracts und weiteren referenzierenden Datensätze werden auf den Ziel-Customer übertragen oder fachlich konsistent behandelt.

Die übrigen Customer-Datensätze werden nicht hard deleted, sondern soft deleted oder fachlich als zusammengeführt markiert.

Der Merge-Vorgang muss atomar ausgeführt und nachvollziehbar protokolliert werden.

Wird ein Customer soft deleted oder deaktiviert, müssen die zugehörigen Contacts durch den Application Layer ebenfalls soft deleted oder deaktiviert werden.

Wird ein zuvor deaktivierter oder soft deleted Customer reaktiviert, müssen die zugehörigen Contacts durch den Application Layer ebenfalls reaktiviert werden, sofern keine fachliche Regel dagegen spricht.

---

## 4. Contact-Regeln

Ein Contact repräsentiert genau einen Kommunikationsweg eines Customers.

Ein Contact gehört genau einem Customer.

Ein Customer kann mehrere Contacts besitzen.

Zulässige Contact-Typen sind email, phone, whatsapp, web und external.

Contact-Werte müssen innerhalb ihres Typs eindeutig sein, sofern sie aktiv und nicht soft deleted sind.

Beim Anlegen eines neuen Contacts muss das System zunächst prüfen, ob der Contact bereits einem bestehenden Customer zugeordnet ist oder einem bestehenden Customer als zusätzlicher Contact zugeordnet werden soll.

Kann ein neuer Contact keinem bestehenden Customer sicher zugeordnet werden, darf das System einen neuen Customer anlegen und den Contact diesem Customer zuordnen, sofern die verfügbaren Informationen für eine fachlich vertretbare Neuanlage ausreichen.

`is_primary` wird durch den Application Layer bestimmt.

Der erste aktive Contact eines Customers wird standardmäßig als primärer Contact gesetzt, sofern keine fachliche Regel etwas anderes vorgibt.

Pro Customer darf höchstens ein aktiver Contact als primärer Contact markiert sein.

Wird ein anderer Contact als primär gesetzt, muss der bisherige primäre Contact desselben Customers durch den Application Layer entsprechend angepasst werden.

Wird ein Contact soft deleted oder deaktiviert, muss das System zuvor prüfen, ob für den zugehörigen Customer danach noch mindestens ein weiterer aktiver Contact verbleibt.

Ist dies nicht der Fall, darf der Contact nicht unabhängig gelöscht oder deaktiviert werden, sofern nicht gleichzeitig eine fachlich erlaubte Gesamtoperation wie Customer-Deaktivierung oder Merge ausgeführt wird.

`is_verified` kennzeichnet, dass ein Contact fachlich als bestätigter und für Zustellung geeigneter Kommunikationsweg gilt.

---

## 5. Contract-Regeln

Ein Contract repräsentiert einen Vertrags-, Produkt- oder Leistungskontext eines Customers.

Ein Contract gehört genau einem Customer.

Ein Customer kann keinen, einen oder mehrere Contracts besitzen.

Ein Contract besitzt eine fachliche Contract-Kennung, zum Beispiel `contract_number`.

`contract_number` muss mindestens innerhalb des relevanten fachlichen Geltungsbereichs eindeutig sein.

Ein Contract kann Statusinformationen besitzen.

Ein Contract kann Gültigkeitszeiträume besitzen, insbesondere `valid_from` und `valid_to`.

Ein Contract kann einen Typ besitzen, der den fachlichen Kontext näher beschreibt, zum Beispiel Vertrag, Produktpaket oder Leistungspaket.

Ein Contract darf nur dem Customer zugeordnet werden, zu dem er fachlich gehört.

Ein Contract kann aktiv, inaktiv, abgelaufen oder anderweitig fachlich eingeordnet sein, sofern das Statusmodell des Systems dies vorsieht.

Ein Contract kann ohne angehängtes Dokument existieren.

Ein Contract kann zusätzlich fachlich relevante Dokumente oder andere Medien besitzen, zum Beispiel Vertragsdokumente, Angebotsunterlagen, Leistungsbeschreibungen oder PDF-Dateien.

Dokumente oder Medien eines Contracts sind ergänzende Nachweise oder Anhänge und nicht der Contract selbst.

Wird ein Customer soft deleted oder deaktiviert, müssen zugehörige Contracts fachlich konsistent behandelt werden.

Wird ein Customer reaktiviert, müssen zugehörige Contracts fachlich konsistent behandelt oder geprüft werden, sofern keine fachliche Regel dagegen spricht.

---

## 6. Inbound-Prüffall-Regeln

Ein Inbound-Prüffall repräsentiert eine eingehende Nachricht, die nicht sicher automatisch einem bestehenden Customer, Contact oder fachlichen Kontext zugeordnet werden kann.

Ein Inbound-Prüffall darf nicht ignoriert oder stillschweigend verworfen werden.

Wenn eine sichere automatische Customer-Zuordnung nicht möglich ist, muss das System den Eingang in einen kontrollierten Prüfprozess überführen.

Ein Inbound-Prüffall wird durch das System erzeugt und für intern zuständige Benutzer sichtbar gemacht.

Die fachliche Prüfung eines Inbound-Prüffalls erfolgt durch einen dafür berechtigten internen Benutzer, insbesondere durch einen Inbound Reviewer.

Ein Inbound-Prüffall kann zu einer Zuordnung zu einem bestehenden Customer, zur Anlage eines neuen Customers oder zu weiterer Klärung führen.

Wenn ein Inbound-Prüffall manuell entschieden wurde, muss das Ergebnis nachvollziehbar protokolliert werden.

Ein Inbound-Prüffall kann zusätzlich Hinweise auf einen Contract-Kontext enthalten, darf aber auch hierbei keine blinde automatische Zuordnung erzwingen.

---

## 7. Ticket-Regeln

Ein Ticket repräsentiert einen fachlichen Supportfall.

Ein Ticket gehört genau einem Customer und genau einer Kategorie.

Ein Ticket kann zusätzlich genau einem Contract zugeordnet sein oder ohne Contract-Zuordnung bestehen.

Wenn ein Ticket einen Contract referenziert, muss dieser Contract demselben Customer gehören wie das Ticket.

Wird eine Kategorie soft deleted oder deaktiviert, bleibt die Kategoriezuordnung bestehender Tickets erhalten.

Eine deaktivierte oder soft deleted Kategorie darf keinen neuen Tickets mehr zugewiesen werden.

Ein Ticket kann genau einem internen Benutzer zugewiesen sein oder unassigned bleiben.

Ein Ticket darf keinem deaktivierten oder soft deleted internen Benutzer zugewiesen sein.

Wird der zugewiesene interne Benutzer deaktiviert oder soft deleted, muss die Zuweisung durch den Application Layer aufgehoben oder neu vergeben werden.

Ein Ticket hat genau einen Kanal.

Zulässige Kanäle sind email, phone, whatsapp, web und external.

Ein Ticket hat jederzeit genau einen Status.

Zulässige Status sind open, in_progress, waiting_for_customer, resolved und closed.

Statuswechsel dürfen nur über definierte fachliche Übergänge erfolgen.

Geschlossene Tickets dürfen nicht weiter bearbeitet oder verändert werden, sofern keine explizite fachliche Reopen-Regel definiert ist.

Ein Ticket bleibt historisch seinem Customer zugeordnet, auch wenn der Customer deaktiviert oder soft deleted wurde.

Ein Ticket bleibt historisch auch einem gesetzten Contract-Kontext zugeordnet, soweit die Nachvollziehbarkeit dies erfordert.

Subject und Description beschreiben den Ticketkopf.

Die eigentliche Kommunikationshistorie wird über Ticket-Nachrichten abgebildet.

Beim Eingang neuer Kommunikation muss das System prüfen, ob ein bestehendes offenes Ticket fortgeführt wird oder ob ein neues Ticket angelegt werden muss.

---

## 8. Kanal-Regeln

Zulässige Kanalwerte sind email, web, phone, whatsapp und external.

Der Kanal beschreibt den ursprünglichen Eingangskanal, über den ein Ticket entstanden ist.

Der Ticket-Kanal wird bei der Erstellung des Tickets festgelegt und darf nur geändert werden, wenn eine fachlich begründete Korrektur erforderlich ist.

Der Kanal eines Tickets ist nicht automatisch identisch mit dem späteren Antwortkanal, beeinflusst jedoch die bevorzugte Antwortmethode.

---

## 9. Status-Regeln

Zulässige Status:

- open
- in_progress
- waiting_for_customer
- resolved
- closed

Bedeutung:

- open: Ticket ist neu eingegangen und wurde noch nicht aktiv bearbeitet.
- in_progress: Ticket wird aktuell fachlich bearbeitet.
- waiting_for_customer: Die Bearbeitung ist vorübergehend pausiert, weil eine Antwort, Information oder Aktion des Customers erforderlich ist.
- resolved: Der fachliche Fall ist gelöst, das Ticket ist aber noch nicht endgültig abgeschlossen.
- closed: Das Ticket ist endgültig abgeschlossen.

Erlaubte Transitionen:

- open → in_progress
- in_progress → waiting_for_customer
- waiting_for_customer → in_progress
- in_progress → resolved
- resolved → closed

Zusätzliche Regeln:

- Ein Ticket hat jederzeit genau einen Status.
- Statuswechsel dürfen nur über erlaubte Transitionen erfolgen.
- Ein geschlossenes Ticket darf nicht weiter bearbeitet oder verändert werden, sofern keine explizite fachliche Reopen-Regel definiert ist.
- `waiting_for_customer` darf nur gesetzt werden, wenn die weitere Bearbeitung ohne Rückmeldung, Information oder Aktion des Customers nicht sinnvoll fortgesetzt werden kann.
- Geht eine neue relevante Rückmeldung des Customers ein, muss ein Ticket im Status `waiting_for_customer` wieder in `in_progress` überführt werden.

---

## 10. Nachrichten-Regeln

Eine Nachricht repräsentiert einen einzelnen Kommunikationseintrag innerhalb eines Tickets.

Eine Nachricht gehört genau zu einem Ticket.

Eine Nachricht hat genau einen Actor als Autor.

Nachrichten-Typen sind public und internal.

Ist der Nachrichtentyp public, darf der zugehörige Actor ein interner Benutzer oder ein Customer sein.

Ist der Nachrichtentyp internal, muss der zugehörige Actor ein interner Benutzer sein.

Die eigentliche Kommunikationshistorie eines Tickets wird durch Nachrichten abgebildet.

Eine Nachricht kann fachlich relevante Anhänge oder Medien besitzen.

Wird eine Nachricht mit Anhängen gespeichert, müssen diese Anhänge fachlich der Nachricht oder dem vorgesehenen Fachobjekt zugeordnet bleiben.

Eine Nachricht bleibt historisch ihrem Ticket und ihrem Actor zugeordnet, auch wenn das zugehörige Ticket, der zugehörige Customer, der zugehörige interne Benutzer oder der zugehörige Actor später deaktiviert oder soft deleted wird.

Wird ein Ticket soft deleted oder deaktiviert, bleiben die zugehörigen Nachrichten historisch erhalten.

Wird ein Actor soft deleted oder deaktiviert, bleiben die von diesem Actor verfassten Nachrichten historisch erhalten.

Für geschlossene Tickets dürfen keine neuen Nachrichten erstellt werden, sofern keine explizite fachliche Reopen-Regel definiert ist.

---

## 11. Antwort-Regeln

Antworten an einen Customer dürfen nur über einen geeigneten, aktiven und nicht soft deleted Contact des zugehörigen Customers erfolgen.

Der ursprüngliche Ticket-Kanal bestimmt die bevorzugte Antwortmethode, sofern ein passender Contact dieses Typs verfügbar und fachlich zulässig ist.

Ist kein passender Contact für den ursprünglichen Ticket-Kanal verfügbar, darf das System einen anderen geeigneten Contact des Customers verwenden, sofern dies fachlich erlaubt ist.

Die Auswahl des Antwort-Contacts erfolgt durch den Application Layer.

Ein Customer kann mehrere Contacts besitzen.

Die Existenz eines Tickets bedeutet nicht, dass jeder Contact automatisch für Antworten verwendet werden darf.

Ist ein Contact als primär markiert, darf dieser bevorzugt verwendet werden, sofern keine kanalspezifische Regel oder der ursprüngliche Ticket-Kanal etwas anderes erfordert.

Auf einen deaktivierten oder soft deleted Contact darf keine neue Antwort zugestellt werden.

Automatisierte Antworten dürfen nur an verifizierte oder fachlich freigegebene Contacts gesendet werden.

Interne Nachrichten sind keine Customer-Antworten und verwenden keinen Customer-Contact als Zustellziel.

---

## 12. Medien- und Dokument-Regeln

Ein Medium repräsentiert eine gespeicherte Datei, die einem fachlichen Objekt zugeordnet werden kann.

Medien können insbesondere Screenshots, Fotos, PDF-Dokumente oder andere fachlich relevante Dateien sein.

Medien dürfen fachlich an Nachrichten, Tickets, Contracts oder andere dafür vorgesehene Fachobjekte angehängt werden.

Eine eingehende Kundenanfrage kann Anhänge enthalten.

Anhänge eingehender Kommunikation müssen gespeichert und dem richtigen fachlichen Kontext zugeordnet werden, sofern die Nachricht fachlich verarbeitet wird.

Contract-Dokumente oder andere zugehörige Medien müssen dem Contract historisch zugeordnet bleiben.

Medien mit eigenem Dokumentationswert dürfen nicht unzulässig verloren gehen, wenn das referenzierte Fachobjekt später deaktiviert oder soft deleted wird, sofern keine speziellere fachliche Regel dagegen spricht.

---

## 13. Audit-Regeln

Fachlich relevante Änderungen müssen nachvollziehbar protokolliert werden.

Mindestens folgende Ereignisse sind auditpflichtig:

- Ticket erstellt
- Ticket-Status geändert
- Ticket-Zuweisung geändert
- Ticket-Kanal, Kategorie oder Contract-Kontext geändert
- Nachricht erstellt
- Customer erstellt
- Customer zusammengeführt
- Contact erstellt
- Contact geändert
- Contact als primär gesetzt oder geändert
- Contact verifiziert, deaktiviert, soft deleted oder reaktiviert
- Contract erstellt, geändert, deaktiviert, soft deleted oder reaktiviert
- Contract-Dokument hinzugefügt, geändert oder entfernt
- Inbound-Prüffall erstellt oder entschieden
- interner Benutzer deaktiviert, soft deleted oder reaktiviert
- Benutzerkonto erstellt, geändert, deaktiviert, soft deleted oder reaktiviert
- Rollen- oder Berechtigungszuordnung geändert
- Login ausgelöst
- Logout ausgelöst
- eigenes Profil geändert
- automatisierte oder KI-gestützte Antwort ausgelöst

Ein Audit-Eintrag muss genau einen Ausführungskontext besitzen.

Der Ausführungskontext ist genau eine der folgenden Arten:

- interner Benutzer
- Systemprozess
- AI-Agent

Die Art des Ausführungskontexts wird durch `context_type` bestimmt.

Ist `context_type = internal_user`, muss ein ausführender interner Benutzer referenziert werden.

In diesem Fall darf kein gesonderter System- oder AI-Kontext gesetzt sein.

Ist `context_type = system` oder `ai_agent`, darf kein interner Benutzer referenziert werden.

In diesem Fall muss der Ausführungskontext fachlich eindeutig benannt werden.

Ein Audit-Eintrag muss mindestens die Art der Aktion, das betroffene Fachobjekt, die betroffene Objekt-ID und den Änderungszeitpunkt enthalten.

Soweit fachlich sinnvoll, müssen außerdem alte und neue Werte der relevanten Felder gespeichert werden.

Audit-Logs sind historisch und dürfen nicht durch normale Fachprozesse verändert oder gelöscht werden.

---

## 14. Soft Delete Regeln

Hard Delete ist für fachliche Hauptdaten im normalen Anwendungskontext verboten.

Stattdessen werden fachliche Objekte durch Soft Delete logisch entfernt, sodass ihre historischen Daten erhalten bleiben.

Soft deleted Daten bleiben für Historie, Nachvollziehbarkeit, Audit, Auswertung und spätere Analyse erhalten.

Die Auswirkungen eines Soft Delete auf abhängige Objekte werden durch fachliche Regeln und den Application Layer bestimmt.

Wird ein Customer oder interner Benutzer soft deleted oder deaktiviert, müssen abhängige Objekte mit gebundenem Lebenszyklus, insbesondere Actor und Contacts, durch den Application Layer konsistent mit deaktiviert oder soft deleted werden.

Historische Objekte mit eigenem Dokumentationswert, insbesondere Tickets, Ticket-Nachrichten, Contract-Dokumente und Audit-Logs, bleiben auch dann erhalten, wenn zugehörige referenzierte Objekte später deaktiviert oder soft deleted werden.

Ein Soft Delete darf keine fachlich inkonsistenten Zustände erzeugen.

Abhängige Daten müssen so behandelt werden, dass Identität, Historie und referenzielle Bedeutung erhalten bleiben.

Wird ein zuvor soft deleted Objekt reaktiviert, müssen abhängige Objekte durch den Application Layer ebenfalls reaktiviert werden, sofern keine fachliche Regel dagegen spricht.

Soft Delete und Reaktivierung müssen fachlich nachvollziehbar sein und, soweit relevant, auditpflichtig behandelt werden.

---

## 15. State Constraints

Ein Ticket hat jederzeit genau einen Status.

Ein Ticket ist jederzeit genau einem Customer und genau einer Kategorie zugeordnet.

Ein Ticket kann höchstens einem internen Benutzer als Bearbeiter zugeordnet sein oder unassigned sein.

Ein Ticket darf keinem deaktivierten oder soft deleted internen Benutzer zugewiesen sein.

Ein Ticket mit Contract-Bezug darf nur einen Contract desselben Customers referenzieren.

Eine Nachricht gehört jederzeit genau zu einem Ticket.

Eine Nachricht hat jederzeit genau einen Actor als Autor.

Ein Actor ist jederzeit genau einem internen Benutzer oder genau einem Customer zugeordnet.

Ein Customer muss jederzeit genau eine eindeutige `customer_number` besitzen.

Ein Customer muss jederzeit mindestens einen aktiven, nicht soft deleted Contact besitzen, sofern der Customer selbst aktiv ist.

Pro Customer darf höchstens ein aktiver Contact als primärer Contact markiert sein.

Ein Audit-Eintrag muss jederzeit genau einen gültigen Ausführungskontext besitzen.

---

## 16. Transition Constraints

Statuswechsel eines Tickets dürfen nur über fachlich erlaubte Transitionen erfolgen.

Erlaubte Statuswechsel sind:

- open → in_progress
- in_progress → waiting_for_customer
- waiting_for_customer → in_progress
- in_progress → resolved
- resolved → closed

Ein Ticket darf nur in den Status `waiting_for_customer` überführt werden, wenn die weitere Bearbeitung ohne Rückmeldung, Information oder Aktion des Customers nicht sinnvoll fortgesetzt werden kann.

Geht eine neue relevante Rückmeldung des Customers ein, muss ein Ticket im Status `waiting_for_customer` wieder in `in_progress` überführt werden.

Ein geschlossenes Ticket darf nicht weiter bearbeitet, nicht erneut zugewiesen und nicht durch neue Nachrichten fortgeführt werden, sofern keine explizite fachliche Reopen-Regel definiert ist.

Ein Inbound-Prüffall darf erst dann als entschieden gelten, wenn eine fachlich nachvollziehbare Zuordnungs- oder Klärungsentscheidung getroffen wurde.

Änderungen an fachlich zusammenhängenden Objekten müssen atomar ausgeführt werden, sofern ein inkonsistenter Zwischenzustand fachlich unzulässig wäre.

Insbesondere müssen Customer-Merge, Customer-Erstellung mit Contacts, Actor-Synchronisation, Contract-Erstellung mit Dokumenten, Inbound-Prüffall-Entscheidungen sowie Soft Delete oder Reaktivierung abhängiger Objekte atomar ausgeführt werden, wenn sonst ein fachlich ungültiger Zwischenzustand entstehen würde.

Transitionen dürfen keine fachlich ungültigen Zustände erzeugen.

---

## 17. Authentifizierung und Autorisierung

Interne fachliche Funktionen des Systems dürfen nur durch authentifizierte interne Benutzer ausgeführt werden.

Ein interner Benutzer muss vor der Bearbeitung von Tickets, Nachrichten, Contacts, Customers, Contracts, Kategorien, Inbound-Prüffällen oder Medien erfolgreich authentifiziert sein.

Administrative Funktionen dürfen nur durch entsprechend berechtigte interne Benutzer ausgeführt werden.

Die Verwaltung interner Benutzerkonten und Rollen ist ausschließlich dafür autorisierten Benutzern erlaubt.

Login und Logout sind fachlich relevante Systemvorgänge.

Ein authentifizierter interner Benutzer darf die eigenen Profildaten einsehen und bearbeiten, sofern keine fachliche Regel dagegen spricht.

Nicht jeder interne Benutzer darf jede fachliche Funktion ausführen.

Die Berechtigung zur Bearbeitung von Tickets, zur Prüfung unklarer Eingänge, zur Verwaltung von Contracts oder zur administrativen Benutzerverwaltung hängt von Rolle und Fachkontext ab.

Autorisierungsentscheidungen werden durch Rolle, Authentifizierungszustand und Application Layer durchgesetzt.

---

## 18. Erweiterbarkeit

Das System ist so zu gestalten, dass zusätzliche Kommunikationskanäle später ergänzt werden können, ohne die grundlegende Fachlogik von Customer, Contact, Contract, Ticket und Nachricht zu verändern.

Das System soll so erweiterbar sein, dass feinere Rollen- und Berechtigungsmodelle später ergänzt werden können, ohne die grundlegende Fachlogik der Hauptobjekte neu zu definieren.

Das System soll so erweiterbar sein, dass AI-Agenten künftig als eigenständige Kommunikationsakteure oder als unterstützende Komponenten bei Inbound-Prüfung, Spam-Erkennung oder Zuordnungsvorschlägen modelliert werden können, sofern hierfür die fachlichen Actor- und Autorisierungsregeln entsprechend erweitert werden.

Bestehende Regeln zu Historie, Audit, Identität und Soft Delete müssen auch bei künftigen Erweiterungen erhalten bleiben.