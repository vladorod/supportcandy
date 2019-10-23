<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction,$wpdb;

$wpsc_ticket_data = $wpscfunction->get_ticket($ticket_id);

// Ticket ID
$str = preg_replace('/{ticket_id}/', $ticket_id, $str);

// Ticket Status
$str = preg_replace('/{ticket_status}/', $this->get_status_name($wpsc_ticket_data['ticket_status']), $str);

// Ticket Category
$str = preg_replace('/{ticket_category}/', $this->get_category_name($wpsc_ticket_data['ticket_category']), $str);

// Ticket Priority
$str = preg_replace('/{ticket_priority}/', $this->get_priority_name($wpsc_ticket_data['ticket_priority']), $str);

// Customer Name
$str = preg_replace('/{customer_name}/', stripslashes($wpsc_ticket_data['customer_name']), $str);

// Customer Email
$str = preg_replace('/{customer_email}/', $wpsc_ticket_data['customer_email'], $str);

// Subject
$ticket_subject = str_replace('$','\$', $wpsc_ticket_data['ticket_subject']);
$str = preg_replace('/{ticket_subject}/', stripslashes($ticket_subject), $str);

// Ticket Description
$ticket_description = $this->get_ticket_description($ticket_id);
$tdec_flag = false;
if (strpos($str, 'ticket_description') !== false) {
	$tdec_flag = true;
}
$ticket_description['description'] = str_replace('$','\$', $ticket_description['description']);
$str = preg_replace('/{ticket_description}/', $ticket_description['description'], $str);

// Last Reply
$last_reply = $this->get_last_reply($ticket_id);
$lr_flag  =  false;
if (strpos($str, 'last_reply') !== false) {
	$lr_flag = true;
}

$last_reply['description'] = str_replace('$','\$',$last_reply['description']);
$str = preg_replace('/{last_reply}/', $last_reply['description'],$str);

// Last Reply User Name
$str = preg_replace('/{last_reply_user_name}/', $last_reply['user_name'], $str);

// Last Reply User Email
$str = preg_replace('/{last_reply_user_email}/', $last_reply['user_email'], $str);

// Last Note
$last_note = $this->get_last_note($ticket_id);
$ln_flag = false;
if (strpos($str, 'last_note') !== false) {
	$ln_flag = true;
}
$last_note['description'] = str_replace('$','\$',$last_note['description']);
$str = preg_replace('/{last_note}/', $last_note['description'], $str);

// Last Note User Name
$str = preg_replace('/{last_note_user_name}/', $last_note['user_name'], $str);

// Last Note User Email
$str = preg_replace('/{last_note_user_email}/', $last_note['user_email'], $str);

// Current User Name
$str = preg_replace('/{current_user_name}/', $current_user->display_name, $str);

// Current User Email
$str = preg_replace('/{current_user_email}/', $current_user->user_email, $str);

// Ticket History
$ticket_history = $this->get_ticket_history($ticket_id);
$ticket_history = str_replace('$','\$',$ticket_history);
$str = preg_replace('/{ticket_history}/', $ticket_history, $str);

 // Assigned Agents
$assigned_agents = $this->get_assigned_agent_names($ticket_id);
$str = preg_replace('/{assigned_agent}/', $assigned_agents, $str);

// Previously Assigned agents
$previously_assigned_agent = $this->get_previously_assigned_agents_names($ticket_id);
$str = preg_replace('/{previously_assigned_agent}/', $previously_assigned_agent, $str);

// Date created
$str = preg_replace('/{date_created}/', get_date_from_gmt($wpsc_ticket_data['date_created'] ,$this->time_elapsed_timestamp($wpsc_ticket_data['date_created']) ), $str);

// Date updated
$str = preg_replace('/{date_updated}/', get_date_from_gmt($wpsc_ticket_data['date_updated']), $str);

// Agent created
$str = preg_replace('/{agent_created}/', $this->get_agent_name($wpsc_ticket_data['agent_created']), $str);

// All ticket history
$ticket_history_all = $this->get_ticket_history_all($ticket_id);
$ticket_history_all = str_replace('$','\$', $ticket_history_all);
$str = preg_replace('/{ticket_history_all}/', $ticket_history_all,$str); 

// All ticket history with all notes
$ticket_history_all_with_notes = $this->get_ticket_history_all_with_notes($ticket_id);
$ticket_history_all_with_notes = str_replace('$','\$',$ticket_history_all_with_notes);
$str = preg_replace('/{ticket_history_all_with_notes}/',$ticket_history_all_with_notes,$str);

// All ticket history of notes
$ticket_notes_history = $this->ticket_notes_history($ticket_id);
$ticket_notes_history = str_replace('$','\$',$ticket_notes_history);
$str = preg_replace('/{ticket_notes_history}/',$ticket_notes_history , $str);

