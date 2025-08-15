php artisan l5-swagger:generate

php artisan queue:work --queue="push_notification" --daemon --tries=4

php artisan storage:link

# generate doc #
php artisan ide-helper:generate
