<?php
namespace VXN\Express\Addon;

use VXN\Express;
use VXN\Express\Contact\Contact_Module;
use VXN\Express\Helper\Util;
use VXN\Express\Job\Job_Module;
use VXN\Express\Team_Member\Team_Member_Module;
use VXN\Express\Testi\Testi_Module;
use VXN\Express\Whatsapp\Whatsapp_Module;
use VXN\Express\Woo\Woo;
use VXN\Express\Woo\Woo_Module;
use VXN\Express\WP\Script\Script;

/**
 * Class to run express add on plugin
 * @package VXN\Express\Addon 
 * @author Vaksin <dev@vaks.in>
 * @since 1.0.0
 */
class Plugin{

    /** @return void  */
    public static function run() {	
        
		add_action('vxn_express_load_modules', function() {
			define( 'VXN_EXPRESS_ADDON_DOMAIN', 'vxn-express' );
			load_plugin_textdomain( VXN_EXPRESS_ADDON_DOMAIN, false, dirname( plugin_basename(VXN_EXPRESS_ADDON_PLUGIN_FILE) ) . '/languages' );
            
            Express::add_module(Util::get_instance(Contact_Module::class));
            Express::add_module(Util::get_instance(Job_Module::class));
            Express::add_module(Util::get_instance(Team_Member_Module::class));            
            Express::add_module(Util::get_instance(Testi_Module::class));
            Express::add_module(Util::get_instance(Whatsapp_Module::class));

            if(Woo::is_activated()){
                Express::add_module(Util::get_instance(Woo_Module::class));
            }
                       
            self::create_fake_yoast_breadcrumbs();
            
        },1);

		add_action('vxn_express_load_modules', function() {
            Express::add_menu_page(Setup_Page_Factory::create());
            
            self::create_shortcode();

            if(Express::Options('vxn_express_setup.txt_excerpt_length')){
                self::set_excerpt_length(Express::Options('vxn_express_setup.txt_excerpt_length'));
            }

            if( Express::Options('vxn_express_setup.chk_fix_bd_img_for_lscache') 
            && ( ! ( is_admin() || isset($_GET['_breakdance_doing_ajax']) ) ) ){
                add_filter( 'litespeed_buffer_before', function ( $content ) {
                    
                    if( !is_front_page() ) return $content;

                    preg_match_all('/<img[^>]*>/i', $content, $matches);
                    foreach ($matches[0] as $match) {
                        $cleaned_tag = preg_replace("/\s+/", " ", $match);
                        $cleaned_tag = str_replace(array("\r", "\n"), '', $cleaned_tag);
                        $content = str_replace($match, $cleaned_tag, $content);
                    }
                    
                    $dom = new \DOMDocument;
                    $dom->loadHTML( $content );                    
                    foreach ( $dom->getElementsByTagName( 'div' ) as $div ) {
                        if( $div->hasAttribute( 'data-no-lazy' ) && $div->getAttribute( 'data-no-lazy' ) == 1 ){
                            foreach ( $div->getElementsByTagName( 'img' ) as $img ) {
                                $img->setAttribute('data-no-lazy', '1');
                            }
                        }
                    }
                                        
                    return $dom->saveHTML() ?? $content;
                }, 0);
            }            

            add_post_type_support( 'page', 'excerpt' );
            
        },999);
        
    }

