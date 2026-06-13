FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install dependencies and optimize
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Ensure Laravel storage directories exist and set permissions
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views && \
    chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data /var/www/html

# Expose port
EXPOSE 80

# Configure Supervisor and Nginx
COPY deployment/nginx.conf /etc/nginx/nginx.conf
COPY deployment/supervisor.conf /etc/supervisor/conf.d/supervisord.conf

# Start process manager
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
