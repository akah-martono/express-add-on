<?php
namespace VXN\Express\Fields;

/**
 * class Select field 
 * @package VXN\Express\Fields
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Select_Field extends Field{

    /** @var array $options */
    protected $options;

    /**
     * @inheritDoc
     */ 
    public function __construct($id) {
        $this->type = 'select';
        parent::__construct($id);
    }    

    /**
     * @param array $options 
     * @return $this 
     */
    public function set_options(array $options){
        $this->options = $options;
        return $this;
    }

    /**
     * @inheritDoc
     */ 
    public function get_sanitized_value(){
        return sanitize_text_field($this->value);
    }        
}