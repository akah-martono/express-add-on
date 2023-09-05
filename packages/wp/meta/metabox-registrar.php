<?php
namespace VXN\Express\WP\Meta;

use VXN\Express\WP\Meta\Metabox;
use VXN\Express\WP\Meta\Meta_Renderer;
use VXN\Express\WP\Meta\Save_Metabox;

/** 
 * This class to register Metabox
 * @package VXN\Express\WP\Meta
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Metabox_Registrar {
    /**
     * @param Metabox|array $metabox 
     * @return void 
     */
    public static function register($metabox){
        if(is_a($metabox, Metabox::class)){
            $render_callback = array(static::class, 'render_callback');
            add_meta_box(
                $metabox['id'], 
                $metabox['title'],
                function($post) use($metabox, $render_callback) {
                    $render_callback($post, $metabox);
                },
                $metabox['screen'],
                $metabox['context'],
                $metabox['priority'],
                $metabox['callback_args']
            );	 
        }else{
            if(is_array($metabox) && !empty($metabox)){
                foreach($metabox as $single_metabox){
                    self::register($single_metabox);
                }
            }
        }
    }

    /**
     * @param mixed $post 
     * @param mixed $metabox 
     * @return void 
     */
    private static function render_callback($post, $metabox){
        if(isset($metabox['nonce'])){
            $nonce_field = '_vxn_nonce_' . $metabox['id'];
            wp_nonce_field( $metabox['nonce'], $nonce_field );
        }
        
        echo '<table class="form-table"><tbody>';

        foreach($metabox['fields'] as $field){
            Meta_Renderer::render_field($post->ID, $field);		
        }

        echo '</tbody></table>';
    }
}
