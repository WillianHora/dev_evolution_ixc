FROM php:8.2-apache-bookworm

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    pkg-config \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_sqlite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

RUN chown -R www-data:www-data /var/www/html/src/database

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

EXPOSE 80