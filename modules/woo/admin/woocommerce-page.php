<?php
namespace VXN\Express\Woo\Admin;

use VXN\Express\WP\Menu_Page\Menu_Page;
use VXN\Express\Fields\Text_field;
use VXN\Express\Fields\Checkbox;
use VXN\Express\Fields\Text_Area;
use VXN\Express\Section\Section;

/**
 * WooCommerce Option Page
 * @package VXN\Express\Woo\Admin
 * @author Vaksin <dev@vaks.in>
 * @since 1.1.8
 */ 
class Woocommerce_Page extends Menu_Page {
    
    public function __construct()
    {
        parent::__construct(__('WooCommerce', VXN_EXPRESS_ADDON_DOMAIN), 'vxn_express_woo');
        parent::set_parent_slug('vxn_express_setup');
        foreach($this->sections() as $section){
            $this->add_section($section);
        }
    }

    /** @inheritDoc */
    private function sections(){
        return [
            self::order_section(),
            self::order_via_whatsapp_section(),
            self::marketplace_section(),
            self::other_section(),
        ];
    }
    /** @return Section  */
    private static function order_section() {
        return (new Section('add-to-cart'))
            ->set_title(__('Order', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_hr_bottom()
            ->add_field(
                (new Checkbox('chk_catalog_mode'))
                ->set_label(__('Catalog Mode', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_text_right(__('Activate catalog mode', VXN_EXPRESS_ADDON_DOMAIN))
            )            
            ->add_field(
                (new Text_Field('txt_atc_text_product_single'))            
                ->set_label(__('ATC on Product Button Text', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_Field('txt_atc_text_shop'))            
                ->set_label(__('ATC on Shop Button Text', VXN_EXPRESS_ADDON_DOMAIN))
            );                 
    }     

    /** @return Section  */
    private static function order_via_whatsapp_section(){
        return (new Section('order-via-whatsapp'))
            ->set_title(__('WhatsApp', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_hr_bottom()
            ->add_field(
                (new Text_Field('txt_wa_order_button_text'))            
                ->set_label(__('WA Order Button Text', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_Area('txt_wa_text_order_woo'))            
                ->set_label(__('WhatsApp Order Text', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_Field('txt_wa_consult_button_text'))            
                ->set_label(__('WA Consult Button Text', VXN_EXPRESS_ADDON_DOMAIN))
            )            
            ->add_field(
                (new Text_Area('txt_wa_text_consult_woo'))            
                ->set_label(__('WhatsApp Consult Text', VXN_EXPRESS_ADDON_DOMAIN))
            );  
    }

    /** @return Section  */
    private static function marketplace_section() {
        return (new Section('order-via-marketplace'))
            ->set_title(__('Order via Marketplace', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_hr_bottom()
            ->add_field(
                (new Text_Field('txt_order_via_shopee'))            
                ->set_label(__('Order via Shopee Text', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_Field('txt_order_via_tokopedia'))            
                ->set_label(__('Order via Tokopedia Text', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_Field('txt_order_via_bukalapak'))            
                ->set_label(__('Order via Bukalapak Text', VXN_EXPRESS_ADDON_DOMAIN))
            );
    }
    
    /** @return Section  */
    private static function other_section() {
        return (new Section('others'))
            ->set_title(__('Others', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_hr_bottom()
            ->add_field(
                (new Text_Field('txt_sale_flash_text'))            
                ->set_label(__('Sale Flash Text', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Checkbox('chk_enable_block_editor'))
                ->set_label(__('Enable Blok Editor on Product', VXN_EXPRESS_ADDON_DOMAIN)) 
            )
            ->add_field(
                (new Text_Area('txt_woo_replace_text'))            
                ->set_label(__('Replace Woocommerce Text', VXN_EXPRESS_ADDON_DOMAIN))
            ); ;
    }       
}