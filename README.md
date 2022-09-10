# E-Colis

laravel server for e-colis  

## Setup
#### Requirements
 - PHP: ^7.3 | ^8.0
 - composer
 
#### Install packages and modules
```
composer install
```

#### env variables
```
cp .env.example .env
php artisan key:generate
```
replace database credentials in .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e-colis
DB_USERNAME=e_colis_db_user
DB_PASSWORD=secret
```
run the migration
```
php artisan:migrate
```

#### run the server
```
php artisan serve
```
