<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpdb, $current_user, $wpscfunction;

$blog_id = get_current_blog_id();

$filter             = $wpscfunction->get_current_filter();
$saved_filters      = get_user_meta($current_user->ID, $blog_id.'_wpsc_filter',true);
$saved_filters      = $saved_filters ? $saved_filters : array();
$general_appearance = get_option('wpsc_appearance_general_settings');

$create_ticket_btn_css       = 'background-color:'.$general_appearance['wpsc_crt_ticket_btn_action_bar_bg_color'].' !important;color:'.$general_appearance['wpsc_crt_ticket_btn_action_bar_text_color'].' !important;';
$action_default_btn_css      = 'background-color:'.$general_appearance['wpsc_default_btn_action_bar_bg_color'].' !important;color:'.$general_appearance['wpsc_default_btn_action_bar_text_color'].' !important;';
$logout_btn_css              = 'background-color:'.$general_appearance['wpsc_sign_out_bg_color'].' !important;color:'.$general_appearance['wpsc_sign_out_text_color'].' !important;';
$wpsc_show_and_hide_filters  = get_option('wpsc_show_and_hide_filters');
$wpsc_appearance_ticket_list = get_option('wpsc_appearance_ticket_list');

$wpsc_on_and_off_auto_refresh = get_option('wpsc_on_and_off_auto_refresh');