// Ticket URL
$ticket_auth_code = $wpsc_ticket_data['ticket_auth_code'];
$ticket_url = get_permalink(get_option('wpsc_support_page_id')).'?support_page=open_ticket&ticket_id='.$ticket_id.'&auth_code='.$ticket_auth_code;
$ticket_url = '<a href="'.$ticket_url.'" target="_blank">'.$ticket_url.'</a>';
$str = preg_replace('/{ticket_url}/', $ticket_url, $str);

// Custom Fields
$fields = get_terms([
	'taxonomy'   => 'wpsc_ticket_custom_fields',
	'hide_empty' => false,
	'orderby'    => 'meta_value_num',
	'meta_key'	 => 'wpsc_tf_load_order',
	'order'    	 => 'ASC',
	'meta_query' => array(
		'relation' => 'AND',
		array(
			'key'       => 'agentonly',
			'value'     => array(0,1),
      'compare'   => 'IN'
		),
		array(
			'key'       => 'wpsc_tf_type',
      'value'     => '0',
			'compare'   => '>'
		),
	),
]);
foreach ($fields as $field) {
	$tf_type = get_term_meta( $field->term_id, 'wpsc_tf_type', true);
	switch ($tf_type) {
		case '1':	
		case '2':
		case '4':
    case '9':
		$text = $wpscfunction->get_ticket_meta($ticket_id,$field->slug,true);
		$str  = preg_replace('/{'.$field->slug.'}/', $text, $str);
		break;

		case '3':
		$text = $wpscfunction->get_ticket_meta($ticket_id,$field->slug);
			if($text){
				$str = preg_replace('/{'.$field->slug.'}/', implode(', ',$text), $str);
			}
			else {
				$str = preg_replace('/{'.$field->slug.'}/', '', $str);
	  	}
		break;

		case '5':
			$text = $wpscfunction->get_ticket_meta($ticket_id,$field->slug,true);
			$str  = preg_replace('/{'.$field->slug.'}/', nl2br($text), $str);
		 break;

		case '6':
			$date = $wpscfunction->get_ticket_meta($ticket_id,$field->slug,true);
		//	if($date){
				$str = preg_replace('/{'.$field->slug.'}/', $this->datetimeToCalenderFormat($date), $str);
			//}
			// else {
			// 	$str = preg_replace('/{'.$field->slug.'}/', '', $str);
			// }
			break;

		case '7':
			$text = $wpscfunction->get_ticket_meta($ticket_id,$field->slug,true);
			$text = $text ? '<a href="'.$text.'" target="_blank">'.$text.'</a>' : '';
			$str  = preg_replace('/{'.$field->slug.'}/', $text, $str);
			 break;

		case '8':
			$text = $wpscfunction->get_ticket_meta($ticket_id,$field->slug,true);
			$text = $text ? '<a href="mailto:'.$text.'" target="_blank">'.$text.'</a>' : '';
			$str  = preg_replace('/{'.$field->slug.'}/', $text, $str);
			 break;

		case '10':
			$attachments = $wpscfunction->get_ticket_meta($ticket_id, $field->slug);
			$auth_id = $wpscfunction->get_ticket_fields($ticket_id,'ticket_auth_code');
			$attach_url = array();
			if($attachments){
				foreach( $attachments as $attachment ):
					$attach      = array();
					$attach_meta = get_term_meta($attachment);
					foreach ($attach_meta as $key => $value) {
						$attach[$key] = $value[0];
					}
					$upload_dir   = wp_upload_dir();
					
					$wpsp_file = get_term_meta($attachment,'wpsp_file');
					
					if ($wpsp_file) {
						$file_url = $upload_dir['baseurl']  . '/wpsp/'. $attach['save_file_name'];
					}else {
						$file_url     = $upload_dir['baseurl'] . '/wpsc/'. $attach['save_file_name'];
					}
					
					$download_url = $attach['is_image'] ? $file_url : site_url('/').'?wpsc_attachment='.$attachment.'&tid='.$ticket_id.'&tac='.$auth_id;
					$attach_url[] = '<a href="'.$download_url.'" target="_blank">'.$attach['filename'].'</a>';
					endforeach;	
					$str = preg_replace('/{'.$field->slug.'}/', implode('<br />',$attach_url), $str);
			 } 
			 else {
				$str = preg_replace('/{'.$field->slug.'}/', ' ', $str);
			}
			break;
		
		case '18':	
			$date =  $wpscfunction->get_ticket_meta( $ticket_id, $field->slug, true );
			if($date){
				$str = preg_replace('/{'.$field->slug.'}/', $date, $str);
			} else {
				$str = preg_replace('/{'.$field->slug.'}/', '', $str);
			}
			break;
	 default :
	 		$str = apply_filters('wpsc_replace_macro_custom',$str,$ticket_id,$tf_type,$field);
			break;
		}
	}

