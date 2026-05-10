# Dockerfile para PHP + Apache
FROM php:8.2-apache

# Instala extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip

# Copia todos os arquivos do projeto
COPY . /var/www/html/

# Define o diretório raiz como /public
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Ativa mod_rewrite (muito importante para URLs amigáveis)
RUN a2enmod rewrite

# Permissões de pastas
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
