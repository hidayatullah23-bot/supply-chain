#!/usr/bin/env bash
set -e

php artisan migrate --force
php artisan db:seed --force

# Provider publik boleh gagal sementara tanpa menggagalkan deployment.
php artisan countries:sync-profiles || true
php artisan countries:sync-currencies || true
php artisan ports:sync-wpi --replace || true

php artisan optimize:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
