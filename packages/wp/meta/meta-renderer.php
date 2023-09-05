<?php
namespace VXN\Express\WP\Meta;

use VXN\Express\Fields\Field;
use VXN\Express\Fields\Checkbox;
use VXN\Express\Fields\Date_Field;
use VXN\Express\Fields\Email_Field;
use VXN\Express\Fields\Field_Renderer;
use VXN\Express\Fields\Number_Field;
use VXN\Express\Fields\Select_Field;
use VXN\Express\Fields\Text_Field;
use VXN\Express\Fields\URL_Field;

/** 
 * This class to render fields in metabox
 * @package VXN\Express\WP\Meta
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Meta_Renderer {

	/**
	 * @param int $post_id 
	 * @param Field $field 
	 * @return void 
	 */
	public static function render_field($post_id, Field $field) {
        $value = get_post_meta( $post_id, $field['id'], true ) ? : $field['default'];
        Field_Renderer::render($field, $value);        	
	}	

    /** @return void  */
    public static function open_section(){
        echo '<table class="form-table"><tbody>';
    }

    /** @return void  */
    public static function close_section(){
        echo '</tbody></table>';
    }
}