    private static function create_shortcode(){
        Express::add_shortcode(
            'vxn_format_idr',
            function( $attr, $content ) {
                $content = do_shortcode($content);
                return esc_html('Rp. ' . number_format(floatval($content),0,",","."));
            }
        );

        Express::add_shortcode(
            'vxn_copyright_text',
            function( $attr, $content ) {
                $copyright = sprintf(
                    'Copyright © %s %s',
                    date('Y'),
                    $content ? do_shortcode($content) : get_bloginfo( 'name' )
                );

                return '<span class="copyright">' . wp_kses_post($copyright) . '</span>';
            }
        );

        Express::add_shortcode(
            'vxn_breadcrumb',
            function( $attr ) {
                $args = shortcode_atts( array(     
                    'home' => null,
                    'separator' => null,
                ), $attr );

                $home = $args['home'];
                $separator = $args['separator'];

                return Util::get_breadcrumb($home, $separator, true);
            }
        );

        Express::add_shortcode(
            'vxn_search_query',
            function( $attr ) {
                $args = shortcode_atts( array(     
                    'search_text' => Express::Options('vxn_express_setup.txt_search_text', 'Silahkan ketik yang ingin anda cari pada kolom pencarian'),
                    'found_text' => Express::Options('vxn_express_setup.txt_search_found_text', 'Hasil pencarian untuk "%s"'),
                    'not_found_text' => Express::Options('vxn_express_setup.txt_search_not_found_text', 'Maaf, hasil pencarian untuk "%s" tidak ditemukan'),
                ), $attr );

                $search_query = get_search_query();
                if($search_query){
                    $text_query = sprintf(get_the_title() ? $args['found_text'] : $args['not_found_text'], $search_query);
                }else{
                    $text_query = $args['search_text'];
                }

                return wp_kses_post($text_query);
            }
        );
        
        Express::add_shortcode(
            'vxn_page_for_posts_title',
            function( ) {
                return get_the_title( get_option('page_for_posts') );                
            }
        );

        Express::add_shortcode(
            'vxn_page_for_posts_content',
            function( ) {                
                return get_post_field('post_content', get_option('page_for_posts'));                
            }
        );   

        Express::add_shortcode(
            'vxn_get_terms', 
            function( $attr ) {
                $args = shortcode_atts( array(     
                    'taxonomy' => '',
                    'separator' => ', ',
                    'link' => 0
                ), $attr );
                
                $terms = wp_get_post_terms( get_the_ID(), $args['taxonomy'] );
                foreach ($terms as $term) {
                    if($args['link']){
                        $ar_terms[] = sprintf('<a href=%s>%s</a>', get_term_link($term, $args['taxonomy']), $term->name) ;
                    }else{
                        $ar_terms[] = $term->name;
                    }
                }

                return implode($args['separator'], $ar_terms);
            }
        );               
    }

    private static function create_fake_yoast_breadcrumbs(){
        add_action('init', function(){
            if ( ! function_exists( 'yoast_breadcrumb' ) ) {
                /**
                 * Fake yoast_breadcrumb (required by breakdance breadcrumb element)
                 *
                 * @param string $before  What to show before the breadcrumb.
                 * @param string $after   What to show after the breadcrumb.
                 * @param bool   $display Whether to display the breadcrumb (true) or return it (false).
                 *
                 * @return string
                 */
                $script = '
                function yoast_breadcrumb( $before = "", $after = "", $display = true ) {
                    echo \VXN\Express\Helper\Util::get_breadcrumb(null, null, false);
                }
                ';
                eval($script); 
            }
        }, 99);   
    }

    private static function set_excerpt_length(int $new_length) {
        add_filter( 'excerpt_length', function($length) use ($new_length) {
            	// Don't change anything inside /wp-admin/
                if ( is_admin() ) {
                    return $length;
                }
                // Set excerpt length to new lenght
                return $new_length;
        }, 999 );
    }

    private static function custom_404_redirect() {
        add_action('template_redirect', function() {
            global $wp_query;
        
            if ( $wp_query->is_404() ){
                $page_name = $wp_query->query_vars['name'];
          
                if ( ! $page_name )
                    return;
            
                $query_args = '';
                if ( ($category =  get_category_by_slug( $page_name )) ){
                    // Here we found a category
                    $wp_query->is_category = true;
                    $query_args = 'category_name=' . $category->slug;
                } elseif ( ($tag = get_term_by('slug', $page_name, 'post_tag') ) ) {
                    // Here we found a tag
                    $wp_query->is_tag = true;
                    $query_args = 'tag=' . $tag->slug;
                } else {
                    // Is not a category or a tag
                    return;
                }
          
                $wp_query->is_404 = false;
                $wp_query->is_archive = true;
                status_header( 200 );
            
                query_posts( $query_args );
            }            
        });
    }
}