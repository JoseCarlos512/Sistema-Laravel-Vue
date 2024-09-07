FROM php:7.1.3-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiar el contenido de tu proyecto al contenedor
COPY . /var/www/html/

# Configurar permisos, si es necesario
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80
EXPOSE 80
