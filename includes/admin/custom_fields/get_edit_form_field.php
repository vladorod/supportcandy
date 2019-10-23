<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$field_id              = isset($_POST) && isset($_POST['field_id']) ? intval($_POST['field_id']) : 0;
if (!$field_id) {exit;}

$custom_field =  get_term_by('id', $field_id, 'wpsc_ticket_custom_fields');
$wpsc_tf_label = get_term_meta( $custom_field->term_id, 'wpsc_tf_label', true);
$wpsc_tf_extra_info = get_term_meta( $custom_field->term_id, 'wpsc_tf_extra_info', true);
$agentonly = get_term_meta( $custom_field->term_id, 'agentonly', true);
$wpsc_tf_status = get_term_meta( $custom_field->term_id, 'wpsc_tf_status', true);
$wpsc_tf_type = get_term_meta( $custom_field->term_id, 'wpsc_tf_type', true);
$wpsc_tf_has_options = 0;
$wpsc_tf_options = get_term_meta( $custom_field->term_id, 'wpsc_tf_options', true);
$wpsc_tf_required = get_term_meta( $custom_field->term_id, 'wpsc_tf_required', true);
$wpsc_tf_width = get_term_meta( $custom_field->term_id, 'wpsc_tf_width', true);
$wpsc_tf_visibility = get_term_meta( $custom_field->term_id, 'wpsc_tf_visibility', true);
$wpsc_tf_personal_info   = get_term_meta( $custom_field->term_id, 'wpsc_tf_personal_info', true);
$wpsc_tf_limit = get_term_meta( $custom_field->term_id, 'wpsc_tf_limit',true);
$wpsc_tf_placeholder_text = get_term_meta(	$custom_field->term_id, 'wpsc_tf_placeholder_text',true);

$field_types           = $wpscfunction->get_custom_field_types();
$conditional_types     = array();
foreach ($field_types as $key => $field) {
	if ($field['has_options']) {
		$conditional_types[]=$key;
	}
}
$placeholder_text = array('1','5','6','7','8','9','18');
$style = '';
if (!(in_array($wpsc_tf_type,$placeholder_text))) {
	$style = 'display:none';
}

$tf_types = array(1,5,7,8,9);
$l_style    = '';
if(!in_array($wpsc_tf_type,$tf_types)){
	$l_style = "display:none";
}

ob_start();
?>
<div class="form-group">
  <label for="wpsc_tf_label"><?php _e('Field Label','supportcandy');?></label>
  <p class="help-block"><?php _e('Insert field label. Please make sure label you are entering should not already exist.','supportcandy');?></p>
  <input id="wpsc_tf_label" class="form-control" name="wpsc_tf_label" value="<?php echo str_replace('"', "&quot;", stripcslashes($wpsc_tf_label))?>" />
</div>
<div class="form-group">
  <label for="wpsc_tf_extra_info"><?php _e('Extra Information','supportcandy');?></label>
  <p class="help-block"><?php _e('Extra information about the field. Useful if you want to give instructions or information about the field in create ticket from. Keep this empty if not needed.','supportcandy');?></p>
  <input id="wpsc_tf_extra_info" class="form-control" name="wpsc_tf_extra_info" value="<?php echo str_replace('"', "&quot;", stripcslashes($wpsc_tf_extra_info))?>" />
</div>
<div class="form-group">
  <label for="wpsc_tf_personal_info"><?php _e('Personal Information','supportcandy');?></label>
  <p class="help-block"><?php _e('Enable or disable personal information in ticket form.','supportcandy');?></p>
  <select id="wpsc_tf_personal_info" class="form-control" name="wpsc_tf_personal_info">
		<option <?php echo $wpsc_tf_personal_info == '1' ? 'selected="selected"' : ''?> value="1"><?php _e('Yes','supportcandy');?></option>
		<option <?php echo $wpsc_tf_personal_info == '0' ? 'selected="selected"' : ''?> value="0"><?php _e('No','supportcandy');?></option>
	</select>
</div>
<div class="form-group">
  <label for="wpsc_tf_type"><?php _e('Field Type','supportcandy');?></label>
  <p class="help-block"><?php _e('Select field type.','supportcandy');?></p>
  <select id="wpsc_tf_type" class="form-control" name="wpsc_tf_type">
		<?php foreach ($field_types as $key => $field) :
      $selected = $wpsc_tf_type == $key ? 'selected="selected"' : '';
      if ($wpsc_tf_type == $key && $field['has_options']) {
        $wpsc_tf_has_options = 1;
      }
      ?>
			<option <?php echo $selected?> data-options="<?php echo $field['has_options']?>" value="<?php echo $key?>"><?php echo $field['label']?></option>
		<?php endforeach;?>
	</select>
</div>

