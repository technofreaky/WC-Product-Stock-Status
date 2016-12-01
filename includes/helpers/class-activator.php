<?php 
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link https://wordpress.org/plugins/wc-product-stock-status/
 * @package WC Product Stock Status
 * @subpackage WC Product Stock Status/core
 * @since 1.0
 */
class WC_Product_Stock_Status_Activator {
	
    public function __construct() {
    }
	
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		require_once(WC_PSS_INC.'helpers/class-version-check.php');
		require_once(WC_PSS_INC.'helpers/class-dependencies.php');
		
		if(WC_Product_Stock_Status_Dependencies(WC_PSS_DEPEN)){
			WC_Product_Stock_Status_Version_Check::activation_check('3.7');	
		} else {
			if ( is_plugin_active(WC_PSS_FILE) ) { deactivate_plugins(WC_PSS_FILE);} 
			wp_die(wc_pstocks_dependency_message());
		}
	} 
 
}