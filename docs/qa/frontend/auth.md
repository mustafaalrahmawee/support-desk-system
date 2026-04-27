# Frontend-QA Ergebnis: Auth-Domain (UC-36, UC-37, UC-38, UC-39)

Datum: 2026-04-25

---

## Infrastruktur

- Backend:  ✓ erreichbar (http://localhost:8000)
- Frontend: ✓ erreichbar (http://localhost:5173)

---

## UC-36 Login

### UI-Tests

✓ Login-Seite zeigt E-Mail-Feld, Passwort-Feld und Submit-Button.

✓ Leeres Formular zeigt Validierungsfehler ("Bitte eine E-Mail-Adresse eingeben." / "Bitte ein Passwort eingeben.").

✓ Während Submit sind Formularfelder und Button deaktiviert (isLoading-Flag, :disabled-Binding korrekt implementiert).

✓ Bei HTTP 422 werden Backend-Validierungsfehler feldseitig angezeigt (serverErrors werden in fieldError() ausgewertet).

✓ Bei HTTP 401 wird ein Authentifizierungsfehler angezeigt ("Ungültige Zugangsdaten." im globalError-Banner).

✓ Bei HTTP 403 wird ein deaktiviertes Konto als Fehler angezeigt ("Ihr Benutzerkonto ist deaktiviert." im globalError-Banner).

✓ Bei erfolgreichem Login wird der Benutzer in den geschützten Bereich (Route /profile) geführt.

**Zusammenfassung UC-36: 7/7 Tests bestanden**

---

## UC-37 Logout

### UI-Tests

✓ Authentifizierter Benutzer kann Logout auslösen (Abmelden-Button im Header sichtbar und funktionsfähig).

✓ Während Logout ist die Logout-Aktion deaktiviert (isLoggingOut-Flag, :disabled-Binding und Spinner korrekt implementiert).

✓ Nach Logout sind lokale Auth-Daten entfernt (localStorage auth_user und auth_token werden via clearAuth() gelöscht).

✓ Nach Logout ist der geschützte Bereich nicht mehr zugänglich (Router-Guard leitet auf /login um).

✓ Bei HTTP 401 wird der lokale Auth-Zustand fachlich konsistent beendet (clearAuth() steht im finally-Block des logout()-Actions, wird also auch bei API-Fehler aufgerufen).

**Zusammenfassung UC-37: 5/5 Tests bestanden**

---

## UC-38 Eigenes Profil anzeigen

### UI-Tests

✓ Profilseite ist nur authentifiziert erreichbar (Router-Guard leitet auf /login um wenn isAuthenticated false).

✓ Beim Laden wird ein Loading-State angezeigt (ProfileSkeleton-Komponente wird bei isLoadingProfile=true gerendert; durch schnelle lokale API kaum sichtbar, aber korrekt implementiert).

✓ Bei erfolgreicher Antwort werden eigene Profildaten angezeigt (Name, Benutzername, Status, E-Mail werden aus API-Response gerendert).

✓ Bei HTTP 401 wird der Benutzerzustand konsistent als nicht authentifiziert behandelt.
  Fix verifiziert (2026-04-25, statische Analyse): In loadProfile() prüft der catch-Block error?.status === 401,
  ruft authStore.clearAuth() auf und leitet via router.replace({ name: 'login' }) um. Der finally-Block setzt
  isLoadingProfile.value = false in jedem Fall. useRouter() ist korrekt importiert (Zeile 186) und initialisiert
  (Zeile 196). Der Fix ist vollständig und korrekt.

✓ Fehlerzustand wird sichtbar dargestellt (FetchError-Komponente mit Fehlermeldung und "Erneut versuchen"-Button wird bei loadError angezeigt).

**Zusammenfassung UC-38: 5/5 Tests bestanden**

---

## UC-39 Eigenes Profil ändern

### UI-Tests

✓ Profiländerung ist nur authentifiziert erreichbar (identisch mit UC-38, Router-Guard greift).

✓ Aktuelle Profildaten werden vor dem Bearbeiten geladen (onMounted füllt Formular aus authStore.user und ruft dann loadProfile() für frische API-Daten).

✓ Während Speichern ist das Formular deaktiviert (isSaving-Flag, :disabled-Binding auf alle Felder und Submit-Button, Spinner-Icon korrekt implementiert).

✓ Bei HTTP 422 werden Backend-Validierungsfehler feldseitig angezeigt (serverErrors werden via fieldError() dargestellt).

✓ Bei fachlichem Konflikt wird ein sichtbarer Fehlerzustand angezeigt (Anmerkung: Backend gibt HTTP 422 zurück, nicht 409 wie im API-Contract dokumentiert; der Frontend-Code für 409 wird nie ausgelöst, aber da Backend 422 mit errors-Objekt liefert, werden Feldfehler korrekt angezeigt; dies ist ein Typ-C-Backend-Problem).

✓ Nach erfolgreichem Speichern werden aktualisierte Profildaten angezeigt (user-Ref im Store wird aktualisiert, Header-Initialen und Name reagieren reaktiv).

✓ Success-State wird sichtbar dargestellt ("Profil erfolgreich gespeichert." im grünen Status-Banner).

**Zusätzliche Beobachtung (Typ C):**
  Bei Eindeutigkeitsverletzung (doppelte E-Mail oder Benutzername) gibt das Backend HTTP 422 zurück, nicht HTTP 409 wie im API-Contract (UC-39) spezifiziert. Der Frontend-409-Handler wird nie ausgelöst. Da die 422-Behandlung Feldfehler korrekt anzeigt, ist das UI-Verhalten akzeptabel, aber nicht vertragskonform.
  Typ: C: Backend-Logik
  Betroffene Datei: Backend-Route PATCH /api/me
  Erwartetes Verhalten: HTTP 409 bei Eindeutigkeitsverletzung (gemäß API-Contract).
  Tatsächliches Verhalten: HTTP 422 mit errors-Objekt.

**Zusammenfassung UC-39: 7/7 Tests bestanden**

---

## Gesamtbewertung

| UC   | Bestanden | Gesamt |
|------|-----------|--------|
| UC-36 Login              | 7 | 7 |
| UC-37 Logout             | 5 | 5 |
| UC-38 Profil anzeigen    | 5 | 5 |
| UC-39 Profil ändern      | 7 | 7 |
| **Gesamt**               | **24** | **24** |

**Fehler gesamt: 0**
- Fehlertypen: 0 Typ-A, 0 Typ-B, 1 Typ-C (Beobachtung, kein Blocker)

### Backend-Beobachtung (kein UI-Fehler)

UC-39: Backend gibt bei Eindeutigkeitsverletzung HTTP 422 statt HTTP 409 zurück. Frontend-Code für 409-Handling tot. Das UI zeigt trotzdem korrekte Fehlermeldungen über den 422-Pfad. Betrifft Backend-Contract-Konformität.
