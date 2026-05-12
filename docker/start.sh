#!/bin/bash
php-fpm -D
sleep 2
exec nginx -g "daemon off;"