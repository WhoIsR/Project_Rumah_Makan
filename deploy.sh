#!/usr/bin/env bash
# deploy.sh — Zero-downtime deployment script for a bare VPS (without Docker).
#
# Usage on the server:
#   bash deploy.sh
#
# Requirements:
#   - PHP 8.2+, Composer, Node 20+, npm, git installed
#   - Web root symlink:  /var/www/html  ->  /var/www/releases/current
#   - First run:  sudo mkdir -p /var/www/{releases,shared}
#                 sudo chown -R $USER:www-data /var/www
#                 cp .env.production.example /var/www/shared/.env
#                 # edit /var/www/shared/.env with real values
#
# On subsequent deploys this script:
#   1. Clones the latest code into a timestamped release directory
#   2. Shares persistent directories (storage, .env) via symlinks
#   3. Installs dependencies and builds assets
#   4. Warms all Laravel caches
#   5. Puts the app into maintenance mode, runs migrations, then swaps symlink
#   6. Restarts PHP-FPM and queue workers
#   7. Removes old releases (keeps last 5)
# ──────────────────────────────────────────────────────────────────────────────

set -euo pipefail

# ─── Configuration ────────────────────────────────────────────────────────────
REPO_URL="${REPO_URL:-https://github.com/WhoIsR/Project_Rumah_Makan.git}"
BRANCH="${BRANCH:-main}"
BASE_DIR="/var/www"
RELEASES_DIR="$BASE_DIR/releases"
SHARED_DIR="$BASE_DIR/shared"
CURRENT_LINK="$BASE_DIR/current"
KEEP_RELEASES=5
TIMESTAMP=$(date +"%Y%m%d%H%M%S")
RELEASE_DIR="$RELEASES_DIR/$TIMESTAMP"

echo "▶  Deploying branch '$BRANCH' → $RELEASE_DIR"

# ─── 1. Clone ────────────────────────────────────────────────────────────────
git clone --depth=1 --branch "$BRANCH" "$REPO_URL" "$RELEASE_DIR"
cd "$RELEASE_DIR"

# ─── 2. Shared symlinks ───────────────────────────────────────────────────────
# Create shared directories if this is the first deploy
mkdir -p "$SHARED_DIR/storage/app/public" \
         "$SHARED_DIR/storage/framework/"{cache/data,sessions,testing,views} \
         "$SHARED_DIR/storage/logs"

# Remove placeholder dirs from the clone, then link to shared
rm -rf storage
ln -nfs "$SHARED_DIR/storage"     storage
ln -nfs "$SHARED_DIR/.env"        .env

# ─── 3. PHP dependencies ──────────────────────────────────────────────────────
composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# ─── 4. Front-end assets ──────────────────────────────────────────────────────
npm ci
npm run build

# ─── 5. Laravel production caches ────────────────────────────────────────────
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# ─── 6. Storage link ──────────────────────────────────────────────────────────
php artisan storage:link

# ─── 7. Maintenance mode + migrations ────────────────────────────────────────
# Use the 'down' token so the old release still serves the 503 page
php artisan down --retry=15 --secret="deploy-$(date +%s)" 2>/dev/null || true

php artisan migrate --force

# ─── 8. Swap the 'current' symlink (atomic) ───────────────────────────────────
ln -nfs "$RELEASE_DIR" "$CURRENT_LINK"

# ─── 9. Bring app back up ────────────────────────────────────────────────────
php artisan up

# ─── 10. Restart services ────────────────────────────────────────────────────
# Reload PHP-FPM (gracefully)
if systemctl is-active --quiet php8.2-fpm; then
    sudo systemctl reload php8.2-fpm
fi

# Restart queue workers managed by Supervisor
if command -v supervisorctl &>/dev/null; then
    sudo supervisorctl restart "laravel-queue:*" || true
fi

# ─── 11. Permissions ─────────────────────────────────────────────────────────
sudo chown -R www-data:www-data "$CURRENT_LINK/bootstrap/cache"
sudo chmod -R 775 "$CURRENT_LINK/bootstrap/cache"

# ─── 12. Remove old releases ─────────────────────────────────────────────────
echo "▶  Pruning old releases (keeping $KEEP_RELEASES)"
ls -1dt "$RELEASES_DIR"/* | tail -n +$((KEEP_RELEASES + 1)) | xargs rm -rf

echo "✅  Deployment complete → $RELEASE_DIR"
