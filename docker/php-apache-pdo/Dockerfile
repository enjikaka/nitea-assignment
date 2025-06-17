FROM php:8.4.8-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite
RUN mkdir -p /var/www/html/logs && chown -R www-data:www-data /var/www/html/logs && chmod 777 /var/www/html/logs
