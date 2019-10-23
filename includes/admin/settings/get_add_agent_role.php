<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

ob_start();
?>
<form id="wpsc_frm_agent_role" action="javascript:function(return false;);" method="post">
  
  <div class="form-group">
    <label style="font-size:16px;"><?php _e('Label','supportcandy');?></label>
    <input id="wpsc_role_label" class="form-control" name="agentrole[label]" value="" />
  </div>
  
  <label style="margin-bottom:20px; font-size:16px;"><?php _e('Ticket Permissions','supportcandy');?></label>
  <div class="form-group row" style="margin-bottom:30px;">
    <div class="col-sm-4">
      <label><?php _e('View unassigned','supportcandy');?></label>
      <p class="help-block"><?php _e('Unassigned ticket list visibility.','supportcandy');?></p>
      <select class="form-control" name="agentrole[view_unassigned]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
    <div class="col-sm-4">
      <label><?php _e('View assigned me','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to user himself. This will also enable private notes.','supportcandy');?></p>
      <select class="form-control" name="agentrole[view_assigned_me]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
    <div class="col-sm-4">
      <label><?php _e('View assigned others','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to all other agents. This will also enable private notes.','supportcandy');?></p>
      <select class="form-control" name="agentrole[view_assigned_others]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
  </div>
  
  <div class="form-group row" style="margin-bottom:30px;">
    <div class="col-sm-4">
      <label><?php _e('Assign unassigned','supportcandy');?></label>
      <p class="help-block"><?php _e('Unassigned ticket assign agent capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[assign_unassigned]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
    <div class="col-sm-4">
      <label><?php _e('Assign assigned me','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to user himself further assign capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[assign_assigned_me]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
    <div class="col-sm-4">
      <label><?php _e('Assign assigned others','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to all other agents further assign capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[assign_assigned_others]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
  </div>
  
  <div class="form-group row" style="margin-bottom:30px;">
		<div class="col-sm-4">
      <label><?php _e('Reply unassigned','supportcandy');?></label>
      <p class="help-block"><?php _e('Unassigned ticket reply capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[reply_unassigned]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
		<div class="col-sm-4">
      <label><?php _e('Reply assigned me','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to user himself reply capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[reply_assigned_me]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
    <div class="col-sm-4">
      <label><?php _e('Reply assigned others','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to all other agents reply capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[reply_assigned_others]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
  </div>
  
  <div class="form-group row" style="margin-bottom:30px;">
		<div class="col-sm-4">
      <label><?php _e('Change status unassigned','supportcandy');?></label>
      <p class="help-block"><?php _e('Unassigned ticket status change capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[cng_tkt_sts_unassigned]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
		<div class="col-sm-4">
      <label><?php _e('Change status assigned me','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to user himself change ticket status capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[cng_tkt_sts_assigned_me]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
    <div class="col-sm-4">
      <label><?php _e('Change status assigned others','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to all other agents change ticket status capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[cng_tkt_sts_assigned_others]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
  </div>
  
  <div class="form-group row" style="margin-bottom:30px;">
		<div class="col-sm-4">
      <label><?php _e('Change ticket fields unassigned','supportcandy');?></label>
      <p class="help-block"><?php _e('Unassigned change ticket fields capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[cng_tkt_field_unassigned]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
		<div class="col-sm-4">
      <label><?php _e('Change ticket fields assigned me','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to user himself change ticket fields capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[cng_tkt_field_assigned_me]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
    <div class="col-sm-4">
      <label><?php _e('Change ticket fields assigned others','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to all other agents change ticket fields capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[cng_tkt_field_assigned_others]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
  </div>
  
  <div class="form-group row" style="margin-bottom:30px;">
		<div class="col-sm-4">
      <label><?php _e('Change agentonly fields unassigned','supportcandy');?></label>
      <p class="help-block"><?php _e('Unassigned change agentonly fields capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[cng_tkt_ao_unassigned]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
		<div class="col-sm-4">
      <label><?php _e('Change agentonly fields assigned me','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to user himself change agentonly fields capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[cng_tkt_ao_assigned_me]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
    <div class="col-sm-4">
      <label><?php _e('Change agentonly fields assigned others','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to all other agents change agentonly fields capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[cng_tkt_ao_assigned_others]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
  </div>
  
  <div class="form-group row" style="margin-bottom:30px;">
		<div class="col-sm-4">
      <label><?php _e('Change Raised By unassigned','supportcandy');?></label>
      <p class="help-block"><?php _e('Unassigned ticket change raised by capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[cng_tkt_rb_unassigned]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
		<div class="col-sm-4">
      <label><?php _e('Change Raised By assigned me','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to user himself change Raised By capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[cng_tkt_rb_assigned_me]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
    <div class="col-sm-4">
      <label><?php _e('Change Raised By assigned others','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to all other agents change Raised By capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[cng_tkt_rb_assigned_others]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
  </div>
  
  <div class="form-group row" style="margin-bottom:30px;">
		<div class="col-sm-4">
      <label><?php _e('Delete unassigned','supportcandy');?></label>
      <p class="help-block"><?php _e('Delete unassigned ticket capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[delete_unassigned]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
		<div class="col-sm-4">
      <label><?php _e('Delete assigned me','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to user himself delete capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[delete_assigned_me]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
    <div class="col-sm-4">
      <label><?php _e('Delete assigned others','supportcandy');?></label>
      <p class="help-block"><?php _e('Ticket assigned to all other agents delete capability.','supportcandy');?></p>
      <select class="form-control" name="agentrole[delete_assigned_others]">
        <option value="0"><?php _e('Disable','supportcandy');?></option>
        <option value="1"><?php _e('Enable','supportcandy');?></option>
      </select>
    </div>
  </div>
	
	<div class="form-group row" style="margin-bottom:30px;">
		<div class="col-sm-4">
			<label><?php _e('Edit/Delete unassigned threads','supportcandy');?></label>
			<p class="help-block"><?php _e('Edit/Delete unassigned ticket threads capability.','supportcandy');?></p>
			<select class="form-control" name="agentrole[edit_delete_unassigned]">
				<option value="0"><?php _e('Disable','supportcandy');?></option>
				<option value="1"><?php _e('Enable','supportcandy');?></option>
			</select>
		</div>
		<div class="col-sm-4">
			<label><?php _e('Edit/Delete assigned me threads','supportcandy');?></label>
			<p class="help-block"><?php _e('Ticket assigned to user himself edit/delete threads capability.','supportcandy');?></p>
			<select class="form-control" name="agentrole[edit_delete_assigned_me]">
				<option value="0"><?php _e('Disable','supportcandy');?></option>
				<option value="1"><?php _e('Enable','supportcandy');?></option>
			</select>
		</div>
		<div class="col-sm-4">
			<label><?php _e('Edit/Delete assigned others threads','supportcandy');?></label>
			<p class="help-block"><?php _e('Ticket assigned to all other agents edit/delete threads capability.','supportcandy');?></p>
			<select class="form-control" name="agentrole[edit_delete_assigned_others]">
				<option value="0"><?php _e('Disable','supportcandy');?></option>
				<option value="1"><?php _e('Enable','supportcandy');?></option>
			</select>
		</div>
	</div>
  
	<div class="form-group row" style="margin-bottom:30px;">
	  <div class="col-sm-4">
	    <label><?php _e('Private note unassigned','supportcandy');?></label>
	    <p class="help-block"><?php _e('Private note unassigned ticket capability.','supportcandy');?></p>
	    <select class="form-control" name="agentrole[view_unassigned_private_note]">
	      <option value="0"><?php _e('Disable','supportcandy');?></option>
	      <option value="1"><?php _e('Enable','supportcandy');?></option>
	    </select>
	  </div>
	  
	  <div class="col-sm-4">
	    <label><?php _e('Private note assigned me','supportcandy');?></label>
	    <p class="help-block"><?php _e('Ticket assigned to user himself private note capability.','supportcandy');?></p>
	    <select class="form-control" name="agentrole[view_assigned_me_private_note]">
	      <option value="0"><?php _e('Disable','supportcandy');?></option>
	      <option value="1"><?php _e('Enable','supportcandy');?></option>
	    </select>
	  </div>
	  
	  <div class="col-sm-4">
	    <label><?php _e('Private note assigned others','supportcandy');?></label>
	    <p class="help-block"><?php _e('Ticket assigned to all other agents private note capability.','supportcandy');?></p>
	    <select class="form-control" name="agentrole[view_assigned_others_private_note]">
	      <option value="0"><?php _e('Disable','supportcandy');?></option>
	      <option value="1"><?php _e('Enable','supportcandy');?></option>
	    </select>
	  </div>
	</div>  
	
	<div class="form-group row" style="margin-bottom:30px;">
		<div class="col-sm-4">
			<label><?php _e('View log unassigned','supportcandy');?></label>
			<p class="help-block"><?php _e('View log unassigned ticket capability.','supportcandy');?></p>
			<select class="form-control" name="agentrole[view_unassigned_log]">
				<option value="0"><?php _e('Disable','supportcandy');?></option>
				<option value="1"><?php _e('Enable','supportcandy');?></option>
			</select>
		</div>
		
		<div class="col-sm-4">
			<label><?php _e('View log assigned me','supportcandy');?></label>
			<p class="help-block"><?php _e('Ticket assigned to user himself view log capability.','supportcandy');?></p>
			<select class="form-control" name="agentrole[view_assigned_me_log]">
				<option value="0"><?php _e('Disable','supportcandy');?></option>
				<option value="1"><?php _e('Enable','supportcandy');?></option>
			</select>
		</div>
		
		<div class="col-sm-4">
			<label><?php _e('View log assigned others','supportcandy');?></label>
			<p class="help-block"><?php _e('Ticket assigned to all other agents view log capability.','supportcandy');?></p>
			<select class="form-control" name="agentrole[view_assigned_others_log]">
				<option value="0"><?php _e('Disable','supportcandy');?></option>
				<option value="1"><?php _e('Enable','supportcandy');?></option>
			</select>
		</div>
	</div>  
	
	<?php 	do_action('wpsc_add_agent_role_item'); ?>

  <input type="hidden" name="action" value="wpsc_settings" />
  <input type="hidden" name="setting_action" value="set_add_agent_role" />
  
</form>
<?php 
$body = ob_get_clean();
ob_start();
?>
<button type="button" class="btn wpsc_popup_close" onclick="wpsc_modal_close();"><?php _e('Close','supportcandy');?></button>
<button type="button" class="btn wpsc_popup_action" onclick="wpsc_set_add_agent_role();"><?php _e('Submit','supportcandy');?></button>
<?php 
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);
