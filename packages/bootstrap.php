<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VXN_EXPRESS_FILE', __FILE__  );
define( 'VXN_EXPRESS_PATH', plugin_dir_path( VXN_EXPRESS_FILE ) );
define( 'VXN_EXPRESS_URL', plugin_dir_url( VXN_EXPRESS_FILE ) );

foreach(glob(VXN_EXPRESS_PATH . '_root' . DIRECTORY_SEPARATOR . '*.php') as $file) {
    require_once $file;
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
		'VXN\Express\Breakdance\\' => 'breakdance',
        'VXN\Express\Section\\' => 'section',
        'VXN\Express\Fields\\' => 'fields',
		'VXN\Express\Helper\\' => 'helper',
		'VXN\Express\WP\\' => 'wp',
	];

	$path = $get_path($class, $config);	

	if ( $path ) {
		require_once VXN_EXPRESS_PATH . $path . '.php';
	}
});