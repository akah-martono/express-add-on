<?php
namespace VXN\Express\WP\Post_Type;

use VXN\Express\Section\Sections_Shortcode_Builder;

/** 
 * This class to create post type shortcode
 * @package VXN\Express\WP\Post_Type
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Post_Type_Shortcode_Builder {
    /**
     * @param Post_Type $post_type 
     * @return array 
     */
    public static function build(Post_Type $post_type){
        return Sections_Shortcode_Builder::build(
            $post_type['sections'], 
            $post_type['shortcode_tag'], 
            function($field) {
                $post = get_post();
                $field->set_value(get_post_meta( $post->ID, $field['id'], true ) ? : '');
                return $field->get_sanitized_value();    
            }
        );
    }
}