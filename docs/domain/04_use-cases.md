# Use Cases — Smart Support Desk System

## Zweck

Diese Datei bündelt die fachlich ableitbaren Use Cases des Smart Support Desk Systems auf Basis von:

- `01_miniworld.md`
- `02_business-rules.md`
- `03_er.md`

Sie deckt nur dokumentierte fachliche Vorgänge ab. Es werden keine zusätzlichen Regeln, Rollen, Status oder Fachobjekte erfunden.

---

## Fachliche Leitplanken

- Interne fachliche Funktionen dürfen nur durch authentifizierte interne Benutzer ausgeführt werden.
- Autorisierung erfolgt rollen- und fachkontextbezogen.
- Hard Delete ist für fachliche Hauptdaten im normalen Fachbetrieb verboten.
- Fachlich zusammenhängende Änderungen müssen atomar ausgeführt werden, wenn sonst ein inkonsistenter Zwischenzustand entstehen würde.
- Fachlich relevante Vorgänge sind auditpflichtig.
- Historische Informationen müssen erhalten bleiben.

---

# Inbound Domain

## UC-01 Eingehende Anfrage automatisch zuordnen und fachlich verarbeiten

**Ziel**  
Eine eingehende Kundenanfrage wird, sofern fachlich sicher möglich, einem bestehenden Customer zugeordnet und als neues oder fortgeführtes Ticket verarbeitet.

**Primäre Akteure**  
Systemprozess

**Beteiligte Entitäten**  
Inbound-Prüffall, Customer, Contact, Contract, Ticket, Nachricht, Actor, Medium, Audit-Log

**Vorbedingungen**
- Eine eingehende Nachricht über einen zulässigen Kanal liegt vor.
- Die Nachricht enthält ausreichend Informationen für eine sichere Zuordnung oder fachlich vertretbare Neuanlage.

**Trigger**  
Eine neue externe Nachricht geht per `email`, `phone`, `whatsapp`, `web` oder `external` ein.

**Hauptablauf**
1. Das System erfasst Kanal, Absenderinformationen, Inhalt und mögliche Anhänge.
2. Das System prüft, ob eine `customer_number` enthalten ist.
3. Falls ja, prüft das System, ob genau ein aktiver Customer sicher gefunden wird.
4. Das System prüft den erkannten Contact gegen den gefundenen oder anderweitig bestimmten Customer.
5. Das System erkennt optional einen fachlich sicher bestimmbaren Contract-Kontext des Customers.
6. Das System prüft, ob die Kommunikation ein bestehendes offenes Ticket fortführt.
7. Falls ja, wird dem Ticket eine neue Nachricht hinzugefügt.
8. Falls nein, wird ein neues Ticket mit Customer, Kategorie, Kanal und optionalem Contract angelegt.
9. Anhänge werden gespeichert und dem richtigen fachlichen Kontext zugeordnet.
10. Der Vorgang wird fachlich nachvollziehbar protokolliert.

**Alternativen / Ausnahmen**
- Ist keine sichere Customer-Zuordnung möglich, wird stattdessen UC-02 ausgelöst.
- Sind `customer_number` und Contact widersprüchlich, wird stattdessen UC-02 ausgelöst.
- Ist kein Contract sicher erkennbar, bleibt das Ticket ohne Contract-Zuordnung.

**Nachbedingungen**
- Die eingehende Nachricht ist fachlich verarbeitet.
- Ein bestehendes Ticket wurde fortgeführt oder ein neues Ticket wurde erstellt.
- Anhänge sind gespeichert.
- Audit-Einträge sind erzeugt, soweit fachlich erforderlich.

---

## UC-02 Unklaren Inbound-Prüffall manuell entscheiden

**Ziel**  
Eine nicht sicher automatisch zuordenbare eingehende Anfrage wird kontrolliert durch einen berechtigten internen Benutzer geprüft und entschieden.

**Primäre Akteure**  
Inbound Reviewer

**Beteiligte Entitäten**  
Inbound-Prüffall, Customer, Contact, Contract, Ticket, Nachricht, Actor, Audit-Log

**Vorbedingungen**
- Ein Inbound-Prüffall existiert im System.
- Der ausführende interne Benutzer ist authentifiziert und fachlich berechtigt.

**Trigger**  
Ein Inbound Reviewer öffnet einen offenen Inbound-Prüffall.

**Hauptablauf**
1. Der Inbound Reviewer sichtet Kanal, Absenderdaten, fachliche Hinweise, Inhalt und mögliche Referenzen wie `customer_number` oder `contract_number`.
2. Der Inbound Reviewer entscheidet, ob die Anfrage einem bestehenden Customer zugeordnet werden kann.
3. Falls kein passender Customer existiert und die Informationen ausreichen, wird ein neuer Customer mit Contact angelegt.
4. Optional wird ein Contract-Kontext gesetzt, sofern dieser fachlich sicher ist.
5. Der Inbound Reviewer entscheidet, ob ein bestehendes offenes Ticket fortgeführt oder ein neues Ticket angelegt wird.
6. Die Anfrage wird als Ticket-Nachricht übernommen.
7. Der Inbound-Prüffall wird als entschieden markiert und die Entscheidung protokolliert.

**Alternativen / Ausnahmen**
- Reicht die Informationslage nicht aus, bleibt weitere Klärung erforderlich.
- Eine blinde Zuordnung trotz Konflikt ist unzulässig.

**Nachbedingungen**
- Der Inbound-Prüffall ist nachvollziehbar entschieden oder bleibt zur weiteren Klärung bestehen.
- Die fachlich getroffene Zuordnung ist protokolliert.

---

# Messages Domain

## UC-03 Öffentliche Nachricht in Ticket erfassen oder senden

**Ziel**  
Innerhalb eines Tickets wird eine öffentliche Nachricht zwischen internem Benutzer und Customer dokumentiert oder an den Customer gesendet.

**Primäre Akteure**  
Support Agent

