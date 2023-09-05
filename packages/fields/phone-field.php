<?php
namespace VXN\Express\Fields;

/**
 * Class Phone field 
 * @package VXN\Express\Fields
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Phone_Field extends Field{
    
    /**
     * @inheritDoc
     */ 
    public function __construct($id) {
        $this->type = 'tel';
        $this->class = 'regular-text';
        parent::__construct($id);
    }
    
    /**
     * @inheritDoc
     */ 
    public function get_sanitized_value(){
        return sanitize_text_field($this->value);
    }   
    
}