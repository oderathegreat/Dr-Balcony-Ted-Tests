# Use an official PHP runtime as a parent image
FROM php:8.0-apache

# Set working directory
WORKDIR /var/www/html

# Install PDO MySQL extension
RUN docker-php-ext-install pdo_mysql

# Copy PHP application files to container
COPY . .

# Copy your SQL dump file (e.g., db.sql) to container
COPY db.sql /docker-entrypoint-initdb.d/

# Expose port 80 for web server
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]

