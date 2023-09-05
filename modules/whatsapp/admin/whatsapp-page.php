<?php
namespace VXN\Express\Whatsapp\Admin;

use VXN\Express\WP\Menu_Page\Menu_Page;
use VXN\Express\Fields\Checkbox;
use VXN\Express\Fields\Phone_Field;
use VXN\Express\Fields\Text_Area;
use VXN\Express\Section\Section;

/**
 * WhatsApp Admin Page
 * @package VXN\Express\Whatsapp\Admin
 * @author Vaksin <dev@vaks.in>
 * @since 1.0.0
 */ 
class Whatsapp_Page extends Menu_Page {

    public function __construct()
    {
        parent::__construct(__('WhatsApp', VXN_EXPRESS_ADDON_DOMAIN), 'vxn_express_whatsapp');
        parent::set_parent_slug('vxn_express_setup');
        foreach($this->sections() as $section){
            $this->add_section($section);
        }
    }

    private function sections(){
        return [
            self::settings_section(),
            self::template_section()
        ];
    }     
    
    /** @return Section  */
    protected static function settings_section() {
        $chk_form_disabled = get_option('vxn_wa_form_data');
        return (new Section('settings'))
            ->set_title(__('WhatsApp Setting', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_field(
                (new Phone_Field('txt_wa_no'))            
                ->set_label(__('WhatsApp Number', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_description(__('Fill in with the complete WhatsApp number e.g. 6281220003131', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Checkbox('chk_show_form'))
                ->set_label(__('Show Form', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_text_right(__('Show Popup form before redirect to WhatsApp', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_description(__('You need to create Breakdance Popup to use this function, please read our documentation', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_disabled($chk_form_disabled ? false : true)
            );        
    }

    /** @return Section  */
    protected static function template_section() {
        return (new Section('template'))
            ->set_title(__('Text Template', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_field(
                (new Text_Area('txt_wa_text_default'))            
                ->set_label(__('Default Text', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_Area('txt_wa_text_order'))            
                ->set_label(__('Order Text', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_Area('txt_wa_text_consult'))            
                ->set_label(__('Consult Text', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_Area('txt_wa_text_consult_product'))            
                ->set_label(__('Product Consult Text', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_Area('txt_wa_text_appointment'))            
                ->set_label(__('Appointment Text', VXN_EXPRESS_ADDON_DOMAIN))
            );
    }      

}