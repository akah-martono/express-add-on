<?php
namespace VXN\Express\WP\Menu_Page;

use VXN\Express;
use VXN\Express\WP\Menu\Menu;
use VXN\Express\WP\Menu_Page\Menu_Page;

/**
 * This class to register Menu Page
 * @package VXN\Express\WP\Menu_Page
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Menu_Page_Registrar  {    
    /**
     * @param Menu_Page $menu_page 
     * @return void 
     */
    public static function register(Menu_Page $menu_page){
        $submenu_pages = Express::menu_pages($menu_page['slug']);

        $is_single_page = empty($submenu_pages);

        add_menu_page(
            $menu_page['page_title'], 
            $menu_page['menu_title'], 
            $menu_page['capability'], 
            $menu_page['slug'],
            $is_single_page ? function() use ($menu_page) {Menu_Page_Renderer::render_page_content($menu_page);} : '',
            $menu_page['icon_url'],
            $menu_page['position']
        );    

        if($is_single_page){
            Menu_Page_Renderer::render_settings_section($menu_page);
        }
        
    }
}