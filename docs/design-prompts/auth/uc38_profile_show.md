# Design Prompt — UC-38 Eigenes Profil anzeigen

## Ziel

Visuelle Referenz für die Anzeige des eigenen Profils eines authentifizierten internen Benutzers erzeugen.

## Quellen

- `docs/by-domain/auth.md`
- `docs/by-use-case/uc38_profile_show.md`
- `docs/design-references/app-shell.png`

## Prompt

Erzeuge einen hochwertigen Desktop- und Mobile-Screen für den Use Case "UC-38 Eigenes Profil anzeigen" im Smart Support Desk System. Der Screen ist geschützt und zeigt ausschließlich die eigenen Profildaten des angemeldeten internen Benutzers.

Respektiere die bestehende UI-Sprache des Projekts: geschützter App-Shell-Rahmen, ruhige helle Arbeitsfläche, strukturierte Karten, klare Labels, gute Lesbarkeit, dezente blaue Akzente und professionelle B2B-Anmutung. Desktop soll Profilinformationen in einer übersichtlichen Hauptkarte mit klarer Seitenhierarchie zeigen. Mobile soll die Inhalte einspaltig, kompakt und gut scannbar darstellen.

Inhaltlich darstellen:

- Seitentitel "Mein Profil" oder gleichwertig
- eigene Benutzerinformationen als read-only Ansicht
- neutraler Ladezustand
- sichtbarer Fehlerzustand, falls Profildaten nicht geladen werden können
- optionale, klar getrennte Aktion zum Bearbeiten nur als Verweis auf UC-39

Nur Profilfelder darstellen, die aus der Use-Case-Datei oder der späteren Implementierung fachlich freigegeben sind. Falls konkrete Felder unklar sind, mit generischen Platzhalterzeilen arbeiten, ohne neue fachliche Felder zu behaupten.

## UI-Zustände

- Ladezustand
- erfolgreiche read-only Profilanzeige
- Fehlerzustand
- responsive Mobile-Variante

## Nicht erfinden

Keine fremden Benutzerprofile, keine Admin-Bearbeitung, keine Rollen- oder Permission-Verwaltung, keine Aktivstatus-Änderung, keine Audit-Historie, keine KPIs, keine nicht dokumentierten Tabs oder Felder ergänzen.
