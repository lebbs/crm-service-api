FROM php:8.2-apache

# System tools needed by Composer packages
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache rewrite
RUN a2enmod rewrite

# Set Apache DocumentRoot to /public
RUN sed -i 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#g' /etc/apache2/sites-available/000-default.conf \
 && printf "%s\n" "<Directory /var/www/html/public>" \
                  "    AllowOverride All" \
                  "    Require all granted" \
                  "</Directory>" >> /etc/apache2/apache2.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files first (better layer caching)
COPY composer.json /var/www/html/composer.json
WORKDIR /var/www/html
RUN composer install --no-interaction --no-progress --prefer-dist

# Copy app source
COPY src/ /var/www/html/

# Optional: tighten permissions
RUN chown -R www-data:www-data /var/www/html
