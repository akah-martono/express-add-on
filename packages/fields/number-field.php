<?php
namespace VXN\Express\Fields;

/**
 * Class Number field 
 * @package VXN\Express\Fields
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Number_Field extends Field{
    /** @var int|float $min */
    protected $min;

    /** @var int|float $max */
    protected $max;

    /** @var string $number_type */
    protected $number_type = 'int';

    /**
     * @inheritDoc
     */ 
    public function __construct($id) {
        $this->type = 'number';
        $this->class = 'regular-text';
        parent::__construct($id);
    }

    /**
     * @param int|float $min 
     * @return $this 
     */
    public function set_min($min){
        $this->min = $min;
        return $this;
    }

    /**
     * @param int|float $max 
     * @return $this 
     */
    public function set_max($max){
        $this->max = $max;
        return $this;
    }    

    /**
     * @param string $number_type 
     * @return $this 
     */
    public function set_number_type($number_type){
        $this->number_type = $number_type;
        return $this;
    }

    /**
     * @inheritDoc
     */  
    public function get_sanitized_value(){
        $value = $this->value; 

        if($this->number_type  == 'int'){
            $value = intval($value);
        }else{
            $value = floatval($value);
        }

        if($this->min){
            if( $value < floatval($this->min) ){
                $value = floatval($this->min);
            }
        }

        if($this->max){
            if( $value > floatval($this->max) ){
                $value = floatval($this->max);
            }
        }        
        
        return $value;
    }   
}