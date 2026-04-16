# Use Cases: Smart Support Desk System

## Allgemeine Regeln für alle Use Cases

Für alle Use Cases, die durch einen internen Benutzer ausgelöst werden, gilt:

- Der Benutzer muss authentifiziert sein.
- Der Benutzer muss aktiv sein.
- Der Benutzer muss für die jeweilige Funktion berechtigt sein.
- Fachlich zusammenhängende Änderungen müssen atomar ausgeführt werden, wenn sonst ein fachlich ungültiger Zwischenzustand entstehen würde.
- Fachlich relevante Änderungen müssen entsprechend der Business Rules nachvollziehbar protokolliert werden.

Das System kennt mehrere interne Rollen mit unterschiedlichen Verantwortlichkeiten, insbesondere:

- Support Agent
- Inbound Reviewer
- Contract Manager
- Admin

Die konkrete Berechtigung eines Benutzers hängt von Rolle und Fachkontext ab.

## Hinweis zur Dokumentstruktur

Diese Datei ist die **Master-Datei** für alle Use Cases des Projekts.

Sie dient für:

- Gesamtüberblick
- Konsistenzprüfung
- fachliche Gesamtpflege

Für fokussierte Arbeit mit kleineren Kontexten werden zusätzlich aufgeteilte Dateien verwendet:

- `docs/02_use-cases/by-domain/` für fachlich zusammenhängende Session-Kontexte
- `docs/02_use-cases/by-use-case/` für atomare einzelne Use Cases

Für normale Implementierungs- oder Review-Sessions soll bevorzugt die kleinste passende Dokumenteinheit verwendet werden.

---

# Phase 1: Eingang, Zuordnung, Inbound-Prüfung und Kommunikation

## Use Case 1: Eingehende Kundenanfrage verarbeiten

### Ziel

Eine neue Kundenanfrage soll im System richtig verarbeitet werden. Die Anfrage soll einem bestehenden Customer und einem bestehenden offenen Ticket zugeordnet oder als neuer Supportfall erfasst werden.

### Beteiligte Akteure

- Customer
- System
- optional interner Benutzer

### Auslöser

Ein Kunde sendet eine neue Anfrage an das System, zum Beispiel per E-Mail, WhatsApp, Web oder Telefon.

### Vorbedingungen

- Der verwendete Kommunikationskanal wird vom System unterstützt.
- Die Anfrage enthält genug Informationen, damit das System den Kanal, einen Contact, eine `customer_number` oder einen anderen fachlichen Hinweis erkennen kann.
- Das System ist betriebsbereit.

### Hauptablauf

1. Ein Kunde sendet eine neue Anfrage an das System.
2. Das System erkennt, über welchen Kanal die Anfrage eingegangen ist.
3. Das System prüft, ob eine `customer_number` vorhanden ist.
4. Wenn eine `customer_number` vorhanden ist, prüft das System, ob dadurch genau ein aktiver Customer sicher identifiziert werden kann.
5. Das System prüft den erkannten Contact fachlich.
6. Das System bestimmt, ob die Anfrage einem bestehenden Customer sicher zugeordnet werden kann.
7. Das System prüft optional, ob ein fachlicher Contract-Kontext sicher erkannt werden kann.
8. Das System prüft, ob die neue Anfrage zu einem bereits offenen Ticket dieses Customers gehört.
9. Wenn ein passendes offenes Ticket gefunden wird, wird dieses Ticket weitergeführt.
10. Wenn kein passendes offenes Ticket gefunden wird, legt das System ein neues Ticket an.
11. Das System speichert die neue Anfrage als öffentliche Nachricht im zugeordneten oder neu angelegten Ticket.
12. Das System speichert mitgesendete Anhänge oder Medien fachlich korrekt.
13. Das System protokolliert die fachlich relevanten Vorgänge.

### Alternativabläufe

#### A1: Contact gehört zu einem bestehenden Customer, aber nicht zu einem bestehenden Ticket

Das System ordnet die Anfrage dem bestehenden Customer zu und legt ein neues Ticket an.

#### A2: Contact ist neu, kann aber einem bestehenden Customer zusätzlich zugeordnet werden

Das System ordnet den neuen Contact dem bestehenden Customer zu und verarbeitet die Anfrage danach im passenden oder in einem neuen Ticket.

#### A3: Ein Contract-Kontext kann nicht sicher erkannt werden

Das Ticket wird zunächst ohne Contract-Zuordnung erstellt oder weitergeführt. Die Contract-Zuordnung kann später durch einen internen Benutzer ergänzt werden.

#### A4: Mehrere mögliche Customer-Zuordnungen sind denkbar oder die Zuordnung ist nicht sicher

Das System erzwingt keine unsichere automatische Zuordnung. Die Anfrage wird in einen kontrollierten Prüfprozess überführt.

### Nachbedingungen

- Die Anfrage ist entweder einem Customer zugeordnet oder als Inbound-Prüffall zur fachlichen Prüfung erhalten.
- Falls die Anfrage sicher zugeordnet werden konnte, ist sie in einem Ticket als Nachricht gespeichert.
- Es gibt entweder ein weitergeführtes oder ein neu angelegtes Ticket oder einen Inbound-Prüffall.
- Fachlich relevante Änderungen sind protokolliert.

### Fachliche Hinweise

- Ein neuer Contact darf nicht automatisch als neuer Customer behandelt werden.
- Eine `customer_number` ist ein vorrangiger Identifikationshinweis, ersetzt aber nicht die Contact-Prüfung.
- Ein Ticket repräsentiert den Supportfall, nicht nur eine einzelne Nachricht.
- Die eigentliche Kommunikationshistorie wird über Ticket-Nachrichten gespeichert.
- Anhänge wie Screenshots, Fotos oder PDFs können Teil der eingehenden Anfrage sein.
- Die historische Nachvollziehbarkeit muss erhalten bleiben.

---

## Use Case 2: Unklaren Inbound-Prüffall prüfen und entscheiden

### Ziel

Ein unklarer eingehender Vorgang soll durch einen berechtigten internen Benutzer fachlich geprüft und entschieden werden.

### Beteiligte Akteure

- Inbound Reviewer
- System

### Auslöser

Ein Inbound-Prüffall liegt vor und soll bearbeitet werden.

### Vorbedingungen

- Der Inbound-Prüffall existiert.
- Der ausführende interne Benutzer ist authentifiziert, aktiv und zur Prüfung berechtigt.

### Hauptablauf

