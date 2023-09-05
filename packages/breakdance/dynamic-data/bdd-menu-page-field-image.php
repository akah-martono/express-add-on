<?php
namespace VXN\Express\Breakdance\Dynamic_Data;

use Breakdance\DynamicData\ImageData;
use Breakdance\DynamicData\ImageField;
use VXN\Express\WP\Menu_Page\Menu_Page;

/** 
 * Breakdance Dynamic Fields for Menu Page Fields (Image) 
 * @package VXN\Express\Breakdance\Dynamic_Data
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class BDD_Menu_Page_Field_Image extends ImageField implements BDD_Menu_Page_Field
{
use BDD_Field_Image;
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
        return $this->menu_page['page_title'] . ' Fields (Image)';
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
        return 'vxn_express_' . $this->menu_page['slug'] . '_menu_page_image_fields';
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
    public function handler($attributes): ImageData
    {        
        return $this->get_handler($attributes, $this->menu_page['shortcode_tag']);
    }
}
