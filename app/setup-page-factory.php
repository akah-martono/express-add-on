<?php
namespace VXN\Express\Addon;

use VXN\Express;
use VXN\Express\Fields\Checkbox;
use VXN\Express\Fields\Number_Field;
use VXN\Express\Fields\Text_Field;
use VXN\Express\Section\Section;
use VXN\Express\WP\Menu_Page\Menu_Page;

/**
 * Class to create setup page
 * @package VXN\Express\Addon 
 * @author Vaksin <dev@vaks.in>
 * @since 1.0.0
 */
class Setup_Page_Factory{

    /** @return Menu_Page  */
    public static function create() :Menu_Page {
        $icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDIwMDEwOTA0Ly9FTiIKICJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy1TVkctMjAwMTA5MDQvRFREL3N2ZzEwLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4wIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiB3aWR0aD0iMzAuMDAwMDAwcHQiIGhlaWdodD0iMjAuMDAwMDAwcHQiIHZpZXdCb3g9IjAgMCAzMC4wMDAwMDAgMjAuMDAwMDAwIgogcHJlc2VydmVBc3BlY3RSYXRpbz0ieE1pZFlNaWQgbWVldCI+Cgo8ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLjAwMDAwMCwyMC4wMDAwMDApIHNjYWxlKDAuMTAwMDAwLC0wLjEwMDAwMCkiCmZpbGw9IiMwMDAwMDAiIHN0cm9rZT0ibm9uZSI+CjxwYXRoIGQ9Ik03NSAxOTAgYy00IC02IDE1IC0xMCA1MCAtMTAgMzUgMCA1NCAtNCA1MCAtMTAgLTMgLTUgLTMwIC0xMCAtNjAKLTEwIC0zMCAwIC01NyAtNCAtNjAgLTEwIC00IC02IDM0IC0xMCAxMDggLTEwIDExMiAwIDExNCAwIDEyNSAyNSA3IDE0IDEyIDI4CjEyIDMwIDAgMTAgLTIxOSA1IC0yMjUgLTV6Ii8+CjxwYXRoIGQ9Ik0yNSAxMjAgYy00IC02IDE1IC0xMCA1MCAtMTAgMzUgMCA1NCAtNCA1MCAtMTAgLTMgLTUgLTMwIC0xMCAtNjAKLTEwIC0zMCAwIC01NyAtNCAtNjAgLTEwIC00IC02IDM0IC0xMCAxMDggLTEwIDExMiAwIDExNCAwIDEyNSAyNSA3IDE0IDEyIDI4CjEyIDMwIDAgMTAgLTIxOSA1IC0yMjUgLTV6Ii8+CjxwYXRoIGQ9Ik0zNSA1MCBjLTQgLTYgMTUgLTEwIDUwIC0xMCAzNSAwIDU0IC00IDUwIC0xMCAtMyAtNSAtMzAgLTEwIC02MAotMTAgLTMwIDAgLTU3IC00IC02MCAtMTAgLTQgLTYgMzQgLTEwIDEwOCAtMTAgMTEyIDAgMTE0IDAgMTI1IDI1IDcgMTQgMTIgMjgKMTIgMzAgMCAxMCAtMjE5IDUgLTIyNSAtNXoiLz4KPC9nPgo8L3N2Zz4K';

        $setup_page = (new Menu_Page(__('Setup', VXN_EXPRESS_ADDON_DOMAIN), 'vxn_express_setup'))
            ->set_menu_title('Express Options')
            ->set_position(3)
            ->set_icon_url($icon);

        $setup_page->add_section(self::setup_section());
        foreach(self::get_module_sections() as $section){
            $setup_page->add_section($section);
        }

        return $setup_page;
    }
    
    private static function setup_section(){
        return (new Section('setup'))
        ->set_title(__('Setup', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_field((new Text_Field('txt_phone_format'))            
                ->set_label(__('Phone Display Format', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder('3|0xxx xxxx xxxx')
            )
            ->add_field((new Number_Field('txt_excerpt_length'))            
                ->set_label(__('Excerpt Length', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder('55')
            )
            ->add_field((new Text_Field('txt_search_text'))            
                ->set_label(__('Search Text', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder('Silahkan ketik yang ingin anda cari pada kolom pencarian')
            )
            ->add_field((new Text_Field('txt_search_found_text'))            
                ->set_label(__('Search Found Text', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder('Hasil pencarian untuk "%s"')
            )
            ->add_field((new Text_Field('txt_search_not_found_text'))            
                ->set_label(__('Search Not Found Text', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_placeholder('Maaf, hasil pencarian untuk "%s" tidak ditemukan')
            )
            ->add_field((new Checkbox('chk_fix_bd_img_for_lscache'))            
                ->set_label(__('Fix LiteSpeed Cache Lazyload', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_text_right(__('Remove line breaks within the image tags', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_description(__('Enable it when you use LiteSpeed Cache for Lazyload image', VXN_EXPRESS_ADDON_DOMAIN))
            );            
            
    }    
    
    /** @return Section[]  */
    private static function get_module_sections(){
        $sections['modules'] = (new Section('modules'))->set_title('Basic Modules');

        foreach(Express::module_sections() as $slug => $title){
            $sections[$slug] = (new Section($slug))->set_title($title);
        }

        foreach(Express::modules() as $module){
            $slug = is_callable(array($module, 'section')) ? call_user_func(array($module, 'section')) : 'modules';
            
            if(!array_key_exists($slug, $sections)){
                $slug='modules';
            }
            
            $chk_field = sprintf('chk_%s_module', call_user_func(array($module, 'slug')));

            $sections[$slug]->add_field(
                (new Checkbox($chk_field))
                ->set_label(call_user_func(array($module, 'name')))
                ->set_text_right(sprintf(__('Activate %s module', VXN_EXPRESS_ADDON_DOMAIN), call_user_func(array($module, 'name'))))
            );
        }

        return $sections;
    }
}