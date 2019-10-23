<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wpscfunction;
if (!($current_user->ID && $current_user->has_cap('wpsc_agent'))) {exit;}

$ticket_id  = isset($_POST['ticket_id']) ? sanitize_text_field($_POST['ticket_id']) : '' ;
$ticket_data = $wpscfunction->get_ticket($ticket_id);
$status_id   	= $ticket_data['ticket_status'];
$priority_id 	= $ticket_data['ticket_priority'];
$category_id  = $ticket_data['ticket_category'];
$wpsc_appearance_modal_window = get_option('wpsc_modal_window');
$wpsc_custom_status_localize   = get_option('wpsc_custom_status_localize');
$wpsc_custom_category_localize = get_option('wpsc_custom_category_localize');
$wpsc_custom_priority_localize = get_option('wpsc_custom_priority_localize');
ob_start();
?>
<form id="frm_get_ticket_change_status" method="post">

	<div class="form-group">
		<label for="wpsc_default_ticket_status"><?php _e('Ticket Status','supportcandy');?></label>
		<select class="form-control" name="status">
			<?php
			$statuses = get_terms([
				'taxonomy'   => 'wpsc_statuses',
				'hide_empty' => false,
				'orderby'    => 'meta_value_num',
				'order'    	 => 'ASC',
				'meta_query' => array('order_clause' => array('key' => 'wpsc_status_load_order')),
			]);
      foreach ( $statuses as $status ) :
				$selected = $status_id == $status->term_id ? 'selected="selected"' : '';
				echo '<option '.$selected.' value="'.$status->term_id.'">'.$wpsc_custom_status_localize['custom_status_'.$status->term_id].'</option>';
			endforeach;
			?>
		</select>
	</div>

	<div class="form-group">
		<label for="wpsc_default_ticket_category"><?php _e('Ticket Category','supportcandy');?></label>
		<select class="form-control" name="category" >
			<?php
			$categories = get_terms([
				'taxonomy'   => 'wpsc_categories',
				'hide_empty' => false,
				'orderby'    => 'meta_value_num',
				'order'    	 => 'ASC',
				'meta_query' => array('order_clause' => array('key' => 'wpsc_category_load_order')),
			]);
			foreach ( $categories as $category ) :
				$selected = $category_id == $category->term_id ? 'selected="selected"' : '';
				echo '<option '.$selected.' value="'.$category->term_id.'">'.$wpsc_custom_category_localize['custom_category_'.$category->term_id].'</option>';
			endforeach;
			?>
		</select>
	</div>

	<div class="form-group">
		<label for="wpsc_default_ticket_priority"><?php _e('Ticket priority','supportcandy');?></label>
		<select class="form-control" name="priority">
			<?php
			$priorities = get_terms([
				'taxonomy'   => 'wpsc_priorities',
				'hide_empty' => false,
				'orderby'    => 'meta_value_num',
				'order'    	 => 'ASC',
				'meta_query' => array('order_clause' => array('key' => 'wpsc_priority_load_order')),
			]);
			foreach ( $priorities as $priority ) :
				$selected = $priority_id == $priority->term_id ? 'selected="selected"' : '';
				echo '<option '.$selected.' value="'.$priority->term_id.'">'.$wpsc_custom_priority_localize['custom_priority_'.$priority->term_id].'</option>';
			endforeach;
			?>
		</select>
	</div>
	<?php do_action('wpsc_after_edit_change_ticket_status',$ticket_id);?>
  <input type="hidden" name="action" value="wpsc_tickets" />
	<input type="hidden" name="setting_action" value="set_change_ticket_status" />
  <input type="hidden" id="wpsc_post_id" name="ticket_id" value="<?php echo htmlentities($ticket_id) ?>" />
	

</form>
<?php
$body = ob_get_clean();

ob_start();
?>
<button type="button" class="btn wpsc_popup_close"  style="background-color:<?php echo $wpsc_appearance_modal_window['wpsc_close_button_bg_color']?> !important;color:<?php echo $wpsc_appearance_modal_window['wpsc_close_button_text_color']?> !important;"    onclick="wpsc_modal_close();"><?php _e('Close','supportcandy');?></button>
<button type="button" class="btn wpsc_popup_action" style="background-color:<?php echo $wpsc_appearance_modal_window['wpsc_action_button_bg_color']?> !important;color:<?php echo $wpsc_appearance_modal_window['wpsc_action_button_text_color']?> !important;" onclick="wpsc_set_change_ticket_status(<?php echo htmlentities($ticket_id)?>);"><?php _e('Save','supportcandy');?></button>
<?php
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);
