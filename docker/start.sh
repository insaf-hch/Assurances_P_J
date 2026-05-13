#!/bin/bash
echo "=== Starting php-fpm ==="
/usr/local/sbin/php-fpm -D
sleep 3

echo "=== Setting up .env ==="
cd /var/www/html

# Extraire depuis MYSQL_URL avec awk (plus fiable que sed)
PARSED_USER=$(echo "$MYSQL_URL" | awk -F'[@/:]' '{print $4}')
PARSED_PASS=$(echo "$MYSQL_URL" | awk -F'@' '{print $1}' | awk -F'://' '{print $2}' | awk -F':' '{print $2}')
PARSED_HOST=$(echo "$MYSQL_URL" | awk -F'@' '{print $2}' | awk -F':' '{print $1}')
PARSED_PORT=$(echo "$MYSQL_URL" | awk -F'@' '{print $2}' | awk -F'[:/]' '{print $2}')
PARSED_DB=$(echo "$MYSQL_URL" | awk -F'/' '{print $NF}')

echo "HOST=$PARSED_HOST"
echo "PORT=$PARSED_PORT"
echo "DB=$PARSED_DB"

cat > .env << ENVEOF
APP_NAME=Laravel
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_URL=${APP_URL}

DB_CONNECTION=mysql
DB_HOST=${PARSED_HOST}
DB_PORT=${PARSED_PORT}
DB_DATABASE=${PARSED_DB}
DB_USERNAME=${PARSED_USER}
DB_PASSWORD=${PARSED_PASS}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stderr
LOG_LEVEL=debug
ENVEOF

echo "=== Copying nginx config ==="
cp /etc/nginx/sites-available/default.template /etc/nginx/sites-available/default
ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

echo "=== Running migrations ==="
php artisan migrate --force 2>&1

echo "=== Starting nginx ==="
exec nginx -g "daemon off;"