**Sekundäre Akteure**  
Customer

**Beteiligte Entitäten**  
Ticket, Ticket-Nachricht, Actor, Contact, Medium, Audit-Log

**Vorbedingungen**
- Das Ticket existiert und ist nicht `closed`.
- Der interne Benutzer ist authentifiziert und berechtigt.
- Für ausgehende Antworten ist ein geeigneter aktiver Contact verfügbar.

**Trigger**  
Ein Support Agent verfasst eine öffentliche Nachricht im Ticket.

**Hauptablauf**
1. Der Support Agent öffnet ein bestehendes Ticket.
2. Der Support Agent erstellt eine Nachricht vom Typ `public`.
3. Das System verwendet den Actor des internen Benutzers als Autor.
4. Optional werden Anhänge hochgeladen und der Nachricht zugeordnet.
5. Für Antworten an den Customer wählt das System einen geeigneten Contact des Customers.
6. Die Nachricht wird gespeichert.
7. Der Vorgang wird auditiert.

**Alternativen / Ausnahmen**
- Für `closed` Tickets darf keine neue Nachricht erstellt werden.
- Ein deaktivierter oder soft deleted Contact darf nicht als Zustellziel verwendet werden.
- Automatisierte Antworten dürfen nur an verifizierte oder fachlich freigegebene Contacts gesendet werden.

**Nachbedingungen**
- Die öffentliche Nachricht ist historisch im Ticket enthalten.
- Zugehörige Medien sind gespeichert.

---

## UC-04 Interne Notiz im Ticket erfassen

**Ziel**  
Ein interner Benutzer dokumentiert interne Informationen oder Abstimmungen innerhalb eines Tickets.

**Primäre Akteure**  
Support Agent

**Beteiligte Entitäten**  
Ticket, Ticket-Nachricht, Actor, Medium, Audit-Log

**Vorbedingungen**
- Das Ticket existiert und ist nicht `closed`.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein Support Agent fügt eine interne Notiz hinzu.

**Hauptablauf**
1. Der Support Agent öffnet ein Ticket.
2. Er erstellt eine Nachricht vom Typ `internal`.
3. Das System prüft, dass der zugehörige Actor ein interner Benutzer ist.
4. Optional werden Anhänge zur Nachricht gespeichert.
5. Die interne Nachricht wird historisch im Ticket abgelegt.
6. Der Vorgang wird auditiert.

**Alternativen / Ausnahmen**
- Ein Customer darf niemals Autor einer internen Nachricht sein.
- Für `closed` Tickets dürfen keine neuen Nachrichten angelegt werden.

**Nachbedingungen**
- Die interne Notiz ist historisch im Ticket dokumentiert.

---

# Customers Domain

## UC-05 Customer anlegen

**Ziel**  
Ein neuer Customer mit eindeutiger `customer_number` wird fachlich korrekt angelegt.

**Primäre Akteure**  
Interner Benutzer mit fachlicher Berechtigung

**Beteiligte Entitäten**  
Customer, Contact, Actor, Audit-Log

**Vorbedingungen**
- Der interne Benutzer ist authentifiziert und berechtigt.
- Eine eindeutige `customer_number` liegt vor.
- Mindestens ein aktiver Contact kann angelegt werden, sofern der Customer aktiv ist.

**Trigger**  
Ein berechtigter interner Benutzer startet die Neuanlage eines Customers.

**Hauptablauf**
1. Der Benutzer erfasst die Customer-Stammdaten.
2. Das System prüft die Eindeutigkeit der `customer_number`.
3. Das System legt den Customer an.
4. Das System legt mindestens einen zugehörigen Contact an.
5. Das System markiert den ersten aktiven Contact standardmäßig als primär, sofern keine andere fachliche Regel greift.
6. Das System erzeugt oder synchronisiert den zugehörigen Actor.
7. Der Vorgang wird atomar ausgeführt und auditiert.

**Alternativen / Ausnahmen**
- Existiert die `customer_number` bereits, wird die Anlage abgelehnt.
- Reicht die Contact-Information nicht aus, darf kein aktiver Customer ohne erforderlichen aktiven Contact verbleiben.

**Nachbedingungen**
- Ein neuer Customer mit mindestens einem aktiven Contact existiert.
- Der Actor ist synchron vorhanden.

---

## UC-06 Customer anzeigen

**Ziel**  
Ein interner Benutzer sieht die fachlich relevanten Stammdaten eines Customers.

**Primäre Akteure**  
Interner Benutzer mit fachlicher Berechtigung

**Beteiligte Entitäten**  
Customer, Contacts, Contracts, Tickets

**Vorbedingungen**
- Der interne Benutzer ist authentifiziert und berechtigt.
- Der Customer existiert.

**Trigger**  
Ein Benutzer öffnet die Detailansicht eines Customers.

**Hauptablauf**
1. Das System lädt Customer-Stammdaten.
2. Das System zeigt zugehörige Contacts.
3. Das System zeigt zugehörige Contracts.
4. Das System zeigt zugehörige Tickets bzw. Verweise darauf.

**Nachbedingungen**
- Die Customer-Daten sind für die fachliche Bearbeitung sichtbar.

---

## UC-07 Customer ändern

**Ziel**  
Ein bestehender Customer wird fachlich korrekt aktualisiert.

**Primäre Akteure**  
Interner Benutzer mit fachlicher Berechtigung

**Beteiligte Entitäten**  
Customer, Audit-Log

**Vorbedingungen**
- Der interne Benutzer ist authentifiziert und berechtigt.
- Der Customer existiert.

**Trigger**  
Ein Benutzer speichert Änderungen an einem Customer.

**Hauptablauf**
1. Der Benutzer ändert fachlich erlaubte Customer-Felder.
2. Das System validiert die Änderungen.
3. Das System speichert die Änderungen.
4. Der Vorgang wird auditiert.

