#!/bin/bash
export PORT=${PORT:-80}
sed -i "s/\$PORT/$PORT/g" /etc/nginx/sites-available/default
php-fpm -D
sleep 2
exec nginx -g "daemon off;"