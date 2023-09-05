<?php
namespace VXN\Express\Fields;

/**
 * Class URL field 
 * @package ...\Field\Types
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class URL_Field extends Field{
     /** @var bool $is_image default = false */
    protected $is_image = false;
    /**
     * @inheritDoc
     */     
    public function __construct($id) {
        $this->type = 'url';
        $this->class = 'regular-text';
        parent::__construct($id);
    }

    /**
     * @param bool $is_image 
     * @return $this 
     */
    public function set_is_image($is_image){
        $this->is_image = $is_image;
        return $this;
    }    
    /**
     * @inheritDoc
     */ 
    public function get_sanitized_value(){
        return sanitize_url($this->value);
    }   
    
}