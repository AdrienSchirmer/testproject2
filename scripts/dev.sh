#!/usr/bin/env bash

set -euo pipefail

colors="#93c5fd,#c4b5fd,#fb7185,#fdba74"

inside_sail() {
    [[ "${LARAVEL_SAIL:-}" == "1" ]]
}

uses_sail() {
    [[ -x ./vendor/bin/sail ]] && [[ -f ./.env ]] && grep -Eq '^DB_HOST=mysql$' ./.env
}

prepare_host_sail_frontend() {
    if ! ./vendor/bin/sail node -e "require('rollup'); require('vite/package.json');" >/dev/null 2>&1; then
        ./vendor/bin/sail root-shell -c "mkdir -p /var/www/html/node_modules && chown -R sail:sail /var/www/html/node_modules"
        ./vendor/bin/sail npm install
    fi
}

prepare_container_frontend() {
    if ! node -e "require('rollup'); require('vite/package.json');" >/dev/null 2>&1; then
        npm install
    fi
}

cleanup_container_dev_processes() {
    pkill -f "php artisan queue:listen --tries=1 --timeout=0" >/dev/null 2>&1 || true
    pkill -f "php artisan pail --timeout=0" >/dev/null 2>&1 || true
    pkill -f "vite" >/dev/null 2>&1 || true
}

if inside_sail; then
    prepare_container_frontend
    cleanup_container_dev_processes

    exec npx concurrently -c "$colors" \
        "php artisan queue:listen --tries=1 --timeout=0" \
        "php artisan pail --timeout=0" \
        "npm run dev" \
        --names=queue,logs,vite --kill-others
fi

if uses_sail; then
    ./vendor/bin/sail up -d >/dev/null
    prepare_host_sail_frontend
    ./vendor/bin/sail bash -lc 'pkill -f "php artisan queue:listen --tries=1 --timeout=0" >/dev/null 2>&1 || true; pkill -f "php artisan pail --timeout=0" >/dev/null 2>&1 || true; pkill -f "vite" >/dev/null 2>&1 || true'

    exec ./vendor/bin/sail npx concurrently -c "$colors" \
        "php artisan queue:listen --tries=1 --timeout=0" \
        "php artisan pail --timeout=0" \
        "npm run dev" \
        --names=queue,logs,vite --kill-others
fi

exec npx concurrently -c "$colors" \
    "php artisan serve" \
    "php artisan queue:listen --tries=1 --timeout=0" \
    "php artisan pail --timeout=0" \
    "npm run dev" \
    --names=server,queue,logs,vite --kill-others
