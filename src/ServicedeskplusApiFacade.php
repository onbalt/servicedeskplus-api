<?php

namespace Onbalt\ServicedeskplusApi;

use Illuminate\Support\Facades\Facade;

class ServicedeskplusApiFacade extends Facade {

	protected static function getFacadeAccessor() {
		return 'servicedeskplus-api';
	}

}
