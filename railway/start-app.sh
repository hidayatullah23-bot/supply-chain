#!/usr/bin/env bash
set -e

bash railway/init-app.sh

php artisan schedule:work &
SCHEDULER_PID=$!

cleanup() {
    kill "$SCHEDULER_PID" 2>/dev/null || true
}
trap cleanup EXIT TERM INT

php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
