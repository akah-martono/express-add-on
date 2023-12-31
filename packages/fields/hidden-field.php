<?php
namespace VXN\Express\Fields;

/**
 * Class Text Area field 
 * @package VXN\Express\Fields
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Hidden_Field extends Field{
    
    /**
     * @inheritDoc
     */    
    public function __construct($id) {
        $this->type = 'hidden';
        parent::__construct($id);
    }
    
    public function get_sanitized_value(){
        return sanitize_text_field($this->value);
    }   
    
}