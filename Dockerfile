# Use a imagem base do PHP com suporte ao Lumen
FROM php:8.1-apache

# Copie o conteúdo do diretório app para o diretório /var/www/html no contêiner
COPY ./app /var/www/html

# Instale as dependências do PHP necessárias para o Lumen
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip

RUN docker-php-ext-install pdo pdo_mysql

# Instale o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Defina a variável de ambiente para permitir a execução do Composer como superusuário
ENV COMPOSER_ALLOW_SUPERUSER 1

# Defina as permissões corretas para o diretório de armazenamento do Lumen
RUN chown -R www-data:www-data /var/www/html/storage

# Execute o composer install para instalar as dependências
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader

# Atualize a configuração do Apache para definir o diretório raiz para /var/www/html/public
RUN sed -i -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && sed -i -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/default-ssl.conf

# Ative o módulo mod_rewrite do Apache
RUN a2enmod rewrite

# Exponha a porta 80 para o Apache
EXPOSE 80

# Inicie o Apache quando o contêiner for iniciado
CMD ["apache2-foreground"]
