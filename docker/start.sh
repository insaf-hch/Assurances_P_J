#!/bin/bash

cd /var/www/html

# Permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage

DB_H=$(echo "$MYSQL_URL" | awk -F'@' '{print $2}' | awk -F':' '{print $1}')
DB_P=$(echo "$MYSQL_URL" | awk -F'/' '{print $NF}')
DB_PASS=$(echo "$MYSQL_URL" | sed 's|mysql://[^:]*:\(.*\)@.*|\1|')

echo "=== Writing .env ==="
cat > /var/www/html/.env << ENVEOF
APP_NAME=Laravel
APP_ENV=production
APP_KEY=$APP_KEY
APP_DEBUG=true
APP_URL=$APP_URL

DB_CONNECTION=mysql
DB_HOST=$DB_H
DB_PORT=3306
DB_DATABASE=$DB_P
DB_USERNAME=root
DB_PASSWORD=$DB_PASS

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stderr
LOG_LEVEL=debug
ENVEOF

# Donner accès au .env pour www-data
chown www-data:www-data /var/www/html/.env
chmod 644 /var/www/html/.env

echo "=== Starting php-fpm ==="
/usr/local/sbin/php-fpm -D
sleep 3

echo "=== Copying nginx config ==="
cp /etc/nginx/sites-available/default.template /etc/nginx/sites-available/default
ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

echo "=== Running migrations ==="
php artisan migrate --force 2>&1

echo "=== Starting nginx ==="
exec nginx -g "daemon off;"