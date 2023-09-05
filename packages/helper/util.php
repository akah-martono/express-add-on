<?php
namespace VXN\Express\Helper;

use VXN\Express\Fields\Field;
use VXN\Express\Fields\Text_Field;
use VXN\Express\Fields\Text_Area;
use Breakdance\Lib\Vendor\Twig\Environment;
use Breakdance\Lib\Vendor\Twig\Loader\ArrayLoader;
/**
 * Static utility functions
 * @package VXN\Express\Helper
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
class Util {    

    /**
     * To format phone number
     * <code>
     * $formated
     * </code>
     * @param string $phone  Example: +6281220003131
     * @param string $format Example: 3|0xxx-xxxx-xxxxx
     * @return string Result from example: 0812-2000-3131
     */
    public static function format_phone($phone, $format){
        
        if(!$format) {
            return $phone;
        }

        $opts = explode('|', $format);        
        $start = 0;
        
        if(count($opts)>1) {
            $start = intval($opts[0]);
            $format = $opts[1];
        }

        $arr_format = str_split($format);
        $arr_phone = str_split($phone);

        $i = $start;
        $result = '';

        foreach($arr_format as $value){
            if($value == 'x'){
                if($i >= count($arr_phone)){
                    break;
                }

                $result .= $arr_phone[$i];

                $i += 1;
            } else {
                $result .= $value;
            }
        }

        if($i <= count($arr_phone)){
            $result .= substr($phone, $i);
        }

        return $result;
    }

    /** @return string  */
    public static function get_current_url(){
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    /**
     * @param mixed $var 
     * @return void 
     */
    public static function print_debug($var){
        echo "< !-- \n DEBUG: \n";
        print_r($var);
        echo "\n --> ";
    }

    /** @return string|false  */
    public static function get_logo_url(){
        $logo_id = get_theme_mod('custom_logo');
        return wp_get_attachment_image_url($logo_id, 'full') ?? '';
    }

    /**
     * @param array $array 
     * @return array 
     */
    public static function remove_empty_array_deep(array $array){
        foreach($array as $key => $value) {
            if(empty($value)) {
                unset($array[$key]);
            }elseif(is_array($value)){
                $array[$key] = self::remove_empty_array_deep($value);
            }
        }
        return $array;
    }

    /**
     * @param Field $field 
     * @return string|float|int|void 
     */
    public static function sanitize_field (Field $field ) { 
    
		switch(true){
			case is_a($field, Text_Field::class):
				return sanitize_text_field($field['value']);
				break;
            case is_a($field, Text_Area::class):
                return esc_textarea($field['value']);
                break;
			case is_a($field, Select_Field::class):
				return sanitize_text_field($field['value']);
				break;	
			case is_a($field, Date_Field::class):
				return sanitize_text_field($field['value']);
				break;
			case is_a($field, Checkbox::class):
				return sanitize_text_field($field['value']);
				break;
			case is_a($field, Number_Field::class):
                $value = sanitize_text_field($field['value']);
                
                if($field['number_type'] = 'float') {
                    return floatval($value);
                }

                return intval($value);

				break;                    
			case is_a($field, Email_Field::class):
				return sanitize_email($field['value']);
				break;                    
			default:
				break;
		}
    }

	/**
	 * @param string $text 
	 * @return string 
	 */
	public static function parse_shortcode_dynamic_var( $text ) {

		if ( strpos( $text, '{page_title}' ) !== false ) {
			$dynamic_var['page_title'] = get_the_title();
		}		

		if ( strpos( $text, '{url}' ) !== false ) {
			$dynamic_var['url'] = get_permalink( get_the_ID() );
		}

		if ( !empty( $dynamic_var ) ) {
			foreach ( $dynamic_var as $key => $value ) {
				$text = str_replace( '{' . $key . '}', $value, $text );
			}
		}

		return str_replace("'", "`", $text);
	}

	/**
	 * @param string $text 
	 * @param array $args 
	 * @return string 
	 */
	public static function replace_shortcode_dynamic_var_with_atts( $text, $args ) {
		if ( !empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				if ( $value ) {
					$text = str_replace( '{' . $key . '}', do_shortcode( $value ), $text );
				}
			}
		}

		return str_replace("'", "`", $text);
	}

    /**
     * @param mixed $key 
     * @param mixed $array 
     * @return mixed 
     */
    public static function get_key_value($key, $array) {
        if(array_key_exists ($key, $array)){
            return $array[$key];
        }else{
            foreach($array as $value){
                if(is_array($value)){
                    $val = static::get_key_value($key, $value);
                    if(!is_null($val)){
                        return $val;
                    }
                }
            }
        }

        return null;
    }

    /**
     * @param mixed $place 
     * @return string 
     */
    public static function google_map_url($place){
        $url = 'https://www.google.com/maps/search/?api=1&query='. urlencode($place);
        return esc_url_raw($url);
    }

    /**
     * To remove empty muldidimensional array
     * @param array $array 
     * @return array 
     */
    public static function remove_empty(array $array){
        $array = array_filter($array, function($var){
            return !empty($var) || $var === '0';
        });

        foreach($array as $key => $value){
            if(is_array($value)){
                $value = self::remove_empty($value);
            }
            $array[$key] = $value;
        }
        return $array;
    }

    /**
     * to generate random password
     * @param int $length 
     * @return string 
     */
    public static function random_password( $length = 8 ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#%^&*()_-=+;:,.?";
        $password = substr( str_shuffle( $chars ), 0, $length );
        return $password;
    }

    /**
     * To get Instance from wp_cache
     * @param string $class 
     * @return mixed 
     */
    public static function get_instance($class){
        $instance = wp_cache_get($class, 'vxn_express_instance');
        if(!$instance){
            if(!class_exists($class)){
                return false;
            }
            $instance = new $class();
            wp_cache_set($class, $instance, 'vxn_express_instance');
        }
        // wp_cache_flush_group( 'vxn_express_class' );
        return $instance;
    }

    /**
     * To get Instance with params from wp_cache
     * @param string  $class 
     * @param string  $key 
     * @param mixed $param 
     * @return mixed 
     */
    public static function get_instance_with_param($class, $key, ...$param){
        $instance = wp_cache_get("{$class}_{$key}", 'vxn_express_instance');
        if(!$instance){
            if(!class_exists($class)){
                return false;
            }
            $instance = new $class(...$param);
            wp_cache_set("{$class}_{$key}", $instance, 'vxn_express_instance');
        }
        return $instance;
    }    

    /**
     * To echo breadcrumb
     * @param string $home_text 
     * @param string $separator 
     * @return void 
     */
    public static function express_breadcrumb($home_text = null, $separator = null) {
        echo self::get_breadcrumb($home_text, $separator);
    }

    /**
     * @param string $method 
     * @param string $key 
     * @param string $data 
     * @return string 
     */
    public static function encrypt($method, $key, $data){
        // $method check https://www.php.net/manual/en/function.openssl-get-cipher-methods.php
        $plaintext = $data;
        $ivlen = openssl_cipher_iv_length($cipher = $method);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
        return $ciphertext;
    }

    /**
     * @param string $method 
     * @param string $key 
     * @param string $data 
     * @return string|false|void 
     */
    public static function decrypt($method, $key, $data) {
        $c = base64_decode($data);
        $ivlen = openssl_cipher_iv_length($cipher = $method);
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($c, $ivlen + $sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        if (hash_equals($hmac, $calcmac))
        {
            return $original_plaintext;
        }
    }

    /**
     * @param string $post_types 
     * @return array 
     */
    public static function get_public_post_urls($post_types=null){        
        if(!$post_types){
            $post_types = get_post_types( ['public' => true]);
            unset($post_types['attachment']);
        }

        $query_args = [
            'post_type' => $post_types,
            'post_status' => 'publish',
            'cache_results'  => false
        ];

        $wp_query = new \WP_Query( $query_args );
        $urls = [];
        if($wp_query->have_posts()){
            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
                $mod_date = new \DateTime(get_the_modified_date('Y-m-d H:i:s'));
                $mod_date->setTimezone(new \DateTimeZone('Asia/Jakarta'));
                $urls[]=[
                    'url' => get_the_permalink(),
                    'modified' => date_format($mod_date, 'Y-m-d H:i:s')
                ];                       
            }
        }

        return $urls;
    }

    /** @return array  */
    public static function get_eweb_auth(){
        $auth = wp_cache_get('vxn_eweb_auth');

        if($auth === false){
            $key_file = ABSPATH . 'eweb.id.key';

            if (!file_exists($key_file)) {
                return [];
            }
    
            $auth = [
                'uid' => self::get_eweb_id(),
                'key' => file_get_contents($key_file)
            ];

            wp_cache_add('vxn_eweb_auth', $auth);
        }

        return $auth;
    }

    /** @return bool  */
    public static function is_eweb(){
        $vxn_is_eweb = wp_cache_get('vxn_is_eweb');

        if(!$vxn_is_eweb){
            $auth = self::get_eweb_auth();

            if(empty($auth)){
                return false;
            }
    
            $args=[	
                'headers'=> [
                    'uid' => $auth['uid'],
                    'key' => $auth['key']
                ],
            ];        
    
            $response=wp_remote_get('https://api.eweb.co.id/verify', $args);
    
            if(is_wp_error($response)){
                return false;
            }
    
            $response=$response['http_response'];
            $data = json_decode($response->get_data(), true);
    
            $vxn_is_eweb = ($data['status'] == 1);
            wp_cache_add('vxn_is_eweb', $vxn_is_eweb);            
        }

        return $vxn_is_eweb;
    }

    /**
     * To enqueue cloudflare purge using eweb API
     * @param string $type 
     * @param array $url 
     * @return bool 
     */
    public static function cf_enqueue_purge(string $type, array $url){
        $auth = self::get_eweb_auth();

        if(empty($auth)){
            self::add_admin_notice('error','Enqueue Purge Error: Fitur ini hanya untuk pelanggan ExpressWEB');
            return false;
        }

        $body = [
            'site'=>get_site_url(),
            'type'=> $type,
            'files' => $url
        ];

        $args=[	
            'body' => json_encode($body,JSON_UNESCAPED_SLASHES),
            'headers'=> [
                'uid' => $auth['uid'],
                'key' => $auth['key'],                
                'Content-Type' => 'application/json',
            ],
        ];

        $response=wp_remote_post('https://api.eweb.co.id/cf/enqueue', $args);
        if(is_wp_error($response)){
            self::add_admin_notice('error', 'Enqueue Purge Error Connection: '  . $response->get_error_message());
            return false;
        }

        $http_response=$response['http_response'];        
        $data = json_decode($http_response->get_data(), true);

        if($data['status'] != 1){
            self::add_admin_notice('error', 'Enqueue Purge Error: '  . $data['message']);            
        }

        return $data['status'] == 1;
    }

    /**
     * @param string $class 
     * @param string $message 
     * @return void 
     */
    public static function add_admin_notice($class, $message){
        // $class = 'error' | 'notice' | 'update'        
        add_action('admin_notices', function() use ($class, $message) {
            echo '
                <div class="' . esc_attr($class) . '">
                    <p>' .
                    wp_kses_post($message) .
                    '<p>
                </div>';					
        });
    }

    /** @return void  */
    public static function get_eweb_id(){
        $vxn_eweb_id = wp_cache_get('vxn_eweb_id');
        
        if($vxn_eweb_id === false){
            $ar_dir = explode('/', explode('/public_html', ABSPATH)[0]);
            $vxn_eweb_id = $ar_dir[count($ar_dir)-1];
            wp_cache_add('vxn_eweb_id', $vxn_eweb_id);
        }

        return $vxn_eweb_id;
    }

    public static function get_express_breadcrumb($home_text = null) :array {
        $breadcrumbs = [];        

        if (!is_front_page()) {
            $home_url = get_option('home');            
            if (!$home_text){
                $home_text = __('Home');
            }

            $breadcrumbs[] = [
                'title' => $home_text,
                'url' => $home_url
            ];
            
            if (is_single() ){
                $post_type = get_post_type();
                if ( $post_type ){
                    if($post_type == 'post'){
                        $page_for_posts_id = get_option( 'page_for_posts' );                        
                        if($page_for_posts_id){
                            $breadcrumbs[] = [
                                'text' => get_the_title($page_for_posts_id),
                                'url' => get_permalink($page_for_posts_id)
                            ];
                        }
                    }else{
                        $post_type_data = get_post_type_object( $post_type );
                        $breadcrumbs[] = [
                            'title' => $post_type_data->labels->name,
                            'url' => $home_url . '/' . $post_type_data->rewrite['slug']
                        ];                        
                    }                    
                }
                $breadcrumbs[] = [
                    'title' => get_the_title(),
                    'url' => self::get_current_url()
                ];                       
            } elseif (is_page()) {
                $breadcrumbs[] = [
                    'title' => the_title(),
                    'url' => self::get_current_url()
                ];                       
            } elseif (is_home()){
                global $post;
                $page_for_posts_id = get_option('page_for_posts');
                if ( $page_for_posts_id ) { 
                    $breadcrumbs[] = [
                        'title' => get_the_title($page_for_posts_id),
                        'url' => self::get_current_url()
                    ];
                }
            } elseif (is_category()){
                $breadcrumbs[] = [
                    'title' => single_term_title(),
                    'url' => self::get_current_url()
                ];                
            } elseif (is_archive()){                
                if ( is_day() ) {
                    $breadcrumbs[] = [
                        'title' => sprintf( __( '%s', 'text_domain' ), get_the_date() ),
                        'url' => self::get_current_url()
                    ];                      
                } elseif ( is_month() ) {
                    $breadcrumbs[] = [
                        'title' => sprintf( __( '%s', 'text_domain' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'text_domain' ) ) ),
                        'url' => self::get_current_url()
                    ];                      
                } elseif ( is_year() ) {
                    $breadcrumbs[] = [
                        'title' => sprintf( __( '%s', 'text_domain' ), get_the_date( _x( 'Y', 'yearly archives date format', 'text_domain' ) ) ),
                        'url' => self::get_current_url()
                    ];                          
                } else {
                    $title = explode(':', get_the_archive_title(), 2);
                    $breadcrumbs[] = [
                        'title' => trim($title[count($title)-1]),
                        'url' => self::get_current_url()
                    ];                         
                }
            }
        }

        return $breadcrumbs;
    }

    public static function get_schemapro_breadcrumb(){
        if ( class_exists( '\BSF_AIOSRS_Pro_Schema_Template' ) ) {            
            return \BSF_AIOSRS_Pro_Schema_Template::get_breadcrumb_list();
        }
        return [];
    }

    public static function get_breadcrumb($home_text = null, $separator = null, $breadcrumb_list_div=true){        
        $separator = $separator ?? "&#187;";
        $separator = ' <span class="separator">' . esc_html($separator) . '</span> ';

        $breadcrumbs = self::get_schemapro_breadcrumb();

        if(empty($breadcrumbs)){
            $breadcrumbs = self::get_express_breadcrumb($home_text);
        }

        $last = array_key_last($breadcrumbs);
        
        $output ='';

        if($breadcrumb_list_div){
            $output .= '<div itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumbs bde-breadcrumbs" aria-label="breadcrumb">';
        }        

        $output .= "<span>";
        foreach($breadcrumbs as $i => $breadcrumb){
            $output .= '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
            $position  = $i + 1;

            if($i == $last){
                $output .= '<span itemprop="item">';
                    $output .= '<span itemscope itemprop="identifier" itemtype="http://schema.org/PropertyValue" hidden>';
                        $output .= '<meta itemprop="url">' . $breadcrumb['url'] . '</span>';
                    $output .= '</span>';
                    $output .= '<span itemprop="name" >' . $breadcrumb['title'] . '</span>'; 
                    $output .= '<meta itemprop="position" content="' . $position . '" />';           
                $output .= '</span>';
            }else{

                $output .= '<a itemprop="item" href="' . $breadcrumb['url'] . '">';
                    $output .= '<span itemprop="name" >' . $breadcrumb['title'] . '</span>'; 
                    $output .= '<meta itemprop="position" content="' . $position . '" />';
                $output .= '</a>';
            }
            
            $output .= '</span>';

            if($i != $last) {
                $output .= $separator;
            }

        }
        $output .= '</span>';

        if($breadcrumb_list_div){
            $output .= '</div>';
        }        

        return $output;
    }

    public static function twig_renderer($template, $data) :string{
        $loader = new ArrayLoader(['template' => $template  ]);
        $twig = new Environment($loader, ['autoescape' => false]);
        return $twig->render('template', $data);         
    }

    public static function replace_translated_text(array $translations, string $domain){
        add_filter( 'gettext',  function ( $translated_text, $text, $the_domain ) use($translations, $domain) {
            if(!is_admin() && $the_domain == $domain){
                foreach($translations as $key => $val){
                    if(str_contains($translated_text, $key)){
                        return str_replace($key, $val, $translated_text);
                    }
                }    
            }
            return $translated_text;
        }, 20, 3 );
    }
    
    /** to make sure the temp dir is unique per site */
    public static function set_new_temp_dir(){
        if ( !defined( 'WP_TEMP_DIR' ) ) {
            $vxn_eweb_id = wp_cache_get('vxn_temp_dir');
            if($vxn_eweb_id === false){
                $current_dir = sys_get_temp_dir();
                $folder = self::get_eweb_id(); 
                if($folder){
                    $new_dir = $current_dir . DIRECTORY_SEPARATOR . $folder;
                    if (!file_exists($new_dir)) {
                        mkdir($new_dir, 0755);
                    }               
                    define('WP_TEMP_DIR', $new_dir);
                }
            }    
        }
    }

    public static function post_loop_builder($query, $block_id, $args=[]){
        $defaults = [
            'class' => 'ew-post',
            'not_found_text' => 'No post found',
            'paginate_args' => [],
        ];

        $args = array_merge($defaults, $args);

        if ( $query->have_posts() ) {
            while ( $query->have_posts()) {
                $query->the_post();
                $block = do_shortcode("[breakdance_block blockId={$block_id}]");
                printf('<div class="%s">%s</div>', $args['class'], $block);
            }
            $total_pages = $query->max_num_pages;            
            if ($total_pages > 1){
                $current_page = max(1,  $query->get( 'paged', 0 ));

                $default_paginate_args = [
                    'base' => get_pagenum_link(1) . '%_%',
                    'format' => '/page/%#%',
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev_text'    => __('« Previous'),
                    'next_text'    => __('Next »'),
                ];

                $paginate_args = array_merge($default_paginate_args, $args['paginate_args']);
                printf(
                    '<div class="ew-pagination">%s</div>',
                    paginate_links($paginate_args)
                );
            }
            $query->reset_postdata();
        } else {
            printf('<div class="ew-not-found bde-div">%s</div>', $args['not_found_text']);            
        }  
    }
}