include WPSC_ABSPATH.'includes/admin/tickets/ticket_list/filters/get_label_count.php';
?>
<div class="row wpsc_tl_action_bar" style="background-color:<?php echo $general_appearance['wpsc_action_bar_color']?> !important;">
  <div class="col-sm-12">
    <button type="button" id="wpsc_load_list_new_ticket_btn" onclick="wpsc_get_create_ticket();" class="btn btn-sm wpsc_create_ticket_btn" style="<?php echo $create_ticket_btn_css?>"><i class="fa fa-plus"></i> <?php _e('New Ticket','supportcandy')?></button>
		<?php if ($current_user->has_cap('wpsc_agent')):?>
       <button type="button" id="wpsc_load_list_agent_setting_btn" onclick="wpsc_get_agent_setting();" class="btn btn-sm wpsc_action_btn" style="<?php echo $action_default_btn_css?>"><i class="fas fa-user-cog"></i> <?php _e('Agent Settings','supportcandy')?></button>
		<?php endif;?>
		<button type="button" class="btn btn-sm visible-xs visible-sm wpsc_action_btn" id="wpsc_load_list_show_filters_btn" onclick="toggle_wpsc_sm_filters(this);" style="<?php echo $action_default_btn_css?>"><i class="fa fa-filter"></i> <?php _e('Show Filters','supportcandy')?></button>
		<?php if($wpsc_show_and_hide_filters){?>
				<button type="button" class="btn btn-sm hidden-xs hidden-sm wpsc_action_btn" id="wpsc_load_list_hide_filters_btn" onclick="toggle_wpsc_md_filters(this);" style="<?php echo $action_default_btn_css?>"><i class="fa fa-filter"></i> <?php _e('Hide Filters','supportcandy')?></button>
		<?php	}else{?>
				<button type="button" class="btn btn-sm hidden-xs hidden-sm wpsc_action_btn" id="wpsc_load_list_hide_filters_btn" onclick="toggle_wpsc_md_filters(this);" style="<?php echo $action_default_btn_css?>"><i class="fa fa-filter"></i> <?php _e('Show Filters','supportcandy')?></button>
			<?php } ?>	
		<?php
			$reset_type = 'all';
		?>
		<button type="button" class="btn btn-sm wpsc_action_btn" id="wpsc_load_list_reset_filters_btn" onclick="wpsc_set_default_filter('<?php echo $reset_type?>');" style="<?php echo $action_default_btn_css?>"><i class="fa fa-retweet"></i> <?php _e('Reset Filters','supportcandy')?></button>
		<?php if ($current_user->has_cap('wpsc_agent')):?>
			<button type="button" class="btn btn-sm wpsc_action_btn" id="wpsc_load_list_auto_refresh_btn" onclick="wpsc_set_toggle_auto_refresh();" style="<?php echo $action_default_btn_css?>"><i class="fas fa-sync-alt"></i> <span id="wpsc_autorefresh_btn_lbl"><?php _e('Auto Refresh : Off','supportcandy')?></span></button>
		<?php endif;?>
		<?php do_action('wpsc_add_btn_after_default_filter');?>
		<?php if ($wpscfunction->has_permission('assign_agent')):?>
    	<button type="button" class="btn btn-sm wpsc_btn_bulk_action wpsc_action_btn checkbox_depend hidden" id="btn_assign_agents" onclick="wpsc_get_bulk_assign_agent();" style="<?php echo $action_default_btn_css?>"><i class="fas fa-users"></i> <?php _e('Assign Agent','supportcandy')?></button>
		<?php endif;?>
		<?php if ($wpscfunction->has_permission('change_status')):?>
    	<button type="button" class="btn btn-sm wpsc_btn_bulk_action wpsc_action_btn checkbox_depend hidden" id="btn_change_statuses" onclick="wpsc_get_bulk_change_status()" style="<?php echo $action_default_btn_css?>"><i class="fa fa-arrow-circle-right"></i> <?php _e('Change Status','supportcandy')?></button>
		<?php endif;?>
		<?php if ($wpscfunction->has_permission('delete_ticket')):?>
			<button type="button" class="btn btn-sm wpsc_btn_bulk_action wpsc_action_btn checkbox_depend hidden" id="btn_delete_tickets"  onclick="wpsc_get_delete_bulk_ticket()" style="<?php echo $action_default_btn_css?>"><i class="fa fa-trash"></i> <?php _e('Delete Tickets','supportcandy')?></button>
			<button type="button" class="btn btn-sm wpsc_btn_bulk_action wpsc_action_btn checkbox_depend hidden" id="btn_restore_tickets"  onclick="wpsc_get_restore_bulk_ticket()" style="<?php echo $action_default_btn_css?>"><i class="fa fa-window-restore"></i> <?php _e('Restore Ticket','supportcandy')?></button>
			<button type="button" class="btn btn-sm wpsc_btn_bulk_action wpsc_action_btn checkbox_depend hidden" id="btn_delete_permanently_bulk_ticket"  onclick="wpsc_get_delete_permanently_bulk_ticket();" style="<?php echo $action_default_btn_css?>"><i class="fa fa-trash"></i> <?php _e('Delete Tickets Permanently','supportcandy')?></button>
		<?php endif;?>
		<?php 
		$wpsc_support_page_id = get_option('wpsc_support_page_id');
		$support_page_url = get_permalink($wpsc_support_page_id);
		$wpsc_allow_sign_out = get_option('wpsc_sign_out');
		if($wpsc_allow_sign_out){?>
			<button class="btn btn-sm pull-right" type="button" id="wpsc_login_link" onclick="window.location.href='<?php echo wp_logout_url($support_page_url) ?>'" style=" <?php echo $logout_btn_css ?>"><i class="fas fa-sign-out-alt"></i> <?php _e('Log Out','supportcandy')?></button>
		<?php }?>
  </div>
</div>

