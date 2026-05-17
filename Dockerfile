FROM php:8.2-apache

# Menginstal ekstensi server yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git sqlite3 libsqlite3-dev

# Mengaktifkan URL Rewrite Apache (Wajib untuk Laravel)
RUN a2enmod rewrite

# Menginstal ekstensi PHP untuk SQLite dan Zip
RUN docker-php-ext-install pdo_sqlite zip

# Menginstal Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Menentukan lokasi folder web
WORKDIR /var/www/html

# Menyalin seluruh file proyek Anda ke dalam server
COPY . /var/www/html

# Menginstal modul Laravel
RUN composer install --optimize-autoloader --no-dev

# Otomatisasi Database & Environment
RUN touch database/database.sqlite
RUN cp .env.example .env
RUN php artisan key:generate

# Mengatur hak akses folder agar tidak error
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache database/

# Mengarahkan Apache langsung ke folder /public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80

# Perintah wajib saat server menyala: Migrate DB & Jalankan Apache
CMD php artisan migrate --force && apache2-foreground
