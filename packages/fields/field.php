<?php
namespace VXN\Express\Fields;

use ArrayAccess;
use VXN\Express\Array_Access;

/**
 * Abstract Class Fields 
 * @package VXN\Express\Fields
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
abstract class Field implements ArrayAccess {
use Array_Access;
    
    /** @var string $id */
    protected $id;
    
    /** @var string $type */
    protected $type;

    /** @var string $name */
    protected $name;

    /** @var string $title */
    protected $label;

    /** @var string $title */
    protected $title;

    /** @var string $pattern */
    protected $pattern;

    /** @var string $value */
    protected $value;

    /** @var string $default */
    protected $default;    

    /** @var string $placeholder */
    protected $placeholder;

    /** @var string $description */
    protected $description;

    /** @var string $text_right */
    protected $text_right;

    /** @var bool $disabled default = false */
    protected $disabled = false;
    
    /** @var bool $required default = false */
    protected $required = false;

    /** @var string $class */
    protected $class;

    /** @var array $attributes */
    protected $attributes = [];

    /** @var array $attriargsbutes */
    protected $args = [];    

    /** @var string $style */
    protected $style;

    /** @var callable $validation_cb */
    protected $validation_cb;

    /** @var array $bdd_types default = [] */
    protected $bdd_types = [];

    /** @return mixed  */
    abstract public function get_sanitized_value();
    
    /**
     * @param string $id 
     * @return void 
     */
    public function __construct($id) {        
        $this->id = $id;        
    }

    /**
     * @param string $name 
     * @return $this 
     */
    public function set_name($name) {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @param string $title 
     * @return $this 
     */
    public function set_title($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $label 
     * @return $this 
     */
    public function set_label($label) {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string $pattern 
     * @return $this 
     */
    public function set_pattern($pattern) {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @param string $value 
     * @return $this 
     */
    public function set_value($value) {
        $this->value = $value;
        return $this;
    }

    /**
     * @param string $value 
     * @return $this 
     */
    public function set_default_value($value) {
        $this->default = $value;
        return $this;
    }    

    /**
     * @param string $value 
     * @return $this 
     */
    public function set_placeholder($placeholder) {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @param string $description 
     * @return $this 
     */
    public function set_description($description) {
        $this->description = $description;
        return $this;
    } 

    /**
     * @param string $text_right 
     * @return $this 
     */
    public function set_text_right($text_right){
        $this->text_right = $text_right;
        return $this;
    }
        
    /**
     * @param string $class 
     * @return $this 
     */
    public function set_class($class) {
        $this->class = $class;
        return $this;
    }

    /**
     * @param string $style 
     * @return $this 
     */
    public function set_style($style) {
        $this->style = $style;
        return $this;
    }    
    /**
     * @param bool $disabled 
     * @return $this 
     */
    public function set_disabled(bool $disabled) {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * @param bool $required 
     * @return $this 
     */
    public function set_required(bool $required) {
        $this->required = $required;
        return $this;
    }    

    /**
     * @param array|string $bdd_types 
     * available types: 
     * 'google map', 
     * @return $this 
     */
    public function add_bdd_types($bdd_types){
        if(!is_array($bdd_types)){
            $bdd_types = [$bdd_types];
        }
        $this->bdd_types = array_unique(array_merge($this->bdd_types, $bdd_types)) ;
        return $this;
    }

    /**
     * @param array|string $attributes 
     * @return $this 
     */
    public function add_attributes($attributes){
        if(!is_array($attributes)){
            $attributes = [$attributes];
        }
        $this->attributes = array_unique(array_merge($this->attributes, $attributes)) ;
        return $this;
    }
    
    /**
     * @param array $args 
     * @return $this 
     */
    public function set_args(array $args){
        $this->args = $args;
        return $this;
    }
    
    /**
     * @param callable $validation_cb 
     * @return $this 
     */
    public function set_validation_callbak(callable $validation_cb){
        $this->validation_cb = $validation_cb;
        return $this;
    }

    public function validate(){
        if(is_callable($this->validation_cb)){
            return call_user_func($this->validation_cb, $this->value);
        }
        return true;
    }
}