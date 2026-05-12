#!/bin/bash
echo "=== Starting php-fpm ==="
/usr/local/sbin/php-fpm -D
sleep 3

echo "=== Copying nginx config ==="
cp /etc/nginx/sites-available/default.template /etc/nginx/sites-available/default
ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

echo "=== Running migrations ==="
cd /var/www/html && php artisan migrate --force 2>&1

echo "=== Laravel logs ==="
tail -f /var/www/html/storage/logs/laravel.log &

echo "=== Starting nginx ==="
exec nginx -g "daemon off;"