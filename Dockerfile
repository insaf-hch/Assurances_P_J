FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libonig-dev libjpeg-dev libfreetype6-dev nginx \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd \
    && rm -f /etc/nginx/sites-enabled/default

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

RUN cp .env.example .env && php artisan key:generate --force && echo "" > .env

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/nginx.conf.template /etc/nginx/sites-available/default.template
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

ENTRYPOINT []
CMD ["/bin/bash", "/start.sh"]