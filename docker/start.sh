#!/bin/bash
echo "=== Starting php-fpm ==="
/usr/local/sbin/php-fpm -D
sleep 3

echo "=== Setting up .env ==="
cd /var/www/html

# Extraire host, port, database depuis MYSQL_URL
export DB_HOST=$(echo "$MYSQL_URL" | awk -F'@' '{print $2}' | awk -F':' '{print $1}')
export DB_PORT=$(echo "$MYSQL_URL" | awk -F'@' '{print $2}' | awk -F'[:/]' '{print $2}')
export DB_DATABASE=$(echo "$MYSQL_URL" | awk -F'/' '{print $NF}')
# Pour user et password, utiliser les variables Railway directement
export DB_USERNAME=root
export DB_PASSWORD=$(echo "$MYSQL_URL" | sed 's|mysql://[^:]*:\(.*\)@.*|\1|')

echo "HOST=$DB_HOST"
echo "PORT=$DB_PORT"  
echo "DB=$DB_DATABASE"
echo "USER=$DB_USERNAME"
echo "PASS_LENGTH=${#DB_PASSWORD}"

cat > .env << ENVEOF
APP_NAME=Laravel
APP_ENV=production
APP_KEY=$APP_KEY
APP_DEBUG=false
APP_URL=$APP_URL

DB_CONNECTION=mysql
DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_DATABASE=$DB_DATABASE
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD

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