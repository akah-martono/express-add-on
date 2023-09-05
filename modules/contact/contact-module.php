<?php
namespace VXN\Express\Contact;

use VXN\Express;
use VXN\Express\Contact\Admin\Contact_Page;
use VXN\Express\Helper\Util;
use VXN\Express\Module_Interface;

/** @package VXN\Express\Contact */
class Contact_Module implements Module_Interface {
    
    /** @inheritDoc */
    public static function name(){
        return 'Contact';
    }

    /** @inheritDoc */
    public static function slug(){
        return 'vxn_express_contact';
    }

    /** @return void  */
    public function run(){
        define( 'VXN_EXPRESS_CONTACT_MODULE_FILE', __FILE__ );
        Express::add_menu_page(Util::get_instance(Contact_Page::class));
    }
}