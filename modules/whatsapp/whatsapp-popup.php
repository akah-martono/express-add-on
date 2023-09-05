<?php
namespace VXN\Express\Whatsapp;

use VXN\Express\Breakdance;
use VXN\Express;

use VXN\Express\WP\Script\Script;

/** @package VXN\Express\Whatsapp */
class Whatsapp_Popup  {

    public static function do_setup(){
		$saved_wa_param = get_option('vxn_wa_form_data');
        if(is_admin()){
            self::add_action_after_save($saved_wa_param);
        }elseif($saved_wa_param && Express::Options('vxn_express_whatsapp.chk_show_form')){
            $script = (New Script('vxn_wa_form', VXN_EXPRESS_WHATSAPP_URL . '/assets/front/js/wa-form.js'))
                        ->set_data(array(self::class, 'script_data'))
                        ->set_ver('1.0.7');         
            Express::add_script($script);
        }
    }

    private static function add_action_after_save($saved_wa_param){
        add_action('breakdance_after_save_document', function($post_id) use($saved_wa_param) {
            if(get_post_type($post_id) == 'breakdance_popup'){
                $opt_name = 'vxn_wa_form_data';
                $wa_param = self::get_param($post_id);
                                
                if($wa_param){
                    $wa_data = [
                        'post_id' => $post_id,
                        'param' => $wa_param
                    ];    
                }

                if(isset($wa_data)) {
                    if(!$saved_wa_param){
                        add_option($opt_name, $wa_data);
                    }else{
                        update_option($opt_name, $wa_data);
                    }
                }                

                if($saved_wa_param && (!isset($wa_data)) && $saved_wa_param['post_id'] == $post_id){
                    delete_option($opt_name);
                    return;
                }
            }                     
        });
    }

    public static function get_param($post_id){        
        $param = self::get_element($post_id);  
        
        $get_field = function($value){
            $patern = "/\{([^\}]*)\}/";
            preg_match_all($patern, $value, $matches);
            $field = $matches[1];
            if($field){
                return $field[0];
            }
            return [];
        };

        foreach($param as $key => $value){
            $param[$key] = $get_field($value);
        }

        $param = array_filter($param);
        if(!array_key_exists('text', $param)){
            return [];
        }

        return $param;        
    }

    public static function get_element($post_id){
        $type = 'actions';
        $element = 'vxn_send_whatsapp';

        $bd_data = Breakdance::get_data($post_id);
        
        if(!isset($bd_data['root']['children'][0]['children'])){
            return [];
        }

        $parent = self::get_parent_element($element, $bd_data['root']['children'][0]['children']);

        if(!$parent){
            return [];
        }

        foreach($parent[$type] as $child){
            if($child == $element){
                return $parent[$element];
            }
        }

        return [];
    }

    public static function get_parent_element($key, $array){
        if(array_key_exists ($key, $array)){
            return $array;
        }else{
            foreach($array as $value){
                if(is_array($value)){
                    $val = self::get_parent_element($key, $value);
                    if(!is_null($val)){
                        return $val;
                    }
                }
            }
        }
        return null;
    }    

	/** @return array  */
	public static function script_data(){
        $option = get_option('vxn_wa_form_data');
        if(!$option){
            return [];
        }
		return [
            'url' => do_shortcode( '[vxn_wa_url]' ),
            'text_default' => do_shortcode('[vxn_wa_text]'),
            'form' => [
                'show' => Express::Options('vxn_express_whatsapp.chk_show_form') ? true : false ,
                'post_id' => $option['post_id'],
                'wa_name_id' => $option['param']['name'] ?? '',
                'wa_phone_id' => $option['param']['phone'] ?? '',
                'wa_text_id' => $option['param']['text'] ?? '',
                'wa_email_id' => $option['param']['email'] ?? ''
            ]        
        ];
	}
}