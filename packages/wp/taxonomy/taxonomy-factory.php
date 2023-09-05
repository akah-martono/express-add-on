<?php
namespace VXN\Express\WP\Taxonomy;

use VXN\Express;

class Taxonomy_Factory {
    /**
     * @param string $page 
     * @param string $prefix 
     * @param string $name 
     * @return Taxonomy|false 
     */
    public static function create_from_setup_page($page, $prefix, $name) :Taxonomy | false {
        $taxonomy_name = Express::Options("{$page}.txt_{$name}_name");
        $taxonomy_description = Express::Options("{$page}.txt_{$name}_description");
        $taxonomy_singular = Express::Options("{$page}.txt_{$name}_singular");
        $taxonomy_menu = Express::Options("{$page}.txt_{$name}_menu");
        $taxonomy_slug = Express::Options("{$page}.txt_{$name}_slug");
        
        if($taxonomy_name && $taxonomy_singular && $taxonomy_menu && $taxonomy_slug){
            return
                (new Taxonomy(
                    "{$prefix}_{$name}",
                    $taxonomy_name,
                    $taxonomy_singular,
                    $taxonomy_menu
                ))
                ->set_slug($taxonomy_slug)
                ->set_description($taxonomy_description);        
        }

        return false;
    }   
        

}
