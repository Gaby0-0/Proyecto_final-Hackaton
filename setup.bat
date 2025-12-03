@echo off
echo ========================================
echo  ConcursITO - Setup del Sistema Admin
echo ========================================
echo.

echo [1/7] Limpiando caches...
call php artisan config:clear
call php artisan route:clear
call php artisan view:clear
echo OK!
echo.

echo [2/7] Instalando dependencias de Composer...
call composer install
echo OK!
echo.

echo [3/7] Instalando dependencias de NPM...
call npm install
echo OK!
echo.

echo [4/7] Ejecutando migraciones...
call php artisan migrate
echo OK!
echo.

echo [5/7] Ejecutando seeders (opcional)...
set /p SEED="Ejecutar seeders para datos de prueba? (S/N): "
if /i "%SEED%"=="S" (
    call php artisan db:seed
    echo Seeders ejecutados!
    echo Usuario admin creado: admin@admin.com / admin123
) else (
    echo Seeders omitidos. Deberas crear un usuario admin manualmente.
)
echo.

echo [6/7] Compilando assets de Tailwind...
echo Esto puede tardar un momento...
start /B cmd /c "npm run build"
timeout /t 3 >nul
echo OK!
echo.

echo [7/7] Limpiando caches finales...
call php artisan config:clear
call php artisan view:clear
echo OK!
echo.

echo ========================================
echo  INSTALACION COMPLETADA!
echo ========================================
echo.
echo Para iniciar el servidor:
echo   php artisan serve
echo.
echo Para compilar assets en modo desarrollo:
echo   npm run dev
echo.
echo Acceder al sistema:
echo   URL: http://localhost:8000/admin
echo   Email: admin@admin.com
echo   Password: admin123
echo.
echo Documentacion completa en:
echo   - MODULO_ADMIN_README.md
echo   - VERIFICACION_SISTEMA.md
echo.
pause
