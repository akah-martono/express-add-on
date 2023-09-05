<?php
namespace VXN\Express\Fields;

use VXN\Express\Fields\Field;
use VXN\Express\Fields\Checkbox;
use VXN\Express\Fields\Date_Field;
use VXN\Express\Fields\Email_Field;
use VXN\Express\Fields\Number_Field;
use VXN\Express\Fields\Select_Field;
use VXN\Express\Fields\Text_Field;
use VXN\Express\Fields\URL_Field;

/** 
 * This class to render fields
 * @package VXN\Express\Fields;
 * @author Vaksin <dev@vaks.in>
 * @since 1.4
 */
class Field_Renderer {

	/**
	 * @param int $post_id 
	 * @param Field $field 
	 * @return void 
	 */
	public static function render(Field $field, $value) {
		switch(true){
			case is_a($field, Text_Field::class):
				self::text_field($field, $value);
				break;
			case is_a($field, Select_Field::class):
				self::select_field($field, $value);
				break;	
			case is_a($field, Date_Field::class):
				self::date_field($field, $value);
				break;
			case is_a($field, Checkbox::class):
				self::checkbox($field, $value);
				break;
			case is_a($field, Number_Field::class):
				self::number_field($field, $value);
				break;                    
			case is_a($field, Email_Field::class):
				self::email_field($field, $value);
				break;
            case is_a($field, URL_Field::class):
                self::url_field($field, $value);
                break;                  
			default:
				break;
		}			
	}	

    /** @return void  */
    public static function open_section(){
        echo '<table class="form-table"><tbody>';
    }

    /** @return void  */
    public static function close_section(){
        echo '</tbody></table>';
    }