1. Ein Inbound Reviewer öffnet einen bestehenden Inbound-Prüffall.
2. Das System zeigt die eingegangene Nachricht, erkannte Hinweise und mögliche Zuordnungsinformationen an.
3. Der Inbound Reviewer prüft die verfügbaren Informationen.
4. Der Inbound Reviewer entscheidet, ob die Nachricht einem bestehenden Customer zugeordnet werden kann, ob ein neuer Customer angelegt werden soll oder ob weitere Klärung erforderlich ist.
5. Das System speichert die Entscheidung nachvollziehbar.
6. Falls eine Customer-Zuordnung möglich ist, verarbeitet das System den Fall weiter zu Ticket und Nachricht.
7. Das System protokolliert die fachlich relevanten Vorgänge.

### Alternativabläufe

#### A1: Bestehender Customer wird ausgewählt

Das System ordnet den Fall dem ausgewählten Customer zu und verarbeitet die Nachricht weiter.

#### A2: Neuer Customer wird angelegt

Das System legt einen neuen Customer an, ordnet den Contact zu, sofern möglich, und verarbeitet die Nachricht weiter.

#### A3: Weitere Klärung erforderlich

Der Prüffall bleibt im Prüfprozess und wird noch nicht als regulärer Ticketfall abgeschlossen.

### Nachbedingungen

- Der Prüffall ist entschieden oder weiterhin fachlich offen.
- Wenn eine Zuordnung erfolgt ist, wurde der Eingang in die normale Ticket- und Nachrichtenlogik überführt.
- Fachlich relevante Änderungen sind protokolliert.

### Fachliche Hinweise

- Unklare Eingänge dürfen nicht ignoriert werden.
- Unsichere Zuordnungen dürfen nicht blind automatisch erzwungen werden.

---

## Use Case 3: Öffentliche Antwort an Customer senden

### Ziel

Ein interner Benutzer soll auf ein bestehendes Ticket öffentlich antworten können, damit der Customer über einen geeigneten Kontaktweg eine fachlich passende Rückmeldung erhält.

### Beteiligte Akteure

- Support Agent
- System
- Customer

### Auslöser

Ein interner Benutzer möchte auf ein bestehendes Ticket öffentlich antworten.

### Vorbedingungen

- Das Ticket existiert.
- Das Ticket ist nicht geschlossen.
- Der interne Benutzer ist authentifiziert, aktiv und berechtigt, auf das Ticket zu antworten.
- Für den zugehörigen Customer existiert mindestens ein geeigneter, aktiver und nicht soft deleted Contact.
- Für automatisierte Antworten muss zusätzlich ein verifizierter oder fachlich freigegebener Contact verfügbar sein.

### Hauptablauf

1. Ein interner Benutzer öffnet ein bestehendes Ticket.
2. Der Benutzer erstellt eine öffentliche Antwort.
3. Das System prüft, ob das Ticket noch beantwortet werden darf.
4. Das System ermittelt den passenden Contact für die Antwort.
5. Das System berücksichtigt dabei den ursprünglichen Ticket-Kanal und die verfügbaren aktiven Contacts des Customers.
6. Das System speichert die Antwort als öffentliche Nachricht im Ticket.
7. Das System versendet oder übergibt die Antwort über den ausgewählten Contact.
8. Das System protokolliert die fachlich relevanten Vorgänge.

### Alternativabläufe

#### A1: Passender Contact für den ursprünglichen Ticket-Kanal ist nicht verfügbar

Das System verwendet einen anderen geeigneten aktiven Contact des Customers, sofern dies fachlich erlaubt ist.

#### A2: Kein geeigneter aktiver Contact ist verfügbar

Die Antwort darf nicht automatisch zugestellt werden. Der Vorgang wird abgebrochen oder zur fachlichen Klärung markiert.

#### A3: Ticket ist bereits geschlossen

Für das Ticket darf keine neue öffentliche Antwort erstellt werden, sofern keine explizite fachliche Reopen-Regel definiert ist.

#### A4: Antwort wird automatisiert erzeugt

Das System darf die Antwort nur über einen verifizierten oder fachlich freigegebenen Contact zustellen.

### Nachbedingungen

- Die öffentliche Antwort ist als Nachricht im Ticket gespeichert.
- Die Antwort wurde einem geeigneten Contact zur Zustellung zugeordnet.
- Fachlich relevante Änderungen und Aktionen sind protokolliert.

---

## Use Case 4: Interne Nachricht im Ticket erstellen

### Ziel

Ein interner Benutzer soll in einem bestehenden Ticket eine interne Nachricht erstellen können, damit fachliche Hinweise, Abstimmungen oder Bearbeitungsnotizen dokumentiert werden.

### Beteiligte Akteure

- Support Agent
- System

### Auslöser

Ein interner Benutzer möchte in einem bestehenden Ticket eine interne Nachricht erfassen.

### Vorbedingungen

- Das Ticket existiert.
- Das Ticket ist nicht geschlossen.
- Der interne Benutzer ist authentifiziert, aktiv und berechtigt, das Ticket zu bearbeiten.
- Für den internen Benutzer existiert ein gültiger Actor.

### Hauptablauf

1. Ein interner Benutzer öffnet ein bestehendes Ticket.
2. Der Benutzer erfasst eine interne Nachricht.
3. Das System prüft, ob das Ticket noch bearbeitet werden darf.
4. Das System ordnet die Nachricht dem Ticket und dem Actor des internen Benutzers zu.
5. Das System speichert die Nachricht als interne Nachricht im Ticket.
6. Das System protokolliert die fachlich relevanten Vorgänge.

### Alternativabläufe

#### A1: Ticket ist bereits geschlossen

Für das Ticket darf keine neue interne Nachricht erstellt werden, sofern keine explizite fachliche Reopen-Regel definiert ist.

#### A2: Für den internen Benutzer existiert kein gültiger Actor

Die interne Nachricht darf nicht gespeichert werden. Der Vorgang wird abgebrochen oder zur fachlichen Klärung markiert.

### Nachbedingungen

- Die interne Nachricht ist im Ticket gespeichert.
- Die Nachricht ist dem Actor des internen Benutzers zugeordnet.
- Fachlich relevante Änderungen und Aktionen sind protokolliert.

---

# Phase 2: Customer- und Contact-Verwaltung

## Use Case 5: Customer manuell anlegen

### Ziel

Ein interner Benutzer soll einen neuen Customer manuell anlegen können.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein interner Benutzer möchte einen neuen Customer manuell im System anlegen.

