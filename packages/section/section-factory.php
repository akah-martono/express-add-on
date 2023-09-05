<?php
namespace VXN\Express\Section;

use VXN\Express\Fields\Text_Area;
use VXN\Express\Fields\Text_Field;

class Section_Factory {
    /** @return Section  */
    public static function create_taxonomy_setup_section(
        $taxonomy_name, 
        $taxonomy_title,
        $name_label,        
        $singular_label,
        $menu_label,
        $slug_label,
        $description_label = '',
        ){

        // if (!$description_label) {
        //     $description_label = __('Description of taxonomy that will be shown in the archive page', VXN_EXPRESS_ADDON_DOMAIN);
        // }
    
        return (new Section("{$taxonomy_name}_setup"))
            ->set_title($taxonomy_title)            
            ->add_field(
                (new Text_Field("txt_{$taxonomy_name}_name"))
                ->set_label(__('Name', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder($name_label)
            )
            // ->add_field(
            //     (new Text_Area("txt_{$taxonomy_name}_description"))
            //     ->set_label(__('Description', VXN_EXPRESS_ADDON_DOMAIN))
            //     ->set_placeholder($description_label)
            // )            
            ->add_field(
                (new Text_field("txt_{$taxonomy_name}_singular"))
                ->set_label(__('Singular', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder($singular_label)
            )
            ->add_field(
                (new Text_field("txt_{$taxonomy_name}_menu"))
                ->set_label(__('Menu', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder($menu_label)
            )
            ->add_field(
                (new Text_field("txt_{$taxonomy_name}_slug"))
                ->set_label(__('Slug', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder($slug_label)
            );
    }   
    
    /** @return Section  */
    public static function create_post_type_setup_section(
        $name_label,        
        $singular_label,
        $menu_label,
        $slug_label,
        $description_label = ''
        ){
        
        if (!$description_label) {
            $description_label = __('Description of post type that will be shown in the archive page', VXN_EXPRESS_ADDON_DOMAIN);
        }

        return (new Section('post_type_setup'))
            ->set_title(__('Post Type Setup', VXN_EXPRESS_ADDON_DOMAIN))            
            ->add_field(
                (new Text_field('txt_cpt_name'))
                ->set_label(__('Name', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder($name_label)
                ->set_default_value($name_label)
            )
            ->add_field(
                (new Text_Area("txt_cpt_description"))
                ->set_label(__('Description', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder($description_label)
            )  
            ->add_field(
                (new Text_field('txt_cpt_singular'))
                ->set_label(__('Singular', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder($singular_label)
                ->set_default_value($singular_label)
            )
            ->add_field(
                (new Text_field('txt_cpt_menu'))
                ->set_label(__('Menu', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder($menu_label)
                ->set_default_value($menu_label)
            )
            ->add_field(
                (new Text_field('txt_cpt_slug'))
                ->set_label(__('Slug', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder($slug_label)
                ->set_default_value($slug_label)
        );
    }       
}