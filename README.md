# Howsy Code Challenge

A challenge to test knowledge of PHP by the company Howsy: https://github.com/noagent/code-challenge

## How to run

Use git to clone the repository

```bash
git clone https://github.com/mithrend/howsy-code-challenge.git
```

Enter project directory

```bash
cd howsy-code-challenge
```

### Docker

#### Requirements

- Docker

Install dependancies

```bash
docker-compose run composer install
```

Run tests

```bash
docker-compose run phpunit
```

### Local

#### Requirements

- PHP 8.3
- Composer

Install dependancies

```bash
composer install
```

Run tests

```bash
vendor/bin/phpunit --testdox
```

Static analysis

```bash
vendor/bin/phpstan analyse --level=9 app test
```

## Design Decisions

### Json data file

The decision was made to store the data in a json file, rather than a database.
This was because this is a small challenge, that does not have a requirement of handling dynamic data.
In a production environment, the data would be stored in a database.

There is a `ProductRepository` class that is used to access the data.
It simply reads the data from the json file and keeps an in-memory representation of the data as an array.
The repository is also a singleton so that the product data is only loaded from file once per request.

### No DI container

I felt that using a DI container was overkill for this challenge. All classes can be easily instantiated,
and the interfaces are built such that it is not difficult to inject mock objects for testing.

### 'Fat' models

I went with the decision to include all logic in the models. I feel that this keeps the code simple and
encapsulates more traditional OO design. In a larger application however it would be perfectly reasonable
to separate out the business logic into separate services.

### Strict types & PHPStan

I decided to use strict types to reduce type errors when working with the code. PHPStan level 9 is used
to perform static analysis and ensure strict coding standards.

### Testing

I decided to use PHPUnit to test the code. There are unit tests as well as integration tests.
In this project all public methods of the models are tested as unit tests, using mocks where appopriate.

The integration testing covers the `ProductRepository` as well as the happy path for the checkout process.
