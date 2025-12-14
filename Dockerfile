FROM php:8.2-fpm

# -----------------------------
# Dependencias del sistema
# -----------------------------
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# -----------------------------
# Extensiones PHP
# -----------------------------
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache

# -----------------------------
# Composer
# -----------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# -----------------------------
# Node.js 20 + npm
# -----------------------------
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# -----------------------------
# Directorio de trabajo
# -----------------------------
WORKDIR /var/www/html

# -----------------------------
# Copiar proyecto
# -----------------------------
COPY . .

# -----------------------------
# Instalar dependencias PHP
# -----------------------------
RUN composer install --no-dev --optimize-autoloader

# -----------------------------
# Instalar dependencias frontend y compilar
# -----------------------------
RUN npm install && npm run build

# -----------------------------
# Permisos
# -----------------------------
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# -----------------------------
# Puerto expuesto (Render)
# -----------------------------
EXPOSE 10000

# -----------------------------
# Arranque de Laravel
# -----------------------------
CMD php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan serve --host=0.0.0.0 --port=10000
