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

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet
COPY . /var/www/html

# Installer les dépendances PHP
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Installer les dépendances Node.js
RUN npm install && npm run build

# Générer la clé d'application
RUN php artisan key:generate

# Exécuter les migrations et les seeders
RUN php artisan migrate --seed --force

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port 80
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]