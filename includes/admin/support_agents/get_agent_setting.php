<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $current_user;

if (!($current_user->ID && $current_user->has_cap('wpsc_agent'))) {exit;}

$wpsc_agent_body = get_user_meta($current_user->ID,'wpsc_agent_signature',true);

$wpsc_appearance_modal_window = get_option('wpsc_modal_window');

ob_start();
?>
<form id="wpsc_frm_agent_setting" method="post">
      <div class="form-group">
         <label class="label label-default" style="font-size:15px;"><?php _e('Signature','supportcandy') ?></label>
      </div>
			<div class="text-right">
        <button id="visual" class="btn btn-primary btn-xs" type="button" onclick="wpsc_get_agent_signature_tinymce('wpsc_agent_signature','agent_signature');"><?php _e('Visual','supportcandy');?></button>
        <button id="text" class="btn btn-default btn-xs" type="button" onclick="wpsc_get_textarea()"><?php _e('Text','supportcandy');?></button>
      </div>
      <div class="form-group">
         <textarea class="form-control" name="wpsc_agent_signature" id="wpsc_agent_signature"><?php echo html_entity_decode($wpsc_agent_body) ?></textarea>
      </div>
      <?php do_action('wpsc_get_agent_setting');?>
      
      <input type="hidden" name="action" value="wpsc_support_agents" />
      <input type="hidden" name="setting_action" value="set_agent_setting" />
</form> 
     
<script>
tinymce.remove();

jQuery(document).ready(function() {
	wpsc_get_agent_signature_tinymce();
});
function wpsc_get_agent_signature_tinymce(selector,body_id){
	jQuery('#visual').addClass('btn btn-primary');
  jQuery('#text').removeClass('btn btn-primary');
  jQuery('#text').addClass('btn btn-default');
	tinymce.init({ 
	  selector:'#wpsc_agent_signature',
	  body_id: 'agent_signature',
	  menubar: false,
		statusbar: false,
	  height : '200',
	  plugins: [
	      'lists link image directionality'
	  ],
	  image_advtab: true,
	  toolbar: 'bold italic underline blockquote | alignleft aligncenter alignright | bullist numlist | rtl | link image',
		file_picker_types: 'image',
		file_picker_callback: function(cb, value, meta) {
			var input = document.createElement('input');
			input.setAttribute('type', 'file');
			input.setAttribute('accept', 'image/*');
			
			input.onchange = function() {
				var file = this.files[0];
				var form_data = new FormData();
				form_data.append('file',file);
				form_data.append('file_name',file.name);
				form_data.append('action','wpsc_tickets');
				form_data.append('setting_action','rb_upload_file');
				jQuery.ajax({
					type : 'post',
					url : wpsc_admin.ajax_url,
					cache: false,
					contentType: false,
					processData: false,
					data: form_data,
					success: function(response_data){
						var responce = JSON.parse(response_data);
						var reader   = new FileReader();
						reader.onload = function () {
							var id        = 'blobid' + (new Date()).getTime();
							var blobCache = tinymce.activeEditor.editorUpload.blobCache;
							var base64    = reader.result.split(',')[1];
							var blobInfo  = blobCache.create(id, file, base64);
							blobCache.add(blobInfo);
							if (responce) {
								cb(responce, { title: 'attach' });
							} else {
								alert("<?php _e('Attached file type not allowed!','supportcandy')?>");
							}
						};
						reader.readAsDataURL(file);
					}
				});
				
			};
			input.click();
		},
	  branding: false,
	  autoresize_bottom_margin: 20,
	  browser_spellcheck : true,
	  relative_urls : false,
	  remove_script_host : false,
	  convert_urls : true,
		setup: function (editor) {
	  }
	});
}
</script>

<?php 
$body = ob_get_clean();

ob_start();
?>
<button type="button" class="btn wpsc_popup_close"  style="background-color:<?php echo $wpsc_appearance_modal_window['wpsc_close_button_bg_color']?> !important;color:<?php echo $wpsc_appearance_modal_window['wpsc_close_button_text_color']?> !important;"    onclick="wpsc_modal_close();"><?php _e('Close','supportcandy');?></button>
<button type="button" class="btn wpsc_popup_action" style="background-color:<?php echo $wpsc_appearance_modal_window['wpsc_action_button_bg_color']?> !important;color:<?php echo $wpsc_appearance_modal_window['wpsc_action_button_text_color']?> !important;" onclick="wpsc_set_agent_setting();"><?php _e('Save Settings','supportcandy');?></button>
<?php 
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);