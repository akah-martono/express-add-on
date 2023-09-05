<?php
namespace VXN\Express\Woo;

use VXN\Express;

/**
 * Class to add marketplace fields on woocommerce product
 * @package VXN\Express\Woo
 * @author Vaksin <dev@vaks.in>
 * @since 1.1.8
 */
class Woo_Marketplace_Fields {
    /** @return void  */
    public static function add_fields(){
        if(
            Express::Options('vxn_express_woo.txt_order_via_bukalapak')
            || Express::Options('vxn_express_woo.txt_order_via_tokopedia')
            || Express::Options('vxn_express_woo.txt_order_via_shopee')
        ){
            add_filter('woocommerce_product_data_tabs', static::class . '::marketplace_tabs');
            add_action('woocommerce_product_data_panels', static::class . '::woocommerce_product_custom_fields');        
            add_action('woocommerce_process_product_meta', static::class . '::woocommerce_product_custom_fields_save');
            add_action('admin_head', static::class . '::styling_tab');    
        }
    }

    /**
     * @param mixed $tabs 
     * @return mixed 
     */
    public static function marketplace_tabs($tabs){
        //unset( $tabs['inventory'] );
        
        $tabs['vxn_marketplace'] = array(
            'label'    => __('Marketplace URL', VXN_EXPRESS_ADDON_DOMAIN),
            'target'   => 'vxn-woo-marketplace',
            'priority' => 10,
        );
        return $tabs;
    }

    /** @return void  */
    public static function styling_tab() {

        if(get_post_type() == 'product'){
            echo 
                '<style>
                    #woocommerce-product-data ul.wc-tabs li.vxn_marketplace_options.vxn_marketplace_tab a:before{
                        content: "\f103";
                    }
                </style>';
        } 
    }

    /** @return void  */
    public static function woocommerce_product_custom_fields() {
        echo '<div id="vxn-woo-marketplace" class="panel woocommerce_options_panel">';

        if(Express::Options('vxn_express_woo.txt_order_via_shopee')){
            woocommerce_wp_text_input([
                'id' => '_vxn_express_order_via_shopee',
                // 'placeholder' => 'https://www.shopee.co.id',
                'label' => esc_attr( __('Shopee URL', VXN_EXPRESS_ADDON_DOMAIN) ),
                'desc_tip' => 'true'
            ]);
        }

        if(Express::Options('vxn_express_woo.txt_order_via_tokopedia')){            
            woocommerce_wp_text_input([
                'id' => '_vxn_express_order_via_tokopedia',
                // 'placeholder' => 'https://www.tokopedia.com',
                'label' => esc_attr( __('Tokopedia URL', VXN_EXPRESS_ADDON_DOMAIN)),
                'desc_tip' => 'true'
            ]);
        }

        if(Express::Options('vxn_express_woo.txt_order_via_bukalapak')){
            woocommerce_wp_text_input([
                'id' => '_vxn_express_order_via_bukalapak',
                // 'placeholder' => 'https://www.bukalapak.com',
                'label' => esc_attr( __('Bukalapak URL', VXN_EXPRESS_ADDON_DOMAIN)),
                'desc_tip' => 'true'
            ]);
        }

        echo '</div>';
    }

    /**
     * @param mixed $post_id 
     * @return void 
     */
    public static function woocommerce_product_custom_fields_save($post_id){
        self::save_post_meta_text($post_id, '_vxn_express_order_via_bukalapak');
        self::save_post_meta_text($post_id, '_vxn_express_order_via_tokopedia');
        self::save_post_meta_text($post_id, '_vxn_express_order_via_shopee');
    }

    /**
     * @param mixed $post_id 
     * @param string $field 
     * @return void 
     */
    public static function save_post_meta_text($post_id, $field){
        $value = $_POST[$field];
        if (!empty($value))
            update_post_meta($post_id, $field, esc_attr($value));
    }

    /** @return void  */
    public static function add_shortcodes() {
        if (Express::Options('vxn_express_woo.txt_order_via_shopee')){
            Express::add_shortcode(
                'vxn_woo_order_via_shopee_url', 
                function() {
                    global $post;
                    $url = get_post_meta($post->ID, '_vxn_express_order_via_shopee', true) ? : '';
                    return esc_url($url);
                }
            );
        }        

        if (Express::Options('vxn_express_woo.txt_order_via_tokopedia')){
            Express::add_shortcode(
                'vxn_woo_order_via_tokopedia_url', 
                function() {
                    global $post;
                    $url = get_post_meta($post->ID, '_vxn_express_order_via_tokopedia', true) ? : '';
                    return esc_url($url);
                }
            );            
        }        
     
        if (Express::Options('vxn_express_woo.txt_order_via_bukalapak')){
            Express::add_shortcode(
                'vxn_woo_order_via_bukalapak_url', 
                function() {
                    global $post;
                    $url = get_post_meta($post->ID, '_vxn_express_order_via_bukalapak', true) ? : '';
                    return esc_url($url);
                }
            );              
        }        
    }
    
}

