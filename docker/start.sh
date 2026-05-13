#!/bin/bash
echo "=== Starting php-fpm ==="
/usr/local/sbin/php-fpm -D
sleep 3

echo "=== ENV VARS DEBUG ==="
echo "MYSQL_URL=$MYSQL_URL"

echo "=== Setting up .env ==="
cd /var/www/html

# Parser MYSQL_URL pour extraire les composants
DB_HOST=$(echo $MYSQL_URL | sed 's/.*@\(.*\):.*/\1/')
DB_PORT=$(echo $MYSQL_URL | sed 's/.*:\([0-9]*\)\/.*/\1/')
DB_DATABASE=$(echo $MYSQL_URL | sed 's/.*\/\(.*\)/\1/')
DB_USERNAME=$(echo $MYSQL_URL | sed 's/.*\/\/\(.*\):.*/\1/')
DB_PASSWORD=$(echo $MYSQL_URL | sed 's/.*:\/\/[^:]*:\(.*\)@.*/\1/')

echo "DB_HOST=$DB_HOST"
echo "DB_PORT=$DB_PORT"
echo "DB_DATABASE=$DB_DATABASE"

cat > .env << EOF
APP_NAME=Laravel
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_URL=${APP_URL}

DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stderr
LOG_LEVEL=debug
EOF

echo "=== Copying nginx config ==="
cp /etc/nginx/sites-available/default.template /etc/nginx/sites-available/default
ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

echo "=== Running migrations ==="
php artisan migrate --force 2>&1

echo "=== Starting nginx ==="
exec nginx -g "daemon off;"