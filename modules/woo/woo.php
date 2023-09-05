<?php
namespace VXN\Express\Woo;

use VXN\Express;
use VXN\Express\Helper\Util;
use WC_Product_Query;
use WC_Query;

/**
 * Express woo core class
 * @package VXN\Express\Woo
 * @author Vaksin <dev@vaks.in>
 * @since 1.1.8
 */
class Woo {
    /** @return void  */
	public static function customize() {        
        if ( ! self::is_activated() ) {
            return;
        }
        self::woo_setup();
    }
    
    /** @return bool  */
    public static function is_activated() {
        return class_exists( 'woocommerce' );
    }

    /** @return void  */
    private static function woo_setup() {

        if ( Express::Options('vxn_express_woo.chk_catalog_mode') ) {
            self::set_catalog_mode();
        }else{
            self::reg_woongkir();
        }

        $atc_text_single = Express::Options('vxn_express_woo.txt_atc_text_product_single');
        if( $atc_text_single ) {
            self::set_atc_text_product_single($atc_text_single);
        }

        $atc_text_shop = Express::Options('vxn_express_woo.txt_atc_text_shop');
        if( $atc_text_shop ) {
            self::set_atc_text_shop($atc_text_shop);
        }

        $sale_flash_text = Express::Options('vxn_express_woo.txt_sale_flash_text');
        if( $sale_flash_text ) {
            self::set_sale_flash_text($sale_flash_text);
        }
        
        if(Express::Options('vxn_express_woo.chk_enable_block_editor')){                        
            self::enable_block_editor_on_product(true);
        }

        $translations_text = Express::Options('vxn_express_woo.txt_woo_replace_text');
        if($translations_text){
            self::replace_translated_text($translations_text);            
        }

        self::add_clear_product_cache_actions();
        self::add_action_woocommerce_product_query();
    }

    /** @return void  */
    private static function reg_woongkir() {
        if ( class_exists( 'Woongkir' ) ) {	
            $woongkir_key = self::get_woongkir_key();
            if($woongkir_key !== false){
                add_filter( 'woongkir_api_key_hardcoded', function() use($woongkir_key) {
                    return $woongkir_key;
                });    
            }

            while ( have_posts()) {
                the_post();
                $block = wp_cache_get('vxn_block_list_' . get_the_ID(), 'vxn_express');
                if( false === $block ){ 
                    $block = do_shortcode('[breakdance_block blockId=1154]');
                    wp_cache_set('vxn_block_list_' . get_the_ID(), $block, 'vxn_express');
                }
                printf('<div class="ew-post-loop">%s</div>', $block);    
            }
        }        
    }

    /** @return string|false|void  */
    private static function get_woongkir_key(){
        $vxn_woongkir_key = wp_cache_get('vxn_woongkir_key', 'vxn_express');

        if($vxn_woongkir_key === false){
            $auth = Util::get_eweb_auth();

            if(empty($auth)){
                return false;
            }
            
            $response=wp_remote_get('https://api.eweb.co.id/license/woongkir', ['headers'=> $auth]);
    
            if(is_wp_error($response)){
                return false;
            }
    
            $response=$response['http_response'];
            $data = json_decode($response->get_data(), true);
            if($data['status'] == 1){
                $vxn_woongkir_key = Util::decrypt('aes-128-cbc-hmac-sha256', $auth['uid'], $data['message']);
                wp_cache_add('vxn_woongkir_key', $vxn_woongkir_key, 'vxn_express');
            }
        }
        
        return $vxn_woongkir_key;
    }

    /**
     * @param string $text 
     * @return void 
     */
    public static function set_atc_text_shop($text) {
        add_filter( 'woocommerce_product_add_to_cart_text', function () use ($text){
            return $text; 
        });
    }

    /**
     * @param string $text 
     * @return void 
     */
    public static function set_atc_text_product_single($text) {
        add_filter( 'woocommerce_product_single_add_to_cart_text', function () use ($text) {
            return $text; 
        });
    }

    /** @return void  */
    public static function remove_atc_button_product_single(){
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
    }