**Alternativen / Ausnahmen**
- Eine Änderung, die die Eindeutigkeit der `customer_number` verletzt, ist unzulässig.

**Nachbedingungen**
- Die aktualisierten Customer-Daten sind gespeichert und nachvollziehbar protokolliert.

---

## UC-08 Customer deaktivieren oder soft löschen

**Ziel**  
Ein Customer wird logisch entfernt oder deaktiviert, ohne Historie zu verlieren.

**Primäre Akteure**  
Interner Benutzer mit fachlicher Berechtigung

**Beteiligte Entitäten**  
Customer, Contacts, Actor, Contracts, Audit-Log

**Vorbedingungen**
- Der interne Benutzer ist authentifiziert und berechtigt.
- Der Customer existiert.

**Trigger**  
Ein berechtigter Benutzer deaktiviert oder soft deleted einen Customer.

**Hauptablauf**
1. Das System markiert den Customer als deaktiviert oder soft deleted.
2. Das System behandelt abhängige Contacts fachlich konsistent mit.
3. Das System synchronisiert den gebundenen Actor fachlich konsistent.
4. Zugehörige Contracts werden fachlich konsistent behandelt.
5. Historische Tickets, Nachrichten und Audit-Daten bleiben erhalten.
6. Der Vorgang wird atomar ausgeführt und auditiert.

**Alternativen / Ausnahmen**
- Ein inkonsistenter Zwischenzustand ist unzulässig.

**Nachbedingungen**
- Der Customer ist logisch entfernt oder deaktiviert.
- Abhängige Objekte mit gebundenem Lebenszyklus sind konsistent behandelt.

---

## UC-09 Customer reaktivieren

**Ziel**  
Ein zuvor deaktivierter oder soft deleted Customer wird fachlich konsistent reaktiviert.

**Primäre Akteure**  
Interner Benutzer mit fachlicher Berechtigung

**Beteiligte Entitäten**  
Customer, Contacts, Actor, Contracts, Audit-Log

**Vorbedingungen**
- Der Customer ist deaktiviert oder soft deleted.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein berechtigter Benutzer startet die Reaktivierung.

**Hauptablauf**
1. Das System reaktiviert den Customer.
2. Das System reaktiviert zugehörige Contacts, sofern keine fachliche Regel dagegenspricht.
3. Das System reaktiviert den gebundenen Actor fachlich konsistent.
4. Zugehörige Contracts werden fachlich konsistent behandelt oder geprüft.
5. Der Vorgang wird atomar ausgeführt und auditiert.

**Nachbedingungen**
- Der Customer ist wieder aktiv.
- Gebundene Objekte sind konsistent reaktiviert, soweit erlaubt.

---

## UC-10 Customer zusammenführen (Merge)

**Ziel**  
Mehrere Customer-Datensätze, die dieselbe fachliche Identität repräsentieren, werden zu genau einem aktiven Ziel-Customer zusammengeführt.

**Primäre Akteure**  
Interner Benutzer mit fachlicher Berechtigung

**Beteiligte Entitäten**  
Customer, Contact, Contract, Ticket, Actor, Audit-Log

**Vorbedingungen**
- Es wurden mehrere Customer-Datensätze als dieselbe fachliche Identität erkannt.
- Ein Ziel-Customer ist fachlich festgelegt.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein berechtigter Benutzer startet einen Merge-Vorgang.

**Hauptablauf**
1. Das System bestimmt den aktiven Ziel-Customer.
2. Aktive Contacts der Quell-Customer werden auf den Ziel-Customer übertragen oder fachlich konsistent behandelt.
3. Tickets und Contracts werden auf den Ziel-Customer übertragen oder konsistent behandelt.
4. Die übrigen Customer-Datensätze werden soft deleted oder fachlich als zusammengeführt markiert.
5. Der Vorgang wird atomar ausgeführt.
6. Der Merge wird nachvollziehbar protokolliert.

**Alternativen / Ausnahmen**
- Hard Delete der Quell-Customer ist unzulässig.
- Ein Merge darf keinen fachlich ungültigen Zwischenzustand erzeugen.

**Nachbedingungen**
- Genau ein Ziel-Customer bleibt aktiv.
- Referenzierende Datensätze sind konsistent behandelt.

---

# Contacts Domain

## UC-11 Contact zu Customer anlegen

**Ziel**  
Ein neuer Contact wird einem bestehenden Customer fachlich korrekt zugeordnet.

**Primäre Akteure**  
Interner Benutzer mit fachlicher Berechtigung

**Beteiligte Entitäten**  
Contact, Customer, Audit-Log

**Vorbedingungen**
- Der Customer existiert.
- Der interne Benutzer ist authentifiziert und berechtigt.
- Der Contact-Wert ist innerhalb seines Typs nicht bereits aktiv einem anderen Customer zugeordnet.

**Trigger**  
Ein berechtigter Benutzer fügt einem Customer einen Contact hinzu.

**Hauptablauf**
1. Der Benutzer erfasst Typ und Wert des Contacts.
2. Das System prüft den Contact-Typ gegen die zulässigen Werte.
3. Das System prüft die Eindeutigkeit des Contact-Werts innerhalb des Typs.
4. Das System ordnet den Contact dem Customer zu.
5. Falls es der erste aktive Contact ist, setzt das System ihn als primär.
6. Der Vorgang wird auditiert.

**Nachbedingungen**
- Der Contact ist dem Customer zugeordnet.

---

## UC-12 Contact ändern

**Ziel**  
Ein bestehender Contact eines Customers wird aktualisiert.

**Primäre Akteure**  
Interner Benutzer mit fachlicher Berechtigung

**Beteiligte Entitäten**  
Contact, Audit-Log

**Vorbedingungen**
- Der Contact existiert.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein berechtigter Benutzer speichert Änderungen am Contact.

