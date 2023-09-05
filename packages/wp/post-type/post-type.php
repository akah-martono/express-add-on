<?php
namespace VXN\Express\WP\Post_Type;

use ArrayAccess;
use VXN\Express\Array_Access;
use VXN\Express\Section\Section;
use VXN\Express\WP\Taxonomy\Taxonomy;

/** 
 * Class Post Type
 * @package VXN\Express\WP\Post_Type
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Post_Type implements ArrayAccess{
use Array_Access;

    /** @var string $post_type */
    protected $post_type;

    /** @var string $slug */
    protected $slug;    

    /** @var string $name */
    protected $name;

    /** @var string $singular */
    protected $singular;

    /** @var string $menu_name */
    protected $menu_name;

    /** @var string $description */
    protected $description;

    /** @var bool $public default true*/
    protected $public = true;

    /** @var bool $show_in_rest default false*/
    protected $show_in_rest = false;

    /** @var string|null $menu_icon default null */
    protected $menu_icon = null;

    /** @var int|float $menu_position default null */
    protected $menu_position = null;    

    /** @var array $supports default ['title', 'editor']*/
    protected $supports = ['title', 'editor'];

    /** @var bool $has_archive default true*/
    protected $has_archive = true;

    /** @var array $sections default []*/
    protected $sections = [];

    /** @var array $taxonomies default []*/
    protected $taxonomies = [];

    /** @var array $remove_features default []*/
    protected $remove_features = [];

    /** @var string $shortcode_tag */
    protected $shortcode_tag;

    /** @var string $enter_title_here */
    protected $enter_title_here;

    /** @var string $nonce default "vxn_{$post_type}_nonce" */
    protected $nonce;

    /** @var bool $enable_submit_via_form default false */
    protected $enable_submit_via_form = false;

    public function __construct($post_type, $name, $singular, $menu_name) {
        $this->post_type = $post_type;
        $this->name = $name;
        $this->singular = $singular;         
        $this->menu_name = $menu_name;
        $this->nonce = "vxn_{$post_type}_nonce";
        $this->shortcode_tag = $post_type;
        $this->slug = str_replace('_', '-', $post_type);
    }

    /**
     * @param Section $section 
     * @return $this 
     */
    public function add_section(Section $section){
        $this->sections[$section['id']] = $section;
        return $this;
    }

    /**
     * @param string $slug 
     * @return $this 
     */
    public function set_slug($slug){
        $this->slug = $slug;
        return $this;
    }

    /**
     * @param string $enter_title_here 
     * @return $this 
     */
    public function set_enter_title_here($enter_title_here){
        $this->enter_title_here = $enter_title_here;
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
     * @param Taxonomy $taxonomy 
     * @return $this 
     */
    public function add_taxonomy(Taxonomy $taxonomy){
        $taxonomy->add_object_type($this->post_type);
        $this->taxonomies[$taxonomy['taxonomy']] = $taxonomy;
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
     * @param bool $public 
     * @return $this 
     */
    public function set_public(bool $public){
        $this->public = $public;
        return $this;
    }
    
    /**
     * @param bool $show_in_rest 
     * @return $this 
     */
    public function set_show_in_rest(bool $show_in_rest){
        $this->show_in_rest = $show_in_rest;
        return $this;
    }
    /**
     * @param string $menu_icon 
     * @return $this 
     */
    public function set_menu_icon($menu_icon){
        $this->menu_icon = $menu_icon;
        return $this;
    }

    /**
     * @param int|float $menu_position 
     * @return $this 
     */
    public function set_menu_position($menu_position) {
        $this->menu_position = $menu_position;
        return $this;
    }       
        
    /**
     * @param bool $has_archive 
     * @return $this 
     */
    public function set_has_archive(bool $has_archive){
        $this->has_archive = $has_archive; // nanti bisa array
        return $this;
    }

    /**
     * @param string|array $supports 
     * @return $this 
     */
    public function add_supports($supports){
        if(is_array($supports)){
            $this->supports = array_merge($this->supports, $supports);
        }else{
            $this->supports[] = $supports;
        }
        return $this;
    }

    /**
     * @param string|array $supports 
     * @return $this 
     */
    public function remove_feature($feature){
        if(is_array($feature)){
            $this->remove_features = array_merge($this->remove_features, $feature);
        }else{
            $this->remove_features[] = $feature;
        }
        return $this;
    }

    /**
     * @param bool $has_archive 
     * @return $this 
     */
    public function set_enable_submit_via_form(bool $enable_submit_via_form){
        $this->enable_submit_via_form = $enable_submit_via_form; // nanti bisa array
        return $this;
    }    
}