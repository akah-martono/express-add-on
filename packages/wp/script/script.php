<?php
namespace VXN\Express\WP\Script;

use ArrayAccess;
use VXN\Express\Array_Access;

/** 
 * Class Script
 * @package VXN\Express\WP\Script
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Script implements ArrayAccess {
use Array_Access;

    /** @var string $handle */
    protected $handle;

    /** @var string $src */
    protected $src;

    /** @var string[] $deps default = []*/
    protected $deps = [];

    /** @var string|bool|null $ver default = false*/
    protected $ver = false;

    /** @var bool $in_footer default = false*/
    protected $in_footer = false;

    /** @var bool $register_only default = false */
    protected $register_only = false;

    /** @var array|callback $data default = [] */
    protected $data = [];

    /**
     * @param string $handle 
     * @param string $src 
     * @param bool $register_only 
     * @return void 
     */
    public function __construct($handle, $src, $register_only =false)
    {
        $this->handle = $handle;
        $this->src = $src;
        $this->register_only = $register_only;
    }

    /**
     * @param string|string[] $deps 
     * @return $this 
     */
    public function add_deps($deps) {
        if(is_array($deps)){
            $this->deps = array_merge($this->deps, $deps);
        }else{
            $this->deps[] = $deps;
        }
        
        return $this;
    }

    /**
     * @param string $ver 
     * @return $this 
     */
    public function set_ver($ver){
        $this->ver = $ver;
        return $this;
    }

    /**
     * @param bool $in_footer 
     * @return $this 
     */
    public function set_in_footer($in_footer){
        $this->in_footer = $in_footer;
        return $this;
    }

    /**
     * @param bool $register_only 
     * @return $this 
     */
    public function set_register_only($register_only){
        $this->register_only = $register_only;
        return $this;
    }

    /**
     * @param array|callback $data 
     * @return $this 
     */
    public function set_data($data){
        $this->data = $data;
        return $this;
    }
}