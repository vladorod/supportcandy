<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpscfunction;
$ticket_notes_history = '';

$threads = get_posts(array(
  'post_type'      => 'wpsc_ticket_thread',
  'post_status'    => 'publish',
  'posts_per_page' => -1,
  'orderby'        => 'date',
  'order'          => 'DESC',
  'meta_query'     => array(
    'relation' => 'AND',
    array(
      'key'     => 'ticket_id',
      'value'   => $ticket_id,
      'compare' => '='
    ),
    array(
      'key'     => 'thread_type',
      'value'   => 'note',
      'compare' => 'IN'
    ),
  ),
));

if($threads){
  foreach ($threads as $thread) {
    $user_name = get_post_meta($thread->ID,'customer_name',true);
    $ticket_notes_history .= '<hr><strong>'.$user_name.'</strong> <small><i>'.$wpscfunction->time_elapsed_timestamp($thread->post_date).'</i></small><br>'.$thread->post_content;
  }
}
