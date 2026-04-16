# Screen Flows: Smart Support Desk System

## Zweck

Diese Datei beschreibt die fachlichen und UI-seitigen Abläufe der zentralen Frontend-Screens des Smart Support Desk Systems.

Die Screen Flows bilden die Brücke zwischen:

- Use Cases
- API-Contracts
- Vue-Screens
- Komponentenlogik
- Store-Aufrufen
- UI-Zuständen

Diese Datei beschreibt nicht die allgemeine Frontend-Architektur, sondern das konkrete Verhalten einzelner Screens aus Sicht des Benutzers und der UI.

## Hinweis zur Dokumentstruktur

Diese Datei ist die **Master-Datei** für die Screen Flows des Projekts.

Sie dient für:

- Gesamtüberblick
- Konsistenzprüfung
- Pflege der vollständigen Screen-Logik

Für fokussierte Frontend-Arbeit werden zusätzlich aufgeteilte Dateien verwendet:

- `docs/04_frontend/screen-flows/by-domain/`
- `docs/04_frontend/screen-flows/by-use-case/`

Wichtige Sonderregel:
Im Bereich `screen-flows/by-use-case/` werden Dateien **screen-orientiert** geschnitten. Wenn mehrere Backend-Use-Cases denselben Frontend-Screen betreffen, sollen sie in einer gemeinsamen Screen-Flow-Datei zusammengefasst werden.

Für normale Frontend-Sessions soll bevorzugt die kleinste passende Dokumenteinheit verwendet werden.

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

Die Screen Flows dürfen den fachlichen Regeln dieser Dokumente nicht widersprechen.

---

## Ziel der Screen Flows

Die Screen Flows sollen klar beschreiben:

- welcher Screen welchen fachlichen Zweck hat
- welchem Use Case der Screen zugeordnet ist
- welche Benutzeraktionen auf dem Screen möglich sind
- welche Store-Funktionen oder Backend-Endpunkte später relevant sind
- welche Erfolgs- und Fehlerzustände im UI sichtbar sein müssen
- welche Lade-, Leer- und Sperrzustände zu berücksichtigen sind
- welche Navigationsfolgen ein Vorgang im Frontend auslöst

---

## Rolle der Screen Flows im Projektworkflow

Die Screen Flows dienen im Projekt nicht nur als UI-Beschreibung, sondern auch als vorbereitende Grundlage für den verbindlichen Frontend-Workflow mit Claude Code, Stitch MCP und Google Labs stitch-skills.

Sie beschreiben den fachlichen Zweck eines Screens, die sichtbaren Hauptbereiche, Benutzeraktionen und UI-Zustände so, dass daraus kontrolliert eine UI-Struktur erzeugt werden kann.

Die Screen Flows sind damit eine fachlich gebundene Zwischenschicht zwischen Use Case, API-Contract, UI-Regeln und späterer UI-Erzeugung.

---

## Allgemeine Regeln für alle Screens

### 1. Jeder wichtige Screen hat einen fachlichen Zweck

Ein Screen darf nicht nur visuell existieren, sondern muss auf mindestens einen Use Case zurückführbar sein.

### 2. Nicht nur den Happy Path abbilden

Jeder Screen soll neben dem Erfolgsfall mindestens folgende Zustände mitdenken:

- loading
- error
- empty
- disabled
- success
- forbidden, wenn relevant
- not found, wenn relevant
- conflict, wenn relevant

### 3. Frontend erfindet keine Fachlogik

Die Regeln für Status, Rollen, Merge, Soft Delete, Reaktivierung, Contact-Auswahl, Contract-Zuordnung oder Inbound-Entscheidungen kommen nicht aus dem Screen selbst, sondern aus den fachlichen Dokumenten.

### 4. Screen-Flows sind UI-orientiert, aber fachlich gebunden

Ein Screen Flow beschreibt:

- was der Benutzer sieht
- was er tut
- wie die UI reagiert
- welche Backend-Folgen erwartet werden

Er ersetzt nicht die Use Cases oder API-Contracts.

### 5. Rollenabhängige Sichtbarkeit ersetzt keine Backend-Autorisierung

Ein Screen darf abhängig von Rolle oder Berechtigung Teile der Oberfläche ausblenden oder sperren.

Die endgültige fachliche Autorisierung erfolgt jedoch im Backend.

### 6. Screens gehen im Projekt durch den verbindlichen Stitch-Workflow

Die in dieser Datei beschriebenen Screens werden im Projekt nicht frei aus Intuition gebaut, sondern aus:

- Use Case
- API-Contract
- Screen Flow
- UI-Regeln

in den verbindlichen Workflow mit Claude Code, Stitch MCP und Google Labs stitch-skills überführt.

Die Datei dient damit als fachlich strukturierte Grundlage für die spätere UI-Erzeugung.

---

## Empfohlene Zuordnung von Screens zu Bereichen

Das Frontend umfasst hauptsächlich diese Screen-Bereiche:

1. Auth
2. eigenes Profil
3. interne Benutzerverwaltung
4. Tickets
5. Customers
6. Contacts
7. Contracts
8. Inbound Review Cases
9. Kategorien
10. Nachrichten und Medien

---

# Abschnitt A: Auth Screens

## Screen A1: Login Screen

### Fachlicher Zweck

