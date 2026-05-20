#!/bin/sh
set -e

# Railway inject $PORT — substitute ke nginx.conf
PORT=${PORT:-8080}
echo "Starting on port $PORT"
sed -i "s/\${PORT}/$PORT/g" /etc/nginx/nginx.conf

# Pastikan APP_KEY ada — kalau env tidak di-set, generate
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "WARNING: APP_KEY tidak di-set. Generating..."
    php artisan key:generate --force --no-interaction
fi

# Pastikan storage permissions OK
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Symlink storage (untuk file uploads)
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link --no-interaction || true
fi

# Wait for database (railway kadang butuh waktu boot MySQL)
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database at $DB_HOST:$DB_PORT..."
    timeout=30
    while ! nc -z "$DB_HOST" "${DB_PORT:-3306}" 2>/dev/null; do
        timeout=$((timeout - 1))
        if [ $timeout -le 0 ]; then
            echo "Database connection timeout — continuing anyway..."
            break
        fi
        sleep 1
    done
fi

# Cache config + routes + views (production optimization)
php artisan config:cache --no-interaction || true
php artisan route:cache --no-interaction || true
php artisan view:cache --no-interaction || true

# Run database migrations (kalau RUN_MIGRATIONS=true)
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force --no-interaction || echo "Migrations failed — continuing"
fi

# Run seeders (kalau RUN_SEEDERS=true) — hati-hati, hanya pertama kali
if [ "$RUN_SEEDERS" = "true" ]; then
    echo "Running seeders..."
    php artisan db:seed --force --no-interaction || echo "Seeders failed — continuing"
fi

exec "$@"
