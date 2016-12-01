<?php
/**
 * WooCommerce General Settings
 *
 * @link https://wordpress.org/plugins/wc-product-stock-status/
 * @package WC Product Stock Status
 * @subpackage WC Product Stock Status/Admin/WC_Settings
 * @since 1.0
 */
if ( ! defined( 'WPINC' ) ) { die; }

if ( ! class_exists( 'WooCommerce_Advanced_Settings' ) ) :

/**
 * WC_Admin_Settings_General
 */
class WooCommerce_Advanced_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'wc_product_stock_status';
		$this->label = __( 'WC Product Stock Status', WC_PSS_TXT );

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
        add_filter( 'woocommerce_sections_'.$this->id,      array( $this, 'output_sections' ));
        add_filter( 'woocommerce_settings_'.$this->id,      array( $this, 'output_settings' )); 
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
	}

    /**
     * Get sections
     *
     * @return array
     */
    public function get_sections() {
        $sections = array(
            ''            => __( 'General', WC_PSS_TXT ),
            'product_display'     => __( 'Display Settings',WC_PSS_TXT ), 
        );


        return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
    }   
    
    
    public function output_settings(){
        global $current_section;
        $settings = $this->get_settings( $current_section ); 
        WC_Admin_Settings::output_fields( $settings );
    }    
    
    
	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings($section = null) { 
        $settings = array();
        
        if($section == 'product_display'){
            $settings = array(
                array( 
                    'title' => __( 'Product Archive Page', WC_PSS_TXT ), 
                    'type' => 'title', 
                    'desc' => __('Shows Stock Status in Shop Page, Search Page or any page which has product listing',WC_PSS_TXT), 
                    'id' => 'wc_product_stock_status_product_archive_start' 
                ), 

                array(
                    'title'    => __( 'Stock Position', WC_PSS_TXT ),
                    'desc'     => __( 'Where to show in product archive page', WC_PSS_TXT ),
                    'id'       => WC_PSS_DB.'archive_position', 
                    'type'     => 'select',  
                    'options'  => array( 
                        '' => __("Hide ",WC_PSS_TXT),
                        'before_image' => __('Before Product Image',WC_PSS_TXT), 'before_title' => __('Before Product Title',WC_PSS_TXT), 'before_price' => __('Before Product Price',WC_PSS_TXT),
                        'after_price'  => __('After Product Price',WC_PSS_TXT),
                    )
                ),


                array(
                    'title'    => __( 'Include Variation Product', WC_PSS_TXT ),
                    'desc'     => __( 'Show Stock Status for Variation Product ', WC_PSS_TXT ),
                    'id'       => WC_PSS_DB.'archive_variation_status', 
                    'type'     => 'checkbox',  
                    'default' => 'on'
                ),

                array( 'type' => 'sectionend', 'id' => 'wc_product_stock_status_product_archive_start'),
                
                
                array( 
                    'title' => __( 'Variation Stock Status Table', WC_PSS_TXT ), 
                    'type' => 'title', 
                    'desc' => __('if this option enabled it will show a table of variation products with stock status as a template. <br/> you can also use this shortcode <code>[wc_product_stock_variation]</code> to display variable product stocks status<br/>
                    provide variable product id by <code>[wc_product_stock_variation id="22"]</code>',WC_PSS_TXT), 
                    'id' => 'wc_product_stock_status_variable_product_archive_start' 
                ), 

                array(
                    'title'    => __( 'Status Table Position', WC_PSS_TXT ),
                    'desc'     => __( 'Where to show  table in single product page', WC_PSS_TXT ),
                    'id'       => WC_PSS_DB.'variable_position', 
                    'type'     => 'select',  
                    'options'  => array( 
                        '' => __("Hide ",WC_PSS_TXT),
                        'before_title' => __('Before Product Title',WC_PSS_TXT),
                        'after_title' => __('After Product Title',WC_PSS_TXT),
                        'before_excerpt' => __('Before Product Excerpt',WC_PSS_TXT),
                        'after_excerpt' => __('After Product Excerpt',WC_PSS_TXT),
                        'before_addtocart' => __('Before Product Add To Cart',WC_PSS_TXT),
                        'after_addtocart' => __('After Product Add To Cart',WC_PSS_TXT), 
                    )
                ),
                
                
                array(
                    'title'    => __( 'Variation Title', WC_PSS_TXT ),
                    'desc'     => __( 'Title format to show in variation table. <br/>
                    
                    <strong>Usabel Tags : </strong> <code>%formatted_name%</code>, <code>%name%</code>, <code>%sku%</code>, <code>%id%</code>
                    
                    ', WC_PSS_TXT ),
                    'id'       => WC_PSS_DB.'variable_title', 
                    'type'     => 'text',   
                ),
 
                array( 'type' => 'sectionend', 'id' => 'wc_product_stock_status_variable_product_archive_start'),

            );    
        } 
        
        $settings = apply_filters('wc_product_stock_status_fields',$settings,$section);
        
        return $settings;
	}
 

	/**
	 * Save settings
	 */
	public function save() {
        global $current_section;
        $settings = $this->get_settings( $current_section );
        WC_Admin_Settings::save_fields( $settings ); 
	}

}

endif;

return new WooCommerce_Advanced_Settings();