<div class="row" style="background-color:<?php echo $general_appearance['wpsc_bg_color']?> !important;color:<?php echo $general_appearance['wpsc_text_color']?> !important;">
	<div id="wpsc_md_filters" class="col-md-2 hidden-xs hidden-sm <?php echo $wpsc_show_and_hide_filters==0 ?'hidden':'visible'?> wpsc_sidebar">
  	<div class="row" style="background-color:<?php echo $wpsc_appearance_ticket_list['wpsc_filter_widgets_bg_color']?> !important;color:<?php echo $wpsc_appearance_ticket_list['wpsc_filter_widgets_text_color']?> !important;border-color:<?php echo $wpsc_appearance_ticket_list['wpsc_filter_widgets_border_color']?> !important;">
			<h4 class="widget_header"><i class="fa fa-filter"></i> <?php _e('Filters','supportcandy')?></h4>
			<hr class="widget_divider">
			<?php
				$labels = $wpscfunction->get_ticket_filter_labels();
				foreach ($labels as $key => $label) {				
					if ( ($current_user->has_cap('wpsc_agent') && $label['visibility']=='agent') || (!$current_user->has_cap('wpsc_agent') && $label['visibility']=='customer') || $label['visibility']=='both' ) {
						?>
						<div onclick="wpsc_set_default_filter('<?php echo $key?>');" class="wpsp_sidebar_labels <?php echo $key?> <?php echo $filter['label']==$key?'active':''?>">
							<?php echo $label['label'];
							$badge_count = 0;
							if($key == 'unresolved_agent'){
								$badge_count = $label_counts['unresolved_agent'];
							}
							echo ($label['has_badge'] && $current_user->has_cap('wpsc_agent'))?' <span class="badge">'.$badge_count.'</span>':''?>
						</div>
						<?php
					}
				}
				?>
			</div>
	    <div class="row" style="background-color:<?php echo $wpsc_appearance_ticket_list['wpsc_filter_widgets_bg_color']?> !important;color:<?php echo $wpsc_appearance_ticket_list['wpsc_filter_widgets_text_color']?> !important;border-color:<?php echo $wpsc_appearance_ticket_list['wpsc_filter_widgets_border_color']?> !important;">
	      <h4 class="widget_header"><i class="fa fa-filter"></i> <?php _e('Saved Filters','supportcandy')?></h4>
				<hr class="widget_divider">
				<?php
				foreach ($saved_filters as $key => $label) {	
					?>
					<div class="wpsp_sidebar_labels">
						<span onclick="wpsc_set_saved_filter('<?php echo $key?>');"class="wpsp_sidebar_labels <?php echo $key?> <?php echo isset($filter['save_label']) && $filter['save_label'] ==$label['save_label']?'active':''?>"><?php echo $label['save_label']?></span>
						<button type="button" class="btn btn-sm btn-danger wpsc_sf_del" onclick="wpsc_delete_saved_filter('<?php echo $key?>');">
							<i class="fa fa-times" style="float:right;"></i>
							</button>
					</div>
					<?php
				}
				if(!$saved_filters) echo __('No filters found!','supportcandy').'<br><br>';
				?>
			</div>
	  </div>
	<div id="wpsc_sm_filters" class="col-sm-12 col-md-2 hidden wpsc_sidebar">
		<div class="row">
			<h4 class="widget_header"><i class="fa fa-filter"></i> <?php _e('Filters','supportcandy')?></h4>
			<hr class="widget_divider">
			<?php
			foreach ($labels as $key => $label) {				
				if ( ($current_user->has_cap('wpsc_agent') && $label['visibility']=='agent') || (!$current_user->has_cap('wpsc_agent') && $label['visibility']=='customer') || $label['visibility']=='both' ) {
					?>
					<div onclick="wpsc_set_default_filter('<?php echo $key?>');" class="wpsp_sidebar_labels <?php echo $key?> <?php echo $filter['label']==$key?'active':''?>">
						<?php echo $label['label'];
						$badge_count = 0;
						if($key == 'unresolved_agent'){
							$badge_count = $label_counts['unresolved_agent'];
						}
						
						echo ($label['has_badge'])?' <span class="badge">'.$badge_count.'</span>':''?>
					</div>
					<?php
				}
			}
			?>
		</div>
		<div class="row">
			<h4 class="widget_header"><i class="fa fa-filter"></i> <?php _e('Saved Filters','supportcandy')?></h4>
			<hr class="widget_divider">
			<?php
			foreach ($saved_filters as $key => $label) {									
				?>
				<div class="wpsp_sidebar_labels">
					<span onclick="wpsc_set_saved_filter('<?php echo $key?>');"><?php echo $label['save_label']?></span>
					<button type="button" class="btn btn-sm btn-danger wpsc_sf_del" onclick="wpsc_delete_saved_filter('<?php echo $key?>');">
						<i class="fa fa-times" style="float:right;"></i>
					</button>
				</div>
				<?php
			}
			if(!$saved_filters) echo __('No filters found!','supportcandy').'<br><br>';
			?>
		</div>
	</div>	

