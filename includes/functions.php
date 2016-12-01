<?php
/**
 * Common Plugin Functions
 * 
 * @link https://wordpress.org/plugins/wc-product-stock-status/
 * @package WC Product Stock Status
 * @subpackage WC Product Stock Status/core
 * @since 1.0
 */
if ( ! defined( 'WPINC' ) ) { die; }


global $wc_pstocks_db_settins_values, $wc_pstocks_vars;
$wc_pstocks_db_settins_values = array();
$wc_pstocks_vars = array();



if(!function_exists('wc_pstocks_vars')){
    function wc_pstocks_vars($key,$values = false){
        global $wc_pstocks_vars;
        if(isset($wc_pstocks_vars[$key])){ 
            return $wc_pstocks_vars[$key]; 
        }
        return $values;
    }
}
if(!function_exists('wc_pstocks_add_vars')){
    function wc_pstocks_add_vars($key,$values){
        global $wc_pstocks_vars;
        if(! isset($wc_pstocks_vars[$key])){ 
            $wc_pstocks_vars[$key] = $values; 
            return true; 
        }
        return false;
    }
}
if(!function_exists('wc_pstocks_remove_vars')){
    function wc_pstocks_remove_vars($key){
        global $wc_pstocks_vars;
        if(isset($wc_pstocks_vars[$key])){ 
            unset($wc_pstocks_vars[$key]);
            return true; 
        }
        return false;
    }
}


if(!function_exists('wc_pstocks_option')){
	function wc_pstocks_option($key = '',$defaults = false){ 
        $value = get_option(WC_PSS_DB.$key,true);
		if(!empty($value)){
			return $value;
		} 
		
		return $defaults;
	}
}



if(!function_exists('wc_pstocks_is_request')){
    /**
	 * What type of request is this?
	 * string $type ajax, frontend or admin
	 * @return bool
	 */
    function wc_pstocks_is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();
            case 'ajax' :
                return defined( 'DOING_AJAX' );
            case 'cron' :
                return defined( 'DOING_CRON' );
            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }
}

if(!function_exists('wc_pstocks_current_screen')){
    /**
     * Gets Current Screen ID from wordpress
     * @return string [Current Screen ID]
     */
    function wc_pstocks_current_screen(){
       $screen =  get_current_screen();
       return $screen->id;
    }
}


if(!function_exists('wc_pstocks_is_screen')){
    function wc_pstocks_is_screen($check_screen = '',$current_screen = ''){
        if(empty($check_screen)) {$check_screen = wc_pstocks_get_screen_ids(); }
        if(empty($current_screen)) {$current_screen = wc_pstocks_current_screen(); }
        
        if(is_array($check_screen)){
            if(in_array($current_screen , $check_screen)){
                return true;
            }
        }
        
        if(is_string($check_screen)){
            if($check_screen == $current_screen){
                return true;
            }
        }
        return false;
    }
}


if(!function_exists('wc_pstocks_get_screen_ids')){
    /**
     * Returns Predefined Screen IDS
     * @return [Array] 
     */
    function wc_pstocks_get_screen_ids(){
        $screen_ids = array();
        $screen_ids[] = 'woocommerce_page_wc-product-stock-status-settings';
        $screen_ids[] = wc_pstocks_vars('settings_page');
        return $screen_ids;
    }
}

if(!function_exists('wc_pstocks_dependency_message')){
	function wc_pstocks_dependency_message(){
		$text = __( WC_PSS_NAME . ' requires <b> WooCommerce </b> To Be Installed..  <br/> <i>Plugin Deactivated</i> ', WC_PSS_TXT);
		return $text;
	}
}

if(!function_exists('wc_pstocks_get_template')){
	function wc_pstocks_get_template($name,$args = array(),$template_base = '',$remote_template = ''){
        if(empty($template_base)){$template_base = WC_PSS_PATH.'templates/';}
        if(empty($remote_template)){$remote_template = 'woocommerce/';}
		wc_get_template( $name, $args ,$remote_template,  $template_base);
	}
}

