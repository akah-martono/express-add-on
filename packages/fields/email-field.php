<?php
namespace VXN\Express\Fields;

/**
 * Class Email field 
 * @package VXN\Express\Fields
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Email_Field extends Field{
    /** @var bool $enable_avatar default = false */
    protected $enable_avatar = false;

    /**
     * @inheritDoc
     */      
    public function __construct($id) {
        $this->type = 'email';
        $this->class = 'regular-text';
        parent::__construct($id);
    }

    /**
     * @param bool $enable_avatar 
     * @return $this 
     */
    public function set_enable_avatar($enable_avatar){
        $this->enable_avatar = $enable_avatar;
        return $this;
    }    
    
    /**
     * @inheritDoc
     */  
    public function get_sanitized_value(){
        return sanitize_email($this->value);
    }      
}