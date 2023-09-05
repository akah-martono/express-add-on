<?php
namespace VXN\Express\WP\Menu_Page;

use VXN\Express;
use VXN\Express\Breakdance;
use VXN\Express\Breakdance\Dynamic_Data\BDD_Menu_Page_Field_Image;
use VXN\Express\Breakdance\Dynamic_Data\BDD_Menu_Page_Field_String;
use VXN\Express\Breakdance\Dynamic_Data\BDD_Menu_Page_Field_URL;

/** 
 * This class to build menu pages and the necessary
 * @package VXN\Express\WP\Menu_Page
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Menu_Pages_Builder {
    /**
     * @param array $post_types 
     * @return void 
     */
    public static function build()
    {
        if( is_admin() ){
            Express::sort_page_menu();
            self::create_menu_pages();
        }else{
            self::create_shortcodes();
        }
        self::generate_breakdance_things();
    }

	private static function create_menu_pages(){
		add_action('admin_menu', function() {
			$parent_slug_group = [];
			foreach(Express::menu_pages() as $menu_page){
				if($menu_page['parent_slug']){
					$parent_slug_group[] = $menu_page['parent_slug'];
				}else{
					Menu_Page_Registrar::register($menu_page);
				}			
			}

			if(!empty($parent_slug_group)){
				$parent_slug_group = array_unique($parent_slug_group);
				foreach($parent_slug_group as $parent_slug){
					Submenu_Page_Registrar::register($parent_slug);
				}
			}
		});
	}

    private static function create_shortcodes() {
        foreach(Express::menu_pages() as $menu_page){
            Menu_Page_Shortcode_Builder::build($menu_page);
        }
    }

    /** @return void  */
    private static function generate_breakdance_things(){
        foreach(Express::menu_pages() as $menu_page){
            if($menu_page['shortcode_tag']){
                self::add_dynamic_field(new BDD_Menu_Page_Field_String($menu_page));
                self::add_dynamic_field(new BDD_Menu_Page_Field_URL($menu_page));    
                self::add_dynamic_field(new BDD_Menu_Page_Field_Image($menu_page));    
            }
        }
    }    

    private static function add_dynamic_field($dynamic_field){
        if($dynamic_field->has_items()){
            Breakdance::add_dynamic_field($dynamic_field);    
        }
    }
}