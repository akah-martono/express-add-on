<?php
namespace VXN\Express;

/**
 * This trait for class who implement ArrayAccess 
 * @package VXN\Express
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
trait Array_Access  {
    
    /**
     * @param mixed $offset 
     * @return bool 
     */
    public function offsetExists($offset): bool {
        return property_exists($this, $offset);
    }

    /**
     * @param mixed $offset 
     * @return mixed 
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * @param mixed $offset 
     * @param mixed $value 
     * @return void 
     */
    public function offsetSet($offset, $value): void {
        $this->$offset = $value;
    }

    /**
     * @param mixed $offset 
     * @return void 
     */
    public function offsetUnset($offset): void {
        unset($this->$offset);
    }

    /** @return array  */
    public function get_vars() :array {
        return $this->validate_array(get_object_vars($this));
    }

    /**
     * @param array $array 
     * @return array 
     */
    protected function validate_array($array) :array {
        foreach($array as $key => $value){
            if(is_array($value)){
                $value = $this->validate_array($value);
            }
            if(is_object($value)){
                $value = $this->object_to_array($value);
            }
            $array[$key] = $value;
        }    
        return $array;
    }

    /**
     * @param object $object 
     * @return array 
     */
    protected function object_to_array($object) :array {
        if(method_exists($object, 'get_vars')){
            return $object->get_vars();
        }
        return get_object_vars($object);
    }
}