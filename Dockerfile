FROM php:8.2-apache

# Extensions PHP pour PostgreSQL
RUN apt-get update && apt-get install -y \
    zip unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring gd

# Copie le code (index.php à la racine)
COPY source/ /var/www/html/

# Apache configuration (avec retours à la ligne)
RUN printf "<Directory /var/www/html>\n    Options Indexes FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>\n" > /etc/apache2/conf-available/myapp.conf

# Activer la conf et le module rewrite
RUN a2enconf myapp
RUN a2enmod rewrite

EXPOSE 80

# docker compose exec pg_db psql -U postgres
# docker compose exec db mysql -u root -p 