Der Login Screen ermöglicht einem internen Benutzer die Anmeldung am System.

### Zugehöriger Use Case

- Use Case 36: Login

### Zugehöriger API-Contract

- `POST /api/login`

### Typischer Screen-Name

- `LoginPage.vue`

### Sichtbare Hauptbereiche

- Titel oder Branding
- Eingabefeld für E-Mail
- Eingabefeld für Passwort
- Login-Button
- Bereich für globale Fehlermeldung

### Benutzeraktionen

- E-Mail eingeben
- Passwort eingeben
- Login auslösen

### Erwartete Frontend-Logik

- Formular mit Vuelidate prüfen
- bei gültiger Eingabe `authStore.login(payload)` aufrufen
- bei Erfolg Auth-Zustand setzen
- Rolleninformationen laden oder aus Login-Response übernehmen
- anschließend in den geschützten Bereich navigieren

### UI-Zustände

#### Initial State

Leeres Formular, Login-Button verfügbar.

#### Loading State

Button deaktiviert, optional Spinner oder Ladeanzeige.

#### Success State

Benutzer wird weitergeleitet.

#### Validation Error State

Feldfehler werden direkt an den Inputs angezeigt.

#### Authentication Error State

Allgemeine Fehlermeldung für ungültige Zugangsdaten.

#### Forbidden State

Hinweis, dass das Konto deaktiviert oder nicht aktiv ist.

### Typische Navigation nach Erfolg

- Dashboard oder erste geschützte Standardseite

---

## Screen A2: Logout Flow

### Fachlicher Zweck

Der Logout ist meist kein eigener großer Screen, sondern ein UI-Flow aus Navigation oder Profilmenü heraus.

### Zugehöriger Use Case

- Use Case 37: Logout

### Zugehöriger API-Contract

- `POST /api/logout`

### Typische UI-Orte

- Topbar
- Profilmenü
- Sidebar

### Benutzeraktionen

- Logout im Menü anklicken

### Erwartete Frontend-Logik

- `authStore.logout()` aufrufen
- lokalen Auth-Zustand löschen
- zur Login-Seite navigieren

### UI-Zustände

#### Initial State

Logout-Eintrag oder Button sichtbar.

#### Loading State

Button oder Menüpunkt kurz gesperrt.

#### Success State

Navigation zur Login-Seite.

#### Error State

Wenn der Backend-Logout fehlschlägt, Benutzer lokal trotzdem als ausgeloggt behandeln, sofern das Sicherheitsmodell dies erlaubt.

---

# Abschnitt B: Profil Screens

## Screen B1: Eigenes Profil anzeigen

### Fachlicher Zweck

Ein interner Benutzer sieht seine eigenen Profildaten und Rollen.

### Zugehöriger Use Case

- Use Case 38: Eigenes Profil anzeigen

### Zugehöriger API-Contract

- `GET /api/me`

### Typischer Screen-Name

- `ProfilePage.vue`

### Sichtbare Hauptbereiche

- Seitentitel
- Name
- Benutzername
- E-Mail
- Aktivstatus
- Rollen
- Button zum Bearbeiten des Profils

### Benutzeraktionen

- Profildaten ansehen
- zur Bearbeitung wechseln

### Erwartete Frontend-Logik

- beim Öffnen `authStore.fetchMe()` aufrufen
- Daten anzeigen
- bei Fehlern passend reagieren

### UI-Zustände

#### Loading State

Skeleton oder Ladeanzeige für Profilinhalte.

#### Success State

Profildaten sichtbar.

#### Error State

Allgemeine Fehlermeldung und Retry-Möglichkeit.

#### Unauthenticated State

Zur Login-Seite weiterleiten.

---

## Screen B2: Eigenes Profil bearbeiten

### Fachlicher Zweck

Ein interner Benutzer ändert erlaubte eigene Profildaten.

### Zugehöriger Use Case

- Use Case 39: Eigenes Profil bearbeiten

### Zugehöriger API-Contract

- `PATCH /api/me`

### Typischer Screen-Name

- `ProfileEditPage.vue`
- oder Profilseite mit eingebettetem Edit-Modus

### Sichtbare Hauptbereiche

- Formularfelder für erlaubte Profildaten
- Speichern-Button
- Abbrechen-Button
- Bereich für Feldfehler
- Bereich für globale Konflikt- oder Erfolgsnachricht

### Benutzeraktionen

- Felder bearbeiten
- speichern
- abbrechen

### Erwartete Frontend-Logik

- Vuelidate für Felder
- `authStore.updateProfile(payload)` aufrufen
- bei Erfolg Daten aktualisieren
- bei Konflikten passende Meldung zeigen

### UI-Zustände

#### Initial State

Formular mit bestehenden Werten.

#### Loading State

Speichern-Button deaktiviert, optional Spinner.

#### Success State

Erfolgsmeldung und aktualisierte Daten sichtbar.

#### Validation Error State

Feldfehler an den Inputs.

#### Conflict State

Zum Beispiel Benutzername bereits vergeben.

#### Unauthenticated State

Zur Login-Seite weiterleiten.

---

# Abschnitt C: Interne Benutzerverwaltung Screens

## Screen C1: Liste interner Benutzer anzeigen

### Fachlicher Zweck

Ein berechtigter Benutzer sieht eine Liste interner Benutzerkonten.

