<?php
namespace VXN\Express\Testi;

use VXN\Express;
use VXN\Express\Fields\Email_Field;
use VXN\Express\Fields\Number_Field;
use VXN\Express\Fields\Text_Field;
use VXN\Express\WP\Post_Type\Post_Type;
use VXN\Express\Section\Section;
use VXN\Express\WP\Taxonomy\Taxonomy;
use VXN\Express\Module_Interface;

/** @package VXN\Express\Testi */
class Testi_Module implements Module_Interface
{
  /** @inheritDoc */
    public static function name()
    {
        return 'Testimonials';
    }

    /** @inheritDoc */
    public static function slug()
    {
        return 'vxn_express_testi';
    }

    /** @inheritDoc */
    public function run(){
        define('VXN_EXPRESS_TESTI_MODULE_FILE', __FILE__);
        // define('VXN_EXPRESS_TESTI_MODULE_PATH', plugin_dir_path(__FILE__));
        // define('VXN_EXPRESS_TESTI_MODULE_URL', plugin_dir_url(__FILE__));

        add_action('vxn_express_loaded', function () {
            Express::add_post_type($this->team_member_post_type());
        });
    }

    /** @return Post_Type  */
    private function team_member_post_type(){
        $post_type = (new Post_Type(
            'vxn_testimonial',
            __('Testimonials', VXN_EXPRESS_ADDON_DOMAIN),
            __('Testimonial', VXN_EXPRESS_ADDON_DOMAIN),
            __('Testimonials', VXN_EXPRESS_ADDON_DOMAIN)
        ))
        ->set_menu_icon('dashicons-testimonial')
        ->set_slug('testimonial')
        ->set_enter_title_here(__('Enter Client Name Here', VXN_EXPRESS_ADDON_DOMAIN))
        ->add_supports('thumbnail')
        ->set_enable_submit_via_form(true)
        ->add_section(
            (new Section('testimonial_info'))
            ->set_title(__('Testimonial Information', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_field(
                (new Email_Field('vxn_email'))
                ->set_label(__('Client Email', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_enable_avatar(true)
            )
            ->add_field(
                (new Text_Field('vxn_client_info'))
                ->set_label(__('Client Info', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Number_Field('vxn_rating'))
                ->set_label(__('Rating', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_class('small-text')
            )
        )
        ->add_taxonomy(
            (new Taxonomy(
                'testi_category',
                __('Testimonial Categories', VXN_EXPRESS_ADDON_DOMAIN),
                __('Testimonial Category', VXN_EXPRESS_ADDON_DOMAIN),
                __('Testimonial Categories', VXN_EXPRESS_ADDON_DOMAIN)
            ))
            ->set_slug('testimonial-category')
        );

        return $post_type;
    }
}
