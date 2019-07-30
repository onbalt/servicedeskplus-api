# Integration with ManageEngine ServiceDesk Plus API
[![Latest Stable Version](https://poser.pugx.org/onbalt/servicedeskplus-api/version)](https://packagist.org/packages/onbalt/servicedeskplus-api)
[![Total Downloads](https://poser.pugx.org/onbalt/servicedeskplus-api/downloads)](https://packagist.org/packages/onbalt/servicedeskplus-api)
[![Latest Unstable Version](https://poser.pugx.org/onbalt/servicedeskplus-api/v/unstable)](//packagist.org/packages/onbalt/servicedeskplus-api)
[![License](https://poser.pugx.org/onbalt/servicedeskplus-api/license)](https://packagist.org/packages/onbalt/servicedeskplus-api)
[![composer.lock available](https://poser.pugx.org/onbalt/servicedeskplus-api/composerlock)](https://packagist.org/packages/onbalt/servicedeskplus-api)

## Installation

This is a Laravel package so you can install it via Composer. Run this command in your terminal from your project directory:

```sh
composer require onbalt/servicedeskplus-api
```

## Laravel Configuration

Below **Laravel 5.5** you have to call this package service in `config/app.php` config file. To do that, add this line in `app.php` in `providers` array:

```php
Onbalt\ServicedeskplusApi\ServicedeskplusApiServiceProvider::class,
```

Below **Laravel 5.5** version to use facade you have to add this line in `app.php` to the `aliases` array:

```php
'ServicedeskplusApi' => Onbalt\ServicedeskplusApi\Facades\ServicedeskplusApi::class,
```

Now run this command in your terminal to publish this package config:

```
php artisan vendor:publish --tag=servicedeskplus-api-config
```

after publishing your config file, open `config/servicedeskplus-api.php` and fill your SDP URL and technician key:

```php
return [
	'api_base_url' => env('SDPAPI_BASE_URL', 'http://helpdesk/api/v3/'),
	'technician_key' => env('SDPAPI_TECHNICIAN_KEY', 'key'),
	'api_version' => env('SDPAPI_VERSION', '3'),
	'api_v1_format' => env('SDPAPI_V1_FORMAT', 'json'),
    'timeout' => 60,
];
```

also you can add config parametrs in `.env` file:
```
SDPAPI_BASE_URL=http://helpdesk/api/v3/
SDPAPI_TECHNICIAN_KEY=YOUR_TECHNICIAN_KEY
```

## Laravel Usage

```php
use Onbalt\ServicedeskplusApi\Facades\ServicedeskplusApi;

// View Request
$response = ServicedeskplusApi::get('requests/111');
var_dump($response->request);
```

## Common Usage

Create and use an instance of a class ServicedeskplusApi instead of Facade:

```php
require 'vendor/autoload.php';

use Onbalt\ServicedeskplusApi\ServicedeskplusApi;

$config = [
	'api_base_url' => 'http://helpdesk/api/v3/',
	'technician_key' => 'YOUR_TECHNICIAN_KEY',
	'api_version' => '3',
	'api_v1_format' => 'json',
    'timeout' => 60,
];

$sdpApi = new ServicedeskplusApi($config);

// View Request
$response = $sdpApi->get('requests/111');
var_dump($response->request);
 ```

## Examples

### View Request
```php
$response = ServicedeskplusApi::get('requests/111');
var_dump($response->request);
 ```

### View all Requests
```php
$inputData = ServicedeskplusApi::prepareInputData([
	'list_info' => [
		'row_count' => 10, //max 100
		'start_index' => 1,
		'sort_field' => 'id',
		'sort_order' => 'asc',
		'get_total_count' => true,
		'search_criteria' => [
			[
				'field' => 'site.name',
				'condition' => 'eq',
				'value' => 'MyCompany',
			],
		],
	]
]);
$response = ServicedeskplusApi::get('requests', $inputData);
var_dump($response->requests);
 ```

## Credits

- [Onbalt](https://github.com/onbalt)
- [All Contributors](../../contributors)