### Zugehöriger Use Case

- Use Case 41: Liste interner Benutzer anzeigen

### Zugehöriger API-Contract

- `GET /api/admin/internal-users`

### Typischer Screen-Name

- `InternalUsersListPage.vue`

### Sichtbare Hauptbereiche

- Seitentitel
- Suchfeld
- Filter für Rolle oder Aktivstatus
- Tabelle oder Listenansicht
- Button „Neuen internen Benutzer anlegen“
- Aktionen pro Eintrag
- Pagination
- Empty State

### Benutzeraktionen

- suchen
- filtern
- paginieren
- Detail oder Bearbeiten öffnen
- Deaktivieren oder Reaktivieren starten
- neuen Benutzer anlegen

### Erwartete Frontend-Logik

- `internalUsersStore.fetchInternalUsers(params)` aufrufen
- Query-Parameter oder lokalen Filterzustand verwalten
- Fehler und leere Zustände behandeln

### UI-Zustände

#### Loading State

Tabellen-Skeleton oder Spinner.

#### Success State

Liste sichtbar.

#### Empty State

Keine Benutzer vorhanden oder kein Treffer.

#### Forbidden State

Zugriff verweigert, weil keine passende Berechtigung vorliegt.

#### Error State

Allgemeiner Fehler mit Retry.

---

## Screen C2: Internen Benutzer anlegen

### Fachlicher Zweck

Ein berechtigter Benutzer erstellt ein neues internes Benutzerkonto inklusive Rollen.

### Zugehöriger Use Case

- Use Case 40: Internen Benutzer durch Admin anlegen

### Zugehöriger API-Contract

- `POST /api/admin/internal-users`

### Typischer Screen-Name

- `InternalUserCreatePage.vue`

### Sichtbare Hauptbereiche

- Seitentitel
- Formular für Name, Benutzername, E-Mail, Passwort, Rollen, Aktivstatus
- Speichern-Button
- Abbrechen-Button

### Benutzeraktionen

- Formular ausfüllen
- Rollen auswählen
- speichern
- abbrechen

### Erwartete Frontend-Logik

- Vuelidate-Regeln anwenden
- `internalUsersStore.createInternalUser(payload)` aufrufen
- bei Erfolg weiterleiten oder Meldung anzeigen

### UI-Zustände

#### Loading State

Speichern-Button deaktiviert.

#### Success State

Weiterleitung zur Liste oder Detailansicht.

#### Validation Error State

Feldfehler anzeigen.

#### Conflict State

E-Mail oder Benutzername bereits vergeben.

#### Forbidden State

Keine Berechtigung.

---

## Screen C3: Internen Benutzer bearbeiten

### Fachlicher Zweck

Ein berechtigter Benutzer ändert die Daten und Rollen eines bestehenden internen Benutzers.

### Zugehöriger Use Case

- Use Case 42: Internen Benutzer durch Admin bearbeiten

### Zugehörige API-Contracts

- `GET /api/admin/internal-users/{id}`
- `PATCH /api/admin/internal-users/{id}`

### Typischer Screen-Name

- `InternalUserEditPage.vue`

### Sichtbare Hauptbereiche

- Formular mit vorhandenen Daten
- Rollenauswahl
- speichern
- abbrechen

### Benutzeraktionen

- Daten ändern
- Rollen ändern
- speichern
- zurückgehen

### Erwartete Frontend-Logik

- `internalUsersStore.fetchInternalUser(id)`
- `internalUsersStore.updateInternalUser(id, payload)`

### UI-Zustände

#### Loading State

Formulardaten laden.

#### Not Found State

Benutzer existiert nicht.

#### Validation Error State

Feldfehler anzeigen.

#### Conflict State

E-Mail oder Benutzername bereits vergeben.

#### Success State

Erfolgsmeldung und aktualisierte Daten.

#### Forbidden State

Keine Berechtigung.

---

## Screen C4: Internen Benutzer deaktivieren / reaktivieren / soft delete

### Fachlicher Zweck

Ein berechtigter Benutzer steuert den Lebenszyklus eines internen Benutzers.

### Zugehöriger Use Case

- Use Case 43: Internen Benutzer durch Admin deaktivieren oder soft delete ausführen

### Zugehörige API-Contracts

- `PATCH /api/admin/internal-users/{id}/deactivate`
- `PATCH /api/admin/internal-users/{id}/reactivate`
- `DELETE /api/admin/internal-users/{id}`

### Typische UI-Orte

- Aktionsspalte in der Liste
- Detailseite
- Confirm-Dialog

### Benutzeraktionen

- deaktivieren
- reaktivieren
- soft deleten
- Dialog bestätigen oder abbrechen

### Erwartete Frontend-Logik

- passende Store-Funktion aufrufen
- Liste oder Detailansicht nach Erfolg aktualisieren

### UI-Zustände

#### Confirm State

Bestätigungsdialog sichtbar.

#### Loading State

Aktion läuft.

#### Success State

Status aktualisiert.

#### Conflict State

Backend meldet fachlichen Konflikt.

#### Forbidden State

Keine Berechtigung.

---

# Abschnitt D: Ticket Screens

## Screen D1: Ticket-Liste anzeigen

### Fachlicher Zweck

Interne Benutzer sehen und filtern Tickets.