### Vorbedingungen

- Der interne Benutzer ist authentifiziert, aktiv und berechtigt, Customers anzulegen.
- Die eingegebenen Daten reichen aus, um einen Customer fachlich sinnvoll zu erfassen.

### Hauptablauf

1. Ein interner Benutzer startet die manuelle Anlage eines neuen Customers.
2. Der Benutzer erfasst die verfügbaren Kundendaten einschließlich `customer_number`.
3. Das System prüft die Eingaben.
4. Das System legt den Customer an.
5. Das System legt mindestens einen ersten Contact an oder fordert einen Contact an, wenn dieser für den gültigen Zustand erforderlich ist.
6. Das System erzeugt bei Bedarf den zugehörigen Actor.
7. Das System protokolliert die fachlich relevanten Vorgänge.

### Alternativabläufe

#### A1: Es gibt Hinweise auf einen bereits bestehenden Customer

Das System legt nicht sofort blind einen neuen Customer an, sondern markiert den Fall zur fachlichen Prüfung oder schlägt eine bestehende Zuordnung vor.

#### A2: Kein gültiger Contact wird angegeben

Der Vorgang wird nicht abgeschlossen, sofern dadurch ein fachlich ungültiger Customer ohne aktiven Contact entstehen würde.

### Nachbedingungen

- Der Customer ist im System angelegt.
- Der Customer besitzt eine eindeutige `customer_number`.
- Der Customer besitzt mindestens einen aktiven Contact.
- Der Customer befindet sich in einem fachlich gültigen Zustand.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 6: Customer-Details anzeigen

### Ziel

Ein interner Benutzer soll alle relevanten Informationen eines Customers anzeigen können.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein interner Benutzer öffnet einen bestehenden Customer.

### Vorbedingungen

- Der Customer existiert.
- Der interne Benutzer ist authentifiziert, aktiv und berechtigt.

### Hauptablauf

1. Ein interner Benutzer öffnet die Detailansicht eines Customers.
2. Das System lädt die Stammdaten des Customers.
3. Das System lädt die zugehörigen Contacts.
4. Das System lädt optional die zugehörigen Contracts und weitere relevante Informationen.
5. Das System zeigt die Daten an.

### Nachbedingungen

- Die relevanten Customer-Informationen sind sichtbar.

---

## Use Case 7: Customer bearbeiten

### Ziel

Ein interner Benutzer soll einen bestehenden Customer ändern können.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein interner Benutzer möchte einen bestehenden Customer ändern.

### Vorbedingungen

- Der Customer existiert.
- Der interne Benutzer ist authentifiziert, aktiv und berechtigt.

### Hauptablauf

1. Ein interner Benutzer öffnet einen bestehenden Customer.
2. Der Benutzer ändert die zulässigen Felder.
3. Das System prüft die Eingaben.
4. Das System speichert die Änderungen.
5. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Die Customer-Daten sind aktualisiert.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 8: Customer deaktivieren oder soft delete ausführen

### Ziel

Ein bestehender Customer soll fachlich korrekt deaktiviert oder soft deleted werden.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein interner Benutzer möchte einen bestehenden Customer deaktivieren oder soft deleten.

### Vorbedingungen

- Der Customer existiert.
- Der interne Benutzer ist authentifiziert, aktiv und berechtigt.
- Die fachlichen Voraussetzungen für Deaktivierung oder Soft Delete sind erfüllt.

### Hauptablauf

1. Ein interner Benutzer wählt einen bestehenden Customer aus.
2. Der Benutzer startet die Deaktivierung oder das Soft Delete.
3. Das System prüft, ob der Customer fachlich deaktiviert oder soft deleted werden darf.
4. Das System identifiziert die abhängigen Objekte mit gebundenem Lebenszyklus, insbesondere Contacts und Actor.
5. Das System deaktiviert oder soft deleted den Customer.
6. Das System behandelt zugehörige Contacts, Actor und Contracts fachlich konsistent.
7. Historische Objekte wie Tickets, Nachrichten, Medien und Audit-Logs bleiben erhalten.
8. Das System protokolliert die fachlich relevanten Vorgänge.
9. Das System führt den gesamten Vorgang atomar aus.

### Nachbedingungen

- Der Customer ist deaktiviert oder soft deleted, sofern die Voraussetzungen erfüllt waren.
- Abhängige Objekte sind fachlich konsistent behandelt.
- Historische Hauptobjekte bleiben erhalten.
- Fachlich relevante Änderungen und Aktionen sind protokolliert.

---

## Use Case 9: Customer reaktivieren

### Ziel

Ein zuvor deaktivierter oder soft deleted Customer soll fachlich korrekt reaktiviert werden.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein interner Benutzer möchte einen zuvor deaktivierten oder soft deleted Customer reaktivieren.

### Vorbedingungen

- Der Customer existiert.
- Der Customer ist aktuell deaktiviert oder soft deleted.
- Der interne Benutzer ist authentifiziert, aktiv und berechtigt.

### Hauptablauf

1. Ein interner Benutzer wählt einen deaktivierten oder soft deleted Customer aus.
2. Der Benutzer startet die Reaktivierung.
3. Das System prüft, ob der Customer fachlich reaktiviert werden darf.
4. Das System reaktiviert den Customer.
5. Das System behandelt zugehörige Contacts, Actor und Contracts fachlich konsistent.
6. Das System prüft, ob nach der Reaktivierung wieder ein fachlich konsistenter Zustand vorliegt.
7. Das System protokolliert die fachlich relevanten Vorgänge.
8. Das System führt den gesamten Vorgang atomar aus.

### Nachbedingungen

- Der Customer ist wieder aktiv, sofern die Voraussetzungen erfüllt waren.
- Abhängige Objekte sind konsistent reaktiviert oder geprüft.
- Der Customer befindet sich wieder in einem fachlich gültigen Zustand.
- Fachlich relevante Änderungen und Aktionen sind protokolliert.

---

## Use Case 10: Customer-Dubletten zusammenführen

### Ziel

Mehrere Customer-Datensätze, die dieselbe fachliche Identität repräsentieren, sollen zu einem Customer zusammengeführt werden.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein interner Benutzer erkennt oder bestätigt, dass mehrere Customer-Datensätze in Wirklichkeit denselben Customer repräsentieren.

### Vorbedingungen

