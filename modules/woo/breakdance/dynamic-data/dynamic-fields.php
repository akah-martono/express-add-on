<?php
namespace VXN\Express\Woo\Breakdance\Dynamic_Data;

use VXN\Express;
use VXN\Express\Breakdance;
use VXN\Express\Helper\Util;
use VXN\Express\Woo\Breakdance\Dynamic_Data\Fields\Woo_Is_On_Sale;
use VXN\Express\Woo\Breakdance\Dynamic_Data\Fields\Woo_Order_Via_Bukalapak_URL;
use VXN\Express\Woo\Breakdance\Dynamic_Data\Fields\Woo_Order_Via_Shopee_URL;
use VXN\Express\Woo\Breakdance\Dynamic_Data\Fields\Woo_Order_Via_Tokopedia_URL;
use VXN\Express\Woo\Breakdance\Dynamic_Data\Fields\Woo_Order_Via_Whatsapp;
use VXN\Express\Woo\Breakdance\Dynamic_Data\Fields\Woo_Whatsapp_Url;

// use function Breakdance\DynamicData\registerField;

class Dynamic_Fields {
 
    public static function register(){
        add_action('init', function() {
            if (!function_exists('\Breakdance\DynamicData\registerField') || !class_exists('\Breakdance\DynamicData\Field')) {
                return;
            }
    
            Breakdance::add_dynamic_field(Util::get_instance(Woo_Whatsapp_Url::class));

            Breakdance::add_dynamic_field(Util::get_instance(Woo_Is_On_Sale::class));
            
            if(Express::Options('vxn_express_woo.txt_order_via_shopee')){
                Breakdance::add_dynamic_field(Util::get_instance(Woo_Order_Via_Shopee_URL::class));
            }

            if(Express::Options('vxn_express_woo.txt_order_via_tokopedia')){
                Breakdance::add_dynamic_field(Util::get_instance(Woo_Order_Via_Tokopedia_URL::class));
            }
            
            if(Express::Options('vxn_express_woo.txt_order_via_bukalapak')){
                Breakdance::add_dynamic_field(Util::get_instance(Woo_Order_Via_Bukalapak_URL::class));
            }           
        });
	}
}