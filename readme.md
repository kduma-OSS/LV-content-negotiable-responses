# Content Negotiable Responses for Laravel

Laravel helpers for creating HTTP responses that automatically negotiate content type based on the client's `Accept` header (JSON, XML, YAML, MsgPack, HTML).

Full documentation: [opensource.duma.sh/libraries/php/laravel-content-negotiable-responses](https://opensource.duma.sh/libraries/php/laravel-content-negotiable-responses)

## Requirements

- PHP `^8.3`
- Laravel `^12.0 || ^13.0`

## Installation

```bash
composer require kduma/content-negotiable-responses
```

## Usage

```php
// Returns JSON, XML, YAML or MsgPack depending on the Accept header
Route::get('/api/data', function () {
    return new \KDuma\ContentNegotiableResponses\ArrayResponse([
        'success' => true,
        'timestamp' => time(),
    ]);
});

// Returns HTML in browsers, JSON/XML/YAML in API clients
Route::get('/', function () {
    return new \KDuma\ContentNegotiableResponses\BasicViewResponse('welcome', [
        'user' => auth()->user(),
    ]);
});
```
