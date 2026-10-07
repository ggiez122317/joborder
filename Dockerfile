# ---- Frontend build ----
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json vite.config.js tailwind.config.js postcss.config.js ./
RUN npm ci
COPY resources ./resources
COPY public ./public
RUN npm run build

# ---- PHP runtime ----
FROM php:8.2-apache

# System deps + PHP extensions needed by Laravel, maatwebsite/excel,
# dompdf, QR generation and MySQL.
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libpng-dev libjpeg-dev libfreetype6-dev \
        libzip-dev libicu-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql mbstring gd zip exif pcntl bcmath intl opcache \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists

# Apache must serve Laravel's public/ directory.
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP deps first (better layer caching).
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Application code + built frontend assets.
COPY . .
COPY --from=frontend /app/public/build ./public/build

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]
