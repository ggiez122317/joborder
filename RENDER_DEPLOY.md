# Deploying to Render (Docker + external MySQL + R2 storage)

This app deploys from GitHub: push to `main` → Render builds the
`Dockerfile` → live. No FTP, no manual uploads.

## 0. What Render needs from you (one-time setup)

### A. Production database (external MySQL)
Render's own MySQL is not free-tier friendly, so use an external host
(Aiven free tier, PlanetScale, or any MySQL 8 you control). Create an
empty database and note down: host, port, database, username, password.

### B. Object storage for uploads (Cloudflare R2, free tier)Profile photos, QR codes, ID templates and PDS uploads must survive
redeploys, so they go to R2 instead of the container disk. The app
switches automatically: when `AWS_BUCKET` + `AWS_ENDPOINT` are set it
uses R2, otherwise local disk (unchanged dev behavior).

1. Cloudflare dashboard → R2 → Create bucket (e.g. `pds-uploads`).
2. Bucket → Settings → Public access → allow (via `r2.dev` subdomain
   or your custom domain). Copy that public URL → `AWS_URL`.
3. R2 → Manage API tokens → create token with Object Read & Write on
   your bucket → `AWS_ACCESS_KEY_ID` / `AWS_SECRET_ACCESS_KEY`.
4. Endpoint is `https://<account-id>.r2.cloudflarestorage.com`
   → `AWS_ENDPOINT`. Region is `auto`.

5. One-time: copy existing local uploads to the bucket so old photos
   and ID templates keep working. From any machine with both the files
   and an S3-compatible CLI (AWS CLI pointed at R2, or `rclone`):

```bash
aws s3 sync storage/app/public/ s3://pds-uploads/ --endpoint-url https://<account-id>.r2.cloudflarestorage.com
```

### C. Production app key
Run locally (never commit the output):

```bash
php artisan key:generate --show
```

Copy the `base64:...` value → Render `APP_KEY`.

## 1. Connect Render

1. Render Dashboard → New → **Blueprint** → select this repo
   (`render.yaml` is at the root).
2. Fill every value marked `sync: false` (DB creds, `APP_KEY`,
   `APP_URL` like `https://<your-app>.onrender.com`, R2 keys,
   mail + reCAPTCHA if used).
3. Deploy. First boot runs `migrations` (`MIGRATE_ON_DEPLOY=true`),
   `storage:link`, and config/route/view caches automatically.

## 2. Post-deploy verification (adapted to this app)

Application:

- [ ] Homepage/login loads with styling (CSS/JS from `public/build`)
- [ ] Logo favicon + images load
- [ ] Admin login works, sessions persist across page loads
- [ ] CSRF works (any form submit, e.g. Add Office)

Database:

- [ ] Dashboard numbers render (DB connected)
- [ ] Records list, Add New PDS, edit, delete
- [ ] Offices page shows the 24 offices
- [ ] Incomplete Queue + Mark Reviewed

Uploads (needs R2 vars set):

- [ ] Upload a profile photo → visible on the record + ID card
- [ ] ID Templates upload works
- [ ] Redeploy (manual or empty commit) → uploaded files still load

Registration links:

- [ ] Generate a link from Records, open it logged-out, submit a test
      record → lands in Incomplete Queue

Laravel/prod sanity:

- [ ] `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` correct
- [ ] `storage/` and `bootstrap/cache/` writable (entrypoint handles it)
- [ ] Mail: user-role accounts need working SMTP for 2FA codes
      (default `MAIL_MAILER=log` only writes to logs)

## 3. Confirm CI/CD with a second push

1. Make a tiny visible change (e.g. login subtitle).
2. `git add . && git commit -m "Test Render automatic deployment" && git push origin main`
3. Render dashboard should start a deployment automatically.
4. When live, confirm the change on the Render URL.

## 4. Security reminders

- Never commit `.env` / `.env.production` (`git ls-files .env` must print nothing).
- `APP_DEBUG=false` in production. Secrets live only in Render env vars.
- If a secret ever gets committed, rotate it even in a private repo.
- No queue worker or cron needed: the app sends mail synchronously
  and has no scheduled jobs (only Laravel's default no-op).