if($tdec_flag){
	if(isset($ticket_description['thread_id'])){
	$report_thread_id   = $ticket_description['thread_id'];
	$report_attachments = get_post_meta($report_thread_id,'attachments',true);
	$auth_id            = $wpsc_ticket_data['ticket_auth_code'];
	$ticket_id          = get_post_meta($report_thread_id,'ticket_id',true);
	$attach_url         = array();
	if($report_attachments){
		$attach_url[]	 = '<strong>Attachments:</strong>';
		foreach( $report_attachments as $attachment ):
			$attach      = array();
			$attach_meta = get_term_meta($attachment);
			foreach ($attach_meta as $key => $value) {
				$attach[$key] = $value[0];
			}
			$upload_dir   = wp_upload_dir();
			
			$wpsp_file = get_term_meta($attachment,'wpsp_file');
			
			if ($wpsp_file) {
				$file_url = $upload_dir['baseurl']  . '/wpsp/'. $attach['save_file_name'];
			}else {
				$file_url     = $upload_dir['baseurl'] . '/wpsc/'. $attach['save_file_name'];
			}
			
			$download_url = $attach['is_image'] ? $file_url : site_url('/').'?wpsc_attachment='.$attachment.'&tid='.$ticket_id.'&tac='.$auth_id;
			$attach_url[] = '<a href="'.$download_url.'" target="_blank">'.$attach['filename'].'</a>';
		endforeach;
	}
  $str = $str.implode("<br>",$attach_url);
}
}
if($lr_flag){
	$last_reply_thread_id   = $last_reply['thread_id'];
	$last_reply_attachments = get_post_meta($last_reply_thread_id,'attachments',true);
	$auth_id = $wpsc_ticket_data['ticket_auth_code'];
	$ticket_id = get_post_meta($last_reply_thread_id,'ticket_id',true);
	$attach_url = array();
	if($last_reply_attachments){
		$attach_url[]  = '<strong>Attachments:</strong>';
		foreach( $last_reply_attachments as $attachment ):
			$attach      = array();
			$attach_meta = get_term_meta($attachment);
			foreach ($attach_meta as $key => $value) {
				$attach[$key] = $value[0];
			}
			$upload_dir   = wp_upload_dir();
			$file_url     = $upload_dir['baseurl'] . '/wpsc/'.$attach['save_file_name'];
			$download_url = $attach['is_image'] ? $file_url : site_url('/').'?wpsc_attachment='.$attachment.'&tid='.$ticket_id.'&tac='.$auth_id;
			$attach_url[] = '<a href="'.$download_url.'" target="_blank">'.$attach['filename'].'</a>';
		endforeach;
	}
	$str = $str.implode("<br>",$attach_url);
}
 
if($ln_flag){
	$last_note_thread_id	 = $last_note['thread_id'];
	$last_note_attachments = get_post_meta($last_note_thread_id,'attachments',true);
	$auth_id	= $wpsc_ticket_data['ticket_auth_code'];
	$ticket_id = get_post_meta($last_note_thread_id,'ticket_id',true);
	$attach_url = array();
	if($last_note_attachments){
		$attach_url[] 	= '<strong>Attachments:</strong>';
		foreach( $last_note_attachments as $attachment ):
			$attach      = array();
			$attach_meta = get_term_meta($attachment);
			foreach ($attach_meta as $key => $value) {
				$attach[$key] = $value[0];
			}
			$upload_dir   = wp_upload_dir();
			
			$wpsp_file = get_term_meta($attachment,'wpsp_file');
			
			if ($wpsp_file) {
				$file_url = $upload_dir['baseurl']  . '/wpsp/'. $attach['save_file_name'];
			}else {
				$file_url     = $upload_dir['baseurl'] . '/wpsc/'. $attach['save_file_name'];
			}
			
			$download_url = $attach['is_image'] ? $file_url : site_url('/').'?wpsc_attachment='.$attachment.'&tid='.$ticket_id.'&tac='.$auth_id;
			$attach_url[] = '<a href="'.$download_url.'" target="_blank">'.$attach['filename'].'</a>';
		endforeach;
	 }
	$str = $str.implode("<br>",$attach_url); 
}

$date_closed =  $wpscfunction->time_elapsed_timestamp($wpscfunction->get_ticket_meta($ticket_id, 'date_closed', true));
$str  = preg_replace('/{date_closed}/', $date_closed, $str);

$str = apply_filters('wpsc_replace_macro',$str,$ticket_id);