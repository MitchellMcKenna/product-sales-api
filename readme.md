# Laravel JSON API Example

Example of a simple API, built using [Laravel](https://laravel.com) and implementing the [JSON API](http://jsonapi.org) spec.

This API simulates a simple store platform; there is products and sales orders of those products. 

## API Endpoints

There are CRUD endpoints for both Product and Order resources. The is also an example of how to implement a more complex endpoint which calculates the top sellers over a given time period.

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

## Features

* Validation - utilizes laravel form request validation to keep lean controllers.
* API Documentation - via Blueprint and Apiary.
* JSON API compliant responses - via API Resources.
* JSON API compliant errors - validation errors and other error responses.
* Resource Controllers - for clean separation of responsibility.
* Complex Raw Query Example with Pagination - Top Sellers uses a Query Object and manual LengthAwarePaginator.
* Caching - ResponseCache middleware to cache all successful GET requests. Cache is busted via the Observers on Create/Update of Orders/Products.
* Profiling - performance via Clockwork.
* Postman collection - with example API calls.
* Unit Tests - utilizes an in-memory database and db factories to simulate real calls.

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

## Design Decisions

* JSON API spec was chosen to provide a consistent request, response and error structure which promotes RESTful design. The flat document structure and relationships eliminate duplicate data to minimize response sizes.
* MySQL is used as the database technology for this app; the data required is structured and relational data. Alternatively any RDBMS would allow for maintaining the relationships and normalized data, such as PostGreSQL.
* Eloquent is used as the database layer as the ORM provides an extremely simple interface for most database calls. Alternatively Doctrine ORM could be used if DataMapper pattern is preferred.
* One perceivable downside to using Eloquent is that related objects are retrieved using a separate db call instead of a join. A previously highly debated topic in the Laravel community. One of the reasons for this is because the related table, for example in a one-to-many relationship, could result in many overlapping column names so a separate db call helps to solve this. Eager loading avoids N+1 queries but 1 additional db call is done. Alternatively if this is an issue you can do this using a join manually via the eloquent query builder.
* A Query Object was used for the aggregate raw query for Top Sellers as such is not possible using eloquent query builder or a local scope. Alternatively since the queries aren't being used anywhere else, doing the queries in private functions directly in the controller could have been done. Another well accepted approached would be to use Repositories or a Service instead. 
* Models are injected via dependency injection rather than using static calls to their facades to simplify unit testing.
* Laravel 5.5's API Resources was used for simple response creation in JSON API format. Alternatively [Fractal](http://fractal.thephpleague.com/) could be used.
* Top Sellers' `quantity` is included in the `meta` offset of each product instead of as part of the products attributes to clearly convey this is not data which can be PATCH'ed by the client.
* ResponseCache coupled with Observers is used to provide a simple caching layer without cluttering controllers with caching logic. Another acceptable approach would be to use Repositories with a Cache Decorator.
* Most GET requests (if there is a cache-miss) issue 2 database calls; 1 to get the object(s) requested and one for count of total objects. The 2nd db call is for pagination using LengthAwarePaginator, it can be avoided by using a generic Paginator instead and the client can assume they are at the last page once they get to an empty page of results. However I think this extra db call is worth it so the client knows how many pages of results there is up front, especially necessary if they need to show total number of pages on their end, but also so they can avoid the extra API call of empty results at the end.
* The Order model is setup to eager load it's related Product automatically, this is to avoid N+1 queries when retrieving orders because there is currently no use-cases where orders are not needed without knowing it's related Product.
* Blueprint is used for API documentation for simplicity as Blueprint is pretty much just markdown syntax. This was chosen over say Swagger/OpenAPI annotations in phpDoc blocks to keep controllers clean. Alternatively API docs in OpenAPI format could be stored in a yaml file.
* Clockwork is used for local performance profiling as Laravel-Debugbar does not work natively with API calls.
* Unit tests utilize an in-memory database, db factories, and the RefreshDatabase trait to simulate real db calls. Alternatively if the unit testing suite becomes larger and begins to take too long to run we can switch to classic PHPUnit TestCase and mocking all dependencies.
