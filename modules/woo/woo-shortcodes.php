<?php
namespace VXN\Express\Woo;

use Breakdance\Lib\Vendor\Twig\Environment;
use Breakdance\Lib\Vendor\Twig\Loader\ArrayLoader;
use VXN\Express;
USE VXN\Express\Helper\Util;


/**
 * Class to add express woo shortcodes
 * @package VXN\Express\Woo
 * @author Vaksin <dev@vaks.in>
 * @since 1.1.8
 */
class Woo_Shortcodes {
	
	/** @return void  */
	public static function add() {
        self::add_woo_shortcodes();
	}

	/** @return void  */
	private static function add_woo_shortcodes() {

		if (Woo::is_activated()){
            Express::add_shortcode(
                'vxn_wa_wo_text_order', 
                function( $attr ) {
                    $text = self::get_wa_wo_text( 'txt_wa_text_order_woo' );			
                    return esc_textarea(self::parse_woo_dynamic_var( $text ));
                }
            );

            Express::add_shortcode(
                'vxn_wa_wo_text_consult', 
                function( $attr ) {
					$text = self::get_wa_wo_text( 'txt_wa_text_consult_woo' );			
					return esc_textarea(self::parse_woo_dynamic_var( $text ));
				}
            );

            Express::add_shortcode(
                'vxn_wc_product_price', 
                function() {	
					return self::parse_woo_dynamic_var( '{woo_price}' );
				}
            );

            Express::add_shortcode(
                'vxn_wc_product_sku', 
                function() {	
					return self::parse_woo_dynamic_var( '{woo_sku}' );
				}
            );

            Express::add_shortcode(
                'vxn_wc_product_name', 
                function() {	
					return self::parse_woo_dynamic_var( '{woo_name}' );
				}
            );

            Express::add_shortcode(
                'vxn_wc_product_excerpt', 
                function() {	
					return self::parse_woo_dynamic_var( '{woo_excerpt}' );
				}
            );

            Express::add_shortcode(
                'vxn_wc_stock_status', 
                function() {	
					return self::parse_woo_dynamic_var( '{woo_stock_status}' );
				}
            );

            Express::add_shortcode(
                'vxn_wc_product', 
                function( $attr, $content ) {
                    $content = do_shortcode($content);
                    return self::parse_woo_dynamic_var( $content );
                }
            );
            
            Express::add_shortcode(
                'vxn_wc_attribute', 
                function( $attr ) {
                    $args = shortcode_atts( array(     
                        'name' => '',
                        'separator' => ', ',
                        'link' => 0
                    ), $attr );

                    $taxonomy = 'pa_' . strtolower($args['name']);

                    $post = get_post();
                    $product = wc_get_product( $post->ID );
                    $terms = wp_get_post_terms( $product->get_ID(), $taxonomy );
                    foreach ($terms as $term) {
                        if($args['link']){
                            $ar_terms[] = sprintf('<a href=%s>%s</a>', get_term_link($term, $taxonomy), $term->name) ;
                        }else{
                            $ar_terms[] = $term->name;
                        }
                    }

                    return implode($args['separator'], $ar_terms);
                }
            );     
            
            Express::add_shortcode(
                'vxn_wc_sub_category', 
                function( $atts ) {
                    $atts = shortcode_atts( array(
                        'parent' => get_queried_object_id(),
                        'count' => 0,
                        'link' => 1,
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => 0,
                        'separator' => '',
                        'class' => ''
                    ), $atts );

                    $args = array(
                        'orderby'    => $atts['orderby'],
                        'order'      => $atts['order'],
                        'hide_empty' => $atts['hide_empty'],                        
                        'child_of'   => $atts['parent'],
                    );                    

                    $args = array(
                        'orderby'    => $atts['orderby'],
                        'order'      => $atts['order'],
                        'hide_empty' => $atts['hide_empty'],                        
                        'child_of'   => $atts['parent'],
                    );   
                                        
                    $terms = wp_list_filter(get_terms( 'product_cat', $args ), ['parent' => $atts['parent']]);                    
                    
                    $ar_terms = [];
                    foreach($terms as $the_term){
                        $term = get_term($the_term, 'product_cat');

                        if($atts['hide_empty'] && $term->count == 0){
                            continue;
                        }

                        $term_name = $term->name;
                        
                        if($atts['count']){
                            $term_name .= " ({$term->count})";
                        }

                        if($atts['link']){
                            $ar_terms[] = sprintf('<a href=%s>%s</a>', get_term_link($term), $term_name); 
                        }else{
                            $ar_terms[] = $term_name; 
                        }
                    }

                    if(!$ar_terms) {
                        return '';
                    }

                    if($atts['separator']){
                        $list = implode($atts['separator'], $ar_terms);
                    }else{
                        $list = sprintf('<ul><li><div>%s</div></li></ul>', implode('</div></li><li><div>', $ar_terms));
                    }

                    if($atts['class']){
                        $list = sprintf('<div class="%s">%s</div>', $atts['class']. $list);
                    }

                    return $list;
                }
            );


            Express::add_shortcode(
                'vxn_wc_price_by_variants', 
                function( $atts ) {
                    $atts = shortcode_atts( array(
                        'attr_group' => '',
                        'group_tag' => 'h3',
                        'loader' => '',
                    ), $atts );
                    
                    $product = new \WC_Product_Variable( get_post()->ID );                    

                    // $variations = $product->get_available_variations();
                    $variations = [];
                    
                    foreach($product->get_available_variations() as $variation){
                        $attributes = [];
                        foreach($variation['attributes'] as $key => $value){
                            $attr_id = str_replace('attribute_pa_', '', $key);
                            $attributes[$attr_id] = get_term_by('slug', $value, 'pa_' . $attr_id)->name;

                            $term = get_term_by('slug', $value, 'pa_' . $attr_id);
                            $attributes[$attr_id] = [
                                'name' => $term->name, 
                                'url' => get_term_link($term)
                            ];
                        }

                        $variations[] = [
                            'attributes' => $attributes,
                            'price' => strip_tags($variation['price_html']) 
                        ];
                    }

                    if($atts['loader']){

                        $code = str_replace('<br>', '', $atts['loader']);
                        $loader = new ArrayLoader(['variation' => $code  ]);
                        $twig = new Environment($loader, ['autoescape' => false]);

                        return $twig->render('variation', ['variations' => $variations]); 

                    }elseif($atts['attr_group']){
                        $groups = array_map('trim', explode(',', $atts['attr_group']));
                        $output = '<div class="ew_variations bde-div">';

                        $last_group = '';
                        foreach($variations as $variation){
                            
                            $ar_current_group = [];
                            foreach($groups as $group_id){
                                if(array_key_exists($group_id, $variation['attributes'])){
                                    $ar_current_group[] = $variation['attributes'][$group_id]['name'];
                                }                                
                            }

                            $current_group = implode(', ',$ar_current_group);

                            if($last_group != $current_group){
                                // tutup div ew_variant_group
                                if($last_group != "") {
                                    $output .= '</div>';
                                }

                                $output .= sprintf(
                                    '<div class="ew_variant_group bde-div"><%s class="ew_group_title bde-heading">%s</%s>', 
                                    $atts['group_tag'],
                                    $current_group,
                                    $atts['group_tag']
                                );

                                $last_group = $current_group;
                            }

                            $name = [];  
                            foreach($variation['attributes'] as $key => $value){
                                if(!in_array($key, $groups)){
                                    $name[] = $value['name'];
                                }                                
                            }

                            $output .= sprintf(
                                '<div class="ew_variant_row bde-div"><span class="ew_variant">%s:&nbsp</span><span class="ew_price">%s</span></div>', 
                                implode(', ', $name),
                                $variation['price']
                            ) ;
                        }
                        $output .= '</div></div>';
                        return $output;

                        /* Styling sammple
                            %%SELECTOR%% .ew_variations {
                                gap: 36px;
                                flex-wrap: wrap;
                                flex-direction: row;
                                justify-content: space-between;
                            }

                            %%SELECTOR%% .ew_variant_group {
                                gap: 8px;
                            }

                            %%SELECTOR%% .ew_variant_row {
                            flex-wrap: wrap;
                            flex-direction: row;
                            justify-content: flex-start;
                            padding: 0px 8px;
                            gap: 4px;
                            }

                            %%SELECTOR%% .ew_variant {
                                font-weight:600;
                            }
                         */

                    }else{

                        $output = '<div class="ew_variations">';
                        foreach($variations as $variation){
                            $name = [];

                            foreach($variation['attributes'] as $attribute){
                                $name[] = $attribute['name'];
                            }

                            $output .= sprintf(
                                '<div class="ew_variant_row"><span class="ew_variant">%s:&nbsp</span><span class="ew_price">%s</span></div>', 
                                implode(', ', $name),
                                $variation['price']
                            ) ;
                        }
                        $output .= '</div>';
                        return $output;
                    }

                }
            );    
            
            Express::add_shortcode(
                'vxn_wc_variant_filter', 
                function() {
                    global $wp_query;
                    // $q = $wp_query->query_vars;
                    $filter = [];
                    foreach($wp_query->query_vars as $key => $value){
                        if(substr($key,0,7) == 'filter_'){
                            $filter[str_replace('filter_', '', $key)] = $value;
                        }
                    }
                    $product = new \WC_Product_Variable( get_post()->ID );                                        
                    foreach($product->get_available_variations() as $variation){
                        foreach($variation['attributes'] as $key => $value){
                            $attr_id = str_replace('attribute_pa_', '', $key);
                            $filter = "filter_{$attr_id}";
                            // if(array_key_exists($filter, $q) && $q[$filter] != $value){
                            //     return 0;
                            // }
                        }
                    }

                    return 1;
				}
            );            
		}
	}

