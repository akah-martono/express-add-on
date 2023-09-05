<?php
namespace VXN\Express\WP\Taxonomy;

use ArrayAccess;
use VXN\Express\Array_Access;

/** 
 * Class Taxonomy
 * @package VXN\Express\WP\Taxonomy
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Taxonomy implements ArrayAccess {
use Array_Access;
    
    /** @var string $taxonomy */
    protected $taxonomy;

    /** @var array $object_type default [] */
    protected $object_type = [];

    /** @var string $name */
    protected $name;

    /** @var string $description */
    protected $description;    

    /** @var string $slug */
    protected $slug;    

    /** @var bool $hierarchical default = true */
    protected $hierarchical = true;

    /** @var string $singular_name */
	protected $singular_name;

    /** @var string $menu_name */
    protected $menu_name;

    /** @return void  */
    public function __construct($taxonomy, $name, $singular_name, $menu_name) {
        $this->taxonomy = $taxonomy;
        $this->slug = str_replace('_', '-', $taxonomy);
        $this->name = $name;
        $this->singular_name = $singular_name;
        $this->menu_name = $menu_name;
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
     * @param string $description 
     * @return $this 
     */
    public function set_description($description){
        $this->description = $description;
        return $this;
    }
    /**
     * @param bool $hierarchical 
     * @return $this 
     */
    public function set_hierarchical(bool $hierarchical){
        $this->hierarchical = $hierarchical;
        return $this;
    }

    /**
     * @param string|array $object_type 
     * @return $this 
     */
    public function add_object_type($object_type){
        if(is_array($object_type)){
            $this->object_type = array_merge($this->object_type, $object_type);
        }else{
            $this->object_type[] = $object_type;
        }
        
        return $this;
    }
}