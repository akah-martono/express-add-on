<?php
namespace VXN\Express\Woo;

use VXN\Express;
use VXN\Express\Helper\Util;
use VXN\Express\Module_Interface;
use VXN\Express\Woo\Admin\Woocommerce_Page;
use VXN\Express\Woo\Breakdance\Dynamic_Data\Dynamic_Fields;

/**
 * Express woo module
 * @package VXN\Express\Woo
 * @author Vaksin <dev@vaks.in>
 * @since 1.1.8
 */
class Woo_Module implements Module_Interface {
    /** @inheritDoc */
    public static function name(){
        return 'WooCommerce';
    }

    /** @inheritDoc */
    public static function slug(){
        return 'vxn_express_woo';
    }

    /** @return void  */
     public function run(){
        define( 'VXN_EXPRESS_WOO_MODULE_FILE', __FILE__ );
        define( 'VXN_EXPRESS_WOO_MODULE_PATH', plugin_dir_path( __FILE__ ) );
        define( 'VXN_EXPRESS_WOO_MODULE_URL', plugin_dir_url( __FILE__ ) );
  
        if(Woo::is_activated()){
            Express::add_menu_page(Util::get_instance(Woocommerce_Page::class));

            Dynamic_Fields::register();

			if( is_admin() ){
				Woo_Marketplace_Fields::add_fields();		
			}else{
                Woo_Marketplace_Fields::add_shortcodes();
                Woo_Shortcodes::add();    
			}

            Woo::customize();
        }

    }
}