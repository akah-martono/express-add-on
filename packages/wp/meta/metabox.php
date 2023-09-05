<?php
namespace VXN\Express\WP\Meta;

use ArrayAccess;
use VXN\Express\Section\Section;
use VXN\Express\WP\Meta\Meta_Renderer;
use VXN\Express\Array_Access;

/** 
 * Class Metabox
 * @package VXN\Express\WP\Meta
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Metabox implements ArrayAccess{
use Array_Access;

    /** @var string $id */
    protected $id;

    /** @var string $id */
    protected $title;

    /** @var array $screen */
    protected $screen = [];

    /** @var string $context default 'advanced' */
    protected $context = 'advanced';

    /** @var string $priority default 'default' */
    protected $priority = 'default';

    /** @var string $callback_args default 'default' */
    protected $callback_args = null;

    
    /** @var string $nonce */
    protected $nonce;

    /** @var array $fields default [] */
    protected $fields = [];

    /**
     * @param Section $section 
     * @return void 
     */
    public function __construct(Section $section)
    {
        $this->id = $section['id'];
        $this->title = $section['title'];
        $this->fields = $section['fields']; 
	}

    /**
     * @param string $nonce 
     * @return $this 
     */
    public function set_nonce( $nonce ) {
        $this->nonce = $nonce;
        return $this;
    }

    /**
     * @param string $screen 
     * @return $this 
     */
    public function add_screen( $screen ) {
        if(is_array($screen)){
            $this->screen = array_merge($this->object_type, $screen);
        }else{
            $this->screen[] = $screen;
        }
        return $this;
    }

    /**
     * @param mixed $context 
     * @return $this 
     */
    public function set_context( $context ) {
        $this->context = $context;
        return $this;
    }

    /**
     * @param mixed $priority 
     * @return $this 
     */
    public function set_priority( $priority ) {
        $this->priority = $priority;
        return $this;
    }

    /**
     * @param callback $callback_args 
     * @return $this 
     */
    public function set_callback_args( $callback_args ) {
        $this->callback_args = $callback_args;
        return $this;
    } 
}