- Mindestens zwei Customer-Datensätze existieren.
- Es ist fachlich ausreichend geklärt, dass die betroffenen Datensätze dieselbe Identität repräsentieren.
- Ein Ziel-Customer für den Merge ist festgelegt.

### Hauptablauf

1. Ein interner Benutzer startet den Merge von mehreren Customer-Datensätzen.
2. Das System prüft, ob die ausgewählten Customer-Datensätze fachlich zusammengeführt werden dürfen.
3. Das System bestimmt den Ziel-Customer, der aktiv bestehen bleibt.
4. Das System überträgt die zugehörigen Contacts auf den Ziel-Customer, sofern keine unzulässigen Konflikte entstehen.
5. Das System überträgt die zugehörigen Tickets und Contracts auf den Ziel-Customer oder behandelt sie fachlich konsistent.
6. Das System behandelt weitere referenzierende Datensätze nach den fachlichen Regeln.
7. Das System markiert die übrigen Customer-Datensätze als zusammengeführt und soft deleted oder fachlich inaktiv.
8. Das System protokolliert die fachlich relevanten Vorgänge.
9. Das System führt den gesamten Merge atomar aus.

### Nachbedingungen

- Genau ein Ziel-Customer bleibt als aktiver Customer bestehen.
- Relevante referenzierende Daten sind dem Ziel-Customer zugeordnet oder fachlich konsistent behandelt.
- Die übrigen Customer-Datensätze sind soft deleted oder fachlich als zusammengeführt markiert.
- Fachlich relevante Änderungen und Aktionen sind protokolliert.

---

## Use Case 11: Customer-Contact anlegen

### Ziel

Für einen bestehenden oder neu erkannten Customer soll ein neuer Contact angelegt werden.

### Beteiligte Akteure

- interner Benutzer oder System
- Customer

### Auslöser

Ein neuer Contact wird erkannt oder soll manuell für einen Customer angelegt werden.

### Vorbedingungen

- Der Customer existiert oder wird im selben fachlichen Vorgang neu angelegt.
- Der neue Contact enthält einen zulässigen Contact-Typ und einen fachlich verwertbaren Wert.
- Der Contact-Wert ist innerhalb seines Typs nicht bereits aktiv einem anderen Customer zugeordnet, sofern keine fachliche Merge- oder Prüfregel greift.

### Hauptablauf

1. Ein interner Benutzer oder das System startet das Anlegen eines neuen Contacts.
2. Das System prüft den Contact-Typ und den Contact-Wert.
3. Das System prüft, ob der Contact bereits einem bestehenden Customer zugeordnet ist.
4. Wenn der Contact bereits demselben Customer zugeordnet ist, wird kein neuer Contact doppelt angelegt.
5. Wenn der Contact neu ist und dem Customer zugeordnet werden darf, legt das System den Contact an.
6. Das System prüft, ob der Customer bereits einen aktiven primären Contact besitzt.
7. Wenn noch kein primärer Contact existiert, setzt das System den neuen Contact als primär.
8. Das System speichert den neuen Contact.
9. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Der Contact ist dem richtigen Customer zugeordnet.
- Der Contact ist gespeichert, sofern die fachlichen Prüfungen erfolgreich waren.
- Pro Customer existiert weiterhin höchstens ein aktiver primärer Contact.
- Fachlich relevante Änderungen und Aktionen sind protokolliert.

---

## Use Case 12: Contact als primär setzen

### Ziel

Ein vorhandener Contact soll als primärer Contact eines Customers markiert werden.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein interner Benutzer möchte einen Contact als primär setzen.

### Vorbedingungen

- Der Contact existiert.
- Der Contact ist aktiv und nicht soft deleted.
- Der zugehörige Customer existiert.
- Der interne Benutzer ist authentifiziert, aktiv und berechtigt.

### Hauptablauf

1. Ein interner Benutzer wählt einen bestehenden Contact aus.
2. Der Benutzer setzt den Contact als primär.
3. Das System prüft, ob der Contact als primär zulässig ist.
4. Das System entfernt die Primär-Markierung vom bisher primären aktiven Contact desselben Customers.
5. Das System setzt den ausgewählten Contact als primär.
6. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Genau ein aktiver Contact des Customers ist als primär markiert.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 13: Contact verifizieren

### Ziel

Ein Contact soll als verifiziert markiert werden.

### Beteiligte Akteure

- interner Benutzer oder System
- System

### Auslöser

Ein interner Benutzer oder ein fachlicher Prüfprozess möchte einen Contact als verifiziert markieren.

### Vorbedingungen

- Der Contact existiert.
- Der Contact ist aktiv und nicht soft deleted.

### Hauptablauf

1. Ein interner Benutzer oder das System startet die Verifizierung eines Contacts.
2. Das System prüft, ob die fachlichen Voraussetzungen erfüllt sind.
3. Das System setzt `is_verified`.
4. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Der Contact ist als verifiziert markiert.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 14: Contact deaktivieren oder soft delete ausführen

### Ziel

Ein bestehender Contact soll fachlich korrekt deaktiviert oder soft deleted werden.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein interner Benutzer möchte einen bestehenden Contact deaktivieren oder soft deleten.

### Vorbedingungen

- Der Contact existiert.
- Der Contact gehört zu einem bestehenden Customer.
- Der interne Benutzer ist authentifiziert, aktiv und berechtigt.

### Hauptablauf

1. Ein interner Benutzer wählt einen bestehenden Contact aus.
2. Der Benutzer startet die Deaktivierung oder das Soft Delete des Contacts.
3. Das System prüft, zu welchem Customer der Contact gehört.
4. Das System prüft, ob nach der Deaktivierung oder dem Soft Delete noch mindestens ein weiterer aktiver Contact verbleibt.
5. Wenn dies fachlich zulässig ist, deaktiviert oder soft deleted das System den Contact.
6. Falls der betroffene Contact der primäre Contact war, bestimmt das System bei Bedarf einen anderen aktiven Contact als neuen primären Contact.
7. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Der Contact ist deaktiviert oder soft deleted, sofern die Voraussetzungen erfüllt waren.
- Der Customer besitzt weiterhin mindestens einen aktiven Contact, sofern der Customer selbst aktiv bleibt.
- Pro Customer existiert weiterhin höchstens ein aktiver primärer Contact.
- Fachlich relevante Änderungen und Aktionen sind protokolliert.

---

## Use Case 15: Contact reaktivieren

### Ziel

