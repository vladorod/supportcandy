<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpscfunction,$wpdb;

$ticket_id_data  = isset($_POST['ticket_id']) ? (sanitize_text_field($_POST['ticket_id'])) : '' ;
$ticket_ids = explode(',', $ticket_id_data);

foreach ($ticket_ids as $ticket_id ) {
	
	$wpdb->delete($wpdb->prefix.'wpsc_ticket', array( 'id' => $ticket_id));
	
	$args = array(
		'post_type'      => 'wpsc_ticket_thread',
		'post_status'    => array('publish','trash'),
		'posts_per_page' => -1,
		'meta_query'     => array(
			 array(
				'key'     => 'ticket_id',
				'value'   => $ticket_id,
				'compare' => '='
			),
		),
	);
	$ticket_threads = get_posts($args);
	if($ticket_threads) {
		foreach ($ticket_threads as $ticket_thread ) {
			 wp_delete_post($ticket_thread->ID,true);
		}
	}
}

