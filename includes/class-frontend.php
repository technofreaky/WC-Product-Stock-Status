<?php
/**
 * Dependency Checker
 *
 * Checks if required Dependency plugin is enabled
 *
 * @link https://wordpress.org/plugins/wc-product-stock-status/
 * @package WC Product Stock Status
 * @subpackage WC Product Stock Status/FrontEnd
 * @since 1.0
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WC_Product_Stock_Status_Functions {

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
        $loop_post = wc_pstocks_option('archive_position','before_price');
        
        $loop_key = '';
        $loop_pos = ''; 
        
        if($loop_post == 'before_image'){$loop_key = 'woocommerce_before_shop_loop_item_title'; $loop_pos = 9;}
		else if($loop_post == 'before_title'){$loop_key = 'woocommerce_shop_loop_item_title'; $loop_pos = 9;}
		else if($loop_post == 'before_price'){$loop_key = 'woocommerce_after_shop_loop_item_title'; $loop_pos = 9;}
		else if($loop_post == 'after_price'){$loop_key = 'woocommerce_after_shop_loop_item_title'; $loop_pos = 11;} 
        
        add_action($loop_key,array($this,'print_stock_status'),$loop_pos);
    }
    
    public function print_stock_status(){
        global $product; 
        
        if($product->is_type( 'variable' ) ) {
            $availability = $product->get_availability();
            if(!empty($availability['availability'])){
                echo $availability['availability'];    
            } else {
                $childrens = $product->get_children(false);
                $stock = 0;
                $in_stock = null;
                foreach($childrens as $id){
                    $s = get_post_meta( $id, '_stock', true );
                    if(!empty($s)){
                        $stock = $stock + intval($s);
                    }
                }
                
                if($stock > 0){
                    $text = WC_Product_Stock_Status()->stock_base->woo_rename_stock_status($availability,$product,$stock,true,true);
                    $text = empty($text['availability']) ? '' : '<p class="stock '.esc_attr($text['class']).'">'.esc_html($text['availability']).'</p>'; 

                    echo $text;
                }
                
            }
            
        }
        
        
    }
}