	/**
	 * @param string $text 
	 * @return string 
	 */
	private static function parse_woo_dynamic_var( $text ) {
        global $post;
		$product = wc_get_product( $post->ID );
        
        if(!$product) return $text;

		$product_price = $product->get_price();
		$product_id = $product->get_ID();	

		if ( strpos( $text, '{woo_product}' ) !== false ) {
			$dynamic_var['woo_product'] = $product->get_name(); // get_the_title();
		}

		if ( strpos( $text, '{woo_name}' ) !== false ) {
			$dynamic_var['woo_name'] = $product->get_name(); // get_the_title();
		}        

        if ( strpos( $text, '{woo_excerpt}' ) !== false ) {
			$dynamic_var['woo_excerpt'] = get_the_excerpt($product_id); // get_the_title();
		}        
		
		if ( strpos( $text, '{woo_price}' ) !== false ) {
			$dynamic_var['woo_price'] = strip_tags( wc_price( $product_price ) );
		}
		
		if ( strpos( $text, '{woo_sku}' ) !== false ) {
			$dynamic_var['woo_sku'] = get_post_meta( $product_id, '_sku', true );
		}

		if ( strpos( $text, '{woo_stock_status}' ) !== false ) {
			$dynamic_var['woo_stock_status'] = $product->get_stock_status();
		}        
					
		if ( strpos( $text, '{woo_categories}' ) !== false ) {
			$cats_array = array();
			$cats = '';
			$terms = wp_get_post_terms( $product_id, 'product_cat' );
			foreach ($terms as $term) {
				$cats_array[] = $term->name;
			}
			$cats = implode(', ', $cats_array);
			$dynamic_var['woo_categories'] = $cats;
		}
		
		if ( strpos( $text, '{woo_tags}' ) !== false ) {
			$tags_array = array();
			$tags = '';
			$terms = wp_get_post_terms( $product_id, 'product_tag' );
			foreach ($terms as $term) {
				$tags_array[] = $term->name;
			}
			$tags = implode(', ', $tags_array);
			$dynamic_var['woo_tags'] = $tags;
		}

		if ( !empty( $dynamic_var ) ) {
			foreach ( $dynamic_var as $key => $value ) {
				$text = str_replace( '{' . $key . '}', $value, $text );
			}
		}

		$text = Util::parse_shortcode_dynamic_var($text);

		return str_replace("'", "`", $text);
	}


	/**
	 * @param string $field 
	 * @return string 
	 */
	private static function get_wa_wo_text( $field ) {
		// return Express::Options('woo')[$field] ?? Express::Options('whatsapp')['txt-wa-text-default'];
        return Express::Options("vxn_express_woo.{$field}") ?? Express::Options('vxn_express_whatsapp.txt_wa_text_default', '');
	}

}

