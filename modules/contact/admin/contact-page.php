<?php
namespace VXN\Express\Contact\Admin;

use VXN\Express\WP\Menu_Page\Menu_Page;
use VXN\Express\Fields\Email_Field;
use VXN\Express\Fields\Phone_Field;
use VXN\Express\Fields\Text_Area;
use VXN\Express\Fields\Text_field;
use VXN\Express\Fields\URL_Field;
use VXN\Express\Section\Section;

/**
 * Contact Option Page
 * @package VXN\Express\Contact\Admin
 * @author Vaksin <dev@vaks.in>
 * @since 1.0.2 moved to module
 * @since 1.0.0
 */ 
class Contact_Page extends Menu_Page {
    
    public function __construct()
    {
        parent::__construct(__('Contact', VXN_EXPRESS_ADDON_DOMAIN), 'vxn_express_contact');
        parent::set_parent_slug('vxn_express_setup');
        foreach($this->sections() as $section){
            $this->add_section($section);
        }
    }

    /** @inheritDoc */
    private function sections(){
        return [
            self::contact_detail_section(),            
            self::sosmed_section()
        ];
    }
        
    /** @return Section  */
    private static function contact_detail_section(){
        return (new Section('contact'))
            ->set_title(__('Contact Detail', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_field(
                (new Phone_Field('txt_phone'))            
                ->set_label(__('Phone', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_description(__('Fill in with the complete telephone number e.g. +6281220003131', VXN_EXPRESS_ADDON_DOMAIN))
            )
            
            ->add_field(
                (new Email_Field('txt_email'))
                ->set_label(__('Email', VXN_EXPRESS_ADDON_DOMAIN))
            )

            ->add_field(
                (new Text_Area('txt_address'))
                ->set_label(__('Address', VXN_EXPRESS_ADDON_DOMAIN))
            )
            
            ->add_field(
                (new Text_field('txt_place'))
                ->set_label(__('Google Business Profile Name', VXN_EXPRESS_ADDON_DOMAIN))
            );

    }

    /** @return Section  */
    private static function sosmed_section(){
        return (new Section('sosmed'))
            ->set_title(__('Social Media', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_field(
                (new URL_Field('txt_facebook_url'))            
                ->set_label(__('Facebook URL', VXN_EXPRESS_ADDON_DOMAIN))
            )
            
            ->add_field(
                (new URL_Field('txt_instagram_url'))            
                ->set_label(__('Instagram URL', VXN_EXPRESS_ADDON_DOMAIN))
            )
            
            ->add_field(
                (new URL_Field('txt_twitter_url'))            
                ->set_label(__('Twitter URL', VXN_EXPRESS_ADDON_DOMAIN))
            )

            ->add_field(
                (new URL_Field('txt_youtube_url'))            
                ->set_label(__('Youtube URL', VXN_EXPRESS_ADDON_DOMAIN))
            )
            
            ->add_field(
                (new URL_Field('txt_linkedin_url'))
                ->set_label(__('LinkedIn URL', VXN_EXPRESS_ADDON_DOMAIN))
            );
    }
}