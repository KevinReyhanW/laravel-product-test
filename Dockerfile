# Use official PHP image with Composer
FROM php:8.3-apache

# Install extensions and tools
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip sqlite3 \
    && docker-php-ext-install pdo pdo_sqlite

# Enable mod_rewrite for Laravel routes
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install composer dependencies
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
