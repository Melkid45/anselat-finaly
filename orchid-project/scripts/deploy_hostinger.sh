#!/usr/bin/env bash
set -Eeuo pipefail

PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
DEPLOY_BRANCH="${DEPLOY_BRANCH:-main}"
DEPLOY_WITH_GIT_PULL="${DEPLOY_WITH_GIT_PULL:-0}"

if [[ ! -f artisan ]]; then
  echo "[deploy] ERROR: artisan file not found. Run this script from Laravel project root."
  exit 1
fi

if ! command -v "$PHP_BIN" >/dev/null 2>&1; then
  echo "[deploy] ERROR: PHP binary '$PHP_BIN' not found."
  exit 1
fi

if ! command -v "$COMPOSER_BIN" >/dev/null 2>&1; then
  echo "[deploy] ERROR: Composer binary '$COMPOSER_BIN' not found."
  exit 1
fi

echo "[deploy] Starting deploy in $(pwd)"

cleanup() {
  "$PHP_BIN" artisan up >/dev/null 2>&1 || true
}
trap cleanup EXIT

if [[ "$DEPLOY_WITH_GIT_PULL" == "1" ]]; then
  if command -v git >/dev/null 2>&1; then
    echo "[deploy] Fetching latest code for branch '$DEPLOY_BRANCH'"
    git fetch --all --prune
    git checkout "$DEPLOY_BRANCH"
    git pull --ff-only origin "$DEPLOY_BRANCH"
  else
    echo "[deploy] WARNING: git not found, skipping pull."
  fi
fi

echo "[deploy] Enabling maintenance mode"
"$PHP_BIN" artisan down --retry=60 || true

echo "[deploy] Installing composer dependencies"
"$COMPOSER_BIN" install --no-dev --prefer-dist --no-interaction --optimize-autoloader

echo "[deploy] Clearing old caches"
"$PHP_BIN" artisan optimize:clear

echo "[deploy] Running migrations"
"$PHP_BIN" artisan migrate --force

echo "[deploy] Ensuring storage symlink"
"$PHP_BIN" artisan storage:link || true

echo "[deploy] Caching config/routes/views"
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache

echo "[deploy] Restarting queue workers (if any)"
"$PHP_BIN" artisan queue:restart || true

echo "[deploy] Fixing writable permissions"
chmod -R ug+rw storage bootstrap/cache || true

echo "[deploy] Bringing app up"
"$PHP_BIN" artisan up || true

echo "[deploy] Done."
