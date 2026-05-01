# Design Prompt — UC-39 Eigenes Profil ändern

## Ziel

Visuelle Referenz für die Änderung eigener Profildaten eines authentifizierten internen Benutzers erzeugen.

## Quellen

- `docs/by-domain/auth.md`
- `docs/by-use-case/uc39_profile_update.md`
- `docs/design-references/app-shell.png`

## Prompt

Erzeuge einen hochwertigen Desktop- und Mobile-Screen für den Use Case "UC-39 Eigenes Profil ändern" im Smart Support Desk System. Der Screen ist geschützt und zeigt ein Formular, mit dem der angemeldete interne Benutzer ausschließlich fachlich erlaubte eigene Profildaten ändern kann.

Respektiere die bestehende UI-Sprache des Projekts: geschützter App-Shell-Rahmen, helle Arbeitsfläche, präzise Formularlabels, feldnahe Fehler, klare Primäraktion, dezente blaue Akzente und professionelle Support-Desk-Anmutung. Desktop soll ein fokussiertes Profilformular in einer Karte zeigen. Mobile soll einspaltig, touchfreundlich und ohne überflüssige Nebenbereiche funktionieren.

Inhaltlich darstellen:

- Seitentitel "Profil bearbeiten" oder gleichwertig
- Formular mit nur fachlich erlaubten Profilfeldern
- read-only Darstellung nicht änderbarer Kontoinformationen, falls erforderlich
- Speichern-Aktion
- Abbrechen- oder Zurück-Aktion zur Profilanzeige
- feldnahe Validierungsfehler
- globaler Fehlerzustand
- Erfolgshinweis nach Speicherung
- Ladezustand beim Speichern

Wenn konkrete editierbare Felder nicht festgelegt sind, Platzhalter für "fachlich erlaubtes Profilfeld" verwenden und keine neuen Fachfelder behaupten.

## UI-Zustände

- Initialzustand mit geladenen Profildaten
- Validierungsfehler
- laufender Speichervorgang
- erfolgreiche Speicherung
- globaler Fehlerzustand
- responsive Mobile-Variante

## Nicht erfinden

Keine Rollen, Permissions, Aktivstatus, Admin-Notizen, Passwortänderung, MFA, fremde Benutzer, Audit-Historie, KPIs, Tabs oder nicht dokumentierten Profilfelder ergänzen.
