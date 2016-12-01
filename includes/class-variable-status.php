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

class WC_Product_Stock_Status_Variable_Functions {

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
        $show_pos = wc_pstocks_option('variable_position'); 
        $key = '';
        $pos = '';
        if($show_pos == 'before_title'){$key = 'woocommerce_template_single_title'; $pos = 1;}
		else if($show_pos == 'after_title'){$key = 'woocommerce_template_single_title'; $pos = 6;}
		else if($show_pos == 'before_excerpt'){$key = 'woocommerce_template_single_excerpt'; $pos = 19;}
		else if($show_pos == 'after_excerpt'){$key = 'woocommerce_template_single_excerpt'; $pos = 21;}
		else if($show_pos == 'before_addtocart'){$key = 'woocommerce_template_single_add_to_cart'; $pos = 29;}
		else if($show_pos == 'after_addtocart'){$key = 'woocommerce_template_single_add_to_cart'; $pos = 31;}
        
        add_action('woocommerce_single_product_summary',array($this,'print_stock_status'),$pos);
    }
    
    public function print_stock_status(){
        global $product; 
        
        if($product->is_type( 'variable' ) ) {
            $childrens = $product->get_children();
            $values = array();
            foreach($childrens as $child){
                $prod = wc_get_product($child);
                $search_replace = array();
                $search_replace['%formatted_name%'] = $prod->get_formatted_name();
                $search_replace['%name%'] = $prod->get_formatted_variation_attributes();
                $search_replace['%sku%'] = $prod->get_sku();
                $search_replace['%id%'] = $prod->get_variation_id();
                
                $format = wc_pstocks_option('variable_title');
                $format = str_replace(array_keys($search_replace),array_values($search_replace),$format);
                $status = $prod->get_availability();;
                $status['title'] = $format;
                $status['ID'] = $child;
                $status['formatted_name'] = $prod->get_formatted_name();
                $status['name'] = $prod->get_formatted_variation_attributes();
                $status['sku'] = $prod->get_sku();
                $status['id'] = $prod->get_variation_id(); 
                $values[$child] = $status; 
            } 
            
            $this->generate_table($values);
        } 
    }
    
    public function generate_table($values){
        wc_pstocks_get_template('stock-variation-table.php',array("args"=>$values));
    }
}

return new WC_Product_Stock_Status_Variable_Functions;