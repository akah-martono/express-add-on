<?php
namespace VXN\Express\WP\Post_Type;

use VXN\Express;
use VXN\Express\WP\Taxonomy\Taxonomy;

class Post_Type_Factory {
    /** @return Post_Type  */
    public static function create_from_setup_page(
        $page,
        $post_type,
        $default_name,        
        $default_singular,
        $default_menu,
        $default_slug,
        $default_description =''
    ){

        $cpt_name = Express::Options("{$page}.txt_cpt_name", $default_name);        
        $cpt_singular = Express::Options("{$page}.txt_cpt_singular", $default_singular);
        $cpt_menu = Express::Options("{$page}.txt_cpt_menu", $default_menu);
        $cpt_slug = Express::Options("{$page}.txt_cpt_slug", $default_slug);
        $cpt_description = Express::Options("{$page}.txt_cpt_description", $default_description);

        return 
            (new Post_Type ( $post_type, $cpt_name, $cpt_singular, $cpt_menu))            
            ->set_description($cpt_description)
            ->set_slug($cpt_slug)
            ->set_enter_title_here(sprintf(__('Enter %s Here', VXN_EXPRESS_ADDON_DOMAIN), $cpt_singular));
    }  
}