<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$wpsc_appearance_login_form = isset($_POST) && isset($_POST['get_login_form']) ? $wpscfunction->sanitize_array($_POST['get_login_form']) : array();

update_option('wpsc_appearance_login_form',$wpsc_appearance_login_form);
	
do_action('wpsc_set_appearance_login_form');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','supportcandy').'" }';