<?php

return [
	'api_base_url' => env('SDPAPI_BASE_URL', 'http://helpdesk/api/v3/'),
	'technician_key' => env('SDPAPI_TECHNICIAN_KEY', 'key'),
	'api_version' => env('SDPAPI_VERSION', '3'),
	'api_v1_format' => env('SDPAPI_V1_FORMAT', 'json'),
    'timeout' => 60,
];