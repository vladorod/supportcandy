<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$time = get_option('wpsc_garbage_collection_time' ,'');
$check_flag = false;

$now = time();
$ago = strtotime($time);
$diff = $now - $ago;
if($diff >= 86400){
	$check_flag = true;
}

if(!$check_flag){
	return;
}

$attachments = get_terms([
  'taxonomy'   => 'wpsc_attachment',
  'hide_empty' => false,
  'meta_query' => array(
    'relation' => 'AND',
    array(
      'key'     => 'active',
      'value'   =>  0,
      'compare' => '='
    ),
  ),
]);

if($attachments){
  foreach($attachments as $attachment){
    $updated_time   = get_term_meta($attachment->term_id,'time_uploaded',true);
		$file_name      = get_term_meta($attachment->term_id,'save_file_name',true);
		$wp_upload_dir  = wp_upload_dir();
		$file_path      = $wp_upload_dir['basedir'].'/'.'wpsc'.'/'.$file_name;
    $attachment_updated_time = strtotime($updated_time);
    $now  = time();
    $diff = $now - $attachment_updated_time;
    if($diff > 86400){
			wp_delete_term($attachment->term_id,'wpsc_attachment');
			if(file_exists($file_path)){
				unlink($file_path);
			}
    }
  }
}

update_option('wpsc_garbage_collection_time', date("Y-m-d H:i:s"));