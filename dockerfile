FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev zlib1g-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && docker-php-ext-enable intl zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Set proper permissions
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port jika menggunakan `artisan serve`
EXPOSE 8000

# Alternatif: Jika tidak menggunakan Nginx dan hanya ingin pakai `artisan serve`
# CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
