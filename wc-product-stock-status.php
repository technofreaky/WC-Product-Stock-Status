<?php 
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wordpress.org/plugins/wc-product-stock-status/
 * @since             1.0
 * @package           WC Product Stock Status
 *
 * @wordpress-plugin
 * Plugin Name:       WC Product Stock Status
 * Plugin URI:        https://wordpress.org/plugins/wc-product-stock-status/
 * Description:       Display Product Stock Infromation in frontend. use <code> [wc_product_stock_variation]</code>  to display in detailed stock info for variation product.
 * Version:           1.0
 * Author:            Varun Sridharan
 * Author URI:        http://varunsridharan.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-product-stock-status
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) { die; }
 
define('WC_PSS_FILE',plugin_basename( __FILE__ ));
define('WC_PSS_PATH',plugin_dir_path( __FILE__ )); # Plugin DIR
define('WC_PSS_INC',WC_PSS_PATH.'includes/'); # Plugin INC Folder
define('WC_PSS_DEPEN','woocommerce/woocommerce.php');

register_activation_hook( __FILE__, 'wc_pstocks_activate_plugin' );
register_deactivation_hook( __FILE__, 'wc_pstocks_deactivate_plugin' );
register_deactivation_hook( WC_PSS_DEPEN, 'wc_pstocks_dependency_deactivate' );



/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function wc_pstocks_activate_plugin() {
	require_once(WC_PSS_INC.'helpers/class-activator.php');
	WC_Product_Stock_Status_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function wc_pstocks_deactivate_plugin() {
	require_once(WC_PSS_INC.'helpers/class-deactivator.php');
	WC_Product_Stock_Status_Deactivator::deactivate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function wc_pstocks_dependency_deactivate() {
	require_once(WC_PSS_INC.'helpers/class-deactivator.php');
	WC_Product_Stock_Status_Deactivator::dependency_deactivate();
}



require_once(WC_PSS_INC.'functions.php');
require_once(WC_PSS_PATH.'bootstrap.php');

if(!function_exists('WC_Product_Stock_Status')){
    function WC_Product_Stock_Status(){
        return WC_Product_Stock_Status::get_instance();
    }
}
WC_Product_Stock_Status();