FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip

COPY . /var/www/html/

# Aponta para a pasta public (ajustado para sua estrutura)
RUN sed -i 's|/var/www/html|/var/www/html/neucat_store/public|' /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80
