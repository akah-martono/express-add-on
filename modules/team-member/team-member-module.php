<?php
namespace VXN\Express\Team_Member;

use VXN\Express;
use VXN\Express\Fields\Email_Field;
use VXN\Express\Fields\Text_Field;
use VXN\Express\Fields\URL_Field;
use VXN\Express\WP\Post_Type\Post_Type;
use VXN\Express\Section\Section;
use VXN\Express\Module_Interface;
use VXN\Express\Section\Section_Factory;
use VXN\Express\WP\Menu_Page\Menu_Page;
use VXN\Express\WP\Post_Type\Post_Type_Factory;
use VXN\Express\WP\Taxonomy\Taxonomy_Factory;

/** @package VXN\Express\Testi */
class Team_Member_Module implements Module_Interface
{
    private $page;
    private $name;
    private $singular;
    private $menu;
    private $slug;  

    /** @inheritDoc */
    public static function name()
    {
        return 'Team Members';
    }

    /** @inheritDoc */
    public static function slug()
    {
        return 'vxn_express_team_member';
    }

    /** @inheritDoc */
    public function run(){
        define('VXN_EXPRESS_TEAM_MEMBER_MODULE_FILE', __FILE__);

        $this->create_post_type();
    }

    /** @return void  */
    private function create_post_type(){
        $this->page = 'vxn_express_team';
        $this->name = __('Team Members', VXN_EXPRESS_ADDON_DOMAIN);
        $this->singular = __('Team Member', VXN_EXPRESS_ADDON_DOMAIN);
        $this->menu = __('Team Members', VXN_EXPRESS_ADDON_DOMAIN);
        $this->slug = 'team';  

        Express::add_menu_page($this->post_type_setup_page());
        Express::add_post_type($this->team_post_type());
    }

        /** @return Menu_Page */
    private function post_type_setup_page() {
        $page = wp_cache_get('vxn_team_post_type_setup_page', 'vxn_express');
        if( false === $page ) {
            $page = new Menu_Page(__('Team Member CPT', VXN_EXPRESS_ADDON_DOMAIN), $this->page);
            $page->set_parent_slug('vxn_express_setup');
    
            foreach($this->page_setup_sections() as $section){
                $page->add_section($section);
            }

            wp_cache_set('vxn_team_post_type_setup_page', $page, 'vxn_express');
        }
        return $page;
    }

    /** @return Section[] */
    private function page_setup_sections(){
        return [
            Section_Factory::create_post_type_setup_section(
                name_label: $this->name(),
                singular_label: $this->singular,
                menu_label: $this->menu,
                slug_label: $this->slug
            ),

            Section_Factory::create_taxonomy_setup_section(
                taxonomy_name: 'taxonomy_first',
                taxonomy_title: 'First Taxonomy Setup',
                name_label: 'Team Departments',                
                singular_label: 'Team Department',
                menu_label: 'Team Departments',
                slug_label: 'team-department'
            ),

            Section_Factory::create_taxonomy_setup_section(
                taxonomy_name: 'taxonomy_second',
                taxonomy_title: 'Second Taxonomy Setup',
                name_label: 'Team Roles',
                singular_label: 'Team Role',
                menu_label: 'Team Roles',
                slug_label: 'team-role'
            )
            
        ];    
    }

    /** @return Post_Type  */
    private function team_post_type(){
        $post_type = wp_cache_get('vxn_team_post_type', 'vxn_express');
        if( false === $post_type ){    
            $post_type = Post_Type_Factory::create_from_setup_page(
                page: $this->page,
                post_type: 'vxn_team_member',
                default_name: $this->name,
                default_singular: $this->singular,
                default_menu: $this->menu,
                default_slug: $this->slug
            )
            ->set_menu_icon('dashicons-groups')
            ->add_supports(['thumbnail']);

            foreach($this->post_type_sections() as $section){
                $post_type->add_section($section);
            }

            $taxonomy_first = Taxonomy_Factory::create_from_setup_page($this->page, str_replace('-','_', $this->slug), 'taxonomy_first');
            $taxonomy_second = Taxonomy_Factory::create_from_setup_page($this->page, str_replace('-','_', $this->slug), 'taxonomy_second');  
            
            if(false !== $taxonomy_first) {
                $post_type->add_taxonomy($taxonomy_first);
            }

            if(false !== $taxonomy_second) {
                $post_type->add_taxonomy($taxonomy_second);
            }

            wp_cache_set('vxn_team_post_type', $post_type, 'vxn_express');
        }
       return $post_type;
    }

    /** @return Section[] */
    private function post_type_sections(){
        return [        
            /** Member Information */
            (new Section('member_info'))
            ->set_title(__('Member Information', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_field(
                (new Email_Field('vxn_email'))
                ->set_label(__('Email', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_enable_avatar(true)
            )
            ->add_field(
                (new Text_Field('vxn_member_info'))
                ->set_label(__('Member Info', VXN_EXPRESS_ADDON_DOMAIN))
            ),
        
            /** Social Information */
            (new Section('social_info'))
            ->set_title(__('Social Information', VXN_EXPRESS_ADDON_DOMAIN))
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
            ),
        ];
    }
}
