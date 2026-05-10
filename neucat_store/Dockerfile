FROM php:8.2-apache

# Instala extensões básicas do PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip

# Copia os arquivos do projeto para o container
COPY . /var/www/html/

# Configura o Apache para usar a pasta "public" como raiz (muito importante)
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Ativa o mod_rewrite (para links bonitos)
RUN a2enmod rewrite

# Permissões corretas
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
