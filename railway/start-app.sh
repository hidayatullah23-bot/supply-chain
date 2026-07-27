#!/usr/bin/env bash
set -e

php artisan schedule:work &
SCHEDULER_PID=$!

cleanup() {
    kill "$SCHEDULER_PID" 2>/dev/null || true
}
trap cleanup EXIT TERM INT

apache2-foreground
