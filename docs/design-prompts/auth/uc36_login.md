# Design Prompt — UC-36 Login

## Ziel

Visuelle Referenz für den Login eines internen Benutzers im Smart Support Desk System erzeugen.

## Quellen

- `docs/by-domain/auth.md`
- `docs/by-use-case/uc36_login.md`
- `docs/design-references/app-shell.png`

## Prompt

Erzeuge einen hochwertigen Desktop- und Mobile-Screen für den Use Case "UC-36 Login" im Smart Support Desk System. Die Seite ist öffentlich und dient ausschließlich der Anmeldung interner Benutzer.

Verwende die bestehende UI-Sprache des Projekts: klare B2B-Support-Desk-Oberfläche, ruhige helle Flächen, präzise Typografie, Tailwind-kompatible Kartenstruktur, dezente blaue Akzente, professionelle Abstände und gut erkennbare Formularzustände. Desktop darf eine linke Marken-/Kontextfläche und rechts eine kompakte Login-Karte zeigen. Mobile soll dieselbe Funktion linear, gut lesbar und ohne überflüssige Navigation darstellen.

Inhaltlich darstellen:

- Produktname oder Support-Desk-Kontext
- Login-Formular für Zugangsdaten
- Submit-Aktion "Anmelden"
- Ladezustand am Submit
- sichtbarer globaler Authentifizierungsfehler
- feldnahe Validierungsfehler für Pflichtfelder

Die Darstellung soll deutlich machen, dass nach erfolgreicher Authentifizierung interne Funktionen erreichbar werden. Es sollen keine administrativen Funktionen und keine fachlichen Ticketdaten sichtbar sein.

## UI-Zustände

- leerer Initialzustand
- Pflichtfeldfehler
- globale Fehlermeldung bei falschen Zugangsdaten
- Ladezustand während Anmeldung
- responsive Mobile-Variante

## Nicht erfinden

Keine Registrierung, kein Passwort-Reset, keine MFA, keine Social Logins, keine Rollenwahl, keine KPIs, keine Tickets, keine Admin-Funktionen, keine nicht dokumentierten Felder, Tabs oder Menüs ergänzen.