### Zugehörige Use Cases

- Use Case 22 als Einstieg
- Use Cases 24 bis 30 indirekt als Weiterbearbeitung

### Zugehöriger API-Contract

- `GET /api/tickets`

### Typischer Screen-Name

- `TicketsListPage.vue`

### Sichtbare Hauptbereiche

- Seitentitel
- Suchfeld
- Statusfilter
- Prioritätsfilter
- Kategoriefilter
- optional Filter für Bearbeiter
- optional Filter für Contract
- Tabelle oder Kartenansicht
- Pagination
- Empty State

### Benutzeraktionen

- suchen
- filtern
- Ticket öffnen
- optional neues Ticket anlegen

### Erwartete Frontend-Logik

- `ticketsStore.fetchTickets(params)`
- Filterzustand verwalten
- Listenansicht aktualisieren

### UI-Zustände

#### Loading State

Liste lädt.

#### Success State

Tickets sichtbar.

#### Empty State

Keine Treffer.

#### Error State

Fehlermeldung und Retry.

---

## Screen D2: Ticket-Detail anzeigen

### Fachlicher Zweck

Ein Benutzer sieht alle relevanten Informationen zu einem Ticket und arbeitet damit weiter.

### Zugehörige Use Cases

- Use Cases 23 bis 30
- Use Cases 3 und 4 in Verbindung mit Nachrichten

### Zugehörige API-Contracts

- `GET /api/tickets/{id}`
- zusätzliche Nachrichten-, Media- und Ticket-Aktions-Endpunkte

### Typischer Screen-Name

- `TicketDetailPage.vue`

### Sichtbare Hauptbereiche

- Ticketkopf mit Subject, Status, Priorität, Kanal, Kategorie
- Customer-Zuordnung
- optional Contract-Zuordnung
- Bearbeiter-Zuordnung
- Nachrichtenbereich
- interner Aktionsbereich
- öffentliche Antwort
- Statusaktionen
- Kategorie-, Prioritäts- und Contract-Aktionen

### Benutzeraktionen

- Ticket lesen
- Nachricht senden
- Status ändern
- zuweisen
- Kategorie ändern
- Priorität ändern
- Contract setzen oder entfernen
- Medien hochladen

### Erwartete Frontend-Logik

- `ticketsStore.fetchTicket(id)`
- `messagesStore.fetchMessages(ticketId)`
- zusätzliche Aktionen aus Stores aufrufen

### UI-Zustände

#### Loading State

Detaildaten und Nachrichten laden.

#### Not Found State

Ticket existiert nicht.

#### Closed State

Bestimmte Aktionen sind deaktiviert.

#### Error State

Fehler beim Laden.

#### Success State

Alle Daten sichtbar.

#### Forbidden State

Zugriff verweigert, wenn das Backend keine Berechtigung gibt.

---

## Screen D3: Ticket zuweisen oder Zuweisung ändern

### Fachlicher Zweck

Ein Ticket wird einem Bearbeiter zugewiesen oder neu zugewiesen.

### Zugehöriger Use Case

- Use Case 24: Ticket einem internen Benutzer zuweisen oder Zuweisung ändern

### Zugehöriger API-Contract

- `PATCH /api/tickets/{id}/assignment`

### Typische UI-Orte

- Ticket-Detailseite
- Dropdown oder Modal

### Benutzeraktionen

- Bearbeiter auswählen
- Zuweisung entfernen
- speichern

### Erwartete Frontend-Logik

- `ticketsStore.assignTicket(id, payload)`

### UI-Zustände

#### Loading State

Aktion läuft.

#### Success State

Neuer Bearbeiter sichtbar.

#### Conflict State

Zum Beispiel Ticket geschlossen oder Benutzer deaktiviert.

#### Validation Error State

Ungültige Eingabe.

---

## Screen D4: Ticket-Status ändern

### Fachlicher Zweck

Der Ticketstatus wird fachlich korrekt geändert.

### Zugehörige Use Cases

- Use Case 25
- Use Case 26
- Use Case 27

### Zugehörige API-Contracts

- `PATCH /api/tickets/{id}/status`
- `PATCH /api/tickets/{id}/resolve`
- `PATCH /api/tickets/{id}/close`

### Typische UI-Orte

- Ticket-Detailseite
- Status-Dropdown
- Aktionsbuttons

### Benutzeraktionen

- Status ändern
- resolved setzen
- schließen

### Erwartete Frontend-Logik

- `ticketsStore.changeStatus(id, payload)`
- `ticketsStore.resolveTicket(id)`
- `ticketsStore.closeTicket(id)`

### UI-Zustände

#### Loading State

Statusaktion läuft.

#### Success State

Neuer Status sichtbar.

#### Conflict State

Transition nicht erlaubt.

#### Closed State

weitere Bearbeitung gesperrt.

---

## Screen D5: Ticket-Kategorie ändern

### Fachlicher Zweck

Ein Ticket wird fachlich neu kategorisiert.

### Zugehöriger Use Case

- Use Case 28: Kategorie eines Tickets setzen oder ändern

### Zugehöriger API-Contract

- `PATCH /api/tickets/{id}/category`

### Typische UI-Orte

- Ticket-Detailseite
- Auswahlfeld oder Modal

### UI-Zustände

