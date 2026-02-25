# Hostinger Deploy (Git + Laravel)

## 1) What to prepare

- Domain configured in Hostinger.
- SSH access enabled in hPanel.
- MySQL database created (db name/user/password).
- Mail SMTP credentials ready.
- `APP_KEY` generated.

## 2) Recommended folder structure on Hostinger

Use app root outside public web root, for example:

- Laravel app: `/home/<user>/apps/anselat/orchid-project`
- Public root in Hostinger domain settings: `/home/<user>/apps/anselat/orchid-project/public`

If Hostinger plan does not allow changing document root, keep app in `public_html` but this is less clean.

## 3) Clone from GitHub to server

On your local machine generate a deploy SSH key pair (if needed), add public key to GitHub Deploy Keys (repo settings, read-only), then on server:

```bash
cd /home/<user>/apps/anselat
git clone git@github.com:<your-user>/<your-repo>.git
cd orchid-project
```

## 4) Configure `.env` on server

Minimum required:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://your-domain.tld`
- `DB_CONNECTION=mysql`
- `DB_HOST=...`
- `DB_PORT=3306`
- `DB_DATABASE=...`
- `DB_USERNAME=...`
- `DB_PASSWORD=...`
- `MAIL_MAILER=smtp`
- `MAIL_HOST=...`
- `MAIL_PORT=...`
- `MAIL_USERNAME=...`
- `MAIL_PASSWORD=...`
- `MAIL_FROM_ADDRESS=...`
- `MAIL_FROM_NAME=...`
- `ADMIN_EMAIL=...`
- `FILESYSTEM_DISK=public`
- `PLATFORM_FILESYSTEM_DISK=public`

Invoice fields (for PDF invoice):

- `INVOICE_COMPANY_NAME`
- `INVOICE_COMPANY_REG_NUMBER`
- `INVOICE_COMPANY_VAT_NUMBER`
- `INVOICE_COMPANY_IBAN`
- `INVOICE_COMPANY_SWIFT`
- `INVOICE_COMPANY_BANK`
- `INVOICE_COMPANY_ADDRESS`
- `INVOICE_COMPANY_EMAIL`
- `INVOICE_COMPANY_PHONE`
- `INVOICE_LOGO_PATH` (example: `images/dist/logo-dark.svg`)

## 5) First deploy on server

```bash
cd /home/<user>/apps/anselat/orchid-project
chmod +x scripts/deploy_hostinger.sh
./scripts/deploy_hostinger.sh
```

## 6) Next deploys from Git (manual)

```bash
cd /home/<user>/apps/anselat/orchid-project
git pull --ff-only origin main
./scripts/deploy_hostinger.sh
```

## 7) Auto-deploy from GitHub Actions

Workflow file is in:

- `.github/workflows/deploy-hostinger.yml`

Add these GitHub repository secrets:

- `HOSTINGER_SSH_HOST` (example: `srv123.hostinger.com`)
- `HOSTINGER_SSH_PORT` (usually `65002`, check hPanel)
- `HOSTINGER_SSH_USER`
- `HOSTINGER_SSH_KEY` (private key for SSH, multiline)
- `HOSTINGER_APP_DIR` (full path to Laravel app root, where `artisan` exists)
- `HOSTINGER_DEPLOY_BRANCH` (optional, default `main`)

Then push to `main` and workflow will SSH to server and run deploy script.

## 8) Post-deploy sanity check

- Open `https://your-domain.tld/lv`
- Open admin `/admin`
- Save one entity in Orchid (check DB write)
- Upload image (check `public/storage`)
- Submit calculator form (check both client/admin emails)
- Verify SEO/locale routes (`/lv/...`, `/en/...`, `/ru/...`)

## 9) Rollback (quick)

```bash
cd /home/<user>/apps/anselat/orchid-project
git log --oneline -n 5
git checkout <previous-commit>
./scripts/deploy_hostinger.sh
```

