# Create Dummy Operation

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![The Whole Fruit Manifesto](https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen)](https://github.com/the-whole-fruit/manifesto)

This package provides Create Dummy Operation functionality for projects that use the [Backpack for Laravel](https://backpackforlaravel.com/) administration panel.  
This package adds a button on your CRUD list view, to create dummy entries. It works on CRUDs whose Models use the `hasFactory` trait.

## Screenshots

![Backpack Create Dummy Operation Addon](https://user-images.githubusercontent.com/1838187/103494087-fa4fe680-4e2c-11eb-8458-4c18be75c086.gif)


## Installation

Via Composer

``` bash
composer require promatik/create-dummy-operation-for-backpack
```

## Usage

To use the Operation this package provides, inside your custom CrudController add:

```php
use \Promatik\CreateDummyOperation\Http\Controllers\Operations\CreateDummyOperation;
```

### Configurations
To change the configs you must edit `configs\backpack\crud.php` adding `createDummy` array to operations.

```php
'operations' => [
    ...
    'createDummy' => [
        // environments where the button should appear (default is ['local', 'testing'])
        'environment' => ['local'],

        // default value for the number of entries to be added (default is 25)
        'default' => 10,
    ],
```

## Overwriting

If you need to change the translations or the button view, you can easily publish the files to your app, and modify those files any way you want. But please keep in mind that you will not be getting any updates on those files.


- **Publishing views**:
```bash
php artisan vendor:publish --provider="Promatik\CreateDummyOperation\AddonServiceProvider" --tag="views"
```
- **Publishing translations**:
```bash
php artisan vendor:publish --provider="Promatik\CreateDummyOperation\AddonServiceProvider" --tag="lang"
```

## Change log

Changes are documented here on Github. Please see the [Releases tab](https://github.com/promatik/create-dummy-operation-for-backpack/releases).

## Testing

``` bash
composer test
```

## Contributing

Please see [contributing.md](contributing.md) for a todolist and howtos.

## Security

If you discover any security related issues, please email promatik@gmail.com instead of using the issue tracker.

## Credits

- [Antonio Almeida][link-author]
- [All Contributors][link-contributors]

## License

This project was released under MIT, so you can install it on top of any Backpack & Laravel project. Please see the [license file](license.md) for more information. 

However, please note that you do need Backpack installed, so you need to also abide by its [YUMMY License](https://github.com/Laravel-Backpack/CRUD/blob/master/LICENSE.md). That means in production you'll need a Backpack license code. You can get a free one for non-commercial use (or a paid one for commercial use) on [backpackforlaravel.com](https://backpackforlaravel.com).


[ico-version]: https://img.shields.io/packagist/v/promatik/create-dummy-operation-for-backpack.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/promatik/create-dummy-operation-for-backpack.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/promatik/create-dummy-operation-for-backpack
[link-downloads]: https://packagist.org/packages/promatik/create-dummy-operation-for-backpack
[link-author]: https://github.com/promatik
[link-contributors]: ../../contributors