if(!function_exists('wc_pstocks_settings_products_json')){
    function wc_pstocks_settings_products_json($ids){
        $json_ids    = array();
        if(!empty($ids)){
            $ids = explode(',',$ids);
            foreach ( $ids as $product_id ) {
                $product = wc_get_product( $product_id );
                $json_ids[ $product_id ] = wp_kses_post( $product->get_formatted_name() );
            }   
        }
        return $json_ids;
    }
}

if(!function_exists('wc_pstocks_settings_get_categories')){
    function wc_pstocks_settings_get_categories($tax='product_cat'){
        $args = array();
        $args['hide_empty'] = false;
        $args['number'] = 0; 
        $args['pad_counts'] = true; 
        $args['update_term_meta_cache'] = false;
        $terms = get_terms($tax,$args);
        $output = array();
        
        foreach($terms as $term){
            $output[$term->term_id] = $term->name .' ('.$term->count.') ';
        }
        
        return $output; 
    }
}

if(!function_exists('wc_pstocks_settings_page_link')){
    function wc_pstocks_settings_page_link($tab = '',$section = ''){
        $settings_url = admin_url('admin.php?page='.WC_PSS_SLUG.'-settings');
        if(!empty($tab)){$settings_url .= '&tab='.$tab;}
        if(!empty($section)){$settings_url .= '#'.$section;}
        return $settings_url;
    }   
}

if(!function_exists('wc_pstocks_get_settings_sample')){
	/**
	 * Retunrs the sample array of the settings framework
	 * @param [string] [$type = 'page' | 'section' | 'field'] [[Description]]
	 */
	function wc_pstocks_get_settings_sample($type = 'page'){
		$return = array();
		
		if($type == 'page'){
			$return = array( 
				'id'=>'settings_general', 
				'slug'=>'general', 
				'title'=>__('General',WC_PSS_TXT),
				'multiform' => 'false / true',
				'submit' => array( 
					'text' => __('Save Changes',WC_PSS_TXT), 
					'type' => 'primary / secondary / delete', 
					'name' => 'submit'
				)
			);
			
		} else if($type == 'section'){
			$return['page_id'][] = array(
				'id'=>'general',
				'title'=>'general', 
				'desc' => 'general',
				'submit' => array(
					'text' => __('Save Changes',WC_PSS_TXT), 
					'type' => 'primary / secondary / delete', 
					'name' => 'submit'
				)
			);
		} else if($type == 'field'){
			$return['page_id']['section_id'][] = array(
				'id' => '',
				'type' => 'text, textarea, checkbox, multicheckbox, radio, select, field_row, extra',
				'label' => '',
				'options' => 'Only required for type radio, select, multicheckbox [KEY Value Pair]',
				'desc' => '',
				'size' => '',
				'default' => '',
				'attr' => "Key Value Pair",
				'before' => 'Content before the field label',
				'after' => 'Content after the field label',
				'content' => 'Content used for type extra' ,
				'text_type' => "Set the type for text input field (e.g. 'hidden' )",
			);
		}
	}
}

if(!function_exists('wc_pstocks_check_active_addon')){
	function wc_pstocks_check_active_addon($slug){
		$addons = wc_pstocks_get_active_addons();
		if(in_array($slug,$addons)){ return true; }
		return false;
	}
}

if(!function_exists('wc_pstocks_get_active_addons')){
	/**
	 * Returns Active Addons List
	 * @return [[Type]] [[Description]]
	 */
	function wc_pstocks_get_active_addons(){
		$addons = get_option(WC_PSS_DB.'active_addons',array()); 
		return $addons;
	}
}

if(!function_exists('wc_pstocks_update_active_addons')){
	/**
	 * Returns Active Addons List
	 * @return [[Type]] [[Description]]
	 */
	function wc_pstocks_update_active_addons($addons){
		update_option(WC_PSS_DB.'active_addons',$addons); 
		return true;
	}
}

if(!function_exists('wc_pstocks_activate_addon')){
	function wc_pstocks_activate_addon($slug){
		$active_list = wc_pstocks_get_active_addons();
		if(!in_array($slug,$active_list)){
			$active_list[] = $slug;
			wc_pstocks_update_active_addons($active_list);
			return true;
		}
		return false;
	}
}

