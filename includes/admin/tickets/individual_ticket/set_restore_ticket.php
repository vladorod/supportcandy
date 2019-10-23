<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wpscfunction,$wpdb;
if (!($current_user->ID && $current_user->has_cap('wpsc_agent'))) {
  exit;
}

$ticket_id   = isset($_POST['ticket_id']) ? sanitize_text_field($_POST['ticket_id']) : 0 ;
if(!$ticket_id) die();

$meta_value =array(
	'active' => '1'
);

$wpdb->update($wpdb->prefix.'wpsc_ticket', $meta_value, array('id'=>$ticket_id));

do_action('wpsc_restore_ticket',$ticket_id);