Ein zuvor deaktivierter oder soft deleted Contact soll fachlich korrekt reaktiviert werden.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein interner Benutzer möchte einen zuvor deaktivierten oder soft deleted Contact reaktivieren.

### Vorbedingungen

- Der Contact existiert.
- Der Contact ist aktuell deaktiviert oder soft deleted.
- Der zugehörige Customer darf fachlich aktiv sein oder im selben Vorgang reaktiviert werden.

### Hauptablauf

1. Ein interner Benutzer wählt einen deaktivierten oder soft deleted Contact aus.
2. Der Benutzer startet die Reaktivierung.
3. Das System prüft, ob der Contact fachlich reaktiviert werden darf.
4. Das System reaktiviert den Contact.
5. Das System prüft, ob die Eindeutigkeit des Contact-Werts weiterhin gewahrt ist.
6. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Der Contact ist wieder aktiv, sofern die Voraussetzungen erfüllt waren.
- Fachlich relevante Änderungen sind protokolliert.

---

# Phase 3: Contracts und Vertragsdokumente

## Use Case 16: Contract für Customer anlegen

### Ziel

Ein berechtigter interner Benutzer soll für einen bestehenden Customer einen neuen Contract anlegen können.

### Beteiligte Akteure

- Contract Manager
- System

### Auslöser

Ein interner Benutzer möchte für einen Customer einen Contract anlegen.

### Vorbedingungen

- Der Customer existiert.
- Der interne Benutzer ist authentifiziert, aktiv und berechtigt.
- Die eingegebenen Contract-Daten sind fachlich zulässig.

### Hauptablauf

1. Ein Contract Manager öffnet einen bestehenden Customer.
2. Der Benutzer startet die Anlage eines neuen Contracts.
3. Der Benutzer erfasst die Contract-Daten.
4. Das System prüft die Eingaben.
5. Das System legt den Contract an.
6. Das System protokolliert die fachlich relevanten Vorgänge.

### Alternativabläufe

#### A1: Ein Contract mit derselben fachlichen Kennung ist im relevanten Geltungsbereich bereits vorhanden

Die Anlage wird abgelehnt oder zur fachlichen Klärung markiert.

### Nachbedingungen

- Der Contract ist gespeichert.
- Der Contract ist dem richtigen Customer zugeordnet.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 17: Contracts eines Customers anzeigen

### Ziel

Ein interner Benutzer soll die Contracts eines Customers anzeigen können.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein interner Benutzer öffnet den Contract-Bereich eines Customers.

### Vorbedingungen

- Der Customer existiert.
- Der interne Benutzer ist authentifiziert, aktiv und berechtigt.

### Hauptablauf

1. Ein interner Benutzer öffnet den Contract-Bereich eines Customers.
2. Das System lädt die zugehörigen Contracts.
3. Das System zeigt die Contracts an.

### Nachbedingungen

- Die Contracts des Customers sind sichtbar.

---

## Use Case 18: Contract-Details anzeigen

### Ziel

Ein interner Benutzer soll einen Contract mit seinen zugehörigen Informationen und Dokumenten anzeigen können.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein interner Benutzer öffnet einen bestehenden Contract.

### Vorbedingungen

- Der Contract existiert.
- Der interne Benutzer ist authentifiziert, aktiv und berechtigt.

### Hauptablauf

1. Ein interner Benutzer öffnet einen Contract.
2. Das System lädt die Contract-Daten.
3. Das System lädt die zugehörigen Dokumente oder Medien.
4. Das System zeigt die Informationen an.

### Nachbedingungen

- Die Contract-Details sind sichtbar.

---

## Use Case 19: Contract bearbeiten

### Ziel

Ein Contract soll fachlich korrekt geändert werden können.

### Beteiligte Akteure

- Contract Manager
- System

### Auslöser

Ein interner Benutzer möchte einen bestehenden Contract ändern.

### Vorbedingungen

- Der Contract existiert.
- Der interne Benutzer ist authentifiziert, aktiv und berechtigt.
- Die geänderten Daten sind fachlich zulässig.

### Hauptablauf

1. Ein Contract Manager öffnet einen bestehenden Contract.
2. Der Benutzer ändert die zulässigen Felder.
3. Das System prüft die Eingaben.
4. Das System speichert die Änderungen.
5. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Die Contract-Daten sind aktualisiert.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 20: Contract deaktivieren oder soft delete ausführen

### Ziel

Ein Contract soll fachlich korrekt deaktiviert oder soft deleted werden.

### Beteiligte Akteure

- Contract Manager
- System

### Auslöser

Ein interner Benutzer möchte einen bestehenden Contract deaktivieren oder soft deleten.

### Vorbedingungen

- Der Contract existiert.
- Der interne Benutzer ist authentifiziert, aktiv und berechtigt.
- Die fachlichen Voraussetzungen sind erfüllt.

### Hauptablauf

1. Ein Contract Manager wählt einen bestehenden Contract aus.
2. Der Benutzer startet die Deaktivierung oder das Soft Delete.
3. Das System prüft, ob der Vorgang fachlich zulässig ist.
4. Das System aktualisiert den Contract fachlich korrekt.
5. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Der Contract ist deaktiviert oder soft deleted, sofern die Voraussetzungen erfüllt waren.
- Historische Bezüge bleiben erhalten, soweit erforderlich.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 21: Dokument an Contract anhängen

### Ziel

Zu einem Contract soll ein Dokument oder Medium gespeichert werden können.

### Beteiligte Akteure

- Contract Manager
- System

### Auslöser

Ein interner Benutzer möchte ein Dokument an einen bestehenden Contract anhängen.

### Vorbedingungen

- Der Contract existiert.
- Der interne Benutzer ist authentifiziert, aktiv und berechtigt.
- Die Datei ist fachlich und technisch zulässig.

### Hauptablauf

1. Ein Contract Manager öffnet einen bestehenden Contract.
2. Der Benutzer lädt eine Datei hoch.
3. Das System prüft die Datei und den Contract.
4. Das System speichert die Datei als Medium.
5. Das System ordnet die Datei dem Contract zu.
6. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Das Dokument ist gespeichert.
- Das Dokument ist dem Contract zugeordnet.
- Fachlich relevante Änderungen sind protokolliert.

---

# Phase 4: Ticket-Bearbeitung

## Use Case 22: Ticket-Liste anzeigen

### Ziel

Ein interner Benutzer soll Tickets suchen, filtern und öffnen können.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein interner Benutzer öffnet die Ticket-Liste.

### Vorbedingungen