**Hauptablauf**
1. Der Benutzer ändert fachlich erlaubte Felder des Contacts.
2. Das System validiert Typ, Wert und fachliche Konsistenz.
3. Das System speichert die Änderungen.
4. Der Vorgang wird auditiert.

**Nachbedingungen**
- Der Contact ist aktualisiert.

---

## UC-13 Primären Contact setzen

**Ziel**  
Für einen Customer wird genau ein aktiver primärer Contact festgelegt.

**Primäre Akteure**  
Interner Benutzer mit fachlicher Berechtigung

**Beteiligte Entitäten**  
Contact, Customer, Audit-Log

**Vorbedingungen**
- Der Customer besitzt mindestens einen aktiven Contact.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein Benutzer setzt einen Contact als primär.

**Hauptablauf**
1. Das System markiert den ausgewählten aktiven Contact als primär.
2. Das System entfernt die Primär-Markierung von einem zuvor primären Contact desselben Customers.
3. Der Vorgang wird fachlich konsistent gespeichert.
4. Der Vorgang wird auditiert.

**Nachbedingungen**
- Genau ein aktiver Contact des Customers ist als primär markiert.

---

## UC-14 Contact verifizieren

**Ziel**  
Ein Contact wird als bestätigter und zustellfähiger Kommunikationsweg markiert.

**Primäre Akteure**  
Interner Benutzer mit fachlicher Berechtigung

**Beteiligte Entitäten**  
Contact, Audit-Log

**Vorbedingungen**
- Der Contact existiert.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein berechtigter Benutzer markiert einen Contact als verifiziert.

**Hauptablauf**
1. Das System setzt `is_verified` auf wahr.
2. Der Vorgang wird gespeichert.
3. Der Vorgang wird auditiert.

**Nachbedingungen**
- Der Contact ist als verifiziert gekennzeichnet.

---

## UC-15 Contact deaktivieren, soft löschen oder reaktivieren

**Ziel**  
Ein Contact wird fachlich konsistent deaktiviert, soft deleted oder reaktiviert.

**Primäre Akteure**  
Interner Benutzer mit fachlicher Berechtigung

**Beteiligte Entitäten**  
Contact, Customer, Audit-Log

**Vorbedingungen**
- Der Contact existiert.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein berechtigter Benutzer startet die Statusänderung des Contacts.

**Hauptablauf**
1. Das System prüft, ob beim Deaktivieren oder Soft Delete mindestens ein weiterer aktiver Contact des Customers verbleibt, sofern keine erlaubte Gesamtoperation vorliegt.
2. Falls zulässig, wird der Contact deaktiviert oder soft deleted.
3. Bei Reaktivierung wird der Contact wieder aktiv gesetzt, sofern keine fachliche Regel dagegenspricht.
4. Der Vorgang wird gespeichert und auditiert.

**Alternativen / Ausnahmen**
- Der letzte aktive Contact eines aktiven Customers darf nicht unabhängig deaktiviert oder gelöscht werden.

**Nachbedingungen**
- Der Contact ist fachlich konsistent geändert.

---

# Contracts Domain

## UC-16 Contract zu Customer anlegen

**Ziel**  
Ein neuer Contract wird für einen Customer angelegt.

**Primäre Akteure**  
Contract Manager

**Beteiligte Entitäten**  
Contract, Customer, Audit-Log

**Vorbedingungen**
- Der Customer existiert.
- Der interne Benutzer ist authentifiziert und berechtigt.
- Die `contract_number` ist im relevanten fachlichen Geltungsbereich eindeutig.

**Trigger**  
Ein Contract Manager startet die Anlage eines Contracts.

**Hauptablauf**
1. Der Benutzer erfasst Contract-Daten wie Nummer, Typ, Status und Gültigkeitszeitraum.
2. Das System validiert die Zuordnung zum Customer.
3. Das System legt den Contract an.
4. Der Vorgang wird auditiert.

**Nachbedingungen**
- Der Contract ist dem Customer zugeordnet.

---

## UC-17 Contract anzeigen

**Ziel**  
Ein interner Benutzer sieht die Details eines Contracts einschließlich seines Customer-Kontexts und möglicher Dokumente.

**Primäre Akteure**  
Contract Manager

**Beteiligte Entitäten**  
Contract, Customer, Medium

**Vorbedingungen**
- Der Contract existiert.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein Benutzer öffnet die Contract-Detailansicht.

**Hauptablauf**
1. Das System lädt Contract-Stammdaten.
2. Das System zeigt Customer-Bezug.
3. Das System zeigt zugehörige Medien oder Dokumente.

**Nachbedingungen**
- Der Contract-Kontext ist fachlich sichtbar.

---

## UC-18 Contract ändern

**Ziel**  
Ein bestehender Contract wird aktualisiert.

**Primäre Akteure**  
Contract Manager

**Beteiligte Entitäten**  
Contract, Audit-Log

**Vorbedingungen**
- Der Contract existiert.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein Contract Manager speichert Änderungen am Contract.

**Hauptablauf**
1. Der Benutzer ändert fachlich erlaubte Contract-Felder.
2. Das System validiert die Änderungen.
3. Das System speichert die Änderungen.
4. Der Vorgang wird auditiert.

**Nachbedingungen**
- Der Contract ist aktualisiert.

---

## UC-19 Contract deaktivieren oder soft löschen

**Ziel**  
Ein Contract wird logisch entfernt oder deaktiviert, ohne seine historische Bedeutung zu verlieren.

**Primäre Akteure**  
Contract Manager

**Beteiligte Entitäten**  
Contract, Audit-Log

**Vorbedingungen**
- Der Contract existiert.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein Contract Manager deaktiviert oder soft deleted einen Contract.

**Hauptablauf**
1. Das System markiert den Contract als deaktiviert oder soft deleted.
2. Historische Ticket-Bezüge bleiben erhalten.
3. Zugehörige Medien bleiben historisch zugeordnet.
4. Der Vorgang wird auditiert.

