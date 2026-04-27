# Backend-QA: Auth-Domain

**Datum:** 2026-04-25  
**Umgebung:** Docker, API http://localhost:8000  
**Getestete UCs:** UC-36, UC-37, UC-38, UC-39

---

## Ergebnisse

### UC-36 Login — POST /api/login

| Test | Erwartung | Ergebnis |
|---|---|---|
| Erfolgreicher Login | 200 + token + internal_user | ✅ 200 |
| Leere Felder | 422 + Feldfehler | ✅ 422 |
| Falsches Passwort (≥8 Zeichen) | 401 + Fehlermeldung | ✅ 401 — `"Ungültige Zugangsdaten."` |
| Passwort < 8 Zeichen | 422 (Validierung) | ✅ 422 — Formale Validierung greift vor Auth |
| Inaktiver Benutzer | 403 + Fehlermeldung | ✅ 403 — `"Ihr Benutzerkonto ist deaktiviert."` |
| Audit-Log erstellt | `user_logged_in` in audit_logs | ✅ Bestätigt |

---

### UC-38 Eigenes Profil anzeigen — GET /api/me

| Test | Erwartung | Ergebnis |
|---|---|---|
| Authentifiziert | 200 + Profildaten des eigenen Users | ✅ 200 |
| Ohne Token | 401 | ✅ 401 |

---

### UC-39 Eigenes Profil aktualisieren — PATCH /api/me

| Test | Erwartung | Ergebnis |
|---|---|---|
| Valide Felder | 200 + aktualisierte Profildaten | ✅ 200 |
| Leerer Body `{}` | 422 | ✅ 422 — `"Mindestens ein Feld muss angegeben werden."` |
| Duplikat-E-Mail | 422 (unique-Verletzung) | ✅ 422 — laut UC: "HTTP 409 oder validierungsnahe Ablehnung" |
| Ohne Token | 401 | ✅ 401 |
| Audit-Log erstellt | `profile_updated` mit old/new values | ✅ Bestätigt — old/new values aus Model nach refresh() |

---

### UC-37 Logout — POST /api/logout

| Test | Erwartung | Ergebnis |
|---|---|---|
| Authentifiziert | 200 + data: null | ✅ 200 |
| Token nach Logout ungültig | 401 auf GET /api/me | ✅ 401 |
| Ohne Token | 401 | ✅ 401 |
| Audit-Log erstellt | `user_logged_out` in audit_logs | ✅ Bestätigt |

---

## Nachbesserungen während QA

1. **bootstrap/app.php** — `AuthenticationException` custom message war hardcoded auf `"Unauthenticated."`. Behoben: `$e->getMessage()` wird jetzt verwendet. Zusätzlich `AuthorizationException` (403) ergänzt.
2. **UpdateOwnProfileRequest** — Leerer PATCH `{}` wurde mit 200 beantwortet. Behoben: `withValidator()` prüft ob mindestens ein Feld übergeben wurde.

---

## Gesamtbewertung

**✅ Backend-QA bestanden** — Alle dokumentierten Test-Cases erfüllt.  
Audit-Logs für `user_logged_in`, `user_logged_out`, `profile_updated` korrekt geschrieben.