    /** @return void  */
    public static function remove_atc_button_product_shop(){
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
    }

    /**
     * @param bool $catalog_mode 
     * @return void 
     */
    public static function set_catalog_mode(bool $catalog_mode = true){
        add_filter( 'woocommerce_is_purchasable', function() use ($catalog_mode) {
             return !$catalog_mode; 
        } );
    }

    /** @return void  */
    public static function direct_checkout(){
        //remove ajax_add_to_cart class
        add_action('wp_footer', function() {
            if( is_shop() || is_product_category() || is_product_tag() || is_page() ){
                echo 
                '<script>
                window.onload = function(){	
                    var atc_ajax = document.querySelectorAll(".ajax_add_to_cart");

                    [].forEach.call(atc_ajax, function(el) {
                        el.classList.remove("ajax_add_to_cart");
                    });
                }
                </script>';
            }
        });

        //remove add to cart message
        add_filter( 'wc_add_to_cart_message_html', '__return_false' );

        //direct checkout
        add_filter('woocommerce_add_to_cart_redirect', function() {
            return wc_get_checkout_url();
        });         
    }

    /** @return void  */
    public static function no_cart(){
        //empty cart before adding
        add_filter( 'woocommerce_add_to_cart_validation', function ( $passed, $product_id, $quantity ) {
            if( ! WC()->cart->is_empty() )
                WC()->cart->empty_cart();
            return $passed;
        }, 20, 3 );        
    }

    /**
     * @param callable $callback 
     * @return void 
     */
    public static function set_atc_url(callable $callback){
        // woocommerce_product_add_to_cart_url
        add_filter( 'woocommerce_product_add_to_cart_url', $callback, 20, 3 );          
    }

    public static function set_sale_flash_text($text){
        add_filter('woocommerce_sale_flash', function() use ($text) {
            return "<span class=\"onsale\">{$text}</span>";            
        });
    }

    public static function replace_translated_text($translations_text){
        $translations = [];
        foreach(preg_split("/\r\n|\n|\r/", $translations_text) as $text_line){
            $translation = explode('=', $text_line);
            if(is_array($translation) && count($translation) == 2) {
                $translations[$translation[0]] = $translation[1];
            }                
        }
        if($translations){
            Util::replace_translated_text($translations, 'woocommerce');
        }
    }

    public static function is_on_sale($post_id = 0){
        if(!$post_id){
            global $post;
            $post_id = $post->ID ;    
        }

        $product = wc_get_product( $post_id );
        return $product->is_on_sale();
    }

    public static function enable_block_editor_on_product(bool $enable = true){    
        add_filter('use_block_editor_for_post_type', function ($can_edit, $post_type) use ($enable){
            if($post_type == 'product'){
                $can_edit = $enable;
            }
            return $can_edit;
        }, 10, 2);
    }

    public static function remove_schema(){
        add_action( 'init', function() {
            remove_action( 'wp_footer', array( WC()->structured_data, 'output_structured_data' ), 10 ); // This removes structured data from all frontend pages
        } );        
    }
    
    public static function show_category_image(){
        add_action( 'woocommerce_archive_description', function() {
            if ( is_product_category() ){
                global $wp_query;
                $cat = $wp_query->get_queried_object();
                $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                $image = wp_get_attachment_url( $thumbnail_id );
                if ( $image ) {
                    echo '<img src="' . $image . '" alt="' . $cat->name . '" />';
                }
            }
        }, 2 );
    }

    private static function set_variation_group(array $variations, string|array $group_by){
        if(is_string($group_by)){
            $group_by = array_map('trim', explode(',', $group_by));
        }
        
        foreach($variations as $key => $variation){
            $group_attributes = [];
            foreach($group_by as $group){
                if(array_key_exists($group, $variation['attributes'])){
                    $group_attributes[] = $variation['attributes'][$group]['name'];
                }                                
            }
            $variations[$key]['group'] =implode(', ', $group_attributes); 
        }
        
        $groups=[];
        // get unique group name
        foreach($variations as $variation){
            if(!in_array($variation['group'], $groups)){
                array_push($groups, $variation['group']);
            }
        }
        
        // sort variations based on group (follow current group order)
        $sorted = [];
        foreach($groups as $group){
            $filtered = array_filter($variations, function($var) use ($group) {
                return $var['group'] == $group;
            }); 

            foreach($filtered as $variation)
            {
                $sorted[] = $variation;
            }
        }

        if($sorted){
            $variations = $sorted;
        }

        return $variations;
    }