- Loading
- Success
- Conflict bei deaktivierter Kategorie
- Validation Error

---

## Screen D6: Ticket-Priorität ändern

### Fachlicher Zweck

Die Priorität eines Tickets wird geändert.

### Zugehöriger Use Case

- Use Case 29: Priorität eines Tickets setzen oder ändern

### Zugehöriger API-Contract

- `PATCH /api/tickets/{id}/priority`

### Typische UI-Orte

- Ticket-Detailseite
- Prioritäts-Dropdown

### UI-Zustände

- Loading
- Success
- Validation Error

---

## Screen D7: Contract-Kontext eines Tickets setzen oder ändern

### Fachlicher Zweck

Ein Ticket wird einem fachlich passenden Contract zugeordnet oder die Contract-Zuordnung wird entfernt.

### Zugehöriger Use Case

- Use Case 30: Contract-Kontext eines Tickets setzen oder ändern

### Zugehöriger API-Contract

- `PATCH /api/tickets/{id}/contract`

### Typische UI-Orte

- Ticket-Detailseite
- Auswahlfeld oder Modal

### Sichtbare Hauptbereiche

- aktuelle Contract-Zuordnung
- Auswahlliste passender Contracts des Customers
- Aktion zum Entfernen der Zuordnung

### Benutzeraktionen

- Contract auswählen
- Contract-Zuordnung entfernen
- speichern

### Erwartete Frontend-Logik

- `ticketsStore.setContract(id, payload)`
- verfügbare Contracts des Customers laden oder bereits im Detailkontext vorhalten

### UI-Zustände

#### Loading State

Aktion läuft.

#### Success State

Contract-Kontext aktualisiert.

#### Conflict State

Zum Beispiel Contract gehört nicht zum Customer oder Ticket ist geschlossen.

#### Empty State

Customer hat keine Contracts.

---

# Abschnitt E: Customer Screens

## Screen E1: Customer-Liste anzeigen

### Fachlicher Zweck

Interne Benutzer suchen und sehen Customers.

### Zugehörige Use Cases

- Use Cases 5 bis 10 als Einstieg in Customer-Verwaltung

### Zugehöriger API-Contract

- `GET /api/customers`

### Typischer Screen-Name

- `CustomersListPage.vue`

### Sichtbare Hauptbereiche

- Suche
- Filter aktiv/inaktiv
- optional Suche nach `customer_number`
- Liste oder Tabelle
- Aktionen
- Empty State

### UI-Zustände

- Loading
- Success
- Empty
- Error

---

## Screen E2: Customer-Detail anzeigen

### Fachlicher Zweck

Ein Benutzer sieht die Stammdaten und den Lebenszyklus eines Customers.

### Zugehörige Use Cases

- Use Cases 6 bis 15 indirekt
- Use Cases 16 bis 21 indirekt für Contract-Bereich

### Zugehörige API-Contracts

- `GET /api/customers/{id}`
- Contact- und Contract-bezogene Endpunkte zusätzlich

### Typischer Screen-Name

- `CustomerDetailPage.vue`

### Sichtbare Hauptbereiche

- Stammdaten
- `customer_number`
- Contacts
- Contracts
- Status
- Aktionen für Deaktivierung, Reaktivierung, Merge
- zugehörige Tickets optional

### UI-Zustände

- Loading
- Not Found
- Success
- Error

---

## Screen E3: Customer manuell anlegen

### Fachlicher Zweck

Ein interner Benutzer legt einen Customer manuell an.

### Zugehöriger Use Case

- Use Case 5: Customer manuell anlegen

### Zugehöriger API-Contract

- `POST /api/customers`

### Typischer Screen-Name

- `CustomerCreatePage.vue`

### Sichtbare Hauptbereiche

- Customer-Stammdatenformular
- Feld für `customer_number`
- erster Contact
- Speichern / Abbrechen

### UI-Zustände

- Loading
- Validation Error
- Conflict bei `customer_number` oder Contact-Kollision
- Success

---

## Screen E4: Customer bearbeiten

### Fachlicher Zweck

Ein interner Benutzer ändert einen bestehenden Customer.

### Zugehöriger Use Case

- Use Case 7: Customer bearbeiten

### Zugehörige API-Contracts

- `GET /api/customers/{id}`
- `PATCH /api/customers/{id}`

### Typischer Screen-Name

- `CustomerEditPage.vue`

### Sichtbare Hauptbereiche

- Customer-Stammdaten
- `customer_number`
- Speichern / Abbrechen

### UI-Zustände

- Loading
- Validation Error
- Conflict
- Success
- Not Found

---

## Screen E5: Customer deaktivieren / reaktivieren / soft delete

### Fachlicher Zweck

Der Customer-Lebenszyklus wird im UI gesteuert.

### Zugehörige Use Cases

- Use Case 8
- Use Case 9

### Zugehörige API-Contracts

- `PATCH /api/customers/{id}/deactivate`
- `PATCH /api/customers/{id}/reactivate`
- `DELETE /api/customers/{id}`

### Typische UI-Orte

- Customer-Detail
- Listenaktion
- Confirm-Dialog

### UI-Zustände

- Confirm
- Loading
- Success
- Conflict

---

## Screen E6: Customer-Merge

### Fachlicher Zweck

Mehrere Dubletten werden kontrolliert zusammengeführt.

