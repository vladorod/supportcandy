<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $current_user,$post;

if (!($current_user->ID && $current_user->has_cap('wpsc_agent'))) {exit;}

$wpsc_agent_signature = isset($_POST) && isset($_POST['wpsc_agent_signature']) ? htmlspecialchars($_POST['wpsc_agent_signature']) : '';
update_user_meta($current_user->ID,'wpsc_agent_signature', sanitize_text_field($wpsc_agent_signature));

?>
