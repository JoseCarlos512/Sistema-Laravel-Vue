FROM php:7.1.3-apache

# Instala extensiones necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiar el contenido de tu proyecto al contenedor
COPY . /var/www/html/

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias de Composer
RUN composer install --ignore-platform-reqs

# Instalar dependencias de npm
RUN npm ci

# Exponer el puerto 80
EXPOSE 80
