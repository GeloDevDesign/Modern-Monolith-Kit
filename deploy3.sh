#!/bin/bash

echo "======================================"
echo "🚀 Laravel Deployment"
echo "======================================"

# -------------------------------------------------
# GLOBAL CONFIG (CHANGE ONLY THESE)
# -------------------------------------------------

# Laravel root directory relative to this script
MAIN_DIRECTORY="."

# Git branch to deploy
GIT_BRANCH="main"

# -------------------------------------------------
# PATH RESOLUTION
# -------------------------------------------------

BASE_PATH="$(pwd)"
CORE_DIR="$(cd "$BASE_PATH/$MAIN_DIRECTORY" && pwd)"
PUBLIC_DIR="$BASE_PATH/public_html"

ENV_FILE="$CORE_DIR/.env"
VENDOR_AUTOLOAD="$CORE_DIR/vendor/autoload.php"

# -------------------------------------------------
# SAFETY CHECK
# -------------------------------------------------

if [ ! -f "$CORE_DIR/artisan" ]; then
  echo "❌ Error: Not a Laravel root directory"
  exit 1
fi

cd "$CORE_DIR" || exit 1

# -------------------------------------------------
# DEPLOY LOGIC
# -------------------------------------------------

if [ -f "$VENDOR_AUTOLOAD" ]; then
  echo "✅ Existing installation detected"
  echo "📥 Pulling latest changes from '$GIT_BRANCH'..."

  git pull origin "$GIT_BRANCH" || exit 1

  echo "⚡ Optimizing Laravel..."
  php artisan optimize:clear || true
  php artisan optimize || true

else
  echo "🆕 Fresh installation detected"
  echo "📥 Pulling repository..."

  git pull origin "$GIT_BRANCH" || exit 1

  echo "📦 Installing Composer dependencies..."
  composer install --no-dev --optimize-autoloader --no-interaction || exit 1

  if [ ! -f "$ENV_FILE" ]; then
    echo "🧩 Creating .env file..."

    cat <<EOF > "$ENV_FILE"
APP_NAME=LiveHere
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:ZCpUOeTonq55w+bNW/GEu9v1j7pSfIejDPxQQjcy0rY=
APP_URL=https://livehere-com.us.stackstaging.com/app

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=sdb-85.hosting.stackcp.net
DB_PORT=3306
DB_DATABASE=fdasfdasjkncxapp-353039385098
DB_USERNAME=fdasfdasjkncxapp-353039385098
DB_PASSWORD=28wg9ewdcx

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
EOF
  else
    echo "✅ .env already exists — skipping"
  fi

  echo "🔐 Fixing permissions..."
  chmod -R 775 storage bootstrap/cache || true

  echo "🗄️ Running migrations..."
  php artisan migrate --force || true

  if [ ! -L "$PUBLIC_DIR/storage" ]; then
    echo "🔗 Creating storage symlink..."
    ln -s "$CORE_DIR/storage/app/public" "$PUBLIC_DIR/storage" || true
  fi
fi

# -------------------------------------------------
# PUBLIC ASSETS (ALWAYS RUN)
# -------------------------------------------------

echo "📂 Syncing public assets..."
rsync -av "$CORE_DIR/public/" "$PUBLIC_DIR/" || true

# -------------------------------------------------
# DONE
# -------------------------------------------------

echo "======================================"
echo "✅ Deployment finished (branch: $GIT_BRANCH)"
echo "======================================"
