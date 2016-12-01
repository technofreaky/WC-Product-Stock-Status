<?php 
/**
 * Plugin Main File
 *
 * @link https://wordpress.org/plugins/wc-product-stock-status/
 * @package WC Product Stock Status
 * @subpackage WC Product Stock Status/core
 * @since 1.0
 */
if ( ! defined( 'WPINC' ) ) { die; }
 
class WC_Product_Stock_Status {
	public $version = '1.0';
	public $plugin_vars = array();
	
	protected static $_instance = null; # Required Plugin Class Instance
    protected static $functions = null; # Required Plugin Class Instance
	protected static $admin = null;     # Required Plugin Class Instance
	protected static $settings = null;  # Required Plugin Class Instance

    /**
     * Creates or returns an instance of this class.
     */
    public static function get_instance() {
        if ( null == self::$_instance ) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    /**
     * Class Constructor
     */
    public function __construct() {
        $this->define_constant();
        $this->load_required_files();
        $this->init_class();
        add_action('plugins_loaded', array( $this, 'after_plugins_loaded' ));
        add_filter('load_textdomain_mofile',  array( $this, 'load_plugin_mo_files' ), 10, 2);
    }
	
	/**
	 * Throw error on object clone.
	 *
	 * Cloning instances of the class is forbidden.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cloning instances of the class is forbidden.', WC_PSS_TXT), WC_PSS_V );
	}	

	/**
	 * Disable unserializing of the class
	 *
	 * Unserializing instances of the class is forbidden.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of the class is forbidden.',WC_PSS_TXT), WC_PSS_V);
	}

    /**
     * Loads Required Plugins For Plugin
     */
    private function load_required_files(){
       $this->load_files(WC_PSS_INC.'class-*.php');
	   $this->load_files(WC_PSS_SETTINGS.'class-wp-*.php');
        
       if(wc_pstocks_is_request('admin')){
           $this->load_files(WC_PSS_ADMIN.'class-*.php');
       } 

        do_action('wc_pstocks_before_addons_load');
    }

    
    /**
     * Inits loaded Class
     */
    private function init_class(){
        do_action('wc_pstocks_before_init');
        self::$functions = new WC_Product_Stock_Status_Functions; 
            
        if(wc_pstocks_is_request('admin')){
            self::$admin = new WC_Product_Stock_Status_Admin;
        }
        
        $this->stock_base = new Woo_Stock_Base(); 
        $this->Woo_Stock_Setting = new Woo_Stock_Setting();
		$this->stock_product = new Woo_Stock_Product();
        do_action('wc_pstocks_after_init');
    }
    
	# Returns Plugin's Functions Instance
	public function func(){
		return self::$functions;
	}
	
	# Returns Plugin's Settings Instance
	public function settings(){
		return self::$settings;
	}
	
	# Returns Plugin's Admin Instance
	public function admin(){
		return self::$admin;
	}
    
    /**
     * Loads Files Based On Give Path & regex
     */
    protected function load_files($path,$type = 'require'){
        foreach( glob( $path ) as $files ){
            if($type == 'require'){ require_once( $files ); } 
			else if($type == 'include'){ include_once( $files ); }
        } 
    }
    
    /**
     * Set Plugin Text Domain
     */
    public function after_plugins_loaded(){
        load_plugin_textdomain(WC_PSS_TXT, false, WC_PSS_LANGUAGE_PATH );
    }
    
    /**
     * load translated mo file based on wp settings
     */
    public function load_plugin_mo_files($mofile, $domain) {
        if (WC_PSS_TXT === $domain)
            return WC_PSS_LANGUAGE_PATH.'/'.get_locale().'.mo';

        return $mofile;
    }
    
    /**
     * Define Required Constant
     */
    private function define_constant(){
        $this->define('WC_PSS_NAME', 'WC Product Stock Status'); # Plugin Name
        $this->define('WC_PSS_SLUG', 'wc-product-stock-status'); # Plugin Slug
        $this->define('WC_PSS_TXT',  'wc-product-stock-status'); #plugin lang Domain
		$this->define('WC_PSS_DB', 'wc_pstocks_');
		$this->define('WC_PSS_V',$this->version); # Plugin Version
		
		$this->define('WC_PSS_LANGUAGE_PATH',WC_PSS_PATH.'languages'); # Plugin Language Folder
		$this->define('WC_PSS_ADMIN',WC_PSS_INC.'admin/'); # Plugin Admin Folder
		$this->define('WC_PSS_SETTINGS',WC_PSS_ADMIN.'settings_framework/'); # Plugin Settings Folder

        $this->define('WC_PSS_URL',plugins_url('', __FILE__ ).'/');  # Plugin URL
		$this->define('WC_PSS_CSS',WC_PSS_URL.'includes/css/'); # Plugin CSS URL
		$this->define('WC_PSS_IMG',WC_PSS_URL.'includes/img/'); # Plugin IMG URL
		$this->define('WC_PSS_JS',WC_PSS_URL.'includes/js/'); # Plugin JS URL
        
    }
	
    /**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
    protected function define($key,$value){
        if(!defined($key)){
            define($key,$value);
        }
    }
    
}