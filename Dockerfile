FROM php:8.2-apache
RUN docker-php-ext-install pdo_mysql && a2enmod rewrite
WORKDIR /var/www/html
COPY . .
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader && chown -R www-data:www-data storage bootstrap/cache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf
CMD ["apache2-foreground"]