<div class="form-group" id= "wpsc_placeholder_text"  style="<?php echo $style; ?>">
  <label for="wpsc_tf_placeholder_text"><?php _e('Placeholder text','supportcandy');?></label>
  <p class="help-block"><?php _e('Enter the placeholder text','supportcandy');?></p>
  <input id="wpsc_tf_placeholder_text" class="form-control" name="wpsc_tf_placeholder_text" value="<?php echo str_replace('"', "&quot;", stripcslashes($wpsc_tf_placeholder_text))?>" />
</div>

<div class="form-group" id="wpsc_tf_options_container" style="<?php echo !$wpsc_tf_has_options?'display:none;':'';?>">
  <label for="wpsc_tf_options"><?php _e('Field Options','supportcandy');?></label>
  <p class="help-block"><?php _e('Insert field options. New option should begin on new line.','supportcandy');?></p>
  <textarea style="height:80px !important;" id="wpsc_tf_options" class="form-control" name="wpsc_tf_options"><?php echo stripcslashes(implode('\n', $wpsc_tf_options));?></textarea>
</div>
<div class="form-group">
  <label for="wpsc_tf_required"><?php _e('Required','supportcandy');?></label>
  <p class="help-block"><?php _e('Whether this field is medatory or optional. Yes indicates mendatory whereas no indicates optional.','supportcandy');?></p>
  <select id="wpsc_tf_required" class="form-control" name="wpsc_tf_required">
		<option <?php echo $wpsc_tf_required == '1' ? 'selected="selected"' : ''?> value="1"><?php _e('Yes','supportcandy');?></option>
		<option <?php echo $wpsc_tf_required == '0' ? 'selected="selected"' : ''?> value="0"><?php _e('No','supportcandy');?></option>
	</select>
</div>
<div class="form-group">
  <label for="wpsc_tf_width"><?php _e('Width','supportcandy');?></label>
  <p class="help-block"><?php _e('Please select width of the field in create ticket form.','supportcandy');?></p>
  <select id="wpsc_tf_width" class="form-control" name="wpsc_tf_width">
		<option <?php echo $wpsc_tf_width == '1/3' ? 'selected="selected"' : ''?> value="1/3"><?php _e('1/3 width of Row','supportcandy');?></option>
		<option <?php echo $wpsc_tf_width == '1/2' ? 'selected="selected"' : ''?> value="1/2"><?php _e('Half width of Row','supportcandy');?></option>
		<option <?php echo $wpsc_tf_width == '1' ? 'selected="selected"' : ''?> value="1"><?php _e('Full width of Row','supportcandy');?></option>
	</select>
</div>
<div class="form-group wpsc_edit_limit" id = "wpsc_add_limit" style = "<?php echo $l_style;?>">
  <label for="wpsc_tf_limit"><?php _e('Character Limit','supportcandy');?></label>
  <p class="help-block"><?php _e('Limit number of characters to be accepted for this field. Enter 0 for unlimited characters.','supportcandy');?></p>
  <input type = "number" id="wpsc_tf_limit" class="form-control" name="wpsc_tf_limit" value="<?php echo($wpsc_tf_limit) ?>" />
</div>
<div class="form-group">
  <label for="wpsc_tf_form_visibility"><?php _e('Visibility','supportcandy');?></label>
  <p class="help-block"><?php _e('Select conditions to show this field in create ticket form. Visible if no conditions set. Visible if any one of added conditions matches while creating ticket.','supportcandy');?></p>
  <div class="row">
  	<ul id="wpsc_tf_condition_container" class="wpsp_filter_display_container">
			<?php
			foreach ($wpsc_tf_visibility as $key => $value) {
				$condition = explode('--', stripslashes($value));
				$tf =  get_term_by('id', $condition[0], 'wpsc_ticket_custom_fields');
				$label = get_term_meta( $condition[0], 'wpsc_tf_label', true);
				$value_label = $condition[1];
				if ($tf->slug=='ticket_category') {
					$category =  get_term_by('id', $value_label, 'wpsc_categories');
					$value_label = $category->name;
				}
				if ($tf->slug=='ticket_priority') {
					$priority =  get_term_by('id', $value_label, 'wpsc_priorities');
					$value_label = $priority->name;
				}
				?>
				<li class="wpsp_filter_display_element">
					<div class="flex-container">
						<div class="wpsp_filter_display_text">
							<?php echo htmlentities($label)?>: <?php echo htmlentities($value_label)?>
							<input type="hidden" name="wpsp_tf_condition[]" value="<?php echo htmlentities($value) ?>">
						</div>
						<div class="wpsp_filter_display_remove" onclick="wpsc_remove_filter(this);"><i class="fa fa-times"></i></div>
					</div>
				</li>
				<?php
			}
			?>
		</ul>
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
					'exclude'    => array($custom_field->term_id),
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
					<option value="<?php echo $field->term_id?>"><?php echo htmlentities($label)?></option>
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
<button type="button" class="btn wpsc_popup_action" onclick="wpsc_set_edit_form_field(<?php echo htmlentities($field_id)?>);"><?php _e('Submit','supportcandy');?></button>
<?php 
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);
