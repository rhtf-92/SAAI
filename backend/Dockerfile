# Usamos PHP 8.2 con FPM (FastCGI Process Manager) como base
FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones PHP requeridas:
# libpq-dev (para PostgreSQL), git, zip, unzip, etc.
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    # Instalar extensiones PHP necesarias para Laravel y PostgreSQL
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Instalar Composer (gestor de dependencias de PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo del contenedor
WORKDIR /var/www

# Copiar archivos del proyecto de Laravel (todo lo que está en /backend)
COPY . .

# Instalar dependencias de PHP usando Composer (Optimizadas para producción y sin dependencias de desarrollo)
RUN composer install --optimize-autoloader --no-dev

# Ajustar los permisos para las carpetas de almacenamiento y caché de Laravel
# Esto es crítico para que el servidor web pueda escribir logs y cachear vistas
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Definir el comando de inicio para Render
# Usamos 'php artisan serve' para iniciar el servidor interno de Laravel
# Render usará este contenedor y le enviará tráfico.
CMD php artisan serve --host=0.0.0.0 --port=8080

EXPOSE 8080
