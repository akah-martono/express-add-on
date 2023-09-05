<?php
namespace VXN\Express\WP\Meta;

use VXN\Express\WP\Meta\Metabox;

/** 
 * This class to save Metabox fields
 * @package VXN\Express\WP\Meta
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Save_Metabox {

    /**
     * @param Metabox $metabox 
     * @return void 
     */
    public static function add_action_to_save_post(Metabox $metabox) {   
        if(empty($metabox['screen'])){
            self::save_by_sreen($metabox, null);
        }else{
            foreach($metabox['screen'] as $screen){
                self::save_by_sreen($metabox, $screen);
            }            
        }
    }

    /**
     * @param Metabox $metabox 
     * @param string $screen 
     * @return void 
     */
    private static function save_by_sreen(Metabox $metabox, $screen) {   
        
        $hook = is_null($screen) ? 'save_post' : sprintf('save_post_%s', $screen);
        
        add_action( $hook , function($post_id, $post) use ($metabox){
            if(isset($metabox['nonce'])){
                $nonce_field = '_vxn_nonce_' . $metabox['id'];
                if (! isset( $_POST[ $nonce_field ] ) || ! wp_verify_nonce( $_POST[ $nonce_field ], $metabox['nonce'] ) ) {
                    return $post_id;
                }
            }

            // check current user permissions
            $post_type_check = get_post_type_object( $post->post_type );
        
            if ( ! current_user_can( $post_type_check->cap->edit_post, $post_id ) ) {
                return $post_id;
            }
        
            // Do not save the data if autosave
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
                return $post_id;
            }

            foreach($metabox['fields'] as $field){
                if( isset( $_POST[ $field['id'] ] ) ) {                
                    $field->set_value($_POST[ $field['id'] ]);            
                    update_post_meta( $post_id, $field['id'], $field->get_sanitized_value() );
                } else {
                    delete_post_meta( $post_id, $field['id']  );
                }   
            }    
        
            return $post_id;

        }, 10, 2 );                
    }
}