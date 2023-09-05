<?php
namespace VXN\Express\Breakdance\Dynamic_Data;

use Breakdance\DynamicData\StringData;
use VXN\Express\Fields\Email_Field;
use VXN\Express\Fields\Phone_Field;
use VXN\Express\Fields\URL_Field;

use function Breakdance\Elements\control;

/** 
 * Trait to create Breakdance Dynamic Fields (URL) 
 * @package VXN\Express\Breakdance\Dynamic_Data
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
trait BDD_Field_URL
{
    /** @var array $list_items default = []*/
    protected $list_items = [];

    /**
     * @inheritDoc
     */    
    public function returnTypes()
    {
        return ['url'];
    }    
    
    protected function do_search_fields($sections){
        foreach($sections as $section){            
            foreach($section['fields'] as $field){
                if(is_a($field, URL_Field::class)){
                    $this->list_items[] = ['text' => $field['label'], 'value' => $field['id']];    
                }
                if(is_a($field, Phone_Field::class) || is_a($field, Email_Field::class)){                    
                    $this->list_items[] = ['text' => $field['label'] . ' URL', 'value' => $field['id'] . '_url'];  
                }
                /** khusus untuk Google Business Profile Name di contact */
                if($field['id'] == 'txt_place'){
                    $this->list_items[] = ['text' => $field['label'] . ' URL', 'value' => $field['id'] . '_gmap_url'];  
                }
            }            
        }
    }

    public function has_items(){
        return (!empty($this->list_items));
    }        

    protected function get_controls(){
        return [
            control('field_id', 'Field', [
            'type' => 'dropdown',
            'layout' => 'vertical',
            'items' => $this->list_items])
        ];
    }

    protected function get_handler($attributes, $shortcode_tag) :StringData{

        $field_id = $attributes['field_id'] ?? '';

        $shortcode = sprintf('[%s field="%s"]', $shortcode_tag, $field_id);
        $value = do_shortcode($shortcode);
        
        return StringData::fromString($value);        
    }
}
