FROM php:8.2-apache

# Installer les dépendances
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    nodejs \
    npm

# Installer les extensions PHP
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Configurer le document root d'Apache pour pointer vers le dossier public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet
COPY . /var/www/html

# Copier le fichier .env.example vers .env
RUN cp .env.example .env

# Installer les dépendances PHP
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Installer les dépendances Node.js
RUN npm install && npm run build

# Générer la clé d'application
RUN php artisan key:generate

# Exécuter les migrations et les seeders (commenté pour éviter les erreurs si la base de données n'est pas configurée)
# RUN php artisan migrate --seed --force

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port 80
EXPOSE 80

# Créer un script d'entrée pour exécuter les migrations au démarrage
RUN echo '#!/bin/bash\n\
if [ "$DB_CONNECTION" != "" ]; then\n\
    php artisan migrate --force\n\
    if [ "$SEED_DB" = "true" ]; then\n\
        php artisan db:seed --force\n\
    fi\n\
fi\n\
apache2-foreground' > /usr/local/bin/start-apache.sh

RUN chmod +x /usr/local/bin/start-apache.sh

# Démarrer Apache avec notre script
CMD ["/usr/local/bin/start-apache.sh"]