<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}
$wpsc_gdpr_html= get_option('wpsc_gdpr_html');
$wpsc_terms_and_conditions_html=get_option('wpsc_terms_and_conditions_html');
$wpsc_personal_data_retention_period_time = get_option('wpsc_personal_data_retention_period_time');
?>

<form id="wpsc_terms_and_cond_settings" method="post" action="javascript:wpsc_set_terms_and_condition_settings();">
  
  
  <div class="form-group">
    <label for="wpsc_terms_and_conditions"><?php _e('Terms & Canditions','supportcandy');?></label>
    <p class="help-block"><?php _e("Enable or disable Terms and Condition ticket form.","supportcandy");?></p>
    <select class="form-control" name="wpsc_terms_and_conditions" id="wpsc_terms_and_conditions">
      <?php
      $wpsc_terms_and_conditions = get_option('wpsc_terms_and_conditions');
      $selected = $wpsc_terms_and_conditions == '1' ? 'selected="selected"' : '';
      echo '<option '.$selected.' value="1">'.__('Enable','supportcandy').'</option>';
      $selected = $wpsc_terms_and_conditions == '0' ? 'selected="selected"' : '';
      echo '<option '.$selected.' value="0">'.__('Disable','supportcandy').'</option>';
      ?>
    </select>
  </div>
	
	<div class="form-group">
		<label for="wpsc_terms_and_conditions_html"><?php _e('Terms and Conditions Text','supportcandy');?></label>
		<p class="help-block"><?php _e("Text to show on create ticket page.","supportcandy");?></p>
		<textarea class="form-control" name="wpsc_terms_and_conditions_html" id="wpsc_terms_and_conditions_html"><?php echo htmlentities(stripcslashes($wpsc_terms_and_conditions_html))?></textarea>		
	</div>
	
	<div class="form-group">
    <label for="wpsc_gdpr_settings"><?php _e('GDPR','supportcandy');?></label>
    <p class="help-block"><?php _e('Enable or disable GDPR condition on ticket form.','supportcandy');?></p>
    <select class="form-control" name="wpsc_gdpr_settings" id="wpsc_gdpr_settings">
      <?php
      $wpsc_set_in_gdpr = get_option('wpsc_set_in_gdpr');			
      $selected_gdpr = $wpsc_set_in_gdpr == '1' ? 'selected="selected"' : '';		
      echo '<option '.$selected_gdpr.' value="1">'.__('Enable','supportcandy').'</option>';
      $selected_gdpr = $wpsc_set_in_gdpr == '0' ? 'selected="selected"' : '';
      echo '<option '.$selected_gdpr.' value="0">'.__('Disable','supportcandy').'</option>';
      ?>
    </select>
  </div>
	
	<div class="form-group">
		<label for="wpsc_gdpr_html"><?php _e('GDPR Text','supportcandy');?></label>
		<p class="help-block"><?php _e("Text to show on create ticket page.","supportcandy");?></p>
		<textarea class="form-control" name="wpsc_gdpr_html" id="wpsc_gdpr_html"><?php echo htmlentities(stripcslashes($wpsc_gdpr_html))?></textarea>		
	</div>
	
	<div class="form-group">
    <label for="wpsc_personal_data_retention_period"><?php _e('Personal Data Retention','supportcandy');?></label>
    <p class="help-block"><?php _e('Enable or disable personal data retention policy .','supportcandy');?></p>
		<select class="form-control" name="wpsc_personal_data_retention_type" id="wpsc_personal_data_retention_type">
			<?php
			$wpsc_personal_data_retention_type = get_option('wpsc_personal_data_retention_type');
			$selected = $wpsc_personal_data_retention_type == 'disable' ? 'selected="selected"' : '';
			echo '<option '.$selected.' value="disable">'.__('Disable','supportcandy').'</option>';
			$selected = $wpsc_personal_data_retention_type == 'enable' ? 'selected="selected"' : '';
			echo '<option '.$selected.' value="enable">'.__('Enable','supportcandy').'</option>';
			?>
    </select>
  </div>
	
	<div class="form-group" id="wpsc_personal_data_retention_period_div" style="display:none;" >
    <label for="wpsc_personal_data_retention_period"><?php _e('Set retention period','supportcandy');?></label>
    <p class="help-block"><?php _e('Set time for which personal data should be retained.','supportcandy');?></p>
		<div class="row">
			<div class="col-sm-6" style="padding-left:0px !important;">
					<input type="number" id="wpsc_personal_data_retention_period_time" class="form-control" name="wpsc_personal_data_retention_period_time" value="<?php echo $wpsc_personal_data_retention_period_time?>" />
			</div>
			
			<div class="col-sm-6" style="padding-right: 0px !important;">
				 <select class="form-control" name="wpsc_personal_data_retention_period_unit">
					 <?php
					 $wpsc_personal_data_retention_period_unit = get_option('wpsc_personal_data_retention_period_unit');
					 $selected = $wpsc_personal_data_retention_period_unit == 'days' ? 'selected="selected"' : '';
					 echo '<option '.$selected.' value="days">Days</option>';
					 $selected = $wpsc_personal_data_retention_period_unit == 'months' ? 'selected="selected"' : '';
					 echo '<option '.$selected.' value="months">Months</option>';
					 $selected = $wpsc_personal_data_retention_period_unit == 'years' ? 'selected="selected"' : '';
					 echo '<option '.$selected.' value="years">Years</option>';
					 ?>
				 </select>
		  </div>
   </div>
 </div>

  <button type="submit" class="btn btn-success"><?php _e('Save Changes','supportcandy');?></button>
  <img class="wpsc_submit_wait" style="display:none;" src="<?php echo WPSC_PLUGIN_URL.'asset/images/ajax-loader@2x.gif';?>">
  <input type="hidden" name="action" value="wpsc_settings" />
  <input type="hidden" name="setting_action" value="set_terms_and_condition_settings" />
</form>  
<script>

jQuery(document).ready(function(){
	
	<?php
	if($wpsc_personal_data_retention_type == 'enable'){
		?>
		jQuery('#wpsc_personal_data_retention_period_div').show();
		<?php
	}
	?>
	jQuery('#wpsc_personal_data_retention_type').on('change', function() {
		if(this.value=='enable'){			 
			 jQuery('#wpsc_personal_data_retention_period_div').show();
		}else {
			 jQuery('#wpsc_personal_data_retention_period_div').hide(); 
		}
 });
});

tinymce.remove();
tinymce.init({ 
  selector:'#wpsc_gdpr_html',
  body_id: 'gdpr_body',
  menubar: false,
	statusbar: false,
  height : '100',
  plugins: [
      'lists link image directionality'
  ],
  image_advtab: true,
  toolbar: 'bold italic underline blockquote | alignleft aligncenter alignright | bullist numlist | rtl | link image',
  branding: false,
  autoresize_bottom_margin: 20,
  browser_spellcheck : true,
  relative_urls : false,
  remove_script_host : false,
  convert_urls : true,
	setup: function (editor) {
  }
});
tinymce.init({ 
  selector:'#wpsc_terms_and_conditions_html',
  body_id: 'gdpr_body',
  menubar: false,
	statusbar: false,
  height : '100',
  plugins: [
      'lists link image directionality'
  ],
  image_advtab: true,
  toolbar: 'bold italic underline blockquote | alignleft aligncenter alignright | bullist numlist | rtl | link image',
  branding: false,
  autoresize_bottom_margin: 20,
  browser_spellcheck : true,
  relative_urls : false,
  remove_script_host : false,
  convert_urls : true,
	setup: function (editor) {
  }
});
</script>