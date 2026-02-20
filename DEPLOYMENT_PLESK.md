# Deployment Guide (Plesk / Apache)

Use this checklist to deploy **cms.webvibeinfotech.in** safely on Plesk.

## 1) Prerequisites
- PHP: 8.1+ (match local), extensions: `bcmath`, `ctype`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `gd`, `curl`, `zip`, `fileinfo`, `intl`, `redis` (if used).
- Composer available on the server, or ability to upload the `vendor/` directory.
- MySQL database and user created (provided: `webvibei_cms` / `webvibei_cms`).

## 2) Code & Docroot
- Upload the project to `/var/www/vhosts/webvibeinfotech.in/cms.webvibeinfotech.in/` (the project root).
- **DocumentRoot must point to `public/`**. In Plesk: Hosting Settings → Document Root → set to `cms.webvibeinfotech.in/public`.
- Ensure `public/.htaccess` is present (default Laravel file).

## 3) Environment (.env)
- Place `.env` in the project root with at least:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://cms.webvibeinfotech.in
LOG_CHANNEL=stack
LOG_LEVEL=info
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webvibei_cms
DB_USERNAME=webvibei_cms
DB_PASSWORD=webvibei_cms
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public
```
- Run `php artisan key:generate` if APP_KEY is missing.

## 4) Install Dependencies
From the project root (one level above `public/`):
```
composer install --no-dev --optimize-autoloader
php artisan storage:link
```
If composer is unavailable, install it via Plesk SSH or upload a built `vendor/` from a matching PHP version.

## 5) Database
- Import schema (if fresh): `php artisan migrate --force`.
- Seed (optional initial data): `php artisan db:seed --force`.

## 6) Permissions
- `storage/` and `bootstrap/cache/` writable by the web user (e.g., `www-data`):
```
chown -R www-data:www-data storage bootstrap/cache
find storage bootstrap/cache -type d -exec chmod 775 {} \;
```

## 7) Optimize & Cache
Run after env and deps are set:
```
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
```

## 8) Queues / Scheduler (if used)
- Scheduler: add a cron in Plesk to run every minute:
  `* * * * * /usr/bin/php /var/www/vhosts/webvibeinfotech.in/cms.webvibeinfotech.in/artisan schedule:run >> /dev/null 2>&1`
- Queues (database driver): set a persistent worker, e.g., via Plesk Scheduled Task running every minute:
  `* * * * * /usr/bin/php /var/www/vhosts/webvibeinfotech.in/cms.webvibeinfotech.in/artisan queue:work --stop-when-empty --timeout=120 --tries=3`
  (Or supervise via systemd/supervisor if available.)

## 9) Common 403 / 500 fixes
- 403 at `/`: usually docroot not `public/` or `.htaccess` blocked. Ensure correct DocumentRoot and Apache AllowOverride is On.
- 500 for `vendor/autoload.php`: run `composer install` in project root so `vendor/` exists.
- Permissions: verify `storage`/`bootstrap/cache` writable.
- Clear caches after any env change.

## 10) Post-deploy smoke checks
- Visit `/` → should redirect to login.
- Admin login flow works; dashboards render without errors.
- File upload paths: `storage/app/public` symlinked to `public/storage`.
- Logs: check `storage/logs/laravel.log` for clean startup.

## 11) Rollback plan
- Keep a tar/zip of last known good release and DB backup.
- If deploy fails, restore code and DB, then re-run cache clear.
