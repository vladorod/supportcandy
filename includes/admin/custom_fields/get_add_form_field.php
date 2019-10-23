<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$field_types = $wpscfunction->get_custom_field_types();
$conditional_types = array();
foreach ($field_types as $key => $field) {
	if ($field['has_options']) {
		$conditional_types[]=$key;
	}
}

ob_start();
?>
<div class="form-group">
  <label for="wpsc_tf_label"><?php _e('Field Label','supportcandy');?></label>
  <p class="help-block"><?php _e('Insert field label. Please make sure label you are entering should not already exist.','supportcandy');?></p>
  <input id="wpsc_tf_label" class="form-control" name="wpsc_tf_label" value="" />
</div>
<div class="form-group">
  <label for="wpsc_tf_extra_info"><?php _e('Extra Information','supportcandy');?></label>
  <p class="help-block"><?php _e('Extra information about the field. Useful if you want to give instructions or information about the field in create ticket from. Keep this empty if not needed.','supportcandy');?></p>
  <input id="wpsc_tf_extra_info" class="form-control" name="wpsc_tf_extra_info" value="" />
</div>
<div class="form-group">
  <label for="wpsc_tf_personal_info"><?php _e('Personal Information','supportcandy');?></label>
  <p class="help-block"><?php _e('Enable or disable personal information in ticket form.','supportcandy');?></p>
  <select id="wpsc_tf_personal_info" class="form-control" name="wpsc_tf_personal_info">
		<option value="0"><?php _e('No','supportcandy');?></option>
		<option value="1"><?php _e('Yes','supportcandy');?></option>
	</select>
</div>
<div class="form-group">
  <label for="wpsc_tf_type"><?php _e('Field Type','supportcandy');?></label>
  <p class="help-block"><?php _e('Select field type.','supportcandy');?></p>
  <select id="wpsc_tf_type" class="form-control" name="wpsc_tf_type">
		<?php foreach ($field_types as $key => $field) :?>
			<option data-options="<?php echo $field['has_options']?>" value="<?php echo $key?>"><?php echo $field['label']?></option>
		<?php endforeach;?>
	</select>
</div>
<div id= "wpsc_placeholder_text" class="form-group" >
  <label for="wpsc_tf_type"><?php _e('Placeholder Text','supportcandy');?></label>
  <p class="help-block"><?php _e('Enter the placeholder text.','supportcandy');?></p>
	<input id="wpsc_tf_placeholder_text" class="form-control" name="wpsc_tf_placeholder_text" value="" />
</div>
<div class="form-group" id="wpsc_tf_options_container" style="display:none;">
  <label for="wpsc_tf_options"><?php _e('Field Options','supportcandy');?></label>
  <p class="help-block"><?php _e('Insert field options. New option should begin on new line.','supportcandy');?></p>
  <textarea style="height:80px !important;" id="wpsc_tf_options" class="form-control" name="wpsc_tf_options"></textarea>
</div>
<div class="form-group">
  <label for="wpsc_tf_required"><?php _e('Required','supportcandy');?></label>
  <p class="help-block"><?php _e('Whether this field is medatory or optional. Yes indicates mendatory whereas no indicates optional.','supportcandy');?></p>
  <select id="wpsc_tf_required" class="form-control" name="wpsc_tf_required">
		<option value="1"><?php _e('Yes','supportcandy');?></option>
		<option value="0"><?php _e('No','supportcandy');?></option>
	</select>
</div>
<div class="form-group">
  <label for="wpsc_tf_width"><?php _e('Width','supportcandy');?></label>
  <p class="help-block"><?php _e('Please select width of the field in create ticket form.','supportcandy');?></p>
  <select id="wpsc_tf_width" class="form-control" name="wpsc_tf_width">
		<option value="1/3"><?php _e('1/3 width of Row','supportcandy');?></option>
		<option value="1/2"><?php _e('Half width of Row','supportcandy');?></option>
		<option value="1"><?php _e('Full width of Row','supportcandy');?></option>
	</select>
</div>
<div class="form-group" id = "wpsc_add_limit">
  <label for="wpsc_tf_add_limit"><?php _e('Character Limit','supportcandy');?></label>
  <p class="help-block"><?php _e('Limit number of characters to be accepted for this field. Enter 0 for unlimited characters.','supportcandy');?></p>
  <input type = "number" id="wpsc_tf_add_limit" class="form-control" name="wpsc_tf_add_limit" value="" />
</div>
<div class="form-group">
  <label for="wpsc_tf_form_visibility"><?php _e('Visibility','supportcandy');?></label>
  <p class="help-block"><?php _e('Select conditions to show this field in create ticket form. Visible if no conditions set. Visible if any one of added conditions matches while creating ticket.','supportcandy');?></p>
  <div class="row">
  	<ul id="wpsc_tf_condition_container" class="wpsp_filter_display_container"></ul>
  </div>
	<div class="row" style="background-color:#CCD1D1;padding-top:15px;padding-bottom:15px;">
		<div class="col-sm-5">
			<select id="wpsc_tf_vcf" class="form-control" onchange="wpsc_get_conditional_options(this);" name="wpsc_tf_vcf">
				<option value=""><?php _e('Select field','supportcandy');?></option>
				<?php
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
				      'value'     => '0',
				      'compare'   => '='
				    ),
						array(
							'relation' => 'OR',
							array(
					      'key'       => 'wpsc_tf_type',
					      'value'     => $conditional_types,
					      'compare'   => 'IN'
					    ),
							array(
					      'key'       => 'wpsc_tf_conditional',
					      'value'     => '1',
					      'compare'   => '='
					    )
						)
					),
				]);
				foreach ($fields as $field) {
					$label = get_term_meta( $field->term_id, 'wpsc_tf_label', true);
					?>
					<option value="<?php echo $field->term_id?>"><?php echo $label?></option>
					<?php
				}
				?>
			</select>
		</div>
		<div class="col-sm-5">
			<select id="wpsc_tf_vco" class="form-control" name="wpsc_tf_vco">
				<option value=""><?php _e('Select option','supportcandy');?></option>
			</select>
		</div>
		<div class="col-sm-2">
			<button type="button" class="form-control btn btn-success" onclick="wpsc_add_field_condition();"><?php _e('Add','supportcandy');?></button>
		</div>
  </div>
</div>
<script>
	jQuery('#wpsc_tf_type').change(function(){
		var has_options = jQuery('option:selected',this).data('options');
		if(has_options=='1'){
			jQuery('#wpsc_tf_options_container').show();
		} else {
			jQuery('#wpsc_tf_options_container').hide();
		}
		
		var option = Number(jQuery(this).val());
		var opt_arr = [1,5,7,8,9];
		if ( jQuery.inArray( option, opt_arr) >= 0) {
			jQuery('#wpsc_add_limit').show();
		}else{
			jQuery('#wpsc_add_limit').hide();
		}
		
		var poption = Number(jQuery(this).val());
		var popt_arr = [1,5,6,7,8,9,18];
		if ( jQuery.inArray( poption, popt_arr) >= 0) {
			jQuery('#wpsc_placeholder_text').show();
		}else{
			jQuery('#wpsc_placeholder_text').hide();
		}

	});
</script>
<?php 
$body = ob_get_clean();
ob_start();
?>
<button type="button" class="btn wpsc_popup_close" onclick="wpsc_modal_close();"><?php _e('Close','supportcandy');?></button>
<button type="button" class="btn wpsc_popup_action" onclick="wpsc_set_add_form_field();"><?php _e('Submit','supportcandy');?></button>
<?php 
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);
