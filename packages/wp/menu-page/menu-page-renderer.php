<?php
namespace VXN\Express\WP\Menu_Page;

use VXN\Express;
use VXN\Express\WP\Menu_Page\Menu_Page_Field_Renderer;

/**
 * This class to render page content and setting section in Menu Page
 * @package VXN\Express\WP\Menu_Page
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Menu_Page_Renderer {

    /**
     * @param Menu_Page $menu_page 
     * @param bool $nav_tabs 
     * @return void 
     */
    public static function render_page_content(Menu_Page $menu_page, $nav_tabs = true) {
        $nav_tabs = [];
        if($menu_page['tab_title']){
            $parent_slug = $menu_page['parent_slug'] ?? $menu_page['slug'];        
            $nav_tabs = Express::nav_tabs($parent_slug);
        }

        
        echo '<div class="wrap">';
        
        //title
        echo '<h1>' . esc_html($menu_page['page_title']) . '</h1>';

        //description 
        echo $menu_page['description'] ? '<p>'. sanitize_text_field($menu_page['description']) . '</p>' : '';

        if(!empty($nav_tabs)) {
            // start nav tabs
            echo '<nav class="nav-tab-wrapper">';
            foreach($nav_tabs as $tab) {
                $active_class = $tab['slug'] == $menu_page['slug'] ? 'nav-tab-active' : '';
                printf(
                    '<a href="%s" class="nav-tab %s">%s</a>',
                    esc_url('?page=' . $tab['slug']),
                    esc_html($active_class),
                    esc_html($tab['title'])
                ); 
            }
            echo '</nav>';
            // end nav tabs            
        }


        // start options form
        echo '<form method="post" action="options.php">';
            settings_fields( $menu_page['option_group'] );
            do_settings_sections( $menu_page['slug'] );
            submit_button();        
        echo '</form>';
        // end options form

        //close div
        echo '</div>';
    }

    /**
     * @param Menu_Page $menu_page 
     * @return void 
     */
    public static function render_settings_section (Menu_Page $menu_page)  {        
        $sections = $menu_page['sections'];
        $sanitize_cb = array(self::class, 'sanitize');

		register_setting(
			$menu_page['option_group'], // option_group
			$menu_page['option_name'], // option_name            
			function ($input) use ($sanitize_cb, $sections){
                return $sanitize_cb($sections, $input );
            } // sanitize_callback 
		);        

        foreach ( $menu_page['sections'] as $section ) {
            if ($section['div_top_id']){
                $div_id = $section['div_top_id'];
                add_settings_section( $section['id'] . '_div_top', '', function () use($div_id) {
                    echo '<div id=' . esc_attr($div_id) . '></div>';
                }, $menu_page['slug'] );
            }
            if ($section['hr_top']){
                add_settings_section( $section['id'] . '_hr_top', '', function () {echo '<hr>';}, $menu_page['slug'] );
            }

            add_settings_section(
                $section['id'], // id
                $section['title'], // title
                function () use($section) {
                    echo  $section['info'] ? esc_textarea($section['info']) : '';
                }, // callback
                $menu_page['slug']  // page
            );            
            
            foreach ( $section['fields'] as $field ) {                
                $field['name'] = $menu_page['option_name'] . '[' . $field['id'] . ']';

                $field['value'] = Express::Options($menu_page['slug'] . '.' . $field['id'], '');

                add_settings_field(
                    $field['id'], // id
                    $field['label'], // title
                    function() use ($field) {
                        Menu_Page_Field_Renderer::render_field($field);
                    }, // callback
                    $menu_page['slug'], // page
                    $section['id'], // section
                    array_merge(
                        ['label_for' => $field['id']], 
                        $field['class'] ? ['class' => $field['class']] : []
                    )
                );                
            }

            if ($section['hr_bottom']){
                add_settings_section( $section['id'] . '_hr_bottom', '', function () {echo '<hr>';}, $menu_page['slug']);
            }
        } 
    }

    /**
     * @param array $sections 
     * @param mixed $input 
     * @return array 
     */
    private static function sanitize ($sections, $input ) { 
		$sanitary_values = [];
        foreach ( $sections as $section ) { 
            foreach ( $section['fields'] as $field ) {
                $id = $field['id'];
                $type = $field['type'];
    
                if ( isset( $input[$id] ) ) {
                    if ( $type === 'text' ) {
                        $sanitary_values[$id] = sanitize_text_field( $input[$id] );
                    } elseif ( $type === 'textarea' ) {
                        $sanitary_values[$id] = esc_textarea( $input[$id] );
                    } elseif ( $type === 'email' ) {
                        $sanitary_values[$id] = sanitize_email( $input[$id] );
                    } elseif ( $type === 'phone' ) {
                        $sanitary_values[$id] = preg_replace( '/[^\d+]/', '', $input[$id] );
                    } else {
                        $sanitary_values[$id] = $input[$id];
                    } 
                }
            }
        }
		return $sanitary_values;
    }  
}
