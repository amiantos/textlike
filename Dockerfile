FROM php:5.6-apache

# Install the legacy mysql extension
RUN docker-php-ext-install mysql

# Enable Apache mod_rewrite for .htaccess support
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html/

# Set proper ownership
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
