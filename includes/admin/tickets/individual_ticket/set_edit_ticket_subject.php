<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wpscfunction,$wpdb;
if (!($current_user->ID && $current_user->has_cap('wpsc_agent'))) {
 exit;
}

$ticket_id    = isset($_POST['ticket_id']) ? sanitize_text_field($_POST['ticket_id']) : '' ;
$subject    = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';

$values = array(
	'ticket_subject' => $subject
);

$wpdb->update($wpdb->prefix.'wpsc_ticket', $values, array('id'=>$ticket_id));
?>