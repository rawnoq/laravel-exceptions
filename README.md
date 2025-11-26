# Rawnod Laravel Exceptions

[![Latest Version](https://img.shields.io/github/v/release/rawnoq/laravel-exceptions?style=flat-square)](https://github.com/rawnoq/laravel-exceptions/releases)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-blue.svg?style=flat-square)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/laravel-%5E11.0%7C%5E12.0-red.svg?style=flat-square)](https://laravel.com)

A professional Laravel package for centralized API exception handling with unified response structure, status codes, and messages.

## Features

- 🎯 **Centralized Exception Handling**: Single point of control for all API exceptions
- 📦 **Unified Response Structure**: Consistent JSON responses across all exceptions
- 🔒 **Security First**: Hides sensitive information in production
- 📝 **Comprehensive Logging**: Automatic logging of unhandled exceptions
- 🔧 **Highly Customizable**: Easy to extend and customize
- 🚀 **Laravel 11+ Ready**: Built for Laravel 11 and 12

## Installation

### Via Composer

```bash
composer require rawnod/laravel-exceptions
```

### Manual Installation

If you're installing manually, add the package to your `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./packages/rawnod/laravel-exceptions"
        }
    ],
    "require": {
        "rawnod/laravel-exceptions": "*"
    }
}
```

Then run:

```bash
composer install
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=exceptions-config
```

This will create `config/exceptions.php` where you can customize exception handling behavior.

## Setup

### Automatic Registration (Recommended)

By default, the package automatically registers the exception handler. No manual configuration is required!

The handler will automatically work for all routes matching the `api_pattern` (default: `api/*`).

To disable automatic registration, set in your `.env`:

```env
EXCEPTIONS_AUTO_REGISTER=false
```

### Manual Registration (Optional)

If you disabled automatic registration, you can manually register the exception handler in your `bootstrap/app.php`:

```php
use Rawnod\Exceptions\ExceptionRenderer;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    // ... other configuration
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->is('api/*')) {
                return app(ExceptionRenderer::class)->render($e);
            }
        });
    })->create();
```

## Supported Exceptions

The package handles the following exception types:

- `HttpResponseException` - Custom HTTP responses
- `ModelNotFoundException` - Eloquent model not found
- `NotFoundHttpException` - Route not found
- `MethodNotAllowedHttpException` - HTTP method not allowed
- `ValidationException` - Form validation errors
- `AuthenticationException` - Unauthenticated requests
- `AuthorizationException` - Unauthorized access
- `InvalidFilterQuery` - Spatie QueryBuilder filter errors
- `InvalidIncludeQuery` - Spatie QueryBuilder include errors
- `InvalidSortQuery` - Spatie QueryBuilder sort errors
- `InvalidFieldQuery` - Spatie QueryBuilder field errors
- `HttpExceptionInterface` - All other HTTP exceptions
- Generic exceptions - Fallback for unhandled exceptions

## Usage

Once installed and configured, the exception handler will automatically catch and format all exceptions for API routes.

### Language Files

The package includes built-in translations in English and Arabic. Translations are automatically loaded from the package.

#### Available Translation Keys

- `exceptions::exceptions.model_not_found` - Model not found message
- `exceptions::exceptions.resource_not_found` - Resource not found message
- `exceptions::exceptions.method_not_allowed` - HTTP method not allowed
- `exceptions::exceptions.validation_failed` - Validation failed message
- `exceptions::exceptions.unauthenticated` - Authentication required
- `exceptions::exceptions.unauthorized` - Authorization failed
- `exceptions::exceptions.server_error` - Server error message
- `exceptions::exceptions.http_exception` - Generic HTTP exception
- `exceptions::exceptions.bad_request` - Bad request message

#### Customizing Translations

You can publish and customize the language files:

```bash
php artisan vendor:publish --tag=exceptions-lang
```

This will copy the language files to `lang/vendor/exceptions/` where you can customize them.

Alternatively, you can override translations in your application's language files by creating files at:
- `lang/en/vendor/exceptions/exceptions.php`
- `lang/ar/vendor/exceptions/exceptions.php`

### Response Structure

All exceptions return a consistent JSON structure using the `rawnoq/laravel-api-response` package format:

```json
{
    "success": false,
    "message": "Error message",
    "data": null,
    "errors": {}
}
```

## Requirements

- PHP 8.2+
- Laravel 11.0+ or 12.0+
- `rawnoq/laravel-api-response` package

## Optional Dependencies

- `spatie/laravel-query-builder` - For query builder exception handling

## License

MIT

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

For issues and questions, please open an issue on [GitHub](https://github.com/rawnoq/laravel-exceptions/issues).

## Links

- [GitHub Repository](https://github.com/rawnoq/laravel-exceptions)
- [Issue Tracker](https://github.com/rawnoq/laravel-exceptions/issues)