    public static function get_price_by_variants(array $args){
        
        $defaults = [
            'attr_group' => '',
            'group_tag' => 'h3',
            'twig_template' => '',
            'wa_link' => false,
            'link_title' => 'Klik di sini untuk order {product}',            
            'data_wa_url' => false
        ];

        $args = wp_parse_args( $args, $defaults );

        $variations = self::get_available_variations(get_post()->ID);

        if($args['attr_group']){
            $variations = self::set_variation_group($variations, $args['attr_group']);
        }

        if($args['twig_template']){
            $template = str_replace('<br>', '', $args['twig_template']);
            return Util::twig_renderer($template, ['variations' => $variations]);
        }

        if($args['attr_group']){
            $groups = array_map('trim', explode(',', $args['attr_group']));
            $output = '<ul class="ew-variant-groups">';

            $last_group = '';
            foreach($variations as $variation){
                if($last_group != $variation['group']){
                
                    if($last_group != "") {
                        $output .= '</ul></li>';
                    }

                    $output .= sprintf(
                        '<li class="ew-variant-group"><%s class="ew-variant-group-title bde-heading">%s</%s>', 
                        $args['group_tag'],
                        $variation['group'],
                        $args['group_tag']
                    );

                    $output .= '<ul class="ew-variant-group-items">';

                    $last_group = $variation['group'];
                }

                $name = [];  
                foreach($variation['attributes'] as $key => $value){
                    if(!in_array($key, $groups)){
                        $name[] = $value['name'];
                    }                                
                }
                
                $variant_price = $variation['price'];
                if($args['wa_link']){
                    $variant_price = sprintf(
                        '<a href="%s" title="%s">%s</a>', 
                        esc_attr($variation['wa_url']),
                        esc_attr(str_replace('{product}', $variation['full_name'], $args['link_title'])),
                        $variation['price']
                    );    
                }
                
                $output .= sprintf(
                    '<li class="ew-variant-group-item" data-variant-id="%s" %s>
                    <span class="ew-variant-name">%s:&nbsp</span><span class="ew-variant-price">%s</span>
                    </li>',
                    $args['id'],
                    $args['data_wa_url'] ? 'data-wa-url="' . $args['data_wa_url'] . '"' : '',
                    implode(', ', $name),
                    $variant_price
                );
            }
            $output .= '</ul></li></ul>';
            return $output;
        }
        
        $output = '<ul class="ew-variant-items">';
        foreach($variations as $variation){
            $name = [];
            foreach($variation['attributes'] as $attribute){
                $name[] = $attribute['name'];
            }

            $variant_price = $variation['price'];
            if($args['wa_link']){
                $variant_price = sprintf(
                    '<a href="%s" title="%s">%s</a>', 
                    esc_attr($variation['wa_url']),
                    esc_attr(str_replace('{product}', $variation['full_name'], $args['link_title'])),
                    $variation['price']
                );    
            }
            
            $output .= sprintf(
                '<li class="ew-variant-item" data-variant-id="%s" %s>
                <span class="ew-variant-name">%s:&nbsp</span><span class="ew-variant-price">%s</span>
                </li>',
                $args['id'],
                $args['data_wa_url'] ? 'data-wa-url="' . $args['data_wa_url'] . '"' : '',
                implode(', ', $name),
                $variant_price
            );
        }
        $output .= '</ul>';
        return $output;
    }  
    
