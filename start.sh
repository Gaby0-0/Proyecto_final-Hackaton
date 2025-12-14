#!/bin/bash

# Ejecutar migraciones
php artisan migrate --force
php artisan db:seed --force 
# Limpiar y cachear configuración
php artisan config:clear
php artisan config:cache
php artisan route:cache

# Iniciar PHP-FPM en segundo plano
php-fpm -D

# Iniciar nginx en primer plano
nginx -g "daemon off;"
