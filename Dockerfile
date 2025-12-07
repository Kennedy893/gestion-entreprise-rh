# Utilise PHP avec Apache (pas FPM)
FROM php:8.2-apache

# Installe les extensions nécessaires pour MySQL et GD
RUN apt-get update && apt-get install -y \
    zip unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring gd

# Active mod_rewrite pour Flight
RUN a2enmod rewrite

# Configure Apache pour autoriser .htaccess
RUN echo "<Directory /var/www/html/>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" > /etc/apache2/conf-available/app.conf \
    && a2enconf app

# Copie ton projet Flight dans le container
COPY source/ /var/www/html/

# Donne les bons droits
RUN chown -R www-data:www-data /var/www/html

# Expose le port HTTP
EXPOSE 80

# Commande par défaut (Apache)
CMD ["apache2-foreground"]
