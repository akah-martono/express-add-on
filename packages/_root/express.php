<?php
namespace VXN;

use ArrayAccess;
use VXN\Express\Array_Access;
use VXN\Express\WP\Meta\Metabox;
use VXN\Express\Handler;
use VXN\Express\Module_Interface;
use VXN\Express\WP\Menu_Page\Menu_Page;
use VXN\Express\WP\Post_Type\Post_Type;
use VXN\Express\WP\Script\Script;
use VXN\Express\WP\Taxonomy\Taxonomy;

/** 
 * The Express
 * @package VXN 
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Express implements ArrayAccess{
use Array_Access;
    private static $modules = [];
    private static $menu_pages = [];
    private static $module_sections = [];
    private static $options = [];
    private static $shortcodes = [];
    private static $scripts = [];
    private static $post_types = [];
    private static $metaboxes = [];
    private static $taxonomies = [];
    private static $nav_tabs = [];

    /** @return void  */
    public static function run(){        
        Handler::run();
    }

    /**
     * @param Module_Interface $module 
     * @return void 
     */
    public static function add_module(Module_Interface $module){
        static::$modules[call_user_func(array($module, 'slug'))] = $module;
    }

    /** @return Module_Interface[]  */
    public static function modules () {
        return static::$modules;
    }

    /**
     * @param mixed $name 
     * @param mixed $slug 
     * @return void 
     */
    public static function add_module_section($name, $slug){
        static::$module_sections[$slug] = $name;
    }

    /** @return array  */
    public static function module_sections () {
        return static::$module_sections;
    }

    /**
     * @param Metabox $metabox 
     * @return void 
     */
    public static function add_metabox(Metabox $metabox){
        static::$metaboxes[$metabox['id']] = $metabox;
    }

    /** @return Metabox[]  */
    public static function metaboxes () {
        return static::$metaboxes;
    }

    /**
     * @param Taxonomy $metabox 
     * @return void 
     */
    public static function add_taxonomy(Taxonomy $taxonomy){
        static::$taxonomies[$taxonomy['taxonomy']] = $taxonomy;
    }

    /** @return Taxonomy[]  */
    public static function taxonomies () {
        return static::$taxonomies;
    }    

    /**
     * @param Menu_Page $page 
     * @return void 
     */
    public static function add_menu_page(Menu_Page $page){
        static::$menu_pages[$page['slug']] = $page;
    }

    /** @return Menu_Page[]  */
    public static function menu_pages ($parent_slug = null) {
        if($parent_slug){
            $submenu_pages = [];
            foreach(static::$menu_pages as $menu_page){
                if($menu_page['parent_slug'] == $parent_slug){
                    $submenu_pages[$menu_page['slug']] = $menu_page;
                }
            }
            return $submenu_pages;
        }

        return static::$menu_pages;
    }

    /**
     * @param string $parent_slug 
     * @return array 
     */
    public static function nav_tabs($parent_slug){
        if(!array_key_exists($parent_slug, self::$nav_tabs)){
            $nav_tabs =[];
            $parent_tab = [];
            foreach(Express::menu_pages() as $menu_page){
                if($menu_page['tab_title']){
                    if($menu_page['slug'] == $parent_slug){
                        $parent_tab = [
                            'slug'=> $menu_page['slug'], 
                            'title'=>$menu_page['tab_title']
                        ];    
                    }elseif($menu_page['parent_slug'] == $parent_slug){
                        $nav_tabs[$menu_page['slug']] = [
                            'slug'=> $menu_page['slug'], 
                            'title'=>$menu_page['tab_title']
                        ];    
                    }
                }
            }

            if(!empty($parent_tab)){
                array_unshift($nav_tabs, $parent_tab);
            }

            self::$nav_tabs[$parent_slug] = $nav_tabs;
        }
        return self::$nav_tabs[$parent_slug];
    }
        
    /**
     * @param Post_Type $page 
     * @return void 
     */
    public static function add_post_type(Post_Type $post_type){
        static::$post_types[$post_type['post_type']] = $post_type;
    }

    /** @return Post_Type[]  */
    public static function post_types () {
        return static::$post_types;
    }

    /**
     * @param string $tag 
     * @param callable $callback 
     * @return void 
     */
    public static function add_shortcode($tag, callable $callback){
        static::$shortcodes[$tag] = $callback;
    }

    /** @return array  */
    public static function shortcodes () {
        return static::$shortcodes;
    }

    /**
     * @param Script $script 
     * @return void 
     */
    public static function add_script(Script $script){
        static::$scripts[$script['handle']] = $script;
    }

    /** @return Script[]  */
    public static function scripts () {
        return static::$scripts;
    }

    /**
     * @param mixed $slug default null
     * @return mixed 
     */
    public static function Options($slug = null, $default = false){

        if(null === $slug){
            if(empty(self::$options)){
                foreach(self::menu_pages() as $page){
                    self::$options[$page['slug']] = get_option($page['option_name']) ;
                }                                
            }
            return self::$options ? : $default;
        }

        if(array_key_exists($slug, self::$options)){
            return self::$options[$slug] ? : $default;
        }

        $param = explode('.', $slug);

        if(count($param) > 1) {
            $arr_option = static::Options($param[0], $default);

            if(is_array($arr_option) && array_key_exists($param[1], $arr_option)){
                return $arr_option[$param[1]] ? : $default;
            }else{
                return $default;
            }
        }

        foreach(self::menu_pages() as $page){
            if($slug == $page['slug']){
                self::$options[$slug] = get_option($page['option_name']) ;
                return self::$options[$slug] ? : $default;
            }
        }  

        return $default;
    }

    public static function sort_page_menu(){
        usort(self::$menu_pages, function ($a, $b)
        {
            if ($a['position'] == $b['position']) {
                return 0;
            }
            return ($a['position'] < $b['position']) ? -1 : 1;
        });             
    }
}