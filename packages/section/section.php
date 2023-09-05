<?php
namespace VXN\Express\Section;

use ArrayAccess;
use VXN\Express\Fields\Field;
use VXN\Express\Array_Access;

/**
 * Class Section 
 * @package VXN\Express
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Section implements ArrayAccess  {
use Array_Access;
    /** @var string $id The ID of the section */    
    protected $id;

    /** @var string $title The title of the section */
    protected $title;

    /** @var string $info The information of the section, will shown below of the title */
    protected $info;

   /** @var string $note The information of the section, will shown below of the table */
   protected $note;

    /** @var array $fields Fields in the section */
    protected $fields = [];

    /** @var bool $hr_top The horizontal line on top of section */
    protected $hr_top = false;

    /** @var bool $hr_bottom The horizontal line on bottom of section */
    protected $hr_bottom = false;

    /** @var string $div_top_id */
    protected $div_top_id;

    /** @var string $div_bottom_id */
    protected $div_bottom_id;
    
    /**
     * @param string $id 
     * @return void 
     */
    public function __construct($id) {
        $this->id = $id;
    }

    /**
     * @param string $title 
     * @return $this 
     */
    public function set_title($title){
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $info 
     * @return $this 
     */
    public function set_info($info){
        $this->info = $info;
        return $this;
    }

    /**
     * @param string $info 
     * @return $this 
     */
    public function set_note($note){
        $this->note = $note;
        return $this;
    } 

    /**
     * @param Field $field 
     * @return $this 
     */
    public function add_field( Field $field ) {
        $this->fields[$field['id']] = $field;
        return $this;
    }

    /** @return $this  */
    public function add_hr_top() {
        $this->hr_top = true;
        return $this;
    }
    
    /** @return $this  */
    public function add_hr_bottom() {
        $this->hr_bottom = true;
        return $this;
    }
    
    /**
     * @param string $div_id 
     * @return $this 
     */
    public function add_div_top($div_id) {
        $this->div_top_id = $div_id;
        return $this;
    }

    /**
     * @param string $div_id 
     * @return $this 
     */
    public function add_div_bottom($div_id) {
        $this->div_bottom_id = $div_id;
        return $this;
    }

    
}