# Deploying to InfinityFree (FTP auto-deploy)

Push to `main` → GitHub Action uploads changed files to InfinityFree.
Manual FileZilla uploads are no longer needed after setup.

## 0. Blockers — check these FIRST

1. **PHP version must be 8.2+** (Laravel 11 requirement). Check in the
   VistaPanel / control panel. If InfinityFree only offers 8.0/8.1 on
   your account, this app **cannot run there** — stop and use Render
   instead (see `RENDER_DEPLOY.md`).
2. **No SSH on free hosting.** That is why `vendor/` is committed to
   this repo and uploaded too (first sync is ~130MB — slow once, then
   only changed files). Never delete `vendor/` from the repo while on
   InfinityFree.

## 1. Database

1. Control panel → MySQL Databases → create one (note host like
   `sqlXXX.epizy.com`, name, user, password).
2. Open phpMyAdmin → import the `pds-*.sql` dump from Downloads
   (structure first; data optional).
3. Later schema changes: visit `/setup/<SETUP_TOKEN>` after each push
   that contains new migrations.

## 2. Server `.env`

1. Locally: `php artisan key:generate --show` → copy the key.
2. On the server (FileZilla, inside `htdocs/`): create file `.env`
   using `.env.infinityfree.example` as template — fill `APP_URL`
   (your `https://...epizy.com`), DB creds, fresh `APP_KEY`,
   and a long random `SETUP_TOKEN`.
3. The root `.htaccess` blocks web access to `.env` even if uploaded.

## 3. GitHub Secrets (repo Settings → Secrets → Actions)

- `FTP_HOST` → e.g. `ftpupload.net`
- `FTP_USERNAME` → e.g. `epiz_12345678` (hosting/VistaPanel username)
- `FTP_PASSWORD` → hosting account password (NOT the client-area one)

Uses explicit FTP-over-TLS. If the Action fails on TLS, change
`protocol: ftps` to `protocol: ftp` in `.github/workflows/ftp-deploy.yml`.

## 4. First deploy

1. Push to `main` (or Run workflow manually). Watch the Actions tab.
2. Visit `https://your-domain/setup/<SETUP_TOKEN>` once → creates
   `storage` link + runs migrations. Then **remove the token line**
   from server `.env` (or the route keeps working for whoever
   guesses it — don't leave it).
3. Open the site. If you see a directory listing or the wrong page,
   the domain must point at `htdocs/` (default) — the root
   `.htaccess` forwards everything into Laravel's `public/`.

## 5. Verify (adapted checklist)

- [ ] Homepage/login loads styled (built CSS/JS is committed)
- [ ] Login works, favicon shows
- [ ] Records CRUD, photo upload visible on ID card
- [ ] Offices page lists 24, Incomplete Queue works
- [ ] Registration link generate → open logged-out → submit → queue
- [ ] `APP_DEBUG=false`, no stack traces on errors
- [ ] Second small push → Action runs → change live (CI/CD proof)

## 6. Caveats on InfinityFree free tier

- **Mail**: external SMTP is restricted; default `MAIL_MAILER=log`
  means user-role 2FA codes go to `storage/logs`, not inboxes.
  Admin logins need no email and work fine.
- **Inodes/files**: ~16k tracked files (mostly `vendor/`). If you hit
  file-count limits, that is the cause — say so and we'll slim it.
- **No cron/queues/scheduler needed** — verified: the app sends mail
  synchronously and has no scheduled jobs.
- Sleep/idle limits don't apply like Render — but resources are
  throttled; heavy Excel imports may time out (use small batches).
