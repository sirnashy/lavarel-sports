# SportStream Deployment Guide

This document outlines deployment configurations for the SportStream platform.

---

## 1. Docker Compose Deployment (Recommended)

To run the entire stack (App, Database, Redis, and Queue worker) in Docker:

1. Copy `.env.example` to `.env` and configure your settings:
   ```bash
   cp .env.example .env
   ```
2. Build and run the containers:
   ```bash
   docker-compose up -d --build
   ```
3. Generate the application key and run migrations:
   ```bash
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate --seed
   ```

---

## 2. Render.com Deployment

1. Create a new project on Render and choose **Blueprint** to import the `render.yaml` file.
2. Render will automatically set up the web server, Redis database, and queue background worker.
3. Add a MySQL database instance (e.g. Render MySQL or external) and configure environment variables.
4. Set the `SPORTSRC_API_KEY` environment variable in the Render Dashboard.

---

## 3. VPS Deployment (Nginx + Ubuntu)

1. **Install Requirements**: Install PHP 8.3+, Nginx, MySQL 8, and Redis.
2. **Setup Code**: Clone code to `/var/www/html/sportstream`.
3. **Configure Nginx**: Use the server block defined in `deployment/nginx.conf`.
4. **Permissions**:
   ```bash
   chown -R www-data:www-data /var/www/html/sportstream
   chmod -R 775 storage bootstrap/cache
   ```
5. **Supervisor Daemon**: Configure supervisor to run the queue worker. Copy `deployment/supervisor.conf` worker section to `/etc/supervisor/conf.d/sportstream.conf` and reload:
   ```bash
   supervisorctl reread
   supervisorctl update
   supervisorctl start laravel-worker:*
   ```
6. **Cron Schedule**: Add the Laravel scheduler cron entry:
   ```cron
   * * * * * cd /var/www/html/sportstream && php artisan schedule:run >> /dev/null 2>&1
   ```

---

## 4. DirectAdmin Setup

If deploying on a shared/semi-dedicated DirectAdmin server:

1. **Upload Files**: Extract the project zip files directly into the domain's root folder (`/private_html` or `/public_html` depending on your hosting setup).
2. **Setup Document Root**:
   * Change the website Document Root to point to the `public/` directory instead of the project root.
   * If your host doesn't allow changing the document root, configure an `.htaccess` redirect at the root level:
     ```apache
     <IfModule mod_rewrite.c>
         RewriteEngine On
         RewriteRule ^(.*)$ public/$1 [L]
     </IfModule>
     ```
3. **Set Permissions**: Ensure `storage` and `bootstrap/cache` directories are writable (mode `775` or `777`).
4. **Cron Job**: Set up a cron task in DirectAdmin running every minute:
   ```bash
   /usr/local/bin/php /home/username/domains/domain.com/public_html/artisan schedule:run >/dev/null 2>&1
   ```
