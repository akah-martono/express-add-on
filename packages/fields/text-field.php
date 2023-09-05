<?php
namespace VXN\Express\Fields;

/**
 * class Text field 
 * @package VXN\Express\Fields
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Text_Field extends Field{
    
    /**
     * @inheritDoc
     */ 
    public function __construct($id) {
        $this->type = 'text';
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