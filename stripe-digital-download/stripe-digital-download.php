<?php

/*
Plugin Name: Stripe Digital Download
Plugin URI: http://pippinsplugins.com/stripe-digital-download-plugin
Description: Serve Digital Downloads Through the Stripe Payment Gateway
Author: Pippin Williamson
Author URI: http://pippinsplugins.com
Version: 1.0
*/

// plugin folder url
if(!defined('SDD_PLUGIN_URL')) {
	define('SDD_PLUGIN_URL', plugin_dir_url( __FILE__ ));
}
 
// plugin folder path
if(!defined('SDD_PLUGIN_DIR')) {
	define('SDD_PLUGIN_DIR', dirname(__FILE__));
}

global $wpdb;

global $sdd_payments_db_name;
$sdd_payments_db_name = $wpdb->prefix . 'stripe_payments';

global $sdd_payments_db_version;
$sdd_payments_db_version = 1.0;

$sdd_options = get_option('sdd_settings');

// function to create the DB / Options / Defaults					
function sdd_options_install() {
   	global $wpdb;
  	global $sdd_payments_db_name;
	global $sdd_payments_db_version;

	// create the RCP subscription level database table
	if($wpdb->get_var("show tables like '$sdd_payments_db_name'") != $sdd_payments_db_name) 
	{
		$sql = "CREATE TABLE " . $sdd_payments_db_name . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`email` tinytext NOT NULL,
		`amount` tinytext NOT NULL,
		`post_id` mediumint NOT NULL,
		`currency` tinytext NOT NULL,
		`date` datetime NOT NULL,
		`token` mediumtext NOT NULL,
		`key` longtext NOT NULL,
		UNIQUE KEY id (id)
		);";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
				
		add_option("sdd_payments_db_version", $sdd_payments_db_version);	
	}

}
// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'sdd_options_install');

/*************************************
* includes
*************************************/

include(SDD_PLUGIN_DIR . '/includes/metabox.php');
include(SDD_PLUGIN_DIR . '/includes/settings.php');
include(SDD_PLUGIN_DIR . '/includes/process-payment.php');
include(SDD_PLUGIN_DIR . '/includes/scripts.php');
include(SDD_PLUGIN_DIR . '/includes/purchase-form.php');

