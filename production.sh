#!/bin/bash

# -----------------------------------------------
# AUTOMATIC PATHS (deploy.sh inside prime-laravel-template)
# -----------------------------------------------

CORE_DIR="$(pwd)"
PUBLIC_DIR="$CORE_DIR/../public_html"
BRANCH="main"

echo "=============================================="
echo "🚀 Laravel Deployment Starting"
echo "📂 Core Path: $CORE_DIR"
echo "📂 Public Path: $PUBLIC_DIR"
echo "🌿 Git Branch: $BRANCH"
echo "=============================================="

# Safety check
if [ ! -f "$CORE_DIR/artisan" ]; then
    echo "❌ ERROR: Laravel root not found in $CORE_DIR. Deployment aborted."
    exit 1
fi

# -----------------------------------------------
# GIT PULL
# -----------------------------------------------
echo "📥 Pulling latest changes from '$BRANCH'..."
git pull origin "$BRANCH" || echo "⚠️ Git pull may have failed. Please check manually."

# -----------------------------------------------
# COMPOSER INSTALL / UPDATE
# -----------------------------------------------
if [ -d "$CORE_DIR/vendor" ]; then
    echo "✅ Composer dependencies already installed."
    read -p "Do you want to update dependencies or do a fresh install? (u = update / f = fresh / n = skip): " composer_mode < /dev/tty

    if [ "$composer_mode" == "u" ]; then
        echo "📦 Running composer update..."
        composer update --no-dev --optimize-autoloader --no-interaction || echo "⚠️ Composer update failed. Continuing deployment."
    elif [ "$composer_mode" == "f" ]; then
        echo "🧹 Removing vendor for fresh install..."
        rm -rf "$CORE_DIR/vendor"
        echo "📦 Performing fresh Composer install..."
        composer install --no-dev --optimize-autoloader --no-interaction || echo "⚠️ Composer install failed. Continuing deployment."
    else
        echo "Skipping Composer installation/update."
    fi
else
    echo "🆕 Fresh deployment detected. Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction || echo "⚠️ Composer install failed. Continuing deployment."
fi

# -----------------------------------------------
# APP KEY
# -----------------------------------------------
if ! grep -q "APP_KEY=" "$CORE_DIR/.env" 2>/dev/null; then
    echo "🔑 Generating APP_KEY..."
    php artisan key:generate --force
fi

# -----------------------------------------------
# SYNC PUBLIC FILES
# -----------------------------------------------
echo "🚚 Syncing public files to public_html..."
rsync -av "$CORE_DIR/public/" "$PUBLIC_DIR/"

echo "🔧 Patching public_html/index.php paths..."
sed -i "s|/../vendor|/../prime-laravel-template/vendor|g" "$PUBLIC_DIR/index.php"
sed -i "s|/../bootstrap|/../prime-laravel-template/bootstrap|g" "$PUBLIC_DIR/index.php"
sed -i "s|/../storage|/../prime-laravel-template/storage|g" "$PUBLIC_DIR/index.php"

# -----------------------------------------------
# STORAGE SYMLINK
# -----------------------------------------------
echo "🔗 Setting up storage symlink..."

if [ -L "$PUBLIC_DIR/storage" ]; then
    echo "⚠️ Storage symlink already exists."
    read -p "Do you want to update/recreate it? (y = yes / n = no): " symlink_update < /dev/tty
    if [ "$symlink_update" == "y" ]; then
        rm -rf "$PUBLIC_DIR/storage"
        ln -s "$CORE_DIR/storage/app/public" "$PUBLIC_DIR/storage"
        echo "✅ Storage symlink updated."
    else
        echo "ℹ️ Keeping existing storage symlink."
    fi
elif [ -d "$PUBLIC_DIR/storage" ]; then
    echo "⚠️ A regular storage folder exists in public_html."
    read -p "Do you want to replace it with a symlink? (y = yes / n = no): " symlink_replace < /dev/tty
    if [ "$symlink_replace" == "y" ]; then
        rm -rf "$PUBLIC_DIR/storage"
        ln -s "$CORE_DIR/storage/app/public" "$PUBLIC_DIR/storage"
        echo "✅ Storage folder replaced with symlink."
    else
        echo "ℹ️ Keeping existing storage folder."
    fi
else
    ln -s "$CORE_DIR/storage/app/public" "$PUBLIC_DIR/storage"
    echo "✅ Storage symlink created."
fi

# -----------------------------------------------
# MIGRATIONS (INTERACTIVE)
# -----------------------------------------------
echo "🗄️ Database Setup..."
read -p "Run migrations? (y = yes / f = fresh / n = skip): " migrate_mode < /dev/tty

if [ "$migrate_mode" == "y" ]; then
    echo "🚀 Running migrations..."
    php artisan migrate --force
elif [ "$migrate_mode" == "f" ]; then
    read -p "⚠️ Confirm database wipe? (yes/no): " confirm < /dev/tty
    if [ "$confirm" == "yes" ]; then
        echo "💥 Running fresh migrations with seeding..."
        php artisan migrate:fresh --seed --force
    else
        echo "ℹ️ Fresh migration canceled."
    fi
else
    echo "ℹ️ Skipping migrations."
fi

# -----------------------------------------------
# CLEAR CACHES
# -----------------------------------------------
echo "🧹 Clearing caches..."
php artisan optimize:clear
php artisan view:cache
php artisan config:cache
php artisan route:cache

echo "=============================================="
echo "✅ Deployment Finished Successfully!"
echo "=============================================="
