FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libonig-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && a2enmod rewrite

COPY .env.example .env
RUN php artisan key:generate

RUN chown -R www-data:www-data /var/www/html/storage

EXPOSE 80

CMD ["apache2-foreground"]
