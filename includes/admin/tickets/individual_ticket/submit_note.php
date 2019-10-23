<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpscfunction, $current_user;

// Get tiket id
$ticket_id = isset($_POST['ticket_id']) ? intval($_POST['ticket_id']) : 0;
if(!$ticket_id) die();

// Check nonce
if( !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'],$ticket_id) ){
    die(__('Cheating huh?', 'supportcandy'));
}

if ($current_user->ID) {
  $customer_name  = $current_user->display_name;
  $customer_email = $current_user->user_email;
}
else {
	$customer_name  = isset($_POST['customer_name']) ? sanitize_text_field($_POST['customer_name']) : '';
  $customer_email = isset($_POST['customer_email']) ? sanitize_text_field($_POST['customer_email']) : '';
}

// Get reply body
$reply_body = isset($_POST['reply_body']) ? wp_kses_post(htmlspecialchars_decode($_POST['reply_body'], ENT_QUOTES)) : '';

// Get reply attachments
$description_attachment = isset($_POST['desc_attachment']) ? $_POST['desc_attachment'] : array();
$attachments = array();
foreach ($description_attachment as $key => $value) {
	$attachment_id = intval($value);
	$attachments[] = $attachment_id;
	update_term_meta ($attachment_id, 'active', '1');
}

$ip_address	= isset($_SERVER) && isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'';
if (strlen($ip_address)>28) {
	$ip_address = '';
} 
$os_platform = $wpscfunction->get_os();
$browser		 = $wpscfunction->get_browser();
// Prepare arguments
$args = array(
  'ticket_id'      => $ticket_id,
  'reply_body'     => $wpscfunction->replace_macro($reply_body,$ticket_id),
  'customer_name'  => $customer_name,
  'customer_email' => $customer_email,
  'attachments'    => $attachments,
  'thread_type'    => 'note',
	'ip_address'		 => $ip_address,
	'reply_source'	 => 'browser',
	'os'						 => $os_platform,
	'browser'				 => $browser
);
$args = apply_filters( 'wpsc_thread_args', $args );
$thread_id = $wpscfunction->submit_ticket_thread($args);

do_action( 'wpsc_after_submit_note', $thread_id, $ticket_id );
