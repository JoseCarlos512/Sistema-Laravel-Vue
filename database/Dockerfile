# Usa una imagen base con PHP 7.1.3
FROM php:7.1.3-apache

# Instala las dependencias necesarias
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libicu-dev libxml2-dev
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install gd zip intl xml

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia el código fuente al contenedor
COPY . /var/www/html

# Configura permisos
RUN chown -R www-data:www-data /var/www/html

# Instala las dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Configura el entorno
RUN cp .env.example .env
RUN php artisan key:generate

# Expone el puerto 80
EXPOSE 80
