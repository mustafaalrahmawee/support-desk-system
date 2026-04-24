# Smart Support Desk System — Claude Code Arbeitsanweisung

## Zweck

Diese Datei steuert das Arbeitsverhalten des Agents in Implementierungs-Sessions.

Für fachliche Wahrheit, Dokumentstruktur, Leseregeln, Arbeitsablauf und Grundregeln gilt `docs/README.md`.

Diese Datei regelt nur:
- Session-Start
- QA-Nutzung
- zusätzliche technische Arbeitsregeln

---

## Session-Start (Pflicht)

Jede Implementierungs-Session startet im Plan-Mode.

Verwendung:
- `/session <domain>`
- `/session <domain> <bundle>`

Vor jeder Implementierung gilt:
1. Plan erstellen
2. Plan bestätigen lassen
3. Erst danach implementieren

Bei Kontextfülle: `/clear` und mit dem nächsten Schritt weitermachen.

---

## QA-Subagenten

- `backend-qa` und `frontend-qa` sind read-only
- sie testen und berichten
- sie ändern keinen Code

Wenn QA Fehler meldet:
1. Bericht analysieren
2. gezielt korrigieren
3. QA erneut ausführen
4. maximal 3 gezielte Nachbesserungsversuche

Nach 3 erfolglosen Versuchen:
- stoppen
- Benutzer informieren
- offenen Fehler, Ursache und bisherige Fixes dokumentieren

---

## Frontend-QA Fehlerklassen

- Typ A: UI-Fehler → Frontend darf angepasst werden
- Typ B: Infrastruktur → keine Vue-Änderung, zuerst Ursache klären
- Typ C: Backend-Logik → Backend korrigieren, nicht Frontend

---

## Technische Arbeitsregeln

Zusätzlich zu den Grundregeln in `docs/README.md` gelten:

- Keine Fachlogik im Controller
- Keine Rollenabfragen verstreut statt in Policies
- Keine fehlende Transaktion bei atomaren Fachvorgängen
- Audit bei fachlich relevanten Änderungen nicht vergessen
- Keine Rollen- oder Statuslogik im Frontend erfinden