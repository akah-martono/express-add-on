<?php
namespace VXN\Express\WP\Style;

use ArrayAccess;
use VXN\Express\Array_Access;

/** 
 * Class Script
 * @package VXN\Express\WP\Script
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Style implements ArrayAccess {
use Array_Access;

    /** @var string $handle */
    protected $handle;

    /** @var string $src */
    protected $src;

    /** @var string[] $deps default = []*/
    protected $deps = [];

    /** @var string|bool|null $ver default = false*/
    protected $ver = false;

    /** @var string $media default = 'all'*/
    protected $media = 'all';

    /** @var bool $register_only default = false */
    protected $register_only = false;


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
     * @param string $media 
     * @return $this 
     */
    public function set_media($media){
        $this->media = $media;
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

}