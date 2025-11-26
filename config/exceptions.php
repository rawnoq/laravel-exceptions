<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Exception Handler Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the API exception handler.
    | You can customize how exceptions are handled and rendered.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Auto Register Exception Handler
    |--------------------------------------------------------------------------
    |
    | When enabled, the exception handler will be automatically registered
    | without needing to manually configure it in bootstrap/app.php.
    | Set to false if you want to manually register the handler.
    |
    */

    'auto_register' => env('EXCEPTIONS_AUTO_REGISTER', true),

    /*
    |--------------------------------------------------------------------------
    | API Routes Pattern
    |--------------------------------------------------------------------------
    |
    | Define the pattern for API routes that should use this exception handler.
    | The default is 'api/*' which matches all routes starting with 'api/'.
    | You can customize this pattern to match your API structure.
    |
    */

    'api_pattern' => env('EXCEPTIONS_API_PATTERN', 'api/*'),

    /*
    |--------------------------------------------------------------------------
    | Enable Exception Logging
    |--------------------------------------------------------------------------
    |
    | When enabled, unhandled exceptions will be logged to the application
    | log files. Set to false to disable logging.
    |
    */

    'log_exceptions' => env('EXCEPTIONS_LOG', true),

    /*
    |--------------------------------------------------------------------------
    | Show Exception Details in Production
    |--------------------------------------------------------------------------
    |
    | When set to false, exception details will be hidden in production
    | and only generic error messages will be shown. This is recommended
    | for security reasons.
    |
    */

    'show_details_in_production' => env('EXCEPTIONS_SHOW_DETAILS', false),

    /*
    |--------------------------------------------------------------------------
    | Translation Namespace
    |--------------------------------------------------------------------------
    |
    | The translation namespace used by the package. Default is 'exceptions'.
    | Translation keys will be accessed as: exceptions::exceptions.key_name
    |
    */

    'translation_namespace' => env('EXCEPTIONS_TRANSLATION_NAMESPACE', 'exceptions'),

    /*
    |--------------------------------------------------------------------------
    | Supported Exception Types
    |--------------------------------------------------------------------------
    |
    | List of exception types that are handled by this package.
    | You can disable handling for specific exception types by setting
    | them to false.
    |
    */

    'supported_exceptions' => [
        'http_response' => true,
        'model_not_found' => true,
        'not_found_http' => true,
        'method_not_allowed' => true,
        'validation' => true,
        'authentication' => true,
        'authorization' => true,
        'query_builder' => true,
        'http_interface' => true,
        'generic' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Query Builder Exceptions
    |--------------------------------------------------------------------------
    |
    | Configuration for Spatie QueryBuilder exceptions handling.
    | These exceptions are treated as bad requests (400).
    |
    */

    'query_builder_exceptions' => [
        'invalid_filter_query' => true,
        'invalid_include_query' => true,
        'invalid_sort_query' => true,
        'invalid_field_query' => true,
        'invalid_append_query' => true,
        'unknown_included_fields_query' => true,
        'invalid_filter_value' => true,
        'invalid_direction' => true,
        'allowed_fields_must_be_called_before_allowed_includes' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Translation Keys Mapping
    |--------------------------------------------------------------------------
    |
    | Map exception types to their translation keys.
    | These keys are used in the language files located at:
    | lang/{locale}/exceptions.php
    |
    */

    'translation_keys' => [
        'model_not_found' => 'exceptions::exceptions.model_not_found',
        'resource_not_found' => 'exceptions::exceptions.resource_not_found',
        'method_not_allowed' => 'exceptions::exceptions.method_not_allowed',
        'validation_failed' => 'exceptions::exceptions.validation_failed',
        'unauthenticated' => 'exceptions::exceptions.unauthenticated',
        'unauthorized' => 'exceptions::exceptions.unauthorized',
        'server_error' => 'exceptions::exceptions.server_error',
        'http_exception' => 'exceptions::exceptions.http_exception',
        'bad_request' => 'exceptions::exceptions.bad_request',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Status Codes
    |--------------------------------------------------------------------------
    |
    | Map exception types to their HTTP status codes.
    | You can customize these if needed.
    |
    */

    'status_codes' => [
        'model_not_found' => 404,
        'resource_not_found' => 404,
        'method_not_allowed' => 405,
        'validation' => 422,
        'authentication' => 401,
        'authorization' => 403,
        'bad_request' => 400,
        'server_error' => 500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Response Structure
    |--------------------------------------------------------------------------
    |
    | Configuration for the JSON response structure.
    | This package uses rawnoq/laravel-api-response format by default.
    |
    */

    'response' => [
        'structure' => [
            'success' => false,
            'message' => null,
            'data' => null,
            'errors' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure what information is logged when exceptions occur.
    |
    */

    'logging' => [
        'enabled' => env('EXCEPTIONS_LOG', true),
        'channel' => env('EXCEPTIONS_LOG_CHANNEL', 'stack'),
        'context' => [
            'exception_class' => true,
            'exception_message' => true,
            'exception_trace' => env('EXCEPTIONS_LOG_TRACE', false),
            'request_url' => true,
            'request_method' => true,
            'request_headers' => env('EXCEPTIONS_LOG_HEADERS', false),
            'request_input' => env('EXCEPTIONS_LOG_INPUT', false),
            'user_id' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Exception Handlers
    |--------------------------------------------------------------------------
    |
    | You can register custom exception handlers here.
    | Format: 'ExceptionClass' => 'HandlerClass'
    |
    | Example:
    | 'custom_handlers' => [
    |     \App\Exceptions\CustomException::class => \App\Handlers\CustomHandler::class,
    | ],
    |
    */

    'custom_handlers' => [],

];

