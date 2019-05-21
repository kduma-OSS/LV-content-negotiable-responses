## Installation

```bash
composer require kduma/content-negotiable-responses
```

## Content Formats (for `ArrayResponse` and `BasicViewResponse`)

Currently supported formats are:
 - `text/plain` (disabled by default, to enable it in your custom class implement `TextResponseInterface`) - resulting response will be output of built-in PHP function `print_r`
 - `application/json` -  resulting response will be output of built-in PHP function `json_encode`
 - `application/yaml` - resulting response will be generated using [symfony/yaml](https://packagist.org/packages/symfony/yaml) package
 - `application/xml` - resulting response will be generated using [spatie/array-to-xml](https://packagist.org/packages/spatie/array-to-xml) package
 - `application/msgpack` - resulting response will be generated using [rybakit/msgpack](https://packagist.org/packages/rybakit/msgpack) package
 - `text/html` (only for `BasicViewResponse`) - resulting response will be generated using view provided to constructor with passed data array

## Usage

### Basic Array Usage (for API responses)

```php
Route::get('/test', function () {
    return new \KDuma\ContentNegotiableResponses\ArrayResponse([
        'success' => true,
        'timestamp' => time(),
    ]);
});
```
As the result, response will be formatted accordingly to acceptable formats passed in `Accept` HTTP header or as JSON if not specified.

### Basic View Usage (for Web and API responses)

```php
Route::get('/', function () {
    return new \KDuma\ContentNegotiableResponses\BasicViewResponse('welcome', [
        'success' => true,
        'timestamp' => time(),
    ]);
});
```

### Customized Usage

```php
namespace App\Http\Responses;

use KDuma\ContentNegotiableResponses\BaseViewResponse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UsersListResponse extends BaseViewResponse
{
    public $users;

    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    protected function generateView(Request $request)
    {
        return $this->view('users.list');
    }
}

Route::get('/users', function () {
    return new \App\Http\Responses\UsersListResponse(\App\User::all());
});
```

As the result, when opening in web browser, there will be rendered `users.list` view with passed all public properties to it.
In non HTML clients (specyfing other `Accept` headers) will get serialized public properties (in any supported format), for example:

```json
{
    "users": [
        {
            "name": "user 1",
            "email": "user1@localhost"
        },
        {
            "name": "user 2",
            "email": "user2@localhost"
        },
        {
            "name": "user 3",
            "email": "user3@localhost"
        }
    ]
}
```

If you want to customize serialized array, you need to override `getDataArray` method:

```php
/**
 * @return array
 */
protected function getDataArray() {
	return [
		'my_super_users_collection' => $this->users->toArray(),
		'hello' => true
	];
}
```

Then the result will be:

```json
{
    "my_super_users_collection": [
        {
            "name": "user 1",
            "email": "user1@localhost"
        },
        {
            "name": "user 2",
            "email": "user2@localhost"
        },
        {
            "name": "user 3",
            "email": "user3@localhost"
        }
    ],
    "hello": true
}
```