# Product Sales API

Example of a simple product sales API.

* Built using [Laravel](https://laravel.com).
* Follows [JSON API](http://jsonapi.org) spec.

## API Endpoints

```
GET|POST /products
GET /products/top-sellers
GET|PATCH|DELETE /products/1
GET|POST /orders
GET|PATCH|DELETE /orders/1
```

### API Docs
API docs: [http://docs.productsalesapi.apiary.io](http://docs.productsalesapi.apiary.io).

The API docs include more detailed examples of the endpoints, query params, input and and response payloads. Written using [Blueprint](https://apiblueprint.org), they can be updated by editing /apiary.apib.

## Requirements

* [Composer](https://getcomposer.org/doc/00-intro.md#globally)
* PHP >= 7.0
* [Vagrant](https://www.vagrantup.com/downloads.html) (or [Valet](https://laravel.com/docs/5.4/valet) if you wish)

## Getting Started (Vagrant)
```
// Install PHP Dependencies
composer install

// Setup the VM
php vendor/bin/homestead make
vagrant up

// Setup environment settings
cp .env.example .env
php artisan key:generate

// SSH into the vagrant
vagrant ssh
cd ~/Code/

// Migrate the database
php artisan migrate

// Seed the database
php artisan db:seed
```

Server welcome page now available at [127.0.0.1:8000](http://127.0.0.1:8000).

API available at /api (eg. [127.0.0.1:8000/api/products](http://127.0.0.1:8000/api/products)).

## Make API Calls Using Postman
You can make API calls using [Postman](https://www.getpostman.com/). In Postman:

1. Import the collection from `/postman/Prouduct Sales API.json.postman_collection`
2. Manage Environments > Import from `/postman/Product Sales API (Vagrant).postman_environment`.
