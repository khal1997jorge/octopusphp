FROM php:8.4-apache

# Configurar apache en contenedor
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite

# Instalar librerias para conexion a MySQL
RUN apt-get update \
    && docker-php-ext-install mysqli \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copiar proyecto al contenedor
COPY public /var/www/html/
COPY src /var/www/html/src/
COPY config /var/www/html/config/

RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html