<?php
namespace VXN\Express\WP\Menu_Page;

use VXN\Express;
use VXN\Express\Section\Sections_Shortcode_Builder;

/** 
 * This class to create menu page shortcode
 * @package VXN\Express\WP\Menu_Page
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Menu_Page_Shortcode_Builder {

    /**
     * @param Menu_Page $menu_page 
     * @return array 
     */
    public static function build(Menu_Page $menu_page){
        return Sections_Shortcode_Builder::build(
            $menu_page['sections'], 
            $menu_page['shortcode_tag'], 
            function($field) use($menu_page) {
                $field->set_value(Express::Options($menu_page['slug'] . '.' . $field['id'], ''));
                return $field->get_sanitized_value();    
            }
        );
    }
}