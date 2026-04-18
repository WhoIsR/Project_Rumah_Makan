# ─────────────────────────────────────────────────
# Stage 1: Build front-end assets (Node + Vite)
# ─────────────────────────────────────────────────
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build

# ─────────────────────────────────────────────────
# Stage 2: PHP production image
# ─────────────────────────────────────────────────
FROM php:8.2-fpm-alpine AS php

# System dependencies
RUN apk add --no-cache \
        bash \
        curl \
        libpng-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        freetype-dev \
        libzip-dev \
        oniguruma-dev \
        icu-dev \
        supervisor \
        nginx \
    && docker-php-ext-configure gd \
           --with-freetype \
           --with-jpeg \
           --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
           gd \
           pdo \
           pdo_mysql \
           mbstring \
           zip \
           exif \
           pcntl \
           intl \
           opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application source
COPY . .

# Copy compiled front-end assets from Stage 1
COPY --from=node-builder /app/public/build ./public/build

# Install PHP dependencies (production only)
RUN composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader

# Set correct ownership
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    && mkdir -p /var/log/supervisor /var/run

# Copy configuration files
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
