<?php
namespace VXN\Express\WP\Style;

/** 
 * This class to register Script
 * @package VXN\Express\WP\Script
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Style_Registrar {
    
    /**
     * @param Script $style 
     * @param bool $auto_enqueue 
     * @return void 
     */
    public static function register(Style $style, $auto_enqueue = true){
        wp_register_style( $style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media']);

        if(!$style['register_only'] & $auto_enqueue){
            self::enqueue( $style['handle'] );
        }                                
    }

    /**
     * @param Style $style 
     * @return void 
     */
    public static function enqueue(Style $style){
        if(!$style['register_only']){
            wp_enqueue_style( $style['handle'] );
        }
    }    
}
