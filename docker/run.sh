#!/bin/sh

cd /var/www

# php artisan migrate:fresh --seed
php artisan cache:clear
php artisan route:cache
npm install 
npm run build

/usr/bin/supervisord -c /etc/supervisord.conf