### Zugehöriger Use Case

- Use Case 10: Customer-Dubletten zusammenführen

### Zugehöriger API-Contract

- `POST /api/customers/merge`

### Typische UI-Orte

- Customer-Detail
- spezieller Merge-Dialog
- Dubletten-Ansicht

### Sichtbare Hauptbereiche

- Auswahl Ziel-Customer
- Auswahl Quell-Customers
- Konflikthinweise
- Bestätigungsdialog

### UI-Zustände

- Loading
- Validation Error
- Conflict
- Success

---

# Abschnitt F: Contact Screens

## Screen F1: Contacts eines Customers anzeigen

### Fachlicher Zweck

Contacts eines Customers werden sichtbar und verwaltbar.

### Zugehörige Use Cases

- Use Cases 11 bis 15

### Zugehörige API-Contracts

- `GET /api/customers/{customerId}/contacts`
- weitere Contact-Endpunkte

### Typische UI-Orte

- Customer-Detailseite
- Contact-Sektion

### Sichtbare Hauptbereiche

- Contact-Liste
- Typ
- Wert
- primär
- verifiziert
- Aktionen

### UI-Zustände

- Loading
- Empty
- Success
- Error

---

## Screen F2: Contact anlegen

### Fachlicher Zweck

Ein neuer Contact wird für einen Customer erstellt.

### Zugehöriger Use Case

- Use Case 11: Customer-Contact anlegen

### Zugehöriger API-Contract

- `POST /api/customers/{customerId}/contacts`

### Typische UI-Orte

- Customer-Detail
- Modal oder Inline-Formular

### UI-Zustände

- Loading
- Validation Error
- Conflict
- Success

---

## Screen F3: Contact als primär setzen

### Fachlicher Zweck

Ein Contact wird als bevorzugter Contact markiert.

### Zugehöriger Use Case

- Use Case 12: Contact als primär setzen

### Zugehöriger API-Contract

- `PATCH /api/contacts/{id}/set-primary`

### Typische UI-Orte

- Contact-Liste
- Aktionsmenü
- Button

### UI-Zustände

- Loading
- Success
- Conflict

---

## Screen F4: Contact verifizieren

### Fachlicher Zweck

Ein Contact wird als bestätigt markiert.

### Zugehöriger Use Case

- Use Case 13: Contact verifizieren

### Zugehöriger API-Contract

- `PATCH /api/contacts/{id}/verify`

### Typische UI-Orte

- Contact-Liste
- Detail-Panel
- Badge-Aktion

### UI-Zustände

- Loading
- Success
- Conflict

---

## Screen F5: Contact deaktivieren / reaktivieren / soft delete

### Fachlicher Zweck

Der Lebenszyklus eines Contacts wird gesteuert.

### Zugehörige Use Cases

- Use Case 14
- Use Case 15

### Zugehörige API-Contracts

- `PATCH /api/contacts/{id}/deactivate`
- `PATCH /api/contacts/{id}/reactivate`
- `DELETE /api/contacts/{id}`

### Typische UI-Orte

- Contact-Liste
- Confirm-Dialog
- Aktionen pro Eintrag

### UI-Zustände

- Confirm
- Loading
- Conflict
- Success

---

# Abschnitt G: Contract Screens

## Screen G1: Contracts eines Customers anzeigen

### Fachlicher Zweck

Ein Benutzer sieht die Contracts eines Customers.

### Zugehöriger Use Case

- Use Case 17: Contracts eines Customers anzeigen

### Zugehöriger API-Contract

- `GET /api/customers/{customerId}/contracts`

### Typischer Screen-Name

- `CustomerContractsSection.vue`
- oder `ContractsListPage.vue` im Customer-Kontext

### Sichtbare Hauptbereiche

- Contract-Liste
- Contract-Name
- Contract-Nummer
- Typ
- Status
- Laufzeit
- Aktionen

### UI-Zustände

- Loading
- Empty
- Success
- Error

---

## Screen G2: Contract anlegen

### Fachlicher Zweck

Ein berechtigter Benutzer legt einen neuen Contract für einen Customer an.

### Zugehöriger Use Case

- Use Case 16: Contract für Customer anlegen

### Zugehöriger API-Contract

- `POST /api/customers/{customerId}/contracts`

### Typischer Screen-Name

- `ContractCreatePage.vue`
- oder Modal im Customer-Kontext

### Sichtbare Hauptbereiche

- Formular für Contract-Nummer, Name, Typ, Status, Laufzeit
- Speichern / Abbrechen

### UI-Zustände

- Loading
- Validation Error
- Conflict
- Success
- Forbidden

---

## Screen G3: Contract-Detail anzeigen

### Fachlicher Zweck

Ein Benutzer sieht einen Contract mit seinen Daten und zugehörigen Dokumenten.

### Zugehöriger Use Case

- Use Case 18: Contract-Details anzeigen

### Zugehörige API-Contracts

- `GET /api/contracts/{id}`
- `GET /api/contracts/{id}/media`

### Typischer Screen-Name

- `ContractDetailPage.vue`

### Sichtbare Hauptbereiche

- Contract-Stammdaten
- Customer-Bezug
- Status
- Laufzeit
- Typ
- Dokumente / Medien
- Aktionen

### UI-Zustände

