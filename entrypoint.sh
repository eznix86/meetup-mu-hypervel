#!/bin/sh

set -eu

php artisan view:cache

exec php artisan start