    /**
     * @param int $post_id 
     * @param Field $field 
     * @return void 
     */
    private static function text_field(Field $field, $value) {        
		echo '
		<tr>
			<th><label for="' . esc_attr($field['id']) .'">' . esc_html($field['label'])  .'</label></th>
			<td>
				<input 
					type="text" 
					id="' . esc_attr($field['id']) .'" 
					name="' . esc_attr($field['id']) .'" 
					value="' . esc_attr( $value ) . '" ' .
                    ($field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '" ' : '' ) .
					($field['pattern'] ? 'pattern="' . esc_attr($field['pattern']) . '" ' : '' ) .
                    ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .
                    ($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                    ($field['style'] ? 'style="' . esc_attr($field['style']) . '" ' : '' ) .
                    ($field['required'] ? 'required ' : '' ) .
				'>' .
                ($field['text_right'] ? '<span>' .	esc_html($field['text_right']) . '</span> ' : '' ) .
                ($field['description'] ? '<p class="description">' .	esc_html($field['description']) . '</p> ' : '' ) .
			'</td>
		</tr>';		
	}

    /**
     * @param int $post_id 
     * @param Field $field 
     * @return void 
     */
    private static function url_field(Field $field, $value) {
		echo '
		<tr>
			<th><label for="' . esc_attr($field['id']) .'">' . esc_html($field['label'])  .'</label></th>
			<td>
				<input 
					type="url" 
					id="' . esc_attr($field['id']) .'" 
					name="' . esc_attr($field['id']) .'" 
					value="' . esc_attr( $value ) . '" ' .
                    ($field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '" ' : '' ) .
					($field['pattern'] ? 'pattern="' . esc_attr($field['pattern']) . '" ' : '' ) .
                    ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .
                    ($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                    ($field['style'] ? 'style="' . esc_attr($field['style']) . '" ' : '' ) .
                    ($field['required'] ? 'required ' : '' ) .
				'>' .
                ($field['text_right'] ? '<span>' .	esc_html($field['text_right']) . '</span> ' : '' ) .
                ($field['description'] ? '<p class="description">' .	esc_html($field['description']) . '</p> ' : '' ) .
			'</td>
		</tr>';		
	}

    /**
     * @param int $post_id 
     * @param Field $field 
     * @return void 
     */
    private static function number_field(Field $field, $value) {
		echo '
		<tr>
			<th><label for="' . esc_attr($field['id']) .'">' . esc_html($field['label'])  .'</label></th>
			<td>
				<input 
					type="number" ' . 
					($field['min'] ? 'min="' . $field['min'] . '" ' : '' ) .
					($field['max'] ? 'max="' . $field['max'] . '" ' : '' ) .
					'id="' . esc_attr($field['id']) .'" 
					name="' . esc_attr($field['id']) .'" 
					value="' . esc_attr( $value ) . '" ' .
                    ($field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '" ' : '' ) .
					($field['pattern'] ? 'pattern="' . esc_attr($field['pattern']) . '" ' : '' ) .
                    ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .
                    ($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                    ($field['style'] ? 'style="' . esc_attr($field['style']) . '" ' : '' ) .
                    ($field['required'] ? 'required ' : '' ) .
				'>' .
                ($field['text_right'] ? '<span>' .	esc_html($field['text_right']) . '</span> ' : '' ) .
                ($field['description'] ? '<p class="description">' .	esc_html($field['description']) . '</p> ' : '' ) .
			'</td>
		</tr>';		
	}	

    /**
     * @param int $post_id 
     * @param Field $field 
     * @return void 
     */
    private static function email_field(Field $field, $value) {
		echo '
		<tr>
			<th><label for="' . esc_attr($field['id']) .'">' . esc_html($field['label'])  .'</label></th>
			<td>
				<input 
					type="email" 
					id="' . esc_attr($field['id']) .'" 
					name="' . esc_attr($field['id']) .'" 
					value="' . esc_attr( $value ) . '" ' .
                    ($field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '" ' : '' ) .
					($field['pattern'] ? 'pattern="' . esc_attr($field['pattern']) . '" ' : '' ) .
                    ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .
                    ($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                    ($field['style'] ? 'style="' . esc_attr($field['style']) . '" ' : '' ) .
                    ($field['required'] ? 'required ' : '' ) .
				'>' .
                ($field['text_right'] ? '<span>' .	esc_html($field['text_right']) . '</span> ' : '' ) .
                ($field['description'] ? '<p class="description">' .	esc_html($field['description']) . '</p> ' : '' ) .
			'</td>
		</tr>';		
	}	

	/**
	 * @param int $post_id 
	 * @param Field $field 
	 * @return void 
	 */
	private static function select_field(Field $field, $value){
		echo '
		<tr>
			<th><label for="' . esc_attr($field['id']) .'">' . esc_html($field['label'])  .'</label></th>
			<td>
				<select 
					id="' . esc_attr($field['id']) .'" 
					name="' . esc_attr($field['id']) .'">
					<option value="">' . __('Select...', VXN_EXPRESS_ADDON_DOMAIN) . '</option>';

					foreach($field['options'] as $key => $label){
						echo '<option value="' . esc_attr($key) .'"' . selected( $key, $value, false ) . '>' . esc_html($label) .'</option>';
					}
		
		echo 
                    ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .
                    ($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                    ($field['style'] ? 'style="' . esc_attr($field['style']) . '" ' : '' ) .
				'</select>' .
                ($field['text_right'] ? '<span>' .	esc_html($field['text_right']) . '</span> ' : '' ) .
                ($field['description'] ? '<p class="description">' .	esc_html($field['description']) . '</p> ' : '' ) .
			'</td>
		</tr>';		
	}

    /**
     * @param int $post_id 
     * @param Field $field 
     * @return void 
     */
    private static function date_field(Field $field, $value){
		echo '
		<tr>
			<th><label for="' . esc_attr($field['id']) .'">' . esc_html($field['label'])  .'</label></th>
			<td>
				<input 
					type="date" ' .
                    ($field['min'] ? 'min="' . $field['min'] . '" ' : '' ) .
					($field['max'] ? 'max="' . $field['max'] . '" ' : '' ) .
					'id="' . esc_attr($field['id']) .'" 
					name="' . esc_attr($field['id']) .'" 
					value="' . esc_attr( $value ) . '" ' .
                    ($field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '" ' : '' ) .
					($field['pattern'] ? 'pattern="' . esc_attr($field['pattern']) . '" ' : '' ) .
                    ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .
                    ($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                    ($field['style'] ? 'style="' . esc_attr($field['style']) . '" ' : '' ) .
                    ($field['required'] ? 'required ' : '' ) .
				'>' .
                ($field['text_right'] ? '<span>' .	esc_html($field['text_right']) . '</span> ' : '' ) .
                ($field['description'] ? '<p class="description">' .	esc_html($field['description']) . '</p> ' : '' ) .
			'</td>
		</tr>';		
	}

	/**
	 * @param int $post_id 
	 * @param Field $field 
	 * @return void 
	 */
	private static function checkbox(Field $field, $value){
		echo '
		<tr>
			<th><label for="' . esc_attr($field['id']) .'">' . esc_html($field['label'])  .'</label></th>
			<td>
				<input 
					type="checkbox" 
					id="' . esc_attr($field['id']) .'" 
					name="' . esc_attr($field['id']) .'" 
					value="1" ' . 
					($value ? 'checked="checked" ' : '') . 		
                    ($field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '" ' : '' ) .
                    ($field['title'] ? 'title="' . esc_attr($field['title']) . '" ' : '' ) .
                    ($field['class'] ? 'class="' . esc_attr($field['class']) . '" ' : '' ) .
                    ($field['style'] ? 'style="' . esc_attr($field['style']) . '" ' : '' ) .
                    ($field['required'] ? 'required ' : '' ) .
				'/>' .
                ($field['text_right'] ? '<label for="' . esc_attr($field['id']) .'">' .	esc_html($field['text_right']) . '</label> ' : '' ) .
                ($field['description'] ? '<p class="description">' .	esc_html($field['description']) . '</p> ' : '' ) .
			'</td>
		</tr>';
	}
}