**Nachbedingungen**
- Der Contract ist logisch entfernt oder deaktiviert.

---

## UC-20 Contract reaktivieren

**Ziel**  
Ein zuvor deaktivierter oder soft deleted Contract wird fachlich konsistent reaktiviert.

**Primäre Akteure**  
Contract Manager

**Beteiligte Entitäten**  
Contract, Audit-Log

**Vorbedingungen**
- Der Contract ist deaktiviert oder soft deleted.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein Contract Manager reaktiviert einen Contract.

**Hauptablauf**
1. Das System reaktiviert den Contract.
2. Das System prüft oder behandelt abhängige fachliche Kontexte konsistent.
3. Der Vorgang wird auditiert.

**Nachbedingungen**
- Der Contract ist wieder aktiv, soweit fachlich zulässig.

---

## UC-21 Contract-Dokument verwalten

**Ziel**  
Fachlich relevante Dokumente oder andere Medien werden einem Contract historisch zugeordnet.

**Primäre Akteure**  
Contract Manager

**Beteiligte Entitäten**  
Contract, Medium, Audit-Log

**Vorbedingungen**
- Der Contract existiert.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein Contract Manager fügt ein Dokument hinzu, ändert es fachlich oder entfernt es fachlich.

**Hauptablauf**
1. Der Benutzer wählt einen bestehenden Contract.
2. Das System speichert das hochgeladene Medium beim Contract.
3. Metadaten wie Originalname, MIME-Typ und Größe werden gespeichert.
4. Änderungen oder fachliches Entfernen werden nachvollziehbar protokolliert.
5. Der Vorgang wird auditiert.

**Nachbedingungen**
- Das Dokument ist historisch dem Contract zugeordnet.

---

# Tickets Domain

## UC-22 Ticket anlegen

**Ziel**  
Ein neuer Supportfall wird für einen Customer angelegt.

**Primäre Akteure**  
Support Agent

**Beteiligte Entitäten**  
Ticket, Customer, Contract, Kategorie, Audit-Log

**Vorbedingungen**
- Der interne Benutzer ist authentifiziert und berechtigt.
- Ein Customer existiert.
- Eine aktive Kategorie ist auswählbar.

**Trigger**  
Ein Support Agent legt manuell ein neues Ticket an.

**Hauptablauf**
1. Der Benutzer erfasst Subject, Description, Kanal und Kategorie.
2. Das System ordnet das Ticket einem Customer zu.
3. Optional setzt das System oder der Benutzer einen Contract desselben Customers.
4. Das System speichert das Ticket mit initialem Status.
5. Der Vorgang wird auditiert.

**Alternativen / Ausnahmen**
- Ein referenzierter Contract muss demselben Customer gehören.
- Deaktivierte oder soft deleted Kategorien dürfen nicht für neue Tickets verwendet werden.

**Nachbedingungen**
- Das Ticket existiert als neuer Supportfall.

---

## UC-23 Ticket anzeigen

**Ziel**  
Ein interner Benutzer sieht einen vollständigen Ticketfall mit Kopf, Status, Zuordnung und Nachrichtenhistorie.

**Primäre Akteure**  
Support Agent

**Beteiligte Entitäten**  
Ticket, Ticket-Nachrichten, Customer, Contract, Kategorie

**Vorbedingungen**
- Das Ticket existiert.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein Benutzer öffnet die Ticket-Detailansicht.

**Hauptablauf**
1. Das System zeigt Subject, Description, Status, Priorität, Kanal, Kategorie und Bearbeiter.
2. Das System zeigt Customer- und optional Contract-Kontext.
3. Das System zeigt die Kommunikationshistorie des Tickets.

**Nachbedingungen**
- Der Ticketfall ist vollständig fachlich einsehbar.

---

## UC-24 Ticket bearbeiten

**Ziel**  
Ein bestehendes Ticket wird fachlich aktualisiert.

**Primäre Akteure**  
Support Agent

**Beteiligte Entitäten**  
Ticket, Kategorie, Contract, Audit-Log

**Vorbedingungen**
- Das Ticket existiert und ist nicht `closed`.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein Support Agent speichert Änderungen am Ticket.

**Hauptablauf**
1. Der Benutzer ändert fachlich erlaubte Felder des Tickets.
2. Das System validiert Kategorie, Kanal und optionalen Contract-Kontext.
3. Das System speichert die Änderungen.
4. Der Vorgang wird auditiert.

**Alternativen / Ausnahmen**
- Für `closed` Tickets sind Änderungen unzulässig, sofern keine Reopen-Regel definiert ist.

**Nachbedingungen**
- Das Ticket ist aktualisiert und die Änderung ist nachvollziehbar protokolliert.

---

## UC-25 Ticket Bearbeiter zuweisen oder ändern

**Ziel**  
Ein Ticket wird einem internen Benutzer zugewiesen oder neu zugewiesen.

**Primäre Akteure**  
Support Agent oder fachlich berechtigter interner Benutzer

**Beteiligte Entitäten**  
Ticket, Internal User, Audit-Log

**Vorbedingungen**
- Das Ticket existiert und ist nicht `closed`.
- Der ausführende Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein berechtigter Benutzer wählt einen Bearbeiter für ein Ticket.

**Hauptablauf**
1. Das System prüft die Berechtigung des ausführenden Benutzers.
2. Das System prüft, dass der neue Bearbeiter aktiv und nicht soft deleted ist.
3. Das System speichert die neue Zuweisung oder entfernt die bestehende Zuweisung.
4. Der Vorgang wird auditiert.

**Alternativen / Ausnahmen**
- Ein deaktivierter oder soft deleted interner Benutzer darf nicht zugewiesen werden.

**Nachbedingungen**
- Das Ticket ist korrekt zugewiesen oder unassigned.

---

## UC-26 Ticket-Status ändern

**Ziel**  
Der Bearbeitungsstatus eines Tickets wird entlang der dokumentierten Transitionen geändert.

