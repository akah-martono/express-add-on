<?php
namespace VXN\Express\WP\Menu_Page;

use ArrayAccess;
use VXN\Express\Array_Access;
use VXN\Express\Section\Section;

/**
 * Class Menu Page 
 * @package VXN\Express\WP\Menu_Page
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Menu_Page implements ArrayAccess  {
use Array_Access;

    /** @var string $menu_title */
    protected $menu_title;    

    /** @var string $page_title */
    protected $page_title;

    /** @var string $tab_title */
    protected $tab_title;

    /** @var string $description */
    protected $description;

    /** @var array $arr_tabs default [] */
    protected $arr_tabs = [];

    /** @var string $option_group */
    protected $option_group;

    /** @var string $option_name */
    protected $option_name;

    /** @var string $slug */
    protected $slug;

    /** @var string $icon_url */
    protected $icon_url;    

    /** @var string $capability default 'manage_options' */
    protected $capability = 'manage_options';

    /** @var int|float $position */
    protected $position;    

    /** @var string $parent_slug */
    protected $parent_slug;

    /** @var array $sections default [] */
    protected $sections = [];

    /** @var string $shortcode_tag */
    protected $shortcode_tag;    

    /**
     * @param string $page_title will applied to menu_title and tab_title by default
     * @param string $slug 
     * @return void 
     */
    public function __construct($page_title, $slug)
    {
        $this->page_title = $page_title;
        $this->menu_title = $page_title;
        $this->tab_title = $page_title;
        $this->option_group = $slug . '_option_group';
        $this->option_name = $slug . '_options';        
        $this->slug = $slug;
        $this->shortcode_tag = $slug;
    }

    /**
     * @param string $menu_title 
     * @return $this 
     */
    public function set_menu_title($menu_title) {
        $this->menu_title = $menu_title;
        return $this;
    }  

    /**
     * @param string $menu_title 
     * @return $this 
     */
    public function set_icon_url($icon_url) {
        $this->icon_url = $icon_url;
        return $this;
    }     
    /**
     * @param string $tab_title 
     * @return $this 
     */
    public function set_tab_title($tab_title) {
        $this->tab_title = $tab_title;
        return $this;
    }      

    /**
     * @param string $description 
     * @return $this
     */
    public function set_description($description){
        $this->description = $description;
        return $this;
    }

    /**
     * @param int|float $position 
     * @return $this 
     */
    public function set_position($position) {
        $this->position = $position;
        return $this;
    }        

    /**
     * @param string $parent_slug 
     * @return $this 
     */
    public function set_parent_slug($parent_slug){
        $this->parent_slug = $parent_slug;
        return $this;
    }

    /**
     * @param string $shortcode_tag 
     * @return $this 
     */
    public function set_shortcode_tag($shortcode_tag){
        $this->shortcode_tag = $shortcode_tag;
        return $this;
    }    

    /**
     * @param Section $section 
     * @return $this 
     */
    public function add_section(Section $section){
        $section->add_hr_bottom(); // default pakai garis di bawah section
        $this->sections[$section['id']] = $section;
        return $this;
    } 
}