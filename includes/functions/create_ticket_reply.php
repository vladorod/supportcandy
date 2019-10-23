<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;

$ticket_id     = $args['ticket_id'];
$old_status_id = $wpscfunction->get_ticket_fields($ticket_id,'ticket_status');
$reply_body    = $args['ticket_description'];

$user      = get_user_by('email',$args['customer_email']);
$signature = $user ? get_user_meta($user->ID,'wpsc_agent_signature',true) : '';

if($signature){
	$signature= stripcslashes(htmlspecialchars_decode($signature, ENT_QUOTES));
	$reply_body.= $signature;
}

$reply_args = array(
  'ticket_id'          => $args['ticket_id'],
  'customer_name'      => $args['customer_name'],
  'customer_email'     => $args['customer_email'],
  'thread_type'        => 'reply',
  'reply_body'         => $wpscfunction->replace_macro($reply_body,$ticket_id),
  'attachments'        => $args['desc_attachment'],
);

$reply_attachment = isset($args['desc_attachment']) ? $args['desc_attachment'] : array();
$attachments = array();
foreach ($reply_attachment as $key => $value) {
	$attachment_id = intval($value);
	$attachments[] = $attachment_id;
	update_term_meta ($attachment_id, 'active', '1');
}

$thread_id=$wpscfunction->submit_ticket_thread($reply_args);

$ticket_status_after_customer_reply = get_option('wpsc_ticket_status_after_customer_reply');
$ticket_status_after_agent_reply    = get_option('wpsc_ticket_status_after_agent_reply');

if( $user && $user->has_cap('wpsc_agent') && $ticket_status_after_agent_reply!=$old_status_id ){
	
	$wpscfunction->change_status( $ticket_id, $ticket_status_after_agent_reply);
	
} else if($ticket_status_after_customer_reply!=$old_status_id) {
	
	$wpscfunction->change_status( $ticket_id, $ticket_status_after_customer_reply);
	
}

do_action( 'wpsc_after_submit_reply', $thread_id, $ticket_id );
