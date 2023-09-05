<?php
namespace VXN\Express\WP\Menu_Page;

use VXN\Express\Fields\Field;
use VXN\Express\Fields\Checkbox;
use VXN\Express\Fields\Date_Field;
use VXN\Express\Fields\Email_Field;
use VXN\Express\Fields\Number_Field;
use VXN\Express\Fields\Phone_Field;
use VXN\Express\Fields\Select_Field;
use VXN\Express\Fields\Text_Area;
use VXN\Express\Fields\Text_Field;
use VXN\Express\Fields\URL_Field;

/**
 * This class to render options field in Menu Page 
 * @package VXN\Express\WP\Menu_Page
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Menu_Page_Field_Renderer {
	/**
	 * @param Field $field 
	 * @return void 
	 */
	public static function render_field(Field $field) {
		switch(true){
			case is_a($field, Text_Field::class):
				self::text_field($field);
				break;
			case is_a($field, Text_Area::class):
				self::text_area($field);
				break;				
			case is_a($field, Select_Field::class):
				self::select_field($field);
				break;	
			case is_a($field, Date_Field::class):
				self::date_field($field);
				break;
			case is_a($field, Checkbox::class):
				self::checkbox($field);
				break;
			case is_a($field, Number_Field::class):
				self::number_field($field);
				break;                    
			case is_a($field, Email_Field::class):
				self::email_field($field);
				break;
			case is_a($field, Phone_Field::class):
				self::phone_field($field);
				break;
            case is_a($field, URL_Field::class):
                self::URL_field($field);
                break;                                   	
			default:
				break;
		}			
	}	

    /**
     * @param Field $field 
     * @return void 
     */
    private static function text_area(Field $field) {
        echo '
            <textarea 
                rows="' . esc_attr($field['rows']) .'" 
                cols="' . esc_attr($field['cols']) .'" 
                name="' . esc_attr($field['name']) .'" 
                id="' . esc_attr($field['id']) .'" ' .
                ($field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '" ' : '' ) .
                ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .
                ($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                ($field['disabled'] ? 'disabled ' : '') . 
            '/>' . esc_attr($field['value'] ? : $field['default'] ) .'</textarea>'
        ;

		if($field['description']){
            echo '<p class="description">' . esc_textarea($field['description']) . '</p>';
        }			
	}

    /**
     * @param Field $field 
     * @return void 
     */
    private static function number_field(Field $field) {
		echo '
			<input 
				type="number" ' . 
				($field['min'] ? 'min="' . $field['min'] . '" ' : '' ) .
				($field['max'] ? 'max="' . $field['max'] . '" ' : '' ) .
				'id="' . esc_attr($field['id']) .'" 
				name="' . esc_attr($field['name']) .'" 
				value="' . esc_attr( $field['value']  ? : $field['default'] ) . '" ' .
                ($field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '" ' : '' ) .
                ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .
				($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                ($field['disabled'] ? 'disabled ' : '') . 
			'>'
		;

		if($field['description']){
            echo '<p class="description">' . esc_textarea($field['description']) . '</p>';
        }		
	}	

    /**
     * @param Field $field 
     * @return void 
     */
    private static function email_field(Field $field) {
		echo '
			<input 
				type="email" 
				id="' . esc_attr($field['id']) .'" 
				name="' . esc_attr($field['name']) .'" 
				value="' . esc_attr( $field['value'] ? : $field['default'] ) . '" ' .
                ($field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '" ' : '' ) .
                ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .
				($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                ($field['disabled'] ? 'disabled ' : '') . 
			'>'
		;	

		if($field['description']){
            echo '<p class="description">' . esc_textarea($field['description']) . '</p>';
        }	
	}	

    /**
     * @param Field $field 
     * @return void 
     */
    private static function text_field(Field $field) {
		echo '
			<input 
				type="text" 
				id="' . esc_attr($field['id']) .'" 
				name="' . esc_attr($field['name']) .'" 
				value="' . esc_attr( $field['value'] ? : $field['default'] ) . '" ' .
                ($field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '" ' : '' ) .
                ($field['pattern'] ? 'pattern="' . esc_attr($field['pattern']) . '" ' : '' ) .
                ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .
				($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                ($field['disabled'] ? 'disabled ' : '') . 
			'>'
		;	

		if($field['description']){
            echo '<p class="description">' . esc_textarea($field['description']) . '</p>';
        }	
	}

    private static function URL_field(Field $field) {
		echo '
			<input 
				type="url" 
				id="' . esc_attr($field['id']) .'" 
				name="' . esc_attr($field['name']) .'" 
				value="' . esc_attr( $field['value'] ? : $field['default'] ) . '" ' .
                ($field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '" ' : '' ) .                
                ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .
				($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                ($field['disabled'] ? 'disabled ' : '') . 
			'>'
		;	

		if($field['description']){
            echo '<p class="description">' . esc_textarea($field['description']) . '</p>';
        }	
	}	    

    /**
     * @param Field $field 
     * @return void 
     */
    private static function phone_field(Field $field) {
		echo '
			<input 
				type="tel" 
				id="' . esc_attr($field['id']) .'" 
				name="' . esc_attr($field['name']) .'" 
				value="' . esc_attr( $field['value'] ? : $field['default']  ) . '" ' .
                ($field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '" ' : '' ) .
                ($field['pattern'] ? 'pattern="' . esc_attr($field['pattern']) . '" ' : '' ) .
                ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .
				($field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '" ' : '' ) .
				($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                ($field['disabled'] ? 'disabled ' : '') . 
			'>'
		;	

		if($field['description']){
            echo '<p class="description">' . esc_textarea($field['description']) . '</p>';
        }	
	}		

	/**
	 * @param Field $field 
	 * @return void 
	 */
	private static function select_field(Field $field){
		echo '
				<select 
					id="' . esc_attr($field['id']) .'" 
					name="' . esc_attr($field['name']) .
                    ($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                    ($field['disabled'] ? 'disabled ' : '') . 
                    '">
					<option value="">' . __('Select...', VXN_EXPRESS_ADDON_DOMAIN) . '</option>'
		;

					foreach($field['options'] as $key => $label){
						echo '<option value="' . esc_attr($key) .'"' . selected( $key, $field['value'] ? : $field['default'] , false ) . '>' . esc_html($label) .'</option>';
					}
		
		echo '
				</select>'
		;		
		
		if($field['description']){
            echo '<p class="description">' . esc_textarea($field['description']) . '</p>';
        }	
	}

    /**
     * @param Field $field 
     * @return void 
     */
    private static function date_field(Field $field){

		echo '
			<input 
				type="date"  ' . 
				($field['min'] ? 'min="' . $field['min'] . '" ' : '' ) .
				($field['max'] ? 'max="' . $field['max'] . '" ' : '' ) .
				'id="' . esc_attr($field['id']) .'" 
				name="' . esc_attr($field['name']) .'" 
				value="' . esc_attr( $field['value'] ? : $field['default'] ) . '" ' .
                ($field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '" ' : '' ) .
                ($field['pattern'] ? 'pattern="' . esc_attr($field['pattern']) . '" ' : '' ) .
                ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .                
				($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                ($field['disabled'] ? 'disabled ' : '') . 
			'>'
		;		

		if($field['description']){
            echo '<p class="description">' . esc_textarea($field['description']) . '</p>';
        }		
	}

	/**
	 * @param Field $field 
	 * @return void 
	 */
	private static function checkbox(Field $field){
		echo '
			<input 
				type="checkbox" 
				id="' . esc_attr($field['id']) .'" 
				name="' . esc_attr($field['name']) .'" 
				value="1" ' . 
                ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .
				(($field['value'] ? : $field['default']) ? 'checked="checked" ' : '') . 
                ($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                ($field['disabled'] ? 'disabled ' : '') . 
			'/>
			<label for="' . esc_attr($field['id']) .'">' .	$field['text_right'] . '</label>'
		;

        if($field['description']){
            echo '<p class="description">' . esc_textarea($field['description']) . '</p>';
        }		
	}
}