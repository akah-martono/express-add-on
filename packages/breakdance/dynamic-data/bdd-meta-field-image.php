<?php
namespace VXN\Express\Breakdance\Dynamic_Data;

use Breakdance\DynamicData\ImageData;
use Breakdance\DynamicData\ImageField;
use VXN\Express\WP\Post_Type\Post_Type;

/** 
 * Breakdance Dynamic Fields for Meta Fields (Image) 
 * @package VXN\Express\Breakdance\Dynamic_Data
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class BDD_Meta_Field_Image extends ImageField implements BDD_Meta_Field
{
use BDD_Field_Image;
    /** @var Post_Type $post_type */
    protected $post_type;
    
    /**
     * @inheritDoc
     */
    public function __construct(Post_Type $post_type)
    {
        $this->post_type = $post_type;
        $this->do_search_fields((array) $this->post_type['sections']);
    }

    /**
     * @inheritDoc
     */
    public function label()
    {
        return $this->post_type['name'] . ' Fields (Image)';
    }

    /**
     * @inheritDoc
     */
    public function category()
    {
        return __('Express Meta Fields', VXN_EXPRESS_ADDON_DOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function slug()
    {
        return 'vxn_express_' . $this->post_type['post_type'] . '_meta_image_fields';
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
        return $this->get_handler($attributes, $this->post_type['shortcode_tag']);
    }
}
