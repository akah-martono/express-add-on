<?php
namespace VXN\Express\Fields;

/**
 * Class Checkbox field 
 * @package VXN\Express\Fields
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Checkbox extends Field{
    /**
     * @inheritDoc
     */
    public function __construct($id) {
        $this->type = 'checkbox';
        parent::__construct($id);
    }

    /**
     * @inheritDoc
     */
    public function get_sanitized_value(){
        return sanitize_text_field($this->value);
    }      

}