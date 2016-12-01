<?php
/**
 * Plugin's Admin code
 *
 * @link https://wordpress.org/plugins/wc-product-stock-status/
 * @package WC Product Stock Status
 * @subpackage WC Product Stock Status/Admin
 * @since 1.0
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WC_Product_Stock_Status_Admin {

    /**
	 * Initialize the class and set its properties.
	 * @since      0.1
	 */
	public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ),99);
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_init', array( $this, 'admin_init' ));

        add_filter( 'plugin_row_meta', array($this, 'plugin_row_links' ), 10, 2 );
        add_filter( 'plugin_action_links_'.WC_PSS_FILE, array($this,'plugin_action_links'),10,10);
        add_filter( 'woocommerce_get_settings_pages',  array($this,'settings_page') ); 
	}
     
    
    /**
     * Inits Admin Sttings
     */
    public function admin_init(){ 
    }
    
    
	/**
	 * Add a new integration to WooCommerce.
	 */
	public function settings_page( $integrations ) {
        foreach(glob(WC_PSS_ADMIN.'wc-settings/woocommerce-settings*.php' ) as $file){
            $integrations[] = require_once($file);
        }
		return $integrations;
	}
    
    /**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {  
        $pages = wc_pstocks_get_screen_ids();
        $current_screen = wc_pstocks_current_screen();
        
        $addon_url = admin_url('admin-ajax.php?action=wc_pstocks_addon_custom_css');
        wp_register_style(WC_PSS_SLUG.'_backend_style',WC_PSS_CSS.'backend.css' , array(), WC_PSS_V, 'all' );  
        wp_register_style(WC_PSS_SLUG.'_addons_style',$addon_url , array(), WC_PSS_V, 'all' );  

        
        if(in_array($current_screen ,$pages)) {
            wp_enqueue_style(WC_PSS_SLUG.'_backend_style');  
            wp_enqueue_style(WC_PSS_SLUG.'_addons_style');  
        }
        
        do_action('wc_pstocks_admin_styles',$current_screen,$pages);
	}
    
    /**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
        $pages = wc_pstocks_get_screen_ids();
        $current_screen = wc_pstocks_current_screen();
        
        $addon_url = admin_url('admin-ajax.php?action=wc_pstocks_addon_custom_js');
        
        wp_register_script(WC_PSS_SLUG.'_backend_script', WC_PSS_JS.'backend.js', array('jquery'), WC_PSS_V, false ); 
        wp_register_script(WC_PSS_SLUG.'_addons_script', $addon_url, array('jquery'), WC_PSS_V, false ); 
        
        
        if(in_array($current_screen ,$pages)) {
            wp_enqueue_script(WC_PSS_SLUG.'_backend_script' ); 
            wp_enqueue_script(WC_PSS_SLUG.'_addons_script' ); 
        } 
        
        do_action('wc_pstocks_admin_scripts',$current_screen); 
 	}
 
    /**
	 * Adds Some Plugin Options
	 * @param  array  $plugin_meta
	 * @param  string $plugin_file
	 * @since 0.11
	 * @return array
	 */
    public function plugin_action_links($action,$file,$plugin_meta,$status){
        $menu_link = admin_url('admin.php?page=wc-product-stock-status-settings');
        $actions[] = sprintf('<a href="%s">%s</a>', $menu_link, __('Settings',WC_PSS_TXT) );
        $actions[] = sprintf('<a href="%s">%s</a>', 'http://varunsridharan.in/plugin-support/', __('Contact Author',WC_PSS_TXT) );
        $action = array_merge($actions,$action);
        return $action;
    }
    
    /**
	 * Adds Some Plugin Options
	 * @param  array  $plugin_meta
	 * @param  string $plugin_file
	 * @since 0.11
	 * @return array
	 */
	public function plugin_row_links( $plugin_meta, $plugin_file ) {
		if ( WC_PSS_FILE == $plugin_file ) {
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', __('F.A.Q',WC_PSS_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', __('View On Github',WC_PSS_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', __('Report Issue',WC_PSS_TXT) );
            $plugin_meta[] = sprintf('&hearts; <a href="%s">%s</a>', '#', __('Donate',WC_PSS_TXT) );
		}
		return $plugin_meta;
	}	    
}