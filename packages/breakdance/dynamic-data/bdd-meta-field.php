<?php
namespace VXN\Express\Breakdance\Dynamic_Data;

use VXN\Express\WP\Post_Type\Post_Type;

/**
 * BDD_Meta_Field Interface
 * @package VXN\Express\Breakdance\Dynamic_Data
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
interface BDD_Meta_Field {
    /**
     * @param Post_Type $post_type
     * @return void 
     */
    public function __construct(Post_Type $post_type);
    
    /** @return bool  */
    public function has_items();
}