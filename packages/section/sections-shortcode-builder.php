<?php
namespace VXN\Express\Section;

use VXN\Express;
use VXN\Express\Fields\Email_Field;
use VXN\Express\Fields\Phone_Field;
use VXN\Express\Fields\Select_Field;
use VXN\Express\Helper\Util;

/** 
 * This class to create shortcode based on sections
 * @package VXN\Express\WP\Post_Type
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Sections_Shortcode_Builder {
    /**
     * @param array $sections 
     * @param string $tag 
     * @param callable $field_value_cb 
     * @return array 
     */

    public static function build($sections, $tag, callable $field_value_cb){
        if($tag){
            Express::add_shortcode(
                $tag, 
                function( $attr ) use($sections, $field_value_cb) {
                    $args = shortcode_atts( array(     
                        'field' => '',
                        'size' => ''
                    ), $attr );
        
                    $field_arg = strtolower((string) $args['field']);
                    $value = '';
                    foreach($sections as $section){
                        foreach($section['fields'] as $field){
                            $field_id = strtolower($field['id']);
                            if($field_arg == $field_id){
                                $value = call_user_func($field_value_cb, $field);
                                break 2;
                            }
                            if($field_arg == $field_id . '_gmap_url'){
                                $value = Util::google_map_url(call_user_func($field_value_cb, $field));
                                break 2;    
                            }    

                            if($field_arg == $field_id . '_url'){
                            
                                if(is_a($field, Phone_Field::class)){
                                    $value = 'tel:' . call_user_func($field_value_cb, $field); 
                                    break 2;    
                                }
                                if(is_a($field, Email_Field::class)){
                                    $value = 'mailto:' . call_user_func($field_value_cb, $field);    
                                    break 2;    
                                }
                            }

                            if($field_arg == $field_id . '_avatar'){
                                if(is_a($field, Email_Field::class) && $field['enable_avatar']){
                                    $avatar_args =  $args['size'] ? ['size' => $args['size']] : [];
                                    $value = get_avatar_url(call_user_func($field_value_cb, $field), $avatar_args);
                                    break 2;    
                                }                            
                            }

                            if($field_arg == $field_id . '_formatted'){
                                if(is_a($field, Phone_Field::class)){
                                    $format = Express::Options('vxn_express_setup.txt_phone_format', '');
                                    $value = Util::format_phone(call_user_func($field_value_cb, $field), $format);
                                    break 2;    
                                }                            
                            }
                            
                            if($field_arg == $field_id . '_label'){
                                $value = call_user_func($field_value_cb, $field);
                                if(is_a($field, Select_Field::class)){
                                    foreach($field['options'] as $key => $label){
                                        if($value == $key){
                                            $value = $label;
                                            break 2;    
                                        }
                                    }                                
                                }
                            }
                        }
                    }
                    return $value;
                }
            );
        }
    } 
}