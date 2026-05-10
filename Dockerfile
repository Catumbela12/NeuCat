FROM php:8.2-cli

# Instala a extensão PDO MySQL necessária para o banco de dados
RUN docker-php-ext-install pdo pdo_mysql

# Copia todos os arquivos do projeto para a pasta /app no container
COPY . /app
WORKDIR /app

# Inicia o servidor PHP rodando na porta correta do Render e usando o router.php
CMD php -S 0.0.0.0:$PORT -t public router.php