**Primäre Akteure**  
Support Agent

**Beteiligte Entitäten**  
Ticket, Ticket-Status-Historie, Audit-Log

**Vorbedingungen**
- Das Ticket existiert.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein Support Agent löst einen Statuswechsel aus.

**Hauptablauf**
1. Das System liest den aktuellen Ticket-Status.
2. Das System prüft, ob die gewünschte Transition erlaubt ist.
3. Das System aktualisiert den Ticket-Status.
4. Das System speichert einen Eintrag in der Ticket-Status-Historie.
5. Der Vorgang wird auditiert.

**Erlaubte Transitionen**
- `open -> in_progress`
- `in_progress -> waiting_for_customer`
- `waiting_for_customer -> in_progress`
- `in_progress -> resolved`
- `resolved -> closed`

**Alternativen / Ausnahmen**
- Nicht dokumentierte Transitionen sind unzulässig.
- `waiting_for_customer` darf nur gesetzt werden, wenn ohne Rückmeldung des Customers keine sinnvolle Fortsetzung möglich ist.

**Nachbedingungen**
- Der neue Status ist gespeichert und historisch dokumentiert.

---

## UC-27 Ticket auf Rückmeldung des Customers warten lassen

**Ziel**  
Ein Ticket wird in den Status `waiting_for_customer` überführt, wenn zur Fortsetzung Informationen des Customers fehlen.

**Primäre Akteure**  
Support Agent

**Beteiligte Entitäten**  
Ticket, Ticket-Status-Historie, Audit-Log

**Vorbedingungen**
- Das Ticket befindet sich in `in_progress`.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein Support Agent markiert das Ticket als wartend auf den Customer.

**Hauptablauf**
1. Das System prüft, ob ohne Rückmeldung des Customers keine sinnvolle Bearbeitung möglich ist.
2. Das System setzt den Status auf `waiting_for_customer`.
3. Das System protokolliert die Statusänderung.
4. Der Vorgang wird auditiert.

**Nachbedingungen**
- Das Ticket wartet formal auf Rückmeldung des Customers.

---

## UC-28 Ticket nach Customer-Rückmeldung fortsetzen

**Ziel**  
Ein Ticket im Status `waiting_for_customer` wird nach neuer relevanter Rückmeldung wieder aktiv bearbeitet.

**Primäre Akteure**  
Systemprozess oder Support Agent

**Beteiligte Entitäten**  
Ticket, Ticket-Nachricht, Ticket-Status-Historie, Audit-Log

**Vorbedingungen**
- Das Ticket befindet sich in `waiting_for_customer`.
- Eine neue relevante Customer-Rückmeldung liegt vor.

**Trigger**  
Eine neue relevante Customer-Nachricht wird dem Ticket zugeordnet.

**Hauptablauf**
1. Das System erkennt die neue relevante Rückmeldung des Customers.
2. Das System fügt die neue Nachricht zum Ticket hinzu.
3. Das System überführt das Ticket wieder nach `in_progress`.
4. Das System protokolliert die Statusänderung.
5. Der Vorgang wird auditiert.

**Nachbedingungen**
- Das Ticket befindet sich wieder in aktiver Bearbeitung.

---

## UC-29 Ticket lösen

**Ziel**  
Ein fachlich gelöster Supportfall wird in den Status `resolved` überführt.

**Primäre Akteure**  
Support Agent

**Beteiligte Entitäten**  
Ticket, Ticket-Status-Historie, Audit-Log

**Vorbedingungen**
- Das Ticket befindet sich in `in_progress`.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein Support Agent markiert den Fall als gelöst.

**Hauptablauf**
1. Das System prüft die zulässige Transition.
2. Das System setzt den Status auf `resolved`.
3. Das System schreibt die Status-Historie.
4. Der Vorgang wird auditiert.

**Nachbedingungen**
- Das Ticket ist fachlich gelöst, aber noch nicht endgültig abgeschlossen.

---

## UC-30 Ticket schließen

**Ziel**  
Ein bereits gelöster Supportfall wird endgültig abgeschlossen.

**Primäre Akteure**  
Support Agent

**Beteiligte Entitäten**  
Ticket, Ticket-Status-Historie, Audit-Log

**Vorbedingungen**
- Das Ticket befindet sich in `resolved`.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein Support Agent schließt das Ticket.

**Hauptablauf**
1. Das System prüft die zulässige Transition von `resolved` nach `closed`.
2. Das System setzt den Status auf `closed`.
3. Das System protokolliert die Statusänderung.
4. Der Vorgang wird auditiert.

**Alternativen / Ausnahmen**
- Nach dem Schließen dürfen keine neuen Nachrichten erstellt und keine weitere Bearbeitung vorgenommen werden, sofern keine explizite Reopen-Regel definiert ist.

**Nachbedingungen**
- Das Ticket ist endgültig abgeschlossen.

---

# Categories Domain

## UC-31 Kategorie anlegen

**Ziel**  
Eine fachliche Kategorie zur Einordnung von Tickets wird angelegt.

**Primäre Akteure**  
Interner Benutzer mit fachlicher Berechtigung

**Beteiligte Entitäten**  
Kategorie

**Vorbedingungen**
- Der interne Benutzer ist authentifiziert und berechtigt.
- Der Kategoriename ist noch nicht vergeben.

**Trigger**  
Ein berechtigter Benutzer legt eine neue Kategorie an.

**Hauptablauf**
1. Der Benutzer erfasst den Kategorienamen.
2. Das System prüft die Eindeutigkeit.
3. Das System speichert die Kategorie als aktiv.

**Nachbedingungen**
- Eine neue Kategorie steht für neue Tickets zur Verfügung.

---

## UC-32 Kategorie deaktivieren oder soft löschen

**Ziel**  
Eine Kategorie wird für neue Tickets gesperrt, ohne historische Ticketzuordnungen zu verlieren.

