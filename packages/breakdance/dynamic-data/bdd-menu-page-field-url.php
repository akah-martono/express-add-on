<?php
namespace VXN\Express\Breakdance\Dynamic_Data;

use Breakdance\DynamicData\StringData;
use Breakdance\DynamicData\StringField;
use VXN\Express\WP\Menu_Page\Menu_Page;

/** 
 * Breakdance Dynamic Fields for Menu Page Fields (URL) 
 * @package VXN\Express\Breakdance\Dynamic_Data
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class BDD_Menu_Page_Field_URL extends StringField implements BDD_Menu_Page_Field
{
use BDD_Field_URL;
    /** @var Menu_Page $Menu_Page */
    protected $menu_page;
    
    /**
     * @inheritDoc
     */
    public function __construct(Menu_Page $menu_page)
    {
        $this->menu_page = $menu_page;
        $this->do_search_fields((array) $this->menu_page['sections']);
    }
    
    /**
     * @inheritDoc
     */
    public function label()
    {
        return $this->menu_page['page_title'] . ' Fields (URL)';
    }

    /**
     * @inheritDoc
     */
    public function category()
    {
        return __('Express Option Fields', VXN_EXPRESS_ADDON_DOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function slug()
    {
        return 'vxn_express_' . $this->menu_page['slug'] . '_menu_page_url_fields';
    }

    /**
     * @inheritDoc
     */        
    public function controls()
    {
        return $this->get_controls();
    }

    /**
     * array $attributes
     */    
    public function handler($attributes): StringData
    {        
        return $this->get_handler($attributes, $this->menu_page['shortcode_tag']);
    }
}
