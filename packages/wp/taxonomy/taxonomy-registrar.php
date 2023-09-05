<?php
namespace VXN\Express\WP\Taxonomy;

/** 
 * This class to resgiter Taxonomy
 * @package VXN\Express\WP\Taxonomy
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Taxonomy_Registrar {
    /** @return void  */
    public static function register(Taxonomy $taxonomy){
        
        $labels = array(
            'name' => _x($taxonomy['name'], 'taxonomy general name'),
            'singular_name' => _x($taxonomy['singular_name'], 'taxonomy singular name'),
            'add_new_item' => sprintf(__( 'Add New %s', VXN_EXPRESS_ADDON_DOMAIN ), $taxonomy['singular_name']),
            'new_item_name' => sprintf(__( 'New %s Name', VXN_EXPRESS_ADDON_DOMAIN ), $taxonomy['singular_name']),
            'edit_item' => sprintf(__( 'Edit %s', VXN_EXPRESS_ADDON_DOMAIN ), $taxonomy['singular_name']),
            'update_item' => sprintf(__( 'Update %s', VXN_EXPRESS_ADDON_DOMAIN ), $taxonomy['singular_name']),
            'all_items' => sprintf(__( 'All %s', VXN_EXPRESS_ADDON_DOMAIN ), $taxonomy['name']),
            'search_items' => sprintf(__( 'Search %s', VXN_EXPRESS_ADDON_DOMAIN ), strtolower($taxonomy['name'])),
            'parent_item' => sprintf(__( 'Parent %s', VXN_EXPRESS_ADDON_DOMAIN ), $taxonomy['singular_name']),
            'parent_item_colon' => sprintf(__( 'Parent %s:', VXN_EXPRESS_ADDON_DOMAIN ), $taxonomy['singular_name']),
            'menu_name' => $taxonomy['menu_name']
        );
  
        $args = array(
            'hierarchical' => $taxonomy['hierarchical'] ,
            'description' => $taxonomy['description'] ,
            'labels' => $labels,            
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => $taxonomy['slug'] ),
        );

        register_taxonomy($taxonomy['taxonomy'], $taxonomy['object_type'], $args);                                           
    }
}
