# Firebird for Laravel

[![Latest Stable Version](https://poser.pugx.org/michalkortas/laravel-firebird/v/stable)](https://packagist.org/packages/michalkortas/laravel-firebird)
[![Tests](https://github.com/michalkortas/laravel-firebird/actions/workflows/tests.yml/badge.svg?branch=5.x)](https://github.com/michalkortas/laravel-firebird/actions/workflows/tests.yml)

This package adds support for the Firebird PDO Database Driver in Laravel applications.

## Version Support

- **PHP:** 8.2, 8.3, 8.4
- **Laravel:** 12.x, 13.x
- **Firebird:** 2.5 and newer

The 5.x line deliberately retains the `FIRST` / `SKIP` query syntax required by
Firebird 2.5. Applications on older Laravel versions should stay on the 4.x line.

## Installation

You can install the package via composer:

```bash
composer require michalkortas/laravel-firebird:^5.0
```

_The package will automatically register itself._

Declare the connection within your `config/database.php` file by using `firebird` as the
driver:
```php
'connections' => [

    'firebird' => [
        'driver'   => 'firebird',
        'host'     => env('DB_HOST', 'localhost'),
        'port'     => env('DB_PORT', '3050'),
        'database' => env('DB_DATABASE', '/path_to/database.fdb'),
        'username' => env('DB_USERNAME', 'sysdba'),
        'password' => env('DB_PASSWORD', 'masterkey'),
        'charset'  => env('DB_CHARSET', 'UTF8'),
        'role'     => null,
    ],

],
```

To register this package in Lumen, you'll also need to add the following line to the service providers in your `config/app.php` file:
`$app->register(\HarryGulliford\Firebird\FirebirdServiceProvider::class);`

## Limitations
This package does not intend to support database migrations and it should not be used for this use case.

## Credits
- [Harry Gulliford](https://github.com/harrygulliford)
- [Jacques van Zuydam](https://github.com/jacquestvanzuydam/laravel-firebird)
- [Simonov Denis](https://github.com/sim1984/laravel-firebird)
- [All Contributors](https://github.com/harrygulliford/laravel-firebird/graphs/contributors)

## License
Licensed under the [MIT](https://choosealicense.com/licenses/mit/) license.
