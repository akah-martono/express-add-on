<?php
namespace VXN\Express\WP\Menu_Page;

use VXN\Express;

/**
 * This class to register Submenu Page
 * @package VXN\Express\WP\Menu_Page
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Submenu_Page_Registrar  {

    /**
     * @param Menu_Page $menu_page 
     * @return void 
     */
    public static function register($parent_slug){
        // $menu_page =
        $submenu_pages = [];

        foreach(Express::menu_pages() as $menu_page){
            if($menu_page['parent_slug'] == $parent_slug){
                $submenu_pages[] = $menu_page;
            }
            if($menu_page['slug'] == $parent_slug){
                $parent_page = $menu_page;
            }
            
        }

        $parent_menu_title = $parent_page['menu_title'];
        if(isset($parent_page)){
            $parent_page['menu_title'] = $parent_page['page_title'];
            $parent_page['parent_slug'] = $parent_page['slug'];
            array_unshift($submenu_pages, $parent_page); //harus di depan supaya nimpa root
        }

        foreach($submenu_pages as $menu_page){
            
            if( $menu_page['tab_title'] && ($menu_page['page_title'] == $menu_page['menu_title'])){
                $menu_page['page_title'] = $parent_menu_title; //supaya seragam
            }            
            add_submenu_page(
                $menu_page['parent_slug'],
                $menu_page['page_title'], 
                $menu_page['menu_title'], 
                $menu_page['capability'], 
                $menu_page['slug'],
                function() use ($menu_page) {Menu_Page_Renderer::render_page_content($menu_page);},
                // $menu_page['position']
            );
            Menu_Page_Renderer::render_settings_section($menu_page);
        }
        
    }
}