<?php if(!$wpsc_show_and_hide_filters){ ?>
			<div class="col-sm-12 col-md-12 wpsc_ticket_list_container table-responsive" >
				<?php include WPSC_ABSPATH . 'includes/admin/tickets/ticket_list/get_ticket_list.php';?>
			</div>
<?php }
else{?>
	<div class="col-sm-12 col-md-10 wpsc_ticket_list_container table-responsive" >
		<?php include WPSC_ABSPATH . 'includes/admin/tickets/ticket_list/get_ticket_list.php';?>
	</div>
<?php } ?>			
</div>

<script>
var wpsc_autorefresh_status  = false;
var wpsc_default_refresh_option = <?php echo $wpsc_on_and_off_auto_refresh ?>;
if (wpsc_default_refresh_option){
	jQuery(document).ready(function() {
		wpsc_set_toggle_auto_refresh();
	});
}

function toggle_wpsc_sm_filters(e){
  if(jQuery('#wpsc_sm_filters').hasClass('hidden')){
    jQuery('#wpsc_sm_filters').removeClass('hidden');
    jQuery('#wpsc_sm_filters').addClass('visible-xs');
    jQuery('#wpsc_sm_filters').addClass('visible-sm');
    jQuery(e).html('<i class="fa fa-filter"></i> <?php _e('Hide Filters','supportcandy')?>');
  } else {
    jQuery('#wpsc_sm_filters').removeClass('visible-xs');
    jQuery('#wpsc_sm_filters').removeClass('visible-sm');
    jQuery('#wpsc_sm_filters').addClass('hidden');
    jQuery(e).html('<i class="fa fa-filter"></i> <?php _e('Show Filters','supportcandy')?>');
  }
}

function toggle_wpsc_md_filters(e){
  if(jQuery('#wpsc_md_filters').hasClass('hidden')){
    jQuery('#wpsc_md_filters').removeClass('hidden');
    jQuery('#wpsc_md_filters').addClass('hidden-xs');
    jQuery('#wpsc_md_filters').addClass('hidden-sm');
		jQuery('.wpsc_ticket_list_container').removeClass('col-md-12');
		jQuery('.wpsc_ticket_list_container').addClass('col-md-10');
    jQuery(e).html('<i class="fa fa-filter"></i> <?php _e('Hide Filters','supportcandy')?>');
  } else {
    jQuery('#wpsc_md_filters').removeClass('hidden-xs');
    jQuery('#wpsc_md_filters').removeClass('hidden-sm');
    jQuery('#wpsc_md_filters').addClass('hidden');
		jQuery('.wpsc_ticket_list_container').removeClass('col-md-10');
		jQuery('.wpsc_ticket_list_container').addClass('col-md-12');
    jQuery(e).html('<i class="fa fa-filter"></i> <?php _e('Show Filters','supportcandy')?>');
  }
}

function wpsc_set_toggle_auto_refresh(){
	if(wpsc_autorefresh_status){
		wpsc_autorefresh_status = false;
		jQuery('#wpsc_autorefresh_btn_lbl').text('<?php _e('Auto Refresh : Off','supportcandy')?>');
	} else {
		wpsc_autorefresh_status = true;
		jQuery('#wpsc_autorefresh_btn_lbl').text('<?php _e('Auto Refresh : On','supportcandy')?>');
		wpsc_refresh_ticket_list();
	}
}

function wpsc_refresh_ticket_list(){
	if(wpsc_autorefresh_status && jQuery(".wpsp_custom_filter_container").is(':hidden')){
		var data = {
	    action: 'wpsc_tickets',
	    setting_action : 'get_ticket_list'
	  };
	  jQuery.post(wpsc_admin.ajax_url, data, function(response) {
	    jQuery('.wpsc_ticket_list_container').html(response);
			toggle_ticket_list_actions();
		});
	}
	setTimeout(wpsc_refresh_ticket_list,60000);
}
</script>