- Loading
- Not Found
- Success
- Error
- Forbidden

---

## Screen G4: Contract bearbeiten

### Fachlicher Zweck

Ein berechtigter Benutzer ändert einen bestehenden Contract.

### Zugehöriger Use Case

- Use Case 19: Contract bearbeiten

### Zugehöriger API-Contract

- `PATCH /api/contracts/{id}`

### Typischer Screen-Name

- `ContractEditPage.vue`

### UI-Zustände

- Loading
- Validation Error
- Conflict
- Success
- Not Found
- Forbidden

---

## Screen G5: Contract deaktivieren / reaktivieren / soft delete

### Fachlicher Zweck

Der Lebenszyklus eines Contracts wird gesteuert.

### Zugehöriger Use Case

- Use Case 20: Contract deaktivieren oder soft delete ausführen

### Zugehörige API-Contracts

- `PATCH /api/contracts/{id}/deactivate`
- `PATCH /api/contracts/{id}/reactivate`
- `DELETE /api/contracts/{id}`

### Typische UI-Orte

- Contract-Detail
- Contract-Liste
- Confirm-Dialog

### UI-Zustände

- Confirm
- Loading
- Conflict
- Success
- Forbidden

---

## Screen G6: Dokument an Contract anhängen

### Fachlicher Zweck

Ein Contract erhält ein Dokument oder anderes Medium.

### Zugehöriger Use Case

- Use Case 21: Dokument an Contract anhängen

### Zugehörige API-Contracts

- `POST /api/contracts/{id}/media`
- `GET /api/contracts/{id}/media`

### Typische UI-Orte

- Contract-Detailseite
- Dokumente-Sektion

### Sichtbare Hauptbereiche

- Upload-Feld
- Liste vorhandener Dokumente
- Dateiname
- optional Typ oder Collection
- Fehlermeldungsbereich

### UI-Zustände

- Loading
- Empty
- Uploading
- Validation Error
- Success
- Error
- Forbidden

---

# Abschnitt H: Inbound Review Screens

## Screen H1: Liste der Inbound-Prüffälle anzeigen

### Fachlicher Zweck

Ein berechtigter Benutzer sieht unklare eingehende Anfragen in einer Prüfwarteschlange.

### Zugehöriger Use Case

- Use Case 2: Unklaren Inbound-Prüffall prüfen und entscheiden

### Zugehöriger API-Contract

- `GET /api/inbound/review-cases`

### Typischer Screen-Name

- `InboundReviewCasesListPage.vue`

### Sichtbare Hauptbereiche

- Seitentitel
- Suchfeld
- Statusfilter
- Kanalfilter
- Tabelle oder Kartenliste
- Pagination
- Empty State

### Benutzeraktionen

- suchen
- filtern
- Prüffall öffnen

### Erwartete Frontend-Logik

- `inboundReviewStore.fetchReviewCases(params)` aufrufen
- Filterzustand verwalten
- Ergebnisse anzeigen

### UI-Zustände

#### Loading State

Liste lädt.

#### Success State

Prüffälle sichtbar.

#### Empty State

Keine offenen Prüffälle vorhanden.

#### Error State

Fehler beim Laden.

#### Forbidden State

Keine passende Rolle oder Berechtigung.

---

## Screen H2: Inbound-Prüffall-Detail anzeigen und entscheiden

### Fachlicher Zweck

Ein berechtigter Benutzer prüft einen unklaren Eingang und trifft eine fachliche Entscheidung.

### Zugehöriger Use Case

- Use Case 2: Unklaren Inbound-Prüffall prüfen und entscheiden

### Zugehörige API-Contracts

- `GET /api/inbound/review-cases/{id}`
- `POST /api/inbound/review-cases/{id}/assign-customer`
- `POST /api/inbound/review-cases/{id}/create-customer`
- `PATCH /api/inbound/review-cases/{id}/mark-pending`

### Typischer Screen-Name

- `InboundReviewCaseDetailPage.vue`

### Sichtbare Hauptbereiche

- Eingangsdaten
- erkannte Hinweise wie Contact, `customer_number`, `contract_number`, Name
- Nachrichtentext
- vorhandene Anhänge, wenn vorhanden
- mögliche Customer-Aktionen
- Entscheidungskommentar

### Benutzeraktionen

- bestehendem Customer zuordnen
- neuen Customer anlegen
- pending markieren
- Notiz erfassen

### Erwartete Frontend-Logik

- `inboundReviewStore.fetchReviewCase(id)`
- `inboundReviewStore.assignExistingCustomer(id, payload)`
- `inboundReviewStore.createCustomerFromReviewCase(id, payload)`
- `inboundReviewStore.markPending(id, payload)`

### UI-Zustände

#### Loading State

Prüffall lädt.

#### Not Found State

Prüffall existiert nicht.

#### Success State

Prüffall und Entscheidungsaktionen sichtbar.

#### Validation Error State

Feldfehler bei Zuordnung oder Customer-Anlage.

#### Conflict State

Backend lehnt Entscheidung wegen fachlichem Konflikt ab.

#### Forbidden State

Keine passende Rolle oder Berechtigung.

---

# Abschnitt I: Kategorie Screens

## Screen I1: Kategorien-Liste anzeigen

### Fachlicher Zweck

Interne Benutzer mit Berechtigung sehen die vorhandenen Kategorien.

