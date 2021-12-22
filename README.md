# FSK-Sales-Manager
Managing customer/sales site developed by Laravel framework and jQuery Datatable

## Running the Project Locally

First, install packages:

```bash
composer update
```

```bash
composer install
```

Second, set environment:

Copy .env.example to .env and update it properly

```bash
php artisan key:generate
```

Third, setup database

```bash
php artisan migrate --seed
```

Run local server:

```bash
php artisan serve
```