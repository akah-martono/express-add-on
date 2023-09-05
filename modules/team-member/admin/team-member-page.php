<?php
namespace VXN\Express\Team_Member\Admin;

use VXN\Express\WP\Menu_Page\Menu_Page;
use VXN\Express\Fields\Text_field;
use VXN\Express\Section\Section;

/**
 * Team Member Option Page
 * @package VXN\Express\Team_Member\Admin
 * @author Vaksin <dev@vaks.in>
 * @since 1.2.5
 */ 
class Team_Member_Page extends Menu_Page {
    
    public function __construct()
    {
        parent::__construct(__('Team Member', VXN_EXPRESS_ADDON_DOMAIN), 'vxn_express_team');
        parent::set_parent_slug('vxn_express_setup');
        foreach($this->sections() as $section){
            $this->add_section($section);
        }
    }

    /** @inheritDoc */
    private function sections(){
        return [
            self::post_type_setup_section(),     
            self::first_taxonomy_setup_section(),
            self::second_taxonomy_setup_section() 
        ];
    }
        
    /** @return Section  */
    private static function post_type_setup_section(){
        return (new Section('post_type_setup'))
            ->set_title(__('Post Type Setup', VXN_EXPRESS_ADDON_DOMAIN))            
            ->add_field(
                (new Text_field('txt_cpt_name'))
                ->set_label(__('Name', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder(__('Team Members', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_default_value(__('Team Members', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_field('txt_cpt_singular'))
                ->set_label(__('Singular', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder(__('Team Member', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_default_value(__('Team Member', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_field('txt_cpt_menu'))
                ->set_label(__('Menu', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder(__('Team Members', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_default_value(__('Team Members', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_field('txt_cpt_slug'))
                ->set_label(__('Slug', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder('team')
                ->set_default_value('team')
            );
    }   

    /** @return Section  */
    private static function first_taxonomy_setup_section(){
        return (new Section('first_taxonomy_setup'))
            ->set_title(__('First Taxonomy Setup', VXN_EXPRESS_ADDON_DOMAIN))            
            ->add_field(
                (new Text_field('txt_taxonomy_first_name'))
                ->set_label(__('Name', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder('Team Departments')
            )
            ->add_field(
                (new Text_field('txt_taxonomy_first_singular'))
                ->set_label(__('Singular', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder('Team Department')
            )
            ->add_field(
                (new Text_field('txt_taxonomy_first_menu'))
                ->set_label(__('Menu', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder('Team Departments')
            )
            ->add_field(
                (new Text_field('txt_taxonomy_first_slug'))
                ->set_label(__('Slug', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder('department')
            );
    }

    /** @return Section  */
    private static function second_taxonomy_setup_section(){
        return (new Section('second_taxonomy_setup'))
            ->set_title(__('Second Taxonomy Setup', VXN_EXPRESS_ADDON_DOMAIN))            
            ->add_field(
                (new Text_field('txt_taxonomy_second_name'))
                ->set_label(__('Name', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder('Team Roles')
            )
            ->add_field(
                (new Text_field('txt_taxonomy_second_singular'))
                ->set_label(__('Singular', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder('Team Role')
            )
            ->add_field(
                (new Text_field('txt_taxonomy_second_menu'))
                ->set_label(__('Menu', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder('Team Roles')
            )
            ->add_field(
                (new Text_field('txt_taxonomy_second_slug'))
                ->set_label('Slug')
                ->set_placeholder('role')
            );
    }        
}