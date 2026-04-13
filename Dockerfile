FROM php:7.4-apache

# Habilitar mod_rewrite (buena práctica)
RUN a2enmod rewrite

# Permisos (opcional pero recomendable)
RUN chown -R www-data:www-data /var/www/html