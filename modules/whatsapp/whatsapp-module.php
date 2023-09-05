<?php
namespace VXN\Express\Whatsapp;

use VXN\Express\Breakdance;
use VXN\Express;

use VXN\Express\Module_Interface;
use VXN\Express\Helper\Util;
use VXN\Express\Whatsapp\Admin\Whatsapp_Page;
use VXN\Express\Whatsapp\Breakdance\Dynamic_Fields\Whatsapp_Url;
use VXN\Express\Whatsapp\Breakdance\Form_Action\Whatsapp_Form_Action;
use VXN\Express\WP\Script\Script;

/** @package VXN\Express\Whatsapp */
class Whatsapp_Module implements Module_Interface {

    /** @inheritDoc */
	public static function name(){
        return 'WhatsApp';
    }

	/** @inheritDoc */
    public static function slug(){
        return 'vxn_express_whatsapp';
    }

	/** @inheritDoc */
    public function run(){
        define( 'VXN_EXPRESS_WHATSAPP_MODULE_FILE', __FILE__ );
		define( 'VXN_EXPRESS_WHATSAPP_URL', plugin_dir_url( VXN_EXPRESS_WHATSAPP_MODULE_FILE ) );

		Breakdance::add_dynamic_field(Util::get_instance(Whatsapp_Url::class));
        Breakdance::add_form_action(Util::get_instance(Whatsapp_Form_Action::class));
		Express::add_menu_page(Util::get_instance(Whatsapp_Page::class));

        Self::add_shortcodes();
        Whatsapp_Popup::do_setup();
    }

    private static function add_shortcodes(){
        /** need to be reviewed */
        Express::add_shortcode(
            'vxn_wa_url', 
            function( $attr ) {				
                $args = shortcode_atts( array(     
                    'text' => '',
                ), $attr );

                $text = (string) $args['text'];

                if($text){
                    do_shortcode($args['text']);
                    $text = '&text=' . $text;
                }
                
                $url = 'https://api.whatsapp.com/send/?phone=' . Express::Options('vxn_express_whatsapp.txt_wa_no', '') . $text;
                return esc_url($url);
            }
        );
        
        Express::add_shortcode(
            'vxn_wa_is_popup', 
            function() {
                return esc_html(Express::Options('vxn_express_whatsapp.chk_show_form', 0));
            }
        );

        Express::add_shortcode(
            'vxn_wa_text', 
            function() {
                $text = self::get_wa_text( 'txt_wa_text_default' );
                return esc_textarea(Util::parse_shortcode_dynamic_var( $text ));
            }
        );

        Express::add_shortcode(
            'vxn_wa_text_order', 
            function( $attr ) {
                $text = self::get_wa_text( 'txt_wa_text_order' );

                $args = shortcode_atts( array(     
                    'product' => '',
                ), $attr );
                
                $text = Util::replace_shortcode_dynamic_var_with_atts( $text, $args );
                
                return esc_textarea(Util::parse_shortcode_dynamic_var( $text ));
            }
        );

        Express::add_shortcode(
            'vxn_wa_text_consult',
            function() {
                $text = self::get_wa_text( 'txt_wa_text_consult' );			
                return esc_textarea(Util::parse_shortcode_dynamic_var( $text ));
            }
        );

        Express::add_shortcode(
            'vxn_wa_text_consult_product',
            function( $attr ) {
                $text = self::get_wa_text( 'txt_wa_text_consult_product' );

                $args = shortcode_atts( array(     
                    'product' => ''
                ), $attr );
                
                $text = Util::replace_shortcode_dynamic_var_with_atts( $text, $args );
                
                return esc_textarea(Util::parse_shortcode_dynamic_var( $text ));
            }
        );

        Express::add_shortcode(
            'vxn_wa_text_appointment', 
            function( $attr ) {
                $text = self::get_wa_text( 'txt_wa_text_appointment' );			
                return esc_textarea(Util::parse_shortcode_dynamic_var( $text ));
            }
        );
    }

    /**
     * @param string $field_id 
     * @return string 
     */
    public static function get_wa_text( $field_id ) {
        return Express::Options('vxn_express_whatsapp.' . $field_id) ? : Express::Options('vxn_express_whatsapp.txt_wa_text_default', '') ;
	}

}