<?php
namespace VXN\Express\Breakdance\Dynamic_Data;

use VXN\Express\WP\Menu_Page\Menu_Page;

/**
 * BDD_Menu_page_Field Interface
 * @package VXN\Express\Breakdance\Dynamic_Data
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
interface BDD_Menu_Page_Field {
    /**
     * @param Menu_Page $post_type
     * @return void 
     */
    public function __construct(Menu_Page $menu_page);
    
    /** @return bool  */
    public function has_items();
}