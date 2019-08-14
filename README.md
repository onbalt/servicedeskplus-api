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

after publishing your config file, open `config/servicedeskplus-api.php` and fill your SDP URL and [technician key](http://ui.servicedeskplus.com/APIDocs3/index.html#authentication):

```php
return [
	'api_base_url' => env('SDPAPI_BASE_URL', 'http://helpdesk.local/api/v3/'),
	'technician_key' => env('SDPAPI_TECHNICIAN_KEY', 'key'),
	'api_version' => env('SDPAPI_VERSION', '3'),
	'api_v1_format' => env('SDPAPI_V1_FORMAT', 'json'),
	'timeout' => 60,
];
```

also you can add config parametrs in `.env` file:
```
SDPAPI_BASE_URL=http://helpdesk.local/api/v3/
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
	'api_base_url' => 'http://helpdesk.local/api/v3/',
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
See: http://ui.servicedeskplus.com/APIDocs3/index.html#view-request
```php
$response = ServicedeskplusApi::get('requests/111');
var_dump($response);
```
```php
object(stdClass)# (2) {
  ["request"]=>
  object(stdClass)# (50) {
    //<all request properties in V3 format>
    //@see: http://ui.servicedeskplus.com/APIDocs3/index.html#request8
  }
  ["response_status"]=>
  object(stdClass)# (2) {
    ["status_code"]=>
    int(2000)
    ["status"]=>
    string(7) "success"
  }
}
```

### View all Requests
See: http://ui.servicedeskplus.com/APIDocs3/index.html#view-all-requests
```php
$inputData = ServicedeskplusApi::prepareJsonInputData([
	'list_info' => [ //@see: http://ui.servicedeskplus.com/APIDocs3/index.html#list-info
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
var_dump($response);
```
```php
object(stdClass)# (3) {
  ["requests"]=>
  array(10) {
    [0]=>
    object(stdClass)# (15) {
      //<base request properties in V3 format>
      //@see: http://ui.servicedeskplus.com/APIDocs3/index.html#view-all-requests
    }
    [1]=>
    //<...>
  }
  ["response_status"]=>
  object(stdClass)# (2) {
    ["status_code"]=>
    int(2000)
    ["status"]=>
    string(7) "success"
  },
  ["list_info"]=>
  object(stdClass)# (8) {
    ["has_more_rows"]=>
    bool(true)
    ["get_total_count"]=>
    string(7) "true"
    ["total_count"]=>
    int(111)
    //<other source properties from $inputData's 'list_info' array>
  }
}
```

### Add new Request
See: http://ui.servicedeskplus.com/APIDocs3/index.html#add-request
```php
$inputData = ServicedeskplusApi::prepareJsonInputData([
	'request' => [
		'subject' => 'Subject of the request',
		'description' => 'HTML description of the request. Contains formatted text.',
		'requester' => [
			'name' => 'Dummy Requester',
			'email_id' => 'dummy.requester@dummy.com',
		],
		'site' => [
			'name' => 'Requester Company',
		],
		'impact' => [
			'name' => 'High',
		],
		'urgency' => [
			'name' => 'High',
		],
		'level' => [
			'name' => 'Tier 1',
		],
		'mode' => [
			'name' => 'Web Form',
		],
	]
]);
$workorder = ServicedeskplusApi::post('requests', null, $inputData);
var_dump($workorder->request->id);
//int(112)
```

### Add Attachment
See: Attachment / Add Attachment at http://beta.servicedeskplusmsp.com/SetUpWizard.do?forwardTo=apidoc&username=demo&password=demo
```php
$inputData = ServicedeskplusApi::prepareJsonInputData([
	'attachment' => [
		'request' => [
			'id' => 112,
		],
	]
]);
$filesData['multipart'] = [[
	'name' => 'file0',
	'contents' => fopen('/full/path/to/file.ext', 'r'),
	'filename' => 'file.ext',
]];
$response = ServicedeskplusApi::post('attachments', $inputData, $filesData);
var_dump($response);
```
```php
object(stdClass)# (2) {
  ["response_status"]=>
  object(stdClass)# (2) {
    ["status_code"]=>
    int(2000)
    ["status"]=>
    string(7) "success"
  }
  ["attachment"]=>
  object(stdClass)# (4) {
    ["id"]=>
    int(101)
    ["file_name"]=>
    string(7) "file.ext"
    ["content_url"]=>
    string(7) "/api/v3/attachments/101"
    ["size"]=>
    string(7) "2.42KB"
  }
}
```

## Credits

- [Onbalt](https://github.com/onbalt)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.