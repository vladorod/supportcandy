<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpscfunction,$wpdb;

$ticket_id_data  = isset($_POST['ticket_id']) ? (sanitize_text_field($_POST['ticket_id'])) : '' ;
$ticket_ids = explode(',', $ticket_id_data);

foreach ($ticket_ids as $ticket_id){

	$meta_value =array(
		'active' => '1'
	);
	$wpdb->update($wpdb->prefix.'wpsc_ticket', $meta_value, array('id'=>$ticket_id));
	do_action('wpsc_restore_ticket',$ticket_id);
}
