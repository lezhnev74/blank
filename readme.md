# Get started
This project is a [frameworkless foundation](https://lessthan12ms.com/frameworkless-foundation-of-your-php-application.html) for a new backend application in PHP.
It is designed in 3-layers: Application, Domain and Infrastructure. It includes, probably, a good initial set of features that one might need.

The idea of this project is to be free in choices of packages. No big framework included, only some popular open-source packages, each of them can be replaced or deinstalled.
This is my comfortable default setup that I feel good about.

## Docker 
This project requires Docker installed, it ships with a `docker-compose.yml` configured. You can tweak it as you need,
by default it comes with PHP 7.4 image.
`./develop` is a proxy tool to pass command inside of the container (via `docker-compose`). Look inside, it supports a number of useful commands:
- `php`
- `composer`
- `test`
- `debug`
- `serve`
- `migrate`

## Safety Net
This package cmoes with a set of quality assurance tools which enforce high coding practices:
- `phpcs`
- `phpcbf`
- `phpstan`
- `deptrac`

There is git hook shipped with this project `.githooks/pre-commit`. It automatically configured during installation (see `scripts` part in `composer.json`).
It will check your code upon new commits and outputs errors in the file `.qa-report.txt`.

## Folders
- `docker` - configure the PHP image
- `docs` - OpenAPI references, see Stoplight Studio
- `web` - web root for the web server
- `src` - all code goes here according to layers.
    - `Domain` - core logic
    - `Application` - user facing logic, usecases
    - `Infrastructure` - low level details and implementation
- `tests` - all tests go here
- `storage` - all data and resources kept here (including logs)


## Installation
- create a new project from this repository: 
    ```
    composer create-project lezhnev74/blank ./
    ```
- install fresh versions of dependencies:
    ```
    ./develop composer require \
    php-di/php-di \
    phlak/config vlucas/phpdotenv \
    monolog/monolog \
    nesbot/carbon \    
    doctrine/dbal doctrine/migrations 
    slim/psr7 slim/slim \
    danielstjules/stringy voku/arrayy ramsey/uuid \
    webmozart/assert \
    ```
    ```
    ./develop composer require --dev \
    mockery/mockery \
    phpstan/phpstan \
    phpunit/phpunit \
    squizlabs/php_codesniffer 
    ```
### After Installation
Probably there are manual configuration required after all big parts are complete:
- check that `.env` file exists
- do global replacement of the root namespace from `Blank\` to `MyProject\` (not only in php files, but everywhere).
- make sure git hook is executable `./.git/gooks/pre-commit`
- run `./develop php -v` and `./develop test` to confirm PHP container is runnable

## Database And Migrations
The project ships with `doctrine/dbal` and `doctrine/migrations`. 
So you can tweak db config in `src/Infrastructure/config.php`.
Also migrations are configured in two files: `cli-config.php` and `migrations.php`.
