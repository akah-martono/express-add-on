<?php
namespace VXN\Express;

use VXN\Express;
use VXN\Express\Helper\Util;
use VXN\Express\WP\Meta\Metabox_Registrar;
use VXN\Express\WP\Menu_Page\Menu_Page_Registrar;
use VXN\Express\WP\Menu_Page\Menu_Pages_Builder;
use VXN\Express\WP\Menu_Page\Submenu_Page_Registrar;
use VXN\Express\WP\Meta\Save_Metabox;
use VXN\Express\WP\Post_Type\Post_Types_Builder;
use VXN\Express\WP\Script\Script_Registrar;
use VXN\Express\WP\Taxonomy\Taxonomy_Registrar;

/** 
 * Express Handler
 * @package VXN\Express
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Handler{
    private static $run = false;

    public static function run(){
        if(!self::$run){
            if(!self::check_requirements()){
                return;
            }

            self::do_preparation();
                        
            add_action( 'plugins_loaded', function() {			
                do_action('vxn_express_load_modules');
                do_action('vxn_express_modules_loaded');
                do_action('vxn_express_init');
                do_action('vxn_express_loaded');
            });

            self::$run = true;
        }        
    }

	/** @return bool  */
	private static function check_requirements() {
        if(!Breakdance::is_active()){
            Util::add_admin_notice('error', 'Express Add On requires <a href="https://s.id/breakdance">Breakdance</a> installed & activated!.');
            return false;
        }
        return true;
	}    

    private static function do_preparation() {
		//fisrt action after module loaded
		add_action('vxn_express_modules_loaded', function() {
			self::run_modules();
        }, 1);		

		add_action('vxn_express_loaded', function() {

			Post_Types_Builder::build();
            Menu_Pages_Builder::build();
			
			self::register_taxonomy();
			
			self::enqueue_script();

			self::register_breakdance();

			if( is_admin() ){
				// self::create_menu_pages();
				self::register_metabox();
			} else {
				self::add_shortcodes();
			}

		}, 999);
	}

	private static function create_menu_pages(){
		add_action('admin_menu', function() {
			$parent_slug_group = [];
			foreach(Express::menu_pages() as $page){
				if($page['parent_slug']){
					$parent_slug_group[] = $page['parent_slug'];
				}else{
					Menu_Page_Registrar::register($page);
				}			
			}

			if(!empty($parent_slug_group)){
				$parent_slug_group = array_unique($parent_slug_group);
				foreach($parent_slug_group as $parent_slug){
					Submenu_Page_Registrar::register($parent_slug);
				}
			}
		});

	}	

	/** @return void  */
	private static function run_modules(){
		foreach(Express::modules() as $module){
			$chk_field = sprintf('chk_%s_module', call_user_func(array($module, 'slug')));
			if(Express::Options('vxn_express_setup.' . $chk_field)){
				call_user_func(array($module, 'run'));
			}  
		}			
	}

	/** @return void  */
	private static function add_shortcodes(){
		// add_action( 'init', function() {
			foreach(Express::shortcodes() as $tag => $callback){
				if ( (!shortcode_exists( $tag )) && is_callable($callback) ){
					add_shortcode($tag, $callback);	
				}	
			}	
		// });   
	}
	
    /** @return void  */
    private static function register_taxonomy(){
        add_action( 'init', function() {
            foreach(Express::taxonomies() as $taxonomy){
                Taxonomy_Registrar::register($taxonomy);
            }    
        });
    }

    /** @return void  */
    private static function register_metabox(){

        /** add action to save_post hook without waiting add_meta_boxes firing */
        foreach(Express::metaboxes() as $metabox){
            Save_Metabox::add_action_to_save_post($metabox);        
        }    

        add_action( 'add_meta_boxes', function() {
            foreach(Express::metaboxes() as $metabox){
                Metabox_Registrar::register($metabox);
            }    
        });
    }

	/** @return void  */
	private static function enqueue_script(){
		add_action('wp_enqueue_scripts', function() {
			foreach(Express::scripts() as $script){
				Script_Registrar::register($script, false);
			}	

			foreach(Express::scripts() as $script){
				Script_Registrar::enqueue($script);
			}	

		});
	}
	
	/** @return void  */
	private static function register_breakdance(){

		if(Breakdance::is_active()){
			add_action('init', function() {
				if ((function_exists('\Breakdance\DynamicData\registerField') && class_exists('\Breakdance\DynamicData\Field'))) {
					foreach(Breakdance::dynamic_fields() as $field){
						\Breakdance\DynamicData\registerField($field);
					}
				}

				if ((function_exists('\Breakdance\Forms\Actions\registerAction') && class_exists('\Breakdance\Forms\Actions\Action'))) {
					foreach(Breakdance::form_actions() as $action){
						\Breakdance\Forms\Actions\registerAction($action);
					}
				}                
			});
		}
	}

}