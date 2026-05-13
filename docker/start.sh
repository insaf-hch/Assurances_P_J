#!/bin/bash
echo "=== Starting php-fpm ==="
/usr/local/sbin/php-fpm -D
sleep 3

cd /var/www/html

export DB_HOST=$(echo "$MYSQL_URL" | awk -F'@' '{print $2}' | awk -F':' '{print $1}')
export DB_PORT=3306
export DB_DATABASE=$(echo "$MYSQL_URL" | awk -F'/' '{print $NF}')
export DB_USERNAME=root
export DB_PASSWORD=$(echo "$MYSQL_URL" | sed 's|mysql://[^:]*:\(.*\)@.*|\1|')
export DB_CONNECTION=mysql
export APP_KEY=$APP_KEY
export APP_ENV=production
export APP_DEBUG=true
export APP_URL=$APP_URL
export SESSION_DRIVER=database
export CACHE_STORE=database
export LOG_CHANNEL=stderr

echo "DB_HOST=$DB_HOST"
echo "DB_DATABASE=$DB_DATABASE"

# Vider complètement le .env pour forcer Laravel à utiliser les variables d'environnement
echo "" > .env

php artisan config:clear
php artisan cache:clear

echo "=== Copying nginx config ==="
cp /etc/nginx/sites-available/default.template /etc/nginx/sites-available/default
ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

echo "=== Running migrations ==="
php artisan migrate --force 2>&1

echo "=== Starting nginx ==="
exec nginx -g "daemon off;"