<?php
namespace VXN\Express\WP\Script;

/** 
 * This class to register Script
 * @package VXN\Express\WP\Script
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Script_Registrar {
    
    /**
     * @param Script $script 
     * @param bool $auto_enqueue 
     * @return void 
     */
    public static function register(Script $script, $auto_enqueue = true){
        wp_register_script( $script['handle'], $script['src'], $script['deps'], $script['ver'], $script['in_footer']);

        if(!$script['register_only'] & $auto_enqueue){
            self::enqueue( $script['handle'] );
        }

        $data = is_callable($script['data']) ? call_user_func($script['data']) : $script['data'];

        if(!empty($data)){            
            wp_localize_script($script['handle'], '_vxn_data_' . str_replace('-', '_', $script['handle']), $data);
        }                                       
    }

    /**
     * @param Script $script 
     * @return void 
     */
    public static function enqueue(Script $script){
        if(!$script['register_only']){
            wp_enqueue_script( $script['handle'] );
        }
    }    
}