**Primäre Akteure**  
Interner Benutzer mit fachlicher Berechtigung

**Beteiligte Entitäten**  
Kategorie

**Vorbedingungen**
- Die Kategorie existiert.
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein berechtigter Benutzer deaktiviert oder soft deleted eine Kategorie.

**Hauptablauf**
1. Das System markiert die Kategorie als deaktiviert oder soft deleted.
2. Bestehende Ticket-Zuordnungen bleiben historisch unverändert erhalten.
3. Die Kategorie steht nicht mehr für neue Tickets zur Verfügung.

**Nachbedingungen**
- Historische Tickets behalten ihre Kategorie.
- Neue Tickets können die Kategorie nicht mehr verwenden.

---

# Media Domain

## UC-33 Medium an fachlichem Objekt speichern

**Ziel**  
Ein fachlich relevantes Medium wird an einem vorgesehenen Fachobjekt gespeichert.

**Primäre Akteure**  
Systemprozess oder berechtigter interner Benutzer

**Beteiligte Entitäten**  
Medium, Ticket, Nachricht, Contract, Audit-Log

**Vorbedingungen**
- Das Zielobjekt unterstützt fachlich Medien.
- Der ausführende Benutzer ist bei manueller Aktion authentifiziert und berechtigt.

**Trigger**  
Ein Anhang wird hochgeladen oder eine eingehende Nachricht enthält eine Datei.

**Hauptablauf**
1. Das System nimmt die Datei entgegen.
2. Das System speichert Dateimetadaten und technischen Speicherbezug.
3. Das System ordnet das Medium polymorph dem vorgesehenen Fachobjekt zu.
4. Soweit fachlich relevant, wird der Vorgang auditiert.

**Nachbedingungen**
- Das Medium ist historisch dem Fachobjekt zugeordnet.

---

# System Domain

## UC-34 Audit-Logs einsehen

**Ziel**  
Ein berechtigter interner Benutzer kann fachlich relevante Änderungen nachvollziehen.

**Primäre Akteure**  
Interner Benutzer mit fachlicher Berechtigung

**Beteiligte Entitäten**  
Audit-Log

**Vorbedingungen**
- Der interne Benutzer ist authentifiziert und berechtigt.

**Trigger**  
Ein berechtigter Benutzer öffnet eine Audit-Ansicht.

**Hauptablauf**
1. Das System zeigt Audit-Einträge mit Aktion, Kontext, betroffenem Fachobjekt, Objekt-ID und Zeitstempel.
2. Soweit vorhanden, zeigt das System alte und neue Werte.
3. Historische Einträge bleiben unveränderbar.

**Nachbedingungen**
- Fachlich relevante Änderungen sind nachvollziehbar.

---

## UC-35 Login- und Logout-Vorgänge protokollieren

**Ziel**  
Anmelde- und Abmeldevorgänge werden als fachlich relevante Ereignisse dokumentiert.

**Primäre Akteure**  
Systemprozess

**Sekundäre Akteure**  
Interner Benutzer

**Beteiligte Entitäten**  
Audit-Log, Benutzerkonto, Personal Access Token

**Vorbedingungen**
- Ein interner Benutzer meldet sich an oder ab.

**Trigger**  
Login oder Logout wird ausgelöst.

**Hauptablauf**
1. Das System verarbeitet Authentifizierung oder Abmeldung.
2. Das System dokumentiert den Vorgang im Audit-Log.
3. Bei tokenbasierter API-Authentifizierung werden die zugehörigen Token fachlich konsistent behandelt.

**Nachbedingungen**
- Login und Logout sind nachvollziehbar dokumentiert.

---

# Auth Domain

## UC-36 Login

**Ziel**  
Ein interner Benutzer authentifiziert sich erfolgreich am System.

**Primäre Akteure**  
Interner Benutzer

**Beteiligte Entitäten**  
Benutzerkonto, Personal Access Token, Audit-Log

**Vorbedingungen**
- Ein internes Benutzerkonto existiert.
- Das Benutzerkonto ist aktiv.

**Trigger**  
Ein interner Benutzer sendet seine Anmeldedaten.

**Hauptablauf**
1. Der Benutzer gibt seine Zugangsdaten ein.
2. Das System prüft die Authentifizierung.
3. Das System gewährt Zugriff auf interne Funktionen.
4. Falls Token-basierte API-Authentifizierung verwendet wird, wird ein Token erzeugt oder verwendet.
5. Der Login wird auditiert.

**Alternativen / Ausnahmen**
- Nicht authentifizierte Benutzer dürfen keine internen Fachfunktionen ausführen.

**Nachbedingungen**
- Der interne Benutzer ist authentifiziert.

---

## UC-37 Logout

**Ziel**  
Ein authentifizierter interner Benutzer meldet sich vom System ab.

**Primäre Akteure**  
Interner Benutzer

**Beteiligte Entitäten**  
Benutzerkonto, Personal Access Token, Audit-Log

**Vorbedingungen**
- Ein interner Benutzer ist authentifiziert.

**Trigger**  
Der Benutzer löst den Logout aus.

**Hauptablauf**
1. Das System beendet die aktuelle Authentifizierung.
2. Falls verwendet, werden zugehörige Token fachlich konsistent entzogen oder beendet.
3. Der Logout wird auditiert.

**Nachbedingungen**
- Der interne Benutzer ist nicht mehr authentifiziert.

---

## UC-38 Eigenes Profil anzeigen

**Ziel**  
Ein authentifizierter interner Benutzer sieht seine eigenen Profildaten.

**Primäre Akteure**  
Interner Benutzer

**Beteiligte Entitäten**  
Benutzerkonto

**Vorbedingungen**
- Der Benutzer ist authentifiziert.

**Trigger**  
Der Benutzer öffnet sein eigenes Profil.

**Hauptablauf**
1. Das System lädt die Profildaten des angemeldeten Benutzers.
2. Das System zeigt die eigenen Benutzerinformationen an.

