<?php
namespace VXN\Express\WP\Post_Type;

use VXN\Express\Breakdance;
use VXN\Express;
use VXN\Express\Breakdance\Dynamic_Data\BDD_Meta_Field_Image;
use VXN\Express\Breakdance\Dynamic_Data\BDD_Meta_Field_String;
use VXN\Express\Breakdance\Dynamic_Data\BDD_Meta_Field_URL;
use VXN\Express\WP\Meta\Metabox;
use VXN\Express\Breakdance\Form_Action\BFA_Store_Post_Factory;

/** 
 * This class to build custom post types and the necessary
 * @package VXN\Express\WP\Post_Type
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Post_Types_Builder {
    /**
     * @param array $post_types 
     * @return void 
     */
    public static function build()
    {
        $post_types = Express::post_types();
        if( is_admin() ){
            self::set_enter_title_here($post_types);
            self::remove_post_type_support($post_types);
            self::generate_metabox($post_types);
        }else{
            self::create_shortcodes($post_types);
        }

        self::create_custom_posts($post_types); // ini kayaknya cuma di admin
        self::create_taxonomies($post_types); // ini kayaknya cuma di admin
        self::generate_breakdance_things($post_types);
    }

    /** @return void  */
    private static function create_custom_posts($post_types){
        add_action( 'init', function() use ($post_types) {
            foreach($post_types as $post_type){
                Post_Type_Registrar::register($post_type);
            }
            
        } );
    }

    /** @return void  */
    private static function create_taxonomies($post_types){  //nanti diubah
        foreach($post_types as $post_type){
            foreach($post_type['taxonomies'] as $taxonomy){
                Express::add_taxonomy($taxonomy);
            }
        }           
    }

    /** @return void  */
    private static function remove_post_type_support($post_types) {
        add_action('admin_init', function () use($post_types) {
            foreach($post_types as $post_type){
                foreach($post_type['remove_features'] as $feature){
                    remove_post_type_support($post_type['post_type'], $feature);
                }    
            }
        });        
    }

    /** @return void  */
    private static function create_shortcodes($post_types) {
        foreach($post_types as $post_type){
            Post_Type_Shortcode_Builder::build($post_type);
        }
    }

    /** @return void  */
    private static function generate_metabox($post_types){
        foreach($post_types as $post_type){
            foreach($post_type['sections'] as $section){
                Express::add_metabox(
                    (new Metabox($section)) // nanto pakai nonce  
                    ->set_nonce('_vxn_' . $post_type['post_type'])                  
                    ->add_screen($post_type['post_type'])
                );
            };
        }
    }

    /** @return void  */
    private static function generate_breakdance_things($post_types){
        foreach($post_types as $post_type){
            
            if($post_type['enable_submit_via_form']){
                $store_action = BFA_Store_Post_Factory::create($post_type); 
                Breakdance::add_form_action($store_action);
            }            

            if($post_type['shortcode_tag']){
                self::add_dynamic_field(new BDD_Meta_Field_String($post_type));
                self::add_dynamic_field(new BDD_Meta_Field_URL($post_type));    
                self::add_dynamic_field(new BDD_Meta_Field_Image($post_type));    
            }
            
        }
    }

    private static function add_dynamic_field($dynamic_field){
        if($dynamic_field->has_items()){
            Breakdance::add_dynamic_field($dynamic_field);    
        }
    }

    /** @return void  */
    private static function set_enter_title_here($post_types){
        foreach($post_types as $post_type){
            if(is_admin() && isset($post_type['enter_title_here'])){
                add_filter( 'enter_title_here', function( $input ) use($post_type) {
                    if( $post_type['post_type'] == get_post_type() ) {
                        return $post_type['enter_title_here'];
                    } else {
                        return $input;
                    }
                });    
            }    
        }
    }
}