### Zugehörige Use Cases

- Use Case 31
- Use Case 32

### Zugehöriger API-Contract

- `GET /api/categories`

### Typischer Screen-Name

- `CategoriesListPage.vue`

### Sichtbare Hauptbereiche

- Liste / Tabelle
- Suchfeld
- Aktivstatus
- Aktionen
- „Kategorie anlegen“

### UI-Zustände

- Loading
- Empty
- Success
- Error

---

## Screen I2: Kategorie anlegen oder bearbeiten

### Fachlicher Zweck

Kategorien werden erstellt oder geändert.

### Zugehöriger Use Case

- Use Case 31: Kategorie anlegen oder ändern

### Zugehörige API-Contracts

- `POST /api/categories`
- `PATCH /api/categories/{id}`

### Typische UI-Orte

- eigene Page oder Modal

### UI-Zustände

- Loading
- Validation Error
- Conflict
- Success
- Forbidden

---

## Screen I3: Kategorie deaktivieren / reaktivieren / soft delete

### Fachlicher Zweck

Der Lebenszyklus einer Kategorie wird gesteuert.

### Zugehöriger Use Case

- Use Case 32: Kategorie deaktivieren oder soft delete ausführen

### Zugehörige API-Contracts

- `PATCH /api/categories/{id}/deactivate`
- `PATCH /api/categories/{id}/reactivate`
- `DELETE /api/categories/{id}`

### UI-Zustände

- Confirm
- Loading
- Success
- Conflict
- Forbidden

---

# Abschnitt J: Message- und Media-Flows

## Screen J1: Nachrichten eines Tickets anzeigen

### Fachlicher Zweck

Die Kommunikationshistorie eines Tickets wird sichtbar.

### Zugehörige Use Cases

- Use Case 1
- Use Case 3
- Use Case 4

### Zugehöriger API-Contract

- `GET /api/tickets/{ticketId}/messages`

### Typische UI-Orte

- Ticket-Detailseite
- Nachrichtenpanel
- Verlaufsliste

### UI-Zustände

- Loading
- Empty
- Success
- Error

---

## Screen J2: Öffentliche Nachricht erstellen

### Fachlicher Zweck

Ein interner Benutzer sendet eine öffentliche Antwort an den Customer.

### Zugehöriger Use Case

- Use Case 3: Öffentliche Antwort an Customer senden

### Zugehöriger API-Contract

- `POST /api/tickets/{ticketId}/messages/public`

### Typische UI-Orte

- Ticket-Detail
- Antwortformular

### Sichtbare Hauptbereiche

- Eingabefeld
- Senden-Button
- optional Contact-Hinweis
- Fehlerbereich

### UI-Zustände

- Loading
- Validation Error
- Conflict
- Success
- Forbidden

---

## Screen J3: Interne Nachricht erstellen

### Fachlicher Zweck

Ein interner Benutzer dokumentiert interne Hinweise.

### Zugehöriger Use Case

- Use Case 4: Interne Nachricht im Ticket erstellen

### Zugehöriger API-Contract

- `POST /api/tickets/{ticketId}/messages/internal`

### Typische UI-Orte

- Ticket-Detail
- interner Kommentarbereich

### UI-Zustände

- Loading
- Validation Error
- Conflict
- Success
- Forbidden

---

## Screen J4: Medium an Ticket anhängen

### Fachlicher Zweck

Ein Benutzer lädt ein Medium direkt an ein Ticket hoch.

### Zugehöriger Use Case

- Use Case 33: Datei an Nachricht, Ticket oder Contract anhängen

### Zugehöriger API-Contract

- `POST /api/tickets/{ticketId}/media`

### Typische UI-Orte

- Ticket-Detailseite
- Mediensektion

### UI-Zustände

- Uploading
- Validation Error
- Success
- Error
- Forbidden

---

## Screen J5: Medium an Nachricht anhängen

### Fachlicher Zweck

Ein Benutzer lädt ein Medium an eine Nachricht hoch.

### Zugehöriger Use Case

- Use Case 33: Datei an Nachricht, Ticket oder Contract anhängen

### Zugehöriger API-Contract

- `POST /api/messages/{messageId}/media`

### Typische UI-Orte

- Nachrichtenverlauf
- Anhangsaktion an einer Nachricht

### UI-Zustände

- Uploading
- Validation Error
- Success
- Error
- Forbidden

---

## Nutzen für Agentic AI

Ein Agentic-AI-Modell soll aus dieser Datei verstehen:

- welche Screens fachlich existieren
- welche Use Cases zu welchem Screen gehören
- welche API-Contracts später relevant sind
- welche UI-Zustände immer mitgedacht werden müssen
- dass nicht nur der Happy Path umgesetzt werden darf
- welche Screen-Namen und Screen-Zwecke im Projekt sinnvoll sind
- dass Claude Code mit Stitch MCP und Google Labs stitch-skills diese Datei als strukturierte Grundlage für die UI-Erzeugung verwendet
- dass Screen-Zweck, Hauptbereiche, Benutzeraktionen und UI-Zustände nicht frei erfunden, sondern aus den Screen Flows übernommen werden sollen
- dass Contracts, Inbound-Prüffälle, RBAC-Sichtbarkeit und Medien feste Teile der Frontend-Flows sind
