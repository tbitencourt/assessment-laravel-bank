# Assessment in Laravel - Bank Api - Thales Bitencourt

This project is an api application. It is able to receive specific requests from the user,
and reply appropriately, including a specific messages in portuguese.

## Content

- [How to test](#how-to-test)
    * [Requirements](#requirements)
    * [Environment](#environment)
    * [Installation](#installation)
- [Features](#features)
- [Conclusion](#conclusion)
- [Contacts](#contacts)

## How to test

### Requirements

1. Docker / Docker Compose
2. Added "assessment-laravel-bank.test" to your system hosts file
3. Port 80 free
4. Internet connection

### Environment

* PHP 8.4.3
* Composer 2.8.5
* Laravel 11.41
* Nginx 1.27.3
* MySQL 8.0

### Installation

1. Clone this project from GitHub repository: `git clone git@github.com:tbitencourt/assessment-laravel-bank.git`
2. Build the container and start it: `docker-compose up -d --build`
3. Enter in container bash: `docker exec -ti assessment-laravel-bank-app bash`
4. Install the packages: `composer install`
5. Create `.env` file from` .env.example`: `php -r "file_exists('.env') || copy('.env.example', '.env');"`
6. Generate Application key: `php artisan key:generate`
7. Run migrations to create database: `php artisan migrate`
8. Open http://assessment-laravel-bank.test

## Features

In this challenge, several best practices have been implemented and clean code. Are they:

* __Application dockerized__

As mentioned, a complete environment has been set up on the docker for a better development experience.

* __All endpoints are inside "api/v1" group__

To make clear that is an API endpoint and using a best practice to version it, v1 in this case, all endpoints are "inside" of "api/v1" group.

* __Currency values are stored as integer__

To avoid some issues with "float" primitive type, all currency values were stored as integer (multiplying their values by 100) and converted again to float (dividing by 100) before return it to user.

* __Public routes__

There is no login nor token in the specification document, so all routes are public without any validation.

* __All valid data are stored in database__

Accounts and valid transactions are persistent on database to make easy track them.

* __Portuguese language__

All endpoint's path, their parameters and user's messages were kept in portuguese as specified on assessment's document.

* __Quality tool on CI__

All quality tools (lint, refactor, types and units) are executed in GitHub Actions CI.

* __Coverage 100%__

Pest test's coverage and type's coverage are 100%.

* __PHPStan at max__

The PHPStan's level is set as "max".

* __Postman Collection__

I exported and added to project all requests I used on Postman. The collection json is available on "docs/" folder.

## Conclusion

Thanks for the opportunity and any questions just ask me. :)

## Contacts:

* [LinkedIn](https://www.linkedin.com/in/thalesbitencourt)

* [Github](https://github.com/tbitencourt)

* [E-mail](mailto:thalesbitencourt@gmail.com)
