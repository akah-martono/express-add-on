<?php
namespace VXN\Express\Fields;

/**
 * Class Date field 
 * @package VXN\Express\Fields
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Date_Field extends Field{

    /** @var string $min */
    protected $min;
    
    /** @var string $min */
    protected $max;
    
    /**
     * @inheritDoc
     */    
    public function __construct($id) {
        $this->type = 'date';        
        parent::__construct($id);
    }

    /**
     * @param string $min 
     * @return $this 
     */
    public function set_min($min){
        $this->min = $min;
        return $this;
    }

    /**
     * @param string $max 
     * @return $this 
     */
    public function set_max($max){
        $this->max = $max;
        return $this;
    }    
    
    /**
     * @inheritDoc
     */
    public function get_sanitized_value(){
        return sanitize_text_field($this->value);
    }    
}