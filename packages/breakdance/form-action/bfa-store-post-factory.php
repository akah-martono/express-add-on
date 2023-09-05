<?php
namespace VXN\Express\Breakdance\Form_Action;

use Breakdance\Forms\Actions\Action;
use VXN\Express\WP\Post_Type\Post_Type;

/**
 * This class to create Breakdance Form Action store post
 * @package VXN\Express\Breakdance\Form_Action
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
Class BFA_Store_Post_Factory{

    /**
     * @param Post_Type $post_type 
     * @return Action 
     */
    public static function create(Post_Type $post_type): Action {

        $store_action = 'VXN\Express\Store_Action\\' . str_replace('-', '_', $post_type['post_type']) ; 

        if(!class_exists($store_action)){
            self::generate_class($store_action);
        }

        return new $store_action($post_type);
    }

    /**
     * @param string $class 
     * @return void 
     */
    private static function generate_class($class){

        $pos = strripos($class, '\\');
        $namespace = substr($class, 0, $pos);
        $class_name = substr($class, $pos+1);
    
        $script = sprintf(
            'namespace %s;
    
            use Breakdance\Forms\Actions\Action;
            use VXN\Express\Breakdance\Form_Action\BFA_Store_Post;
            
            class %s extends Action {
            use BFA_Store_Post;
    
                private static $post_type;
                
                public function __construct($post_type) {
                    static::$post_type = $post_type;
                }
                
                protected static function post_type() {
                    return self::$post_type;
                }
                
            }', 
            $namespace,
            $class_name
        );
    
        eval($script);   
    }
}