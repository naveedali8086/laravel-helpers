# Laravel Helpers

[![Latest Version on Packagist](https://img.shields.io/packagist/v/naveedali8086/laravel-helpers.svg?style=flat-square)](https://packagist.org/packages/naveedali8086/laravel-helpers)
[![Total Downloads](https://img.shields.io/packagist/dt/naveedali8086/laravel-helpers.svg?style=flat-square)](https://packagist.org/packages/naveedali8086/laravel-helpers)
[![License](https://img.shields.io/packagist/l/naveedali8086/laravel-helpers.svg?style=flat-square)](https://packagist.org/packages/naveedali8086/laravel-helpers)

A collection of helpful Laravel utilities including validation rules, helper functions, and traits to make your Laravel development more efficient.

## Requirements

- PHP 8.2 or higher
- Laravel 11.x, or 12.x

## Installation

Install the package via Composer:

```bash
  composer require naveedali8086/laravel-helpers
```
The package will automatically register itself thanks to Laravel's package auto-discovery feature.

# Features

## Quick Start

### Helper Functions

#### Remove Validation Rules

Remove unwanted rules from existing validation rule sets:

```php
// String
format $rules = remove_rule('required|email|unique:users', ['unique']); // Result: 'required|email'

// Array
format $rules = remove_rule(['required', 'email', 'unique:users'], ['unique']); // Result: ['required', 'email']
```


## Documentation

- [Helper Functions](docs/helper-functions.md)
- [Usage Examples](docs/usage-examples.md)
- [Changelog](CHANGELOG.md)

## Testing

```bash
  composer test
```

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security

If you discover any security issues, please email naveedali8086@gmail.com.

## Credits

- [Naveed Ali](https://github.com/naveedali8086)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Support

⭐ If you find this package helpful, please star it on [GitHub](https://github.com/naveedali8086/laravel-helpers)!