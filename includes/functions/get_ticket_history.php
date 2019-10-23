<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$wpsc_thread_limit = get_option('wpsc_thread_limit');

$ticket_history = '';

$threads = get_posts(array(
  'post_type'      => 'wpsc_ticket_thread',
  'post_status'    => 'publish',
  'posts_per_page' => $wpsc_thread_limit+1,
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
      'value'   => array('reply','report'),
      'compare' => 'IN'
    ),
  ),
));

if($threads){
  $cntr = 0;
  foreach ($threads as $thread) {
    if($cntr==0){
      $cntr ++;
      continue;
    }
    $user_name = get_post_meta($thread->ID,'customer_name',true);
    $ticket_history .= '<hr><strong>'.$user_name.'</strong> <small><i>'.$thread->post_date.'</i></small><br>'.$thread->post_content;
  }
}
