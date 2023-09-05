<?php
namespace VXN\Express\Job;

use VXN\Express;
use VXN\Express\Fields\Checkbox;
use VXN\Express\Fields\Date_Field;
use VXN\Express\Fields\Select_Field;
use VXN\Express\Fields\Text_Field;
use VXN\Express\Fields\URL_Field;
use VXN\Express\Helper\Util;
use VXN\Express\WP\Post_Type\Post_Type;
use VXN\Express\Section\Section;
use VXN\Express\Module_Interface;
use VXN\Express\Section\Section_Factory;
use VXN\Express\WP\Menu_Page\Menu_Page;
use VXN\Express\WP\Post_Type\Post_Type_Factory;
use VXN\Express\WP\Taxonomy\Taxonomy_Factory;

/**
 * Express job module
 * @package VXN\Express\Job
 * @author Vaksin <dev@vaks.in>
 * @since 1.2.1
 */
class Job_Module implements Module_Interface
{
    private $page;
    private $name;
    private $singular;
    private $menu;
    private $slug;  

  /** @inheritDoc */
    public static function name()
    {
        return 'Jobs';
    }

    /** @inheritDoc */
    public static function slug()
    {
        return 'vxn_express_job';
    }

    /** @inheritDoc */
    public function run() {

        define( 'VXN_EXPRESS_JOB_MODULE_FILE', __FILE__ );
        define( 'VXN_EXPRESS_JOB_MODULE_PATH', plugin_dir_path( __FILE__ ) );
        define( 'VXN_EXPRESS_JOB_MODULE_URL', plugin_dir_url( __FILE__ ) );

        $this->create_post_type();
    }

    /** @return void  */
    private function create_post_type(){
        $this->page = 'vxn_express_job';
        $this->name = __('Jobs', VXN_EXPRESS_ADDON_DOMAIN);
        $this->singular = __('Job', VXN_EXPRESS_ADDON_DOMAIN);
        $this->menu = __('Job', VXN_EXPRESS_ADDON_DOMAIN);
        $this->slug = 'job';  

        Express::add_menu_page($this->post_type_setup_page());
        Express::add_post_type($this->job_post_type());
    }

    /** @return Post_Type  */
    private function job_post_type(){
        $post_type = wp_cache_get('vxn_job_post_type', 'vxn_express');
        if( false === $post_type ){    
            $post_type = Post_Type_Factory::create_from_setup_page(
                page: $this->page,
                post_type: 'vxn_job',
                default_name: $this->name,
                default_singular: $this->singular,
                default_menu: $this->menu,
                default_slug: $this->slug
            )
            ->set_menu_icon('dashicons-megaphone')
            ->add_supports(['thumbnail','excerpt']);

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

            wp_cache_set('vxn_job_post_type', $post_type, 'vxn_express');
        }
       return $post_type;
    }

    /** @return Section[] */
    private function post_type_sections(){
        return [
            /** Job Detail */
            (new Section('vxn_job_detail'))
            ->set_title(__('Job Detail', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_field(
                (new Date_Field('vxn_valid_through'))
                ->set_label(__('Valid Through', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Select_Field('vxn_employment_type'))
                ->set_label(__('Employment Type', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_options([
                    'FULL_TIME' => __('Full Time', VXN_EXPRESS_ADDON_DOMAIN),
                    'PART_TIME' => __('Part Time', VXN_EXPRESS_ADDON_DOMAIN),
                    'CONTRACTOR' => __('Contractor', VXN_EXPRESS_ADDON_DOMAIN),
                    'TEMPORARY' => __('Temporary', VXN_EXPRESS_ADDON_DOMAIN),
                    'INTERN' => __('Intern', VXN_EXPRESS_ADDON_DOMAIN),
                    'VOLUNTEER' => __('Volunteer', VXN_EXPRESS_ADDON_DOMAIN),
                    'PER_DIEM' => __('Per Diem', VXN_EXPRESS_ADDON_DOMAIN),
                    'OTHER' => __('Other', VXN_EXPRESS_ADDON_DOMAIN),
                ])
            )
            ->add_field(
                (new Text_Field('vxn_salary_currency'))
                ->set_label(__('Salary Currency', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_Field('vxn_salary_value'))
                ->set_label(__('Salary Value', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Select_Field('vxn_salary_Unit'))
                ->set_label(__('Salary Unit', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_options([
                    'HOUR' => __('Hourly', VXN_EXPRESS_ADDON_DOMAIN),
                    'DAY' => __('Daily', VXN_EXPRESS_ADDON_DOMAIN),
                    'WEEK' => __('Weekly', VXN_EXPRESS_ADDON_DOMAIN),
                    'MONTH' => __('Monthly', VXN_EXPRESS_ADDON_DOMAIN),
                    'YEAR' => __('Yearly', VXN_EXPRESS_ADDON_DOMAIN),
                ])
            ),

            /** Hiring Organization */
            (new Section('vxn_organization'))
            ->set_title(__('Hiring Organization', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_field(
                (new Text_Field('vxn_company_name'))
                ->set_label(__('Company Name', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_default_value(get_bloginfo('name'))
            )
            ->add_field(
                (new Text_Field('vxn_company_website'))
                ->set_label(__('Company Website', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_default_value(site_url())
            )
            ->add_field(
                (new Text_Field('vxn_company_logo_url'))
                ->set_label(__('Company Logo URL', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_default_value(Util::get_logo_url() ? : '')
            ),

            /** Job Location */
            (new Section('vxn_location_info'))
            ->set_title(__('Job Location', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_field(
                (new Text_Field('vxn_street_address'))
                ->set_label(__('Address', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_Field('vxn_address_locality'))
                ->set_label(__('Locality', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_Field('vxn_address_region'))
                ->set_label(__('Region', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_Field('vxn_postal_code'))
                ->set_label(__('Postal Code', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Text_Field('vxn_address_country'))
                ->set_label(__('Country', VXN_EXPRESS_ADDON_DOMAIN))
            )
            ->add_field(
                (new Checkbox('vxn_is_remote'))
                ->set_label(__('Remote Job', VXN_EXPRESS_ADDON_DOMAIN))
                ->set_text_right(__('Work Form Home', VXN_EXPRESS_ADDON_DOMAIN))
            ),   

            (new Section('vxn_other_info'))
            ->set_title(__('Others', VXN_EXPRESS_ADDON_DOMAIN))
            ->add_field(
                (new URL_Field('vxn_job_application_form_url'))
                ->set_label(__('Job Application Form URL', VXN_EXPRESS_ADDON_DOMAIN))
            ),
        ];
    }

    /** @return Menu_Page */
    private function post_type_setup_page() {
        $page = wp_cache_get('vxn_job_post_type_setup_page', 'vxn_express');
        if( false === $page ) {
            $page = new Menu_Page(__('Job CPT', VXN_EXPRESS_ADDON_DOMAIN), $this->page);
            $page->set_parent_slug('vxn_express_setup');
    
            foreach($this->page_setup_sections() as $section){
                $page->add_section($section);
            }

            wp_cache_set('vxn_job_post_type_setup_page', $page, 'vxn_express');
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
                name_label: 'Job Departments',                
                singular_label: 'Job Department',
                menu_label: 'Job Departments',
                slug_label: 'job-department'
            ),

            Section_Factory::create_taxonomy_setup_section(
                taxonomy_name: 'taxonomy_second',
                taxonomy_title: 'Second Taxonomy Setup',
                name_label: 'Job Levels',
                singular_label: 'Job Level',
                menu_label: 'Job Levels',
                slug_label: 'job-level'
            )
            
        ];    
    }
}