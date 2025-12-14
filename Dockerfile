FROM php:8.2-fpm

# -----------------------------
# Dependencias del sistema
# -----------------------------
RUN apt-get update && apt-get install -y \
    nginx \
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
    && rm -rf /var/lib/apt/lists/*

# -----------------------------
# Extensiones PHP (MySQL)
# -----------------------------
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
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
# Node.js 20
# -----------------------------
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# -----------------------------
# Directorio de trabajo
# -----------------------------
WORKDIR /var/www/html

# -----------------------------
# Copiar configuración nginx
# -----------------------------
COPY nginx.conf /etc/nginx/sites-available/default

# -----------------------------
# Copiar proyecto
# -----------------------------
COPY . .

# -----------------------------
# Dependencias PHP
# -----------------------------
RUN composer install --no-dev --optimize-autoloader

# -----------------------------
# Frontend
# -----------------------------
RUN npm install && npm run build

# -----------------------------
# Permisos
# -----------------------------
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache public \
    && chmod +x start.sh

# -----------------------------
# Puerto Render
# -----------------------------
EXPOSE 10000

# -----------------------------
# Arranque
# -----------------------------
CMD ["./start.sh"]
