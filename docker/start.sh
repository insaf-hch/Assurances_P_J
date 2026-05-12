#!/bin/bash
export PORT=${PORT:-8080}

# Copier le template et substituer le port
cp /etc/nginx/sites-available/default.template /etc/nginx/sites-available/default
sed -i "s/\${PORT}/$PORT/g" /etc/nginx/sites-available/default
ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Démarrer php-fpm en arrière-plan
php-fpm -D
sleep 2

# Démarrer nginx au premier plan
exec nginx -g "daemon off;"