#!/bin/bash

vendor/bin/openapi -o public/openapi.json app/Http/Controllers/Api
composer dump-autoload
php artisan migrate
php artisan cache:clear