if(!function_exists('wc_pstocks_deactivate_addon')){
	function wc_pstocks_deactivate_addon($slug){
		$active_list = wc_pstocks_get_active_addons();
		if(in_array($slug,$active_list)){
			$key = array_search($slug, $active_list);
			unset($active_list[$key]);
			wc_pstocks_update_active_addons($active_list);
			return true;
		}
		return false;
	}
}

if(!function_exists('wc_pstocks_admin_notice')){
    function wc_pstocks_admin_notice($msg , $type = 'updated'){
        $notice = ' <div class="'.$type.' settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p>'.$msg.'</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        return $notice;
    }
}

if(!function_exists('wc_pstocks_remove_notice')){
    function wc_pstocks_remove_notice($id){
        WC_Product_Stock_Status_Admin_Notices::getInstance()->deleteNotice($id);
        return true;
    }
}

if(!function_exists('wc_pstocks_notice')){
    function wc_pstocks_notice( $message, $type = 'update',$args = array()) {
        $notice = '';
        $defaults = array('times' => 1,'screen' => array(),'users' => array(), 'wraper' => true,'id'=>'');    
        $args = wp_parse_args( $args, $defaults );
        extract($args);
        
        if($type == 'error'){
            $notice = new WC_Product_Stock_Status_Admin_Error_Notice($message,$id,$times, $screen, $users);
        }
        
        if($type == 'update'){
            $notice = new WC_Product_Stock_Status_Admin_Updated_Notice($message,$id,$times, $screen, $users);
        }
        
        if($type == 'upgrade'){
            $notice = new WC_Product_Stock_Status_Admin_UpdateNag_Notice($message,$id,$times, $screen, $users);
        } 
        
        $msgID = $notice->getId();
        $message = str_replace('$msgID$',$msgID,$message);
        $notice->setContent($message);
        $notice->setWrapper($wraper);
        WC_Product_Stock_Status_Admin_Notices::getInstance()->addNotice($notice);
    }
}

if(!function_exists('wc_pstocks_admin_error')){
    function wc_pstocks_admin_error( $message,$times = 1, $id, $screen = array(),$args = array()) {
        $args['id'] = $id;
        $args['times'] = $times;
        $args['screen'] = $screen;
        wc_pstocks_notice($message,'error',$args);
    }
}

if(!function_exists('wc_pstocks_admin_update')){
    function wc_pstocks_admin_update( $message,$times = 1, $id, $screen = array(),$args = array()) {
        $args['id'] = $id;
        $args['times'] = $times;
        $args['screen'] = $screen;
        wc_pstocks_notice($message,'update',$args);
    }
}

if(!function_exists('wc_pstocks_admin_upgrade')){
    function wc_pstocks_admin_upgrade( $message,$times = 1, $id, $screen = array(),$args = array()) {
        $args['id'] = $id;
        $args['times'] = $times;
        $args['screen'] = $screen;
        wc_pstocks_notice($message,'upgrade',$args);
    }
}


if(!function_exists('wc_pstocks_remove_link')){
    function wc_pstocks_remove_link($attributes = '',$msgID = '$msgID$', $text = 'Remove Notice') {
        if(!empty($msgID)){
            $removeKey = WC_PSS_DB.'MSG';
            $url = admin_url().'?'.$removeKey.'='.$msgID ;
            //$url = wp_nonce_url($url, 'WCQDREMOVEMSG');
            $url = urldecode($url);
            $tag = '<a '.$attributes.' href="'.$url.'">'.__($text,WC_PBP_TXT).'</a>';
            return $tag;
        }
    }
}

if(!function_exists('wc_pstocks_get_ajax_overlay')){
	/**
	 * Prints WC PBP Ajax Loading Code
	 */
	function wc_pstocks_get_ajax_overlay($echo = true){
		$return = '<div class="wc_pstocks_ajax_overlay">
		<div class="wc_pstocks_sk-folding-cube">
		<div class="wc_pstocks_sk-cube1 wc_pstocks_sk-cube"></div>
		<div class="wc_pstocks_sk-cube2 wc_pstocks_sk-cube"></div>
		<div class="wc_pstocks_sk-cube4 wc_pstocks_sk-cube"></div>
		<div class="wc_pstocks_sk-cube3 wc_pstocks_sk-cube"></div>
		</div>
		</div>';
		if($echo){echo $return;}
		else{return $return;}
	}
}