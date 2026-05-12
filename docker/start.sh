#!/bin/bash
export PORT=${PORT:-8080}

echo "=== PORT is: $PORT ==="

cp /etc/nginx/sites-available/default.template /etc/nginx/sites-available/default
sed -i "s/\${PORT}/$PORT/g" /etc/nginx/sites-available/default
ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

echo "=== nginx config ==="
cat /etc/nginx/sites-available/default

php-fpm -D
sleep 2

echo "=== starting nginx ==="
exec nginx -g "daemon off;"