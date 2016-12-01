<?php 
if(isset($_REQUEST['change'])){
	$files_check = array();
	get_php_files(__DIR__);
	foreach ($files_check as $f){
		$file = file_get_contents($f);
		
		$file = str_replace('WooCommerce Plugin Boiler Plate','WC Product Stock Status',$file);
		$file = str_replace('woocommerce-plugin-boiler-plate','wc-product-stock-status',$file);
		$file = str_replace('WooCommerce_Plugin_Boiler_Plate','WC_Product_Stock_Status',$file);
		$file = str_replace('https://wordpress.org/plugins/woocommerce-plugin-boiler-plate/', 'https://wordpress.org/plugins/wc-product-stock-status/' , $file ); 
		$file = str_replace('[version]', '1.0' , $file ); 
		$file = str_replace('[package]', 'WC Product Stock Status' , $file ); 
		$file = str_replace('[plugin_name]', 'WC Product Stock Status' , $file ); 
		$file = str_replace('[plugin_url]', 'https://wordpress.org/plugins/wc-product-stock-status/' , $file ); 
		$file = str_replace('wc_pbp_','wc_pstocks_',$file);
		$file = str_replace('PLUGIN_FILE', 'WC_PSS_FILE' , $file);
		$file = str_replace('PLUGIN_PATH', 'WC_PSS_PATH' , $file);
		$file = str_replace('PLUGIN_INC', 'WC_PSS_INC' , $file);
		$file = str_replace('PLUGIN_DEPEN', 'WC_PSS_DEPEN' , $file);
		$file = str_replace('PLUGIN_NAME', 'WC_PSS_NAME' , $file);
		$file = str_replace('PLUGIN_SLUG', 'WC_PSS_SLUG' , $file);
		$file = str_replace('PLUGIN_TXT', 'WC_PSS_TXT' , $file);
		$file = str_replace('PLUGIN_DB', 'WC_PSS_DB' , $file);
		$file = str_replace('PLUGIN_V', 'WC_PSS_V' , $file);
		$file = str_replace('PLUGIN_LANGUAGE_PATH', 'WC_PSS_LANGUAGE_PATH' , $file);
		$file = str_replace('PLUGIN_ADMIN', 'WC_PSS_ADMIN' , $file);
		$file = str_replace('PLUGIN_SETTINGS', 'WC_PSS_SETTINGS' , $file);
		$file = str_replace('PLUGIN_URL', 'WC_PSS_URL' , $file);
		$file = str_replace('PLUGIN_CSS', 'WC_PSS_CSS' , $file);
		$file = str_replace('PLUGIN_IMG', 'WC_PSS_IMG' , $file);
		$file = str_replace('PLUGIN_JS', 'WC_PSS_JS' , $file);		
		
		file_put_contents($f,$file); 
	}
}

function get_php_files($dir = __DIR__){
	global $files_check;
	$files = scandir($dir); 
	foreach($files as $file) {
		if($file == '' || $file == '.' || $file == '..' ){continue;}
		if(is_dir($dir.'/'.$file)){
			get_php_files($dir.'/'.$file);
		} else {
			if(
                pathinfo($dir.'/'.$file, PATHINFO_EXTENSION) == 'php' || 
                pathinfo($dir.'/'.$file, PATHINFO_EXTENSION) == 'txt' || 
                pathinfo($dir.'/'.$file, PATHINFO_EXTENSION) == 'css' || 
                pathinfo($dir.'/'.$file, PATHINFO_EXTENSION) == 'js'  ||
                pathinfo($dir.'/'.$file, PATHINFO_EXTENSION) == 'pot'         
            ){
				if($file == 'generate.php'){continue;}
				$files_check[$file] = $dir.'/'.$file;
			}
		}
	}
}
?> 
[plugin_url] <br/> <br/>
[plugin_name] <br/> <br/>
[version] <br/> <br/>
[package] <br/> <br/>
WooCommerce Plugin Boiler Plate <br/> <br/>
WooCommerce_Plugin_Boiler_Plate  <br/> <br/>
woocommerce-plugin-boiler-plate <br/> <br/>
wc_pbp	 <br/> <br/>
wc-pbp <br/> <br/>
PLUGIN_FILE <br/> <br/>
PLUGIN_PATH <br/> <br/>
PLUGIN_INC <br/> <br/>
PLUGIN_DEPEN <br/> <br/>
PLUGIN_NAME <br/> <br/>
PLUGIN_SLUG <br/> <br/>
PLUGIN_TXT <br/> <br/>
PLUGIN_DB <br/> <br/>
PLUGIN_V <br/> <br/>
PLUGIN_LANGUAGE_PATH <br/> <br/>
PLUGIN_ADMIN <br/> <br/>
PLUGIN_SETTINGS <br/> <br/>
PLUGIN_ADDON <br/> <br/>
PLUGIN_URL <br/> <br/>
PLUGIN_CSS <br/> <br/>
PLUGIN_IMG <br/> <br/>
PLUGIN_JS <br/> <br/>
PLUGIN_ADDON_URL <br/> <br/>
PLUGIN_ADDON_CSS <br/> <br/>
PLUGIN_ADDON_IMG <br/> <br/>
PLUGIN_ADDON_JS <br/> <br/>