- Der interne Benutzer ist authentifiziert und aktiv.

### Hauptablauf

1. Ein interner Benutzer öffnet die Ticket-Liste.
2. Das System lädt die Tickets.
3. Der Benutzer kann suchen, filtern und ein Ticket öffnen.

### Nachbedingungen

- Die relevanten Tickets sind sichtbar.

---

## Use Case 23: Ticket-Details anzeigen

### Ziel

Ein interner Benutzer soll alle relevanten Informationen eines Tickets sehen können.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein interner Benutzer öffnet ein bestehendes Ticket.

### Vorbedingungen

- Das Ticket existiert.
- Der interne Benutzer ist authentifiziert und aktiv.

### Hauptablauf

1. Ein interner Benutzer öffnet ein Ticket.
2. Das System lädt Ticketdaten, Nachrichten, Customer-Bezug und optional den Contract-Kontext.
3. Das System zeigt die Informationen an.

### Nachbedingungen

- Die relevanten Ticket-Informationen sind sichtbar.

---

## Use Case 24: Ticket einem internen Benutzer zuweisen oder Zuweisung ändern

### Ziel

Ein Ticket soll einem internen Benutzer zugewiesen oder einem anderen internen Benutzer neu zugewiesen werden.

### Beteiligte Akteure

- Support Agent oder entsprechend berechtigter interner Benutzer
- System

### Auslöser

Ein interner Benutzer möchte ein Ticket einem Bearbeiter zuweisen oder eine bestehende Zuweisung ändern.

### Vorbedingungen

- Das Ticket existiert.
- Das Ticket ist nicht geschlossen.
- Der ausführende Benutzer ist authentifiziert, aktiv und berechtigt.
- Der ausgewählte interne Benutzer existiert, ist aktiv und ist als Bearbeiter zulässig.

### Hauptablauf

1. Ein interner Benutzer öffnet ein bestehendes Ticket.
2. Der Benutzer wählt einen Bearbeiter oder ändert die bestehende Zuweisung.
3. Das System prüft, ob das Ticket noch bearbeitet werden darf.
4. Das System prüft, ob der ausgewählte interne Benutzer aktiv und als Bearbeiter zulässig ist.
5. Das System speichert die neue Zuweisung.
6. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Das Ticket ist entweder genau einem aktiven internen Benutzer zugewiesen oder unassigned.
- Die Zuweisung ist aktualisiert.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 25: Ticket-Status ändern

### Ziel

Der Status eines bestehenden Tickets soll fachlich korrekt geändert werden.

### Beteiligte Akteure

- Support Agent
- System

### Auslöser

Ein interner Benutzer möchte den Status eines bestehenden Tickets ändern.

### Vorbedingungen

- Das Ticket existiert.
- Der Benutzer ist authentifiziert, aktiv und berechtigt.
- Der gewünschte neue Status ist fachlich zulässig.

### Hauptablauf

1. Ein interner Benutzer öffnet ein bestehendes Ticket.
2. Der Benutzer wählt einen neuen Status.
3. Das System prüft den aktuellen Status.
4. Das System prüft, ob der gewünschte Statuswechsel fachlich erlaubt ist.
5. Das System aktualisiert den Status.
6. Das System speichert einen Eintrag in der Statushistorie.
7. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Das Ticket besitzt den neuen fachlich gültigen Status.
- Die Statusänderung ist in der Historie gespeichert.
- Fachlich relevante Änderungen und Aktionen sind protokolliert.

---

## Use Case 26: Ticket als resolved markieren

### Ziel

Ein Ticket soll als fachlich gelöst markiert werden.

### Beteiligte Akteure

- Support Agent
- System

### Auslöser

Ein interner Benutzer möchte ein bestehendes Ticket als gelöst markieren.

### Vorbedingungen

- Das Ticket existiert.
- Der Statuswechsel nach `resolved` ist fachlich erlaubt.
- Der Benutzer ist authentifiziert, aktiv und berechtigt.

### Hauptablauf

1. Ein interner Benutzer öffnet ein bestehendes Ticket.
2. Der Benutzer markiert das Ticket als gelöst.
3. Das System prüft, ob der Übergang erlaubt ist.
4. Das System setzt den Status auf `resolved`.
5. Das System speichert die Statusänderung in der Historie.
6. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Das Ticket hat den Status `resolved`.
- Die Historie ist aktualisiert.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 27: Ticket schließen

### Ziel

Ein Ticket soll endgültig geschlossen werden.

### Beteiligte Akteure

- Support Agent
- System

### Auslöser

Ein interner Benutzer möchte ein bestehendes Ticket schließen.

### Vorbedingungen

- Das Ticket existiert.
- Der Statuswechsel nach `closed` ist fachlich erlaubt.
- Der Benutzer ist authentifiziert, aktiv und berechtigt.

### Hauptablauf

1. Ein interner Benutzer öffnet ein bestehendes Ticket.
2. Der Benutzer startet das Schließen des Tickets.
3. Das System prüft, ob der Übergang erlaubt ist.
4. Das System setzt den Status auf `closed`.
5. Das System speichert die Statusänderung in der Historie.
6. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Das Ticket hat den Status `closed`.
- Die Historie ist aktualisiert.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 28: Kategorie eines Tickets setzen oder ändern

### Ziel

Ein Ticket soll fachlich richtig kategorisiert werden.

### Beteiligte Akteure

- Support Agent
- System

### Auslöser

Ein interner Benutzer möchte die Kategorie eines bestehenden Tickets setzen oder ändern.

### Vorbedingungen

- Das Ticket existiert.
- Die gewünschte Kategorie existiert und ist aktiv.
- Der Benutzer ist authentifiziert, aktiv und berechtigt.

### Hauptablauf

1. Ein interner Benutzer öffnet ein bestehendes Ticket.
2. Der Benutzer wählt eine Kategorie.
3. Das System prüft die Kategorie.
4. Das System speichert die Änderung.
5. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Das Ticket ist genau einer gültigen Kategorie zugeordnet.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 29: Priorität eines Tickets setzen oder ändern

### Ziel

Die Priorität eines Tickets soll gesetzt oder geändert werden.

### Beteiligte Akteure

- Support Agent
- System

### Auslöser

Ein interner Benutzer möchte die Priorität eines bestehenden Tickets setzen oder ändern.

### Vorbedingungen

- Das Ticket existiert.
- Der Benutzer ist authentifiziert, aktiv und berechtigt.
- Die gewünschte Priorität ist zulässig.