    public static function get_available_variations($product_id = 0){
        if($product_id == 0){ 
            $product_id = get_post()->ID;
        }

        $query_var_filter = self::get_query_wc_filters();
        
        $cache_key = 'vxn_wc_variation_' . md5(serialize($query_var_filter));

        $variations = wp_cache_get($cache_key, "vxn_express_wcp_{$product_id}");
        if($variations === false){
            $product = new \WC_Product_Variable( $product_id );
            $product_name = $product->get_name();
            $variations = [];
    
            foreach($product->get_available_variations() as $variation){
                $attributes = [];            
                $attribute_names = [];
                $filters = [];
                foreach($variation['attributes'] as $key => $value){
                    // woocomerce menambahkan prefix attribute_ untuk attribute terms, aslinya adalah pa_... 
                    $attr_id = str_replace('attribute_pa_', '', $key);

                    $filter_key = "filter_{$attr_id}";
                    if(array_key_exists($filter_key, $query_var_filter)){
                        $filters[$filter_key] = ($query_var_filter[$filter_key] == $value);	
                    }

                    $attributes[$attr_id] = get_term_by('slug', $value, 'pa_' . $attr_id)->name;
    
                    $term = get_term_by('slug', $value, 'pa_' . $attr_id);
                    $attributes[$attr_id] = [
                        'name' => $term->name, 
                        'url' => get_term_link($term)
                    ];
                    $attribute_names[] = $term->name;
                }
    
                if(!empty($filters)){
                    $and_value = true;
                    $or_value = false;
                    foreach($filters as $filter){
                        $and_value = ($and_value && $filter);
                        $or_value =  ($or_value || $filter);
                    }
                    $filters['and'] = $and_value;
                    $filters['or'] = $or_value;    
                } else {
                    $filters['and'] = true;
                    $filters['or'] = true;                    
                }

                $full_name = $product_name . ' ' . implode(', ', $attribute_names);
                    
                $variations[] = [
                    'id' => $variation['variation_id'],
                    'name' => $product_name,                
                    'attributes' => $attributes,
                    'price' => strip_tags($variation['price_html']),
                    'full_name' => $full_name,
                    'wa_url' => do_shortcode(
                        '[vxn_wa_url text="' . do_shortcode('[vxn_wa_text_order product="' . $full_name . '"]') . '"]'
                    ),
                    'filter' => $filters,
                ];                 
            }
    
            wp_cache_set($cache_key . get_the_ID(), $variations, "vxn_express_wcp_{$product_id}");
        }

        return $variations;
    }

    public static function get_query_wc_filters(){
        global $wp_query;

        return array_filter($wp_query->query_vars, function($k) {
            return substr($k, 0, 7) == 'filter_';
        }, ARRAY_FILTER_USE_KEY);
    }

    public static function add_action_woocommerce_product_query(){
        add_action( 'woocommerce_product_query', function($q){
            // $post_ids = array_values(array_filter(get_posts(['post_type'=>'product', 'fields' => 'ids', 'numberposts' => -1]), function($id){                
            $post_ids = array_values(array_filter(self::get_product_ids(), function($id){
                $variants = self::get_available_variations($id);
                if(empty($variants)){
                    return true;
                }
    
                foreach($variants as $variant){
                    if($variant['filter']['and']){ //hanya perlu 1 variant yang true
                        return true;
                    }
                }
                return false;
            }));

            $q->set( 'post__in', $post_ids ); 
        } );
    }

    private static function get_product_ids(){
        $ids = wp_cache_get('vxn_product_ids', 'vxn_express_wc_products');
        if($ids === false){
            $ids = get_posts(['post_type'=>'product', 'fields' => 'ids', 'numberposts' => -1]);
            wp_cache_set('vxn_product_ids', $ids, 'vxn_express_wc_products');
        }
        return $ids;
    }

    public static function clear_product_cache($product_id) {        
        wp_cache_flush_group("vxn_express_wcp_{$product_id}");
        wp_cache_flush_group('vxn_express_wc_products');
    }

    public static function add_clear_product_cache_actions() {
        // On processed update product stock event
        add_action( 'woocommerce_updated_product_stock', array(self::class, 'clear_product_cache') );
        // On admin single product creation/update
        add_action( 'woocommerce_process_product_meta', array(self::class, 'clear_product_cache') );
        // On admin single product variation creation/update
        add_action( 'woocommerce_save_product_variation', array(self::class, 'clear_product_cache') );            
    }
}