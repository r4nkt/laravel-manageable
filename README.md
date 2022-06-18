# Track users who manage models in your Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/r4nkt/laravel-manageable.svg?style=flat-square)](https://packagist.org/packages/r4nkt/laravel-manageable)
[![Build Status](https://img.shields.io/travis/r4nkt/laravel-manageable/master.svg?style=flat-square)](https://travis-ci.org/r4nkt/laravel-manageable)
[![StyleCI](https://styleci.io/repos/119214202/shield?branch=master)](https://styleci.io/repos/119214202)
[![Quality Score](https://img.shields.io/scrutinizer/g/r4nkt/laravel-manageable.svg?style=flat-square)](https://scrutinizer-ci.com/g/r4nkt/laravel-manageable)
[![Total Downloads](https://img.shields.io/packagist/dt/r4nkt/laravel-manageable.svg?style=flat-square)](https://packagist.org/packages/r4nkt/laravel-manageable)

The `r4nkt/laravel-manageable` package allows you to easily track who creates/updates your models.

All you have to do to get started is:

```php
// 1. Add required columns to your table by using our macro manageable
Schema::create('orders', function (Blueprint $table) {
    // ...
    $table->manageable();

    // params: $bigIntegers (default: true), $foreignTable (default: 'users'), $foreignKey (default: 'id')
    $table->manageable(false, 'some_users_table', 'u_id');
});

// 2. Add the Manageable trait to your model
class Order extends Model
{
    use Manageable;
}
```

The macro `manageable` adds the following to your table:
```php
$this->unsignedBigInteger('created_by')->nullable()->index();
$this->unsignedBigInteger('updated_by')->nullable()->index();

$this->foreign('created_by')
    ->references('id')
    ->on('users')
    ->onDelete('set null');

$this->foreign('updated_by')
    ->references('id')
    ->on('users')
    ->onDelete('set null');
```

## Documentation
Until further documentation is provided, please have a look at the tests.

## Installation

You can install the package via composer:

```bash
composer require r4nkt/laravel-manageable
```

The package will automatically register itself.

You can publish the config with:
```bash
php artisan vendor:publish --provider="R4nkt\Manageable\ManageableServiceProvider"
```

## Testing
```bash
composer test
```

## Security

If you discover any security issues, please email dev@r4nkt.com instead of using the issue tracker.

## Credits

- [Morten Poul Jensen](https://github.com/pactode)
- [All contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