### Hauptablauf

1. Ein interner Benutzer öffnet ein bestehendes Ticket.
2. Der Benutzer wählt eine Priorität.
3. Das System prüft den neuen Wert.
4. Das System speichert die Priorität.
5. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Die Priorität ist aktualisiert.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 30: Contract-Kontext eines Tickets setzen oder ändern

### Ziel

Ein Ticket soll einem fachlich passenden Contract zugeordnet oder die bestehende Contract-Zuordnung geändert werden können.

### Beteiligte Akteure

- Support Agent oder Contract Manager
- System

### Auslöser

Ein interner Benutzer möchte den Contract-Kontext eines bestehenden Tickets setzen oder ändern.

### Vorbedingungen

- Das Ticket existiert.
- Der gewünschte Contract existiert.
- Der Benutzer ist authentifiziert, aktiv und berechtigt.
- Der Contract gehört demselben Customer wie das Ticket.

### Hauptablauf

1. Ein interner Benutzer öffnet ein bestehendes Ticket.
2. Der Benutzer wählt einen Contract oder entfernt die bestehende Contract-Zuordnung.
3. Das System prüft, ob der ausgewählte Contract zum Customer des Tickets gehört.
4. Das System speichert die Änderung.
5. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Das Ticket ist fachlich korrekt einem Contract zugeordnet oder ohne Contract gespeichert.
- Fachlich relevante Änderungen sind protokolliert.

---

# Phase 5: Kategorien, Medien, Audit und Konsistenz

## Use Case 31: Kategorie anlegen oder ändern

### Ziel

Eine Kategorie soll angelegt oder geändert werden.

### Beteiligte Akteure

- entsprechend berechtigter interner Benutzer
- System

### Auslöser

Ein interner Benutzer möchte eine Kategorie anlegen oder ändern.

### Vorbedingungen

- Der Benutzer ist authentifiziert, aktiv und berechtigt.
- Der Kategoriename ist fachlich zulässig.

### Hauptablauf

1. Ein interner Benutzer startet die Anlage oder Änderung einer Kategorie.
2. Der Benutzer erfasst oder ändert den Kategorienamen.
3. Das System prüft die Eingaben.
4. Das System speichert die Kategorie.
5. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Die Kategorie ist gespeichert oder aktualisiert.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 32: Kategorie deaktivieren oder soft delete ausführen

### Ziel

Eine Kategorie soll deaktiviert oder soft deleted werden.

### Beteiligte Akteure

- entsprechend berechtigter interner Benutzer
- System

### Auslöser

Ein interner Benutzer möchte eine Kategorie deaktivieren oder soft deleten.

### Vorbedingungen

- Die Kategorie existiert.
- Der Benutzer ist authentifiziert, aktiv und berechtigt.

### Hauptablauf

1. Ein interner Benutzer wählt eine bestehende Kategorie aus.
2. Der Benutzer startet die Deaktivierung oder das Soft Delete.
3. Das System aktualisiert die Kategorie fachlich korrekt.
4. Bestehende Tickets behalten ihre historische Zuordnung.
5. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Die Kategorie ist deaktiviert oder soft deleted.
- Bestehende Tickets behalten ihre historische Kategorie.
- Neue Tickets dürfen dieser Kategorie nicht mehr zugeordnet werden.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 33: Datei an Nachricht, Ticket oder Contract anhängen

### Ziel

Eine Datei soll an ein geeignetes Fachobjekt angehängt werden.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein interner Benutzer möchte eine Datei hochladen und einem Fachobjekt zuordnen.

### Vorbedingungen

- Das Zielobjekt existiert.
- Der Benutzer ist authentifiziert, aktiv und berechtigt.
- Die Datei ist fachlich und technisch zulässig.

### Hauptablauf

1. Ein interner Benutzer wählt ein Zielobjekt aus.
2. Der Benutzer lädt eine Datei hoch.
3. Das System prüft Datei und Zielobjekt.
4. Das System speichert die Datei.
5. Das System ordnet die Datei dem Zielobjekt zu.
6. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Die Datei ist gespeichert.
- Die Datei ist dem Zielobjekt zugeordnet.
- Fachlich relevante Änderungen sind protokolliert.

---

## Use Case 34: Audit-Eintrag erzeugen

### Ziel

Fachlich relevante Änderungen sollen als Audit-Einträge protokolliert werden.

### Beteiligte Akteure

- System
- optional interner Benutzer
- optional AI-Agent oder Systemprozess

### Auslöser

Im System erfolgt eine fachlich relevante Änderung.

### Vorbedingungen

- Es liegt eine fachlich relevante Aktion vor.
- Der Ausführungskontext ist eindeutig bestimmbar.

### Hauptablauf

1. Das System erkennt eine auditpflichtige fachliche Änderung.
2. Das System bestimmt den Ausführungskontext.
3. Das System erfasst Aktion, betroffenes Fachobjekt, Objekt-ID und Änderungszeitpunkt.
4. Das System speichert bei Bedarf alte und neue Werte.
5. Das System legt den Audit-Eintrag an.

### Nachbedingungen

- Der Audit-Eintrag ist gespeichert.
- Der Ausführungskontext ist eindeutig dokumentiert.

---

## Use Case 35: Historische Daten bei Soft Delete erhalten

### Ziel

Beim Soft Delete fachlicher Objekte sollen historische Informationen erhalten bleiben.

### Beteiligte Akteure

- System
- optional interner Benutzer

### Auslöser

Ein fachliches Objekt wird deaktiviert oder soft deleted.

### Vorbedingungen

- Das betroffene Objekt existiert.
- Die fachlichen Regeln für Soft Delete sind erfüllt.

### Hauptablauf

1. Das System startet einen fachlichen Soft-Delete-Vorgang.
2. Das System prüft, welche abhängigen Objekte mitsynchronisiert werden müssen.
3. Das System prüft, welche historischen Objekte erhalten bleiben müssen.
4. Das System führt den Soft Delete fachlich korrekt aus.
5. Das System erhält historische Tickets, Nachrichten, Contract-Dokumente, Audit-Logs und weitere relevante Daten.
6. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Der Soft Delete ist fachlich korrekt durchgeführt.
- Historische Informationen bleiben erhalten.
- Es entstehen keine fachlich inkonsistenten Zustände.

---

# Phase 6: Authentifizierung, Profil und Benutzerverwaltung

## Use Case 36: Login

### Ziel

