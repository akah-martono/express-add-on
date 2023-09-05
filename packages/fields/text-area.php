<?php
namespace VXN\Express\Fields;

/**
 * Class Text Area field 
 * @package VXN\Express\Fields
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Text_Area extends Field{

    /** @var int $rows default = 7 */
    protected $rows = 7;

    /** @var int $cols default = 60 */
    protected $cols = 60;
    
    /**
     * @inheritDoc
     */    
    public function __construct($id) {
        $this->type = 'textarea';
        $this->class = 'regular-text';
        parent::__construct($id);
    }

    /**
     * @param int $rows 
     * @return $this 
     */
    public function set_rows($rows) {
        $this->rows = $rows;
        return $this;
    }

    /**
     * @param int $cols 
     * @return $this 
     */
    public function set_cols($cols) {
        $this->cols = $cols;
        return $this;
    }

    /**
     * @inheritDoc
     */ 
    public function get_sanitized_value(){
        return sanitize_textarea_field($this->value);
    }   
    
}