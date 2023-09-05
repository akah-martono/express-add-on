<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

spl_autoload_register(function($class) {
		
	$get_path = function ($class, $config) {
		foreach($config as $key=>$value){
			if ( substr($class, 0, strlen($key))  === $key  ) {
				$path = str_replace($key, $value . DIRECTORY_SEPARATOR, $class);
				$path = str_replace("\\", DIRECTORY_SEPARATOR, $path);
				$path = str_replace("_", '-', $path);
				return strtolower($path);				
			}
		}
		return false;
	};
	
	$config = [
		'VXN\Express\Addon\\' => 'app',		
		'VXN\Express\Contact\\' => 'modules' . DIRECTORY_SEPARATOR . 'contact',
		'VXN\Express\Whatsapp\\' => 'modules' . DIRECTORY_SEPARATOR . 'whatsapp',
        'VXN\Express\Testi\\' => 'modules' . DIRECTORY_SEPARATOR . 'testi',
        'VXN\Express\Team_Member\\' => 'modules' . DIRECTORY_SEPARATOR . 'team-member',
        'VXN\Express\Woo\\' => 'modules' . DIRECTORY_SEPARATOR . 'woo',
        'VXN\Express\Job\\' => 'modules' . DIRECTORY_SEPARATOR . 'job',
	];

	$path = $get_path($class, $config);

	if ( $path ) {
		require_once VXN_EXPRESS_ADDON_PATH . $path . '.php';
	}
});