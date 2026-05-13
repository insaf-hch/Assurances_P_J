#!/bin/bash
set -e
cd /var/www/html

echo "=== Writing .env ==="
cat > /var/www/html/.env << ENVEOF
APP_NAME=Laravel
APP_ENV=production
APP_KEY=$APP_KEY
APP_DEBUG=true
APP_URL=$APP_URL

DB_CONNECTION=mysql
DB_HOST=${MYSQLHOST}
DB_PORT=${MYSQLPORT:-3306}
DB_DATABASE=${MYSQLDATABASE}
DB_USERNAME=${MYSQLUSER}
DB_PASSWORD=${MYSQLPASSWORD}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stack
LOG_LEVEL=debug
ENVEOF

echo "=== .env contents ==="
cat /var/www/html/.env

echo "=== Clearing caches ==="
php artisan config:clear
php artisan cache:clear

echo "=== Caching fresh config ==="
php artisan config:cache   # ← AJOUTE CETTE LIGNE


echo "=== Fixing permissions ==="
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "=== Starting php-fpm ==="
/usr/local/sbin/php-fpm -D
sleep 2

echo "=== Linking nginx ==="
ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

echo "=== Running migrations ==="
php artisan migrate --force

echo "=== Starting nginx ==="
exec nginx -g "daemon off;"