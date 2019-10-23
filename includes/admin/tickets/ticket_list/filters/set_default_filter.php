<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;

if (!$current_user->ID) die();

$label_key = isset($_POST['label']) ? sanitize_text_field($_POST['label']) : '';
if (!$label_key) die();

$labels = $wpscfunction->get_ticket_filter_labels();
if ( !( ($current_user->has_cap('wpsc_agent') && $labels[$label_key]['visibility']=='agent') || (!$current_user->has_cap('wpsc_agent') && $labels[$label_key]['visibility']=='customer') || $labels[$label_key]['visibility']=='both' ) ) die();

$filter = $wpscfunction->get_default_filter();

$filter['label'] = $label_key;

$filter['query'] = $wpscfunction->get_default_filter_query($label_key);

setcookie('wpsc_ticket_filter',json_encode($filter));
