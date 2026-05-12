#!/bin/bash
set -e
php-fpm -D
sleep 1
nginx -g "daemon off;"