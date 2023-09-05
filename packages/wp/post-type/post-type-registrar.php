<?php
namespace VXN\Express\WP\Post_Type;

/** 
 * This class to register custom post type
 * @package VXN\Express\WP\Post_Type
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Post_Type_Registrar {

    /**
     * @param Post_Type $post_type 
     * @return void 
     */
    public static function register(Post_Type $post_type){
        $labels = array(
            'name' => $post_type['name'],
            'singular_name' => $post_type['singular'],
            'add_new' => __( 'Add New', VXN_EXPRESS_ADDON_DOMAIN ),
            'add_new_item' => sprintf(__( 'Add New %s', VXN_EXPRESS_ADDON_DOMAIN ), $post_type['singular']),
            'edit_item' => sprintf(__( 'Edit %s', VXN_EXPRESS_ADDON_DOMAIN ), $post_type['singular']),
            'new_item' => sprintf(__( 'New %s', VXN_EXPRESS_ADDON_DOMAIN ), $post_type['singular']),
            'all_items' => sprintf(__( 'All %s', VXN_EXPRESS_ADDON_DOMAIN ), $post_type['name']),
            'view_item' => sprintf(__( 'View %s', VXN_EXPRESS_ADDON_DOMAIN ), $post_type['singular']),
            'search_items' => sprintf(__( 'Search %s', VXN_EXPRESS_ADDON_DOMAIN ), strtolower($post_type['singular'])),
            'not_found' => sprintf(__( 'No %s found', VXN_EXPRESS_ADDON_DOMAIN ), strtolower($post_type['singular'])),
            'not_found_in_trash' => sprintf(__( 'No %s found in the Trash', VXN_EXPRESS_ADDON_DOMAIN ), strtolower($post_type['singular'])),
            'parent_item_colon' => sprintf(__( 'Parent %s:', VXN_EXPRESS_ADDON_DOMAIN ), $post_type['singular']),
            'menu_name' => $post_type['menu_name'],
            // 'featured_image' => sprintf(__( '%s image', VXN_EXPRESS_ADDON_DOMAIN ), $post_type['singular']),
            // 'set_featured_image' => sprintf(__( 'Set %s image', VXN_EXPRESS_ADDON_DOMAIN ), $post_type['singular']),
            // 'remove_featured_image' => sprintf(__( 'Remove %s image', VXN_EXPRESS_ADDON_DOMAIN ), $post_type['singular']),
        );

        $args = array(
            'labels' => $labels,
            'description' => $post_type['description'] ?? '',
            'public' => $post_type['public'],
            'show_in_rest' => $post_type['show_in_rest'],
            'menu_icon' => $post_type['menu_icon'],
            'menu_position' => $post_type['menu_position'],
            'supports' => $post_type['supports'],
            'has_archive' => $post_type['has_archive'],
            'rewrite'     => ['slug' => $post_type['slug']], 
        );                
        register_post_type( $post_type['post_type'], $args);
    }
}