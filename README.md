# fido-reader
Small web application which aggregates and displays rss feeds and articles.

Installation

- run composer install
- change database settings to match your own database
- run php artisan migrate
- change settings for user in seed so you are able to login
- run php artisan db:seed
- schedule cron for php artisan command:tick
- login and add rss feed to jumpstart application feed collection