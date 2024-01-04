FROM php:8.2.9-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql mysqli

# Install GD library and other required packages
RUN apt-get install -y libpng-dev libjpeg-dev && \
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd

COPY custom-php.ini /usr/local/etc/php/php.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy your application code
COPY src/ /var/www/html/

# Modify httpd.conf to enable AllowOverride
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Enable mod_rewrite
RUN a2enmod rewrite

# Install and enable xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Create a non-root user
RUN addgroup --gid 1000 mygroup && \
    adduser --system --no-create-home --disabled-password --disabled-login --uid 1000 --ingroup mygroup myuser

# Change ownership of files
RUN chown -R myuser:mygroup /var/www
USER myuser

# Set working directory
WORKDIR /var/www/html