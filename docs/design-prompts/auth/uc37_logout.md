# Design Prompt — UC-37 Logout

## Ziel

Visuelle Referenz für den Logout eines authentifizierten internen Benutzers erzeugen.

## Quellen

- `docs/by-domain/auth.md`
- `docs/by-use-case/uc37_logout.md`
- `docs/design-references/app-shell.png`

## Prompt

Erzeuge einen hochwertigen Desktop- und Mobile-Screen für den Use Case "UC-37 Logout" im Smart Support Desk System. Der Screen zeigt eine geschützte App-Umgebung mit einer klaren Abmelden-Aktion für den aktuell authentifizierten internen Benutzer.

Respektiere die bestehende UI-Sprache des Projekts: professionelle Support-Desk-App, helle Arbeitsfläche, klare Header- oder User-Menü-Struktur, dezente blaue Akzente, präzise Abstände und gut erkennbare Interaktionszustände. Desktop soll den Logout plausibel im geschützten App-Shell-Kontext zeigen. Mobile soll eine reduzierte, gut bedienbare Variante der Abmelden-Aktion darstellen.

Inhaltlich darstellen:

- geschützter App-Shell-Kontext ohne fachliche Detaildaten
- User-/Account-Bereich mit Aktion "Abmelden"
- Lade- oder disabled-Zustand während Logout
- Zustand nach ausgelöstem Logout, der auf Rückkehr zum Login hindeutet

Die Darstellung soll vermitteln, dass die aktuelle Authentifizierung beendet wird und danach keine geschützten Benutzerdaten mehr sichtbar bleiben.

## UI-Zustände

- normaler authentifizierter Zustand mit Abmelden-Aktion
- laufender Logout mit deaktivierter Aktion
- Rückkehr- oder Übergangszustand zur öffentlichen Login-Seite
- Ladezustand während Abmelden
- responsive Mobile-Variante

## Nicht erfinden

Keine Session-Liste, kein Geräte-Management, keine Token-Verwaltung, keine Rollenverwaltung, keine Admin-Funktionen, keine Ticket-KPIs, keine nicht dokumentierten Felder, Tabs oder Sicherheitsfunktionen ergänzen.
