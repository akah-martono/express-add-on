<?php
namespace VXN\Express;

use Breakdance\DynamicData\Field as Dynamic_Field;
use Breakdance\Forms\Actions\Action;

use function Breakdance\Data\get_meta;
use function Breakdance\Data\get_tree;

/** 
 * Breakdance API
 * @package VXN\Express 
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Breakdance{
    protected static $dynamic_fields = [];
    protected static $form_actions = [];

    /**
     * @param int $post_id 
     * @return false|Breakdance\Data\Tree 
     */
    public static function get_data($post_id) {
        return get_tree($post_id);
    }

    /**
     * @param int $post_id 
     * @param string $field_name 
     * @param bool $array_key 
     * @return mixed 
     */
    public static function get_meta($post_id, $field_name, $array_key = false) {
        return get_meta($post_id, $field_name, $array_key);
    }

    /**
     * @param Dynamic_Field $field 
     * @return void 
     */
    public static function add_dynamic_field(Dynamic_Field $field){
        static::$dynamic_fields[call_user_func(array($field, 'slug'))] = $field;
    }

    /** @return array  */
    public static function dynamic_fields() {
        return self::$dynamic_fields;
    }

    /**
     * @param Action $action 
     * @return void 
     */
    public static function add_form_action(Action $action){
        static::$form_actions[call_user_func(array($action, 'slug'))] = $action;
    }

    /** @return array  */
    public static function form_actions() {
        return self::$form_actions;
    }

    public static function is_active(){
        // include_once ABSPATH . 'wp-admin/includes/plugin.php';
        // return (defined('__BREAKDANCE_PLUGIN_FILE__') &&  is_plugin_active(plugin_basename(__BREAKDANCE_PLUGIN_FILE__)));
        return (
            defined('__BREAKDANCE_PLUGIN_FILE__') &&  
            in_array(plugin_basename(__BREAKDANCE_PLUGIN_FILE__), apply_filters('active_plugins', get_option('active_plugins'))));
    }
}