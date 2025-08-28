# Base PHP 8.2 FPM image
FROM php:8.2-fpm

# Arguments for user/group
ARG WWWUSER=1000
ARG WWWGROUP=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    zip \
    sudo \
    npm \
    nodejs \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create system user
RUN groupadd -g ${WWWGROUP} sail \
    && useradd -u ${WWWUSER} -ms /bin/bash -g sail sail \
    && usermod -aG sudo sail \
    && chown -R sail:sail /var/www/html

WORKDIR /var/www/html

USER sail

# Expose PHP-FPM port
EXPOSE 9000

CMD ["php-fpm"]
