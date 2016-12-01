<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
* WC Stock Status Setting Tab functions
*/

class Woo_Stock_Setting extends Woo_Stock_Base {
	
	public function __construct() {  
		// stock status fields
		//add_action( 'woocommerce_settings_tabs_wc_stock_list_rename',array( $this ,'settings_tab' ));
//
//		// save stock status fields value
//		add_action( 'woocommerce_update_options_wc_stock_list_rename',array( $this ,'update_settings' ));

        add_filter('wc_product_stock_status_fields',array($this,'add_settings'),10,2);
		// stock status color css
		add_action( 'wp_head',array( $this,'woo_custom_stock_status_color' ) );
	}
	 
	
	/**
	 * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
	 *
	 * @uses woocommerce_admin_fields()
	 * @uses $this->get_settings()
	 */
	public function settings_tab() {
		woocommerce_admin_fields( $this->get_settings() );
	}
	
	/**
	 * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
	 *
	 * @uses woocommerce_update_options()
	 * @uses $this->get_settings()
	 */
	public function update_settings() {
		woocommerce_update_options( $this->get_settings() );
	}
	
    public function add_settings($settings,$section){ 
        if($section == ''){
            
            $set = $this->get_settings();
            $settings = array_merge($settings,$set); 
        }
        return $settings;
    }
    
	/**
	 * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
	 *
	 * @return array Array of settings for @see woocommerce_admin_fields() function.
	 */
	public function get_settings() {
		
		$settings['section_title'] = array(
				'name'     => __( 'Custom Stock Status', 'woo-custom-stock-status' ),
				'type'     => 'title',
				'desc'     => '',
				'id'       => 'wc_wc_stock_list_rename_section_title'
			);
		
		foreach($this->status_array as $status=>$label){
			$settings[$status] =  array(
				'name' => __( $label, 'woo-custom-stock-status' ),
				'type' => 'text',
				'desc'     => '',
				'id'   => 'wc_slr_'.$status,
				'class' => 'large-text'
			);
		}

		foreach($this->status_color_array as $status => $options ){
			$settings[$status] =  array(
				'name' 		=> __( $options['label'], 'woo-custom-stock-status' ),
				'desc'     	=> '',
				'id'   		=> 'wc_slr_'.$status,
				'type'     	=> 'color',
				'css'      	=> 'width:6em;',
				'default'  	=> $options['default'],
				'autoload' 	=> false,
				'desc_tip' 	=> true
			);
		}
		
		$settings['section_end'] = array(
				 'type' => 'sectionend',
				 'id' => 'wc_wc_stock_list_rename_section_end'
			);
		return $settings;
	}

	/**
	 * load custom stock color css in head
	 */
	public function woo_custom_stock_status_color() {
		$css = '<style>';
		foreach($this->status_color_array as $status_color => $options){
			$status_color_code = (get_option('wc_slr_'.$status_color,$options['default'])=='') ? $options['default'] : get_option('wc_slr_'.$status_color,$options['default']);
			$css .= sprintf('.woocommerce div.product .%s { color: %s }',$status_color,$status_color_code);//For details page
			$css .= sprintf('ul.products .%s { color: %s }',$status_color,$status_color_code);//For listing page
		}
		$css .= '</style><!-- woo-custom-stock-status-color-css -->';
		echo $css;
	}
}