**Nachbedingungen**
- Das eigene Profil ist sichtbar.

---

## UC-39 Eigenes Profil ändern

**Ziel**  
Ein authentifizierter interner Benutzer aktualisiert seine eigenen Profildaten.

**Primäre Akteure**  
Interner Benutzer

**Beteiligte Entitäten**  
Benutzerkonto, Audit-Log

**Vorbedingungen**
- Der Benutzer ist authentifiziert.

**Trigger**  
Der Benutzer speichert Änderungen an seinem Profil.

**Hauptablauf**
1. Der Benutzer ändert fachlich erlaubte Profildaten.
2. Das System validiert die Änderungen.
3. Das System speichert die Änderungen.
4. Der Vorgang wird auditiert.

**Nachbedingungen**
- Das eigene Profil ist aktualisiert.

---

# Users Domain

## UC-40 Internes Benutzerkonto anlegen

**Ziel**  
Ein Admin legt ein neues internes Benutzerkonto an und weist fachlich passende Rollen zu.

**Primäre Akteure**  
Admin

**Beteiligte Entitäten**  
Internal User, Role, Internal User Role, Actor, Audit-Log

**Vorbedingungen**
- Der ausführende Benutzer ist authentifiziert und administrativ berechtigt.
- Benutzername und E-Mail sind eindeutig.

**Trigger**  
Ein Admin startet die Neuanlage eines internen Benutzers.

**Hauptablauf**
1. Der Admin erfasst Stammdaten des internen Benutzers.
2. Das System legt das Benutzerkonto an.
3. Das System weist eine oder mehrere Rollen zu.
4. Das System erzeugt oder synchronisiert den zugehörigen Actor.
5. Der Vorgang wird auditiert.

**Nachbedingungen**
- Ein neues internes Benutzerkonto existiert.

---

## UC-41 Internen Benutzer anzeigen oder Rollen einsehen

**Ziel**  
Ein Admin sieht Daten, Status und Rollen eines internen Benutzers.

**Primäre Akteure**  
Admin

**Beteiligte Entitäten**  
Internal User, Role

**Vorbedingungen**
- Der ausführende Benutzer ist authentifiziert und administrativ berechtigt.
- Das Benutzerkonto existiert.

**Trigger**  
Ein Admin öffnet die Detailansicht eines internen Benutzers.

**Hauptablauf**
1. Das System lädt Benutzerstammdaten.
2. Das System zeigt Aktivstatus und zugewiesene Rollen.

**Nachbedingungen**
- Das interne Benutzerkonto ist administrativ einsehbar.

---

## UC-42 Internen Benutzer ändern oder Rollen zuweisen

**Ziel**  
Ein Admin aktualisiert Benutzerstammdaten und Rollen eines internen Benutzers.

**Primäre Akteure**  
Admin

**Beteiligte Entitäten**  
Internal User, Role, Internal User Role, Audit-Log

**Vorbedingungen**
- Der ausführende Benutzer ist authentifiziert und administrativ berechtigt.
- Das Benutzerkonto existiert.

**Trigger**  
Ein Admin speichert Änderungen an einem internen Benutzerkonto.

**Hauptablauf**
1. Der Admin ändert fachlich erlaubte Benutzerdaten.
2. Das System passt Rollen oder Berechtigungszuordnungen an.
3. Das System speichert die Änderungen.
4. Der Vorgang wird auditiert.

**Nachbedingungen**
- Benutzerdaten und Rollen sind aktualisiert.

---

## UC-43 Internen Benutzer deaktivieren, soft löschen oder reaktivieren

**Ziel**  
Ein internes Benutzerkonto wird fachlich konsistent deaktiviert, soft gelöscht oder aus dem soft-gelöschten Zustand wiederhergestellt.

**Primäre Akteure**  
Admin

**Beteiligte Entitäten**  
Internal User, Actor, Ticket, Audit-Log

**Vorbedingungen**
- Der ausführende Benutzer ist authentifiziert und administrativ berechtigt.
- Das Benutzerkonto existiert.

**Trigger**  
Ein Admin ändert den Status eines internen Benutzers oder stellt ein soft-gelöschtes Benutzerkonto wieder her.

**Hauptablauf**
1. Das System deaktiviert den internen Benutzer, soft deleted ihn bzw. stellt ihn aus dem soft-gelöschten Zustand wieder her.
2. Das System synchronisiert den gebundenen Actor fachlich konsistent.
3. Bestehende Ticket-Zuweisungen werden aufgehoben oder neu vergeben, wenn der Benutzer nicht mehr aktiv ist.
4. Der Vorgang wird atomar ausgeführt, sofern erforderlich.
5. Der Vorgang wird auditiert.

**Nachbedingungen**
- Das Benutzerkonto und gebundene Objekte sind fachlich konsistent geändert.
- Ein zuvor soft-gelöschtes Benutzerkonto kann wieder aktiv sein, sofern fachlich zulässig.

---

# Abdeckung der dokumentierten Fachobjekte

Diese Use Cases decken die in den Dokumenten beschriebenen Hauptobjekte und Kernvorgänge ab:

- Customers
- Contacts
- Contracts
- Tickets
- Ticket-Nachrichten
- Kategorien
- Inbound-Prüffälle
- Actors
- Medien
- Audit-Logs
- interne Benutzerkonten und Rollen
- Authentifizierungsvorgänge

---

# Hinweise für die Weiterverwendung

Diese Datei eignet sich als Grundlage für:

- `docs/by-domain/*.md`
- `docs/by-use-case/ucXX_*.md`
- API-Contract-Ableitung
- Route-/FormRequest-/Controller-/Service-/Model-Zuschnitt
- Frontend-Screen-Zuschnitt
- Testableitung aus Vorbedingungen, Ablauf und Nachbedingungen

Bei Widersprüchen gelten weiterhin die Domain-Dateien als fachliche Wahrheit.
