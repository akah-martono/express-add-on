<?php
namespace VXN\Express\Breakdance\Dynamic_Data;

use Breakdance\DynamicData\StringData;
use Breakdance\DynamicData\StringField;
use VXN\Express\WP\Post_Type\Post_Type;

/** 
 * Breakdance Dynamic Fields for Meta Fields (URL) 
 * @package VXN\Express\Breakdance\Dynamic_Data
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class BDD_Meta_Field_URL extends StringField implements BDD_Meta_Field
{
use BDD_Field_URL;

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
        return $this->post_type['name'] . ' Fields (URL)';
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
        return 'vxn_express_' . $this->post_type['post_type'] . '_meta_url_fields';
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
        return $this->get_handler($attributes, $this->post_type['shortcode_tag']);
    }
}
