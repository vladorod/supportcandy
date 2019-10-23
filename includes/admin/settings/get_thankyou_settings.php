<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$wpsc_thankyou_html = stripslashes(get_option('wpsc_thankyou_html'));
$wpsc_thankyou_url = get_option('wpsc_thankyou_url');

?>
<form id="wpsc_frm_thankyou_settings" method="post" action="javascript:wpsc_set_thankyou_settings();">
    
    <div class="form-group">
      <label for="wpsc_thankyou_html"><?php _e('Thank you text','supportcandy');?></label>
      <p class="help-block"><?php _e("Text to show on thank you screen.","supportcandy");?></p>
			<div class="text-right">
				<button id="visual" class="btn btn-primary btn-xs" type="button" onclick="wpsc_get_tinymce('wpsc_thankyou_html','thankyou_body');"><?php _e('Visual','supportcandy');?></button>
				<button id="text" class="btn btn-default btn-xs" type="button" onclick="wpsc_get_textarea()"><?php _e('Text','supportcandy');?></button>
      </div>
			<textarea class="form-control" name="wpsc_thankyou_html" id="wpsc_thankyou_html"><?php echo htmlentities($wpsc_thankyou_html)?></textarea>
    	<div class="row attachment_link">
					<span onclick="wpsc_get_templates(); "><?php _e('Insert Macros','supportcandy') ?></span>
			</div>
	</div>
	
		<div class="form-group">
      <label for="wpsc_thankyou_url"><?php _e('Thank you page redirect url','supportcandy');?></label>
      <p class="help-block"><?php _e("Url to redirect in case you want to show your custom thank you page. Keep this empty to show above default thank you messege.","supportcandy");?></p>
      <input type="text" class="form-control" name="wpsc_thankyou_url" id="wpsc_thankyou_url" value="<?php echo $wpsc_thankyou_url?>" />
    </div>
		
		<?php do_action('wpsc_get_thankyou_settings');?>
		
    <button type="submit" class="btn btn-success"><?php _e('Save Changes','supportcandy');?></button>
    <img class="wpsc_submit_wait" style="display:none;" src="<?php echo WPSC_PLUGIN_URL.'asset/images/ajax-loader@2x.gif';?>">
    <input type="hidden" name="action" value="wpsc_settings" />
    <input type="hidden" name="setting_action" value="set_thankyou_settings" />
    
</form>

<script>
tinymce.remove();
tinymce.init({ 
  selector:'#wpsc_thankyou_html',
  body_id: 'thankyou_body',
  menubar: false,
	statusbar: false,
  height : '200',
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