Ein interner Benutzer meldet sich am System an.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein Benutzer startet den Login-Vorgang.

### Vorbedingungen

- Das Benutzerkonto existiert.
- Das Benutzerkonto ist aktiv.
- Gültige Anmeldedaten werden eingegeben.

### Hauptablauf

1. Der Benutzer gibt seine Anmeldedaten ein.
2. Das System prüft die Anmeldedaten.
3. Das System authentifiziert den Benutzer.
4. Das System erstellt die Sitzung oder das Zugriffstoken.
5. Das System protokolliert den Login fachlich nachvollziehbar.

### Nachbedingungen

- Der Benutzer ist authentifiziert.
- Der Benutzer kann entsprechend seiner Rollen und Berechtigungen geschützte Funktionen nutzen.

---

## Use Case 37: Logout

### Ziel

Ein authentifizierter interner Benutzer meldet sich vom System ab.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein authentifizierter Benutzer startet den Logout-Vorgang.

### Vorbedingungen

- Der Benutzer ist aktuell authentifiziert.

### Hauptablauf

1. Der Benutzer startet den Logout.
2. Das System beendet die Sitzung oder macht das Zugriffstoken ungültig.
3. Das System protokolliert den Logout fachlich nachvollziehbar.

### Nachbedingungen

- Der Benutzer ist nicht mehr authentifiziert.

---

## Use Case 38: Eigenes Profil anzeigen

### Ziel

Ein authentifizierter interner Benutzer sieht die eigenen Profildaten.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein authentifizierter Benutzer öffnet sein Profil.

### Vorbedingungen

- Der Benutzer ist authentifiziert.

### Hauptablauf

1. Der Benutzer ruft das eigene Profil auf.
2. Das System lädt die zugehörigen Profildaten.
3. Das System zeigt die Profildaten an.

### Nachbedingungen

- Die aktuellen Profildaten des Benutzers sind sichtbar.

---

## Use Case 39: Eigenes Profil bearbeiten

### Ziel

Ein authentifizierter interner Benutzer ändert die eigenen Profildaten.

### Beteiligte Akteure

- interner Benutzer
- System

### Auslöser

Ein authentifizierter Benutzer möchte das eigene Profil ändern.

### Vorbedingungen

- Der Benutzer ist authentifiziert.
- Die neuen Profildaten sind fachlich zulässig.

### Hauptablauf

1. Der Benutzer öffnet das eigene Profil.
2. Der Benutzer ändert die erlaubten Felder.
3. Das System prüft die Eingaben.
4. Das System speichert die Änderungen.
5. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Die eigenen Profildaten sind aktualisiert.
- Die Änderung ist fachlich nachvollziehbar protokolliert.

---

## Use Case 40: Internen Benutzer durch Admin anlegen

### Ziel

Ein authentifizierter Admin legt ein neues internes Benutzerkonto an.

### Beteiligte Akteure

- Admin
- System

### Auslöser

Ein Admin startet die Anlage eines neuen internen Benutzers.

### Vorbedingungen

- Der Admin ist authentifiziert.
- Der Admin besitzt die erforderlichen Rechte.
- Die eingegebenen Benutzerdaten sind gültig.

### Hauptablauf

1. Ein Admin öffnet die Benutzerverwaltung.
2. Der Admin erfasst die Daten des neuen internen Benutzers.
3. Das System prüft die Eingaben.
4. Das System legt den Benutzer an.
5. Das System weist die vorgesehenen Rollen zu.
6. Das System erstellt bei Bedarf den zugehörigen Actor.
7. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Das neue Benutzerkonto ist gespeichert.
- Die vorgesehenen Rollen sind zugewiesen.
- Der neue Benutzer kann nach Aktivierung und Authentifizierung das System nutzen.

---

## Use Case 41: Liste interner Benutzer anzeigen

### Ziel

Ein authentifizierter Admin sieht bestehende interne Benutzerkonten.

### Beteiligte Akteure

- Admin
- System

### Auslöser

Ein Admin öffnet die Benutzerverwaltung.

### Vorbedingungen

- Der Admin ist authentifiziert.
- Der Admin besitzt die erforderlichen Rechte.

### Hauptablauf

1. Der Admin öffnet die Benutzerverwaltung.
2. Das System lädt die Liste der Benutzerkonten.
3. Das System zeigt die Liste an.

### Nachbedingungen

- Die vorhandenen Benutzerkonten sind sichtbar.

---

## Use Case 42: Internen Benutzer durch Admin bearbeiten

### Ziel

Ein authentifizierter Admin ändert die Daten eines bestehenden internen Benutzers.

### Beteiligte Akteure

- Admin
- System

### Auslöser

Ein Admin möchte einen bestehenden internen Benutzer ändern.

### Vorbedingungen

- Der Admin ist authentifiziert.
- Der Admin besitzt die erforderlichen Rechte.
- Der betroffene Benutzer existiert.

### Hauptablauf

1. Der Admin wählt einen bestehenden Benutzer aus.
2. Der Admin ändert die zulässigen Felder und bei Bedarf Rollen.
3. Das System prüft die Eingaben.
4. Das System speichert die Änderungen.
5. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Die Benutzerdaten sind aktualisiert.
- Rollenänderungen sind gespeichert.
- Die Änderung ist nachvollziehbar protokolliert.

---

## Use Case 43: Internen Benutzer durch Admin deaktivieren oder soft delete ausführen

### Ziel

Ein authentifizierter Admin deaktiviert oder soft deleted ein internes Benutzerkonto.

### Beteiligte Akteure

- Admin
- System

### Auslöser

Ein Admin möchte einen internen Benutzer deaktivieren oder soft deleten.

### Vorbedingungen

- Der Admin ist authentifiziert.
- Der Admin besitzt die erforderlichen Rechte.
- Der betroffene Benutzer existiert.

### Hauptablauf

1. Der Admin wählt einen bestehenden Benutzer aus.
2. Der Admin startet die Deaktivierung oder das Soft Delete.
3. Das System prüft die fachlichen Voraussetzungen.
4. Das System deaktiviert oder soft deleted den Benutzer.
5. Das System behandelt Actor, Rollenbezug und Ticket-Zuweisungen fachlich konsistent.
6. Das System protokolliert die fachlich relevanten Vorgänge.

### Nachbedingungen

- Das Benutzerkonto ist deaktiviert oder soft deleted.
- Abhängige Objekte sind fachlich konsistent behandelt.
- Die Änderung ist nachvollziehbar protokolliert.
