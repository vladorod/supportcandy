
<?php
if ( ! defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}
global $current_user;
$wpsc_appearance_signup = get_option('wpsc_appearance_signup');
$general_appearance     = get_option('wpsc_appearance_general_settings');
$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');

$wpsc_registration_captcha = get_option('wpsc_registration_captcha');
$wpsc_recaptcha_type       = get_option('wpsc_recaptcha_type');
$wpsc_get_site_key         = get_option('wpsc_get_site_key');
$wpsc_get_secret_key       = get_option('wpsc_get_secret_key');

?>
<head><script src='https://www.google.com/recaptcha/api.js'></script></head>
	<div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
			<h2 class="form-signup-heading"><?php echo __('Please Sign Up','supportcandy')?></h2>
		  <form id="wpsc_frm_signup_user" method="post">
						<div class="form-group reg_required">
						 <label for="wpsc_register_user_first_name" style="color:<?php echo $general_appearance['wpsc_text_color']?> !important;"><?php _e('First Name','supportcandy');?></label>
						 <input type="text" id="firstname"  class="form-control" name="firstname"   />
						 <div id="wpsc_register_username_error"></div>
					 	</div>
					 
						<div class="form-group reg_required">
							<label for="wpsc_register_user_last_name" style="color:<?php echo $general_appearance['wpsc_text_color']?> !important;"><?php _e('Last Name','supportcandy');?></label>
							<input type="text" id="lastname"  class="form-control" name="lastname"   />
							<div id="wpsc_register_username_error"></div>
						</div>
					 
				   <div class="form-group reg_required">
				     <label for="wpsc_register_user_name" style="color:<?php echo $general_appearance['wpsc_text_color']?> !important;"><?php _e('Username','supportcandy');?></label>
			       <input type="text" id="username"  class="form-control" name="username"   />
						 <div id="wpsc_register_username_error"></div>
				   </div>
					
		       <div class="form-group reg_required">
		         <label for="wpsc_register_email" style="color:<?php echo $general_appearance['wpsc_text_color']?> !important;"><?php _e('Email','supportcandy');?></label>
		         <input id="email" class="form-control" name="email" />      
				     <div id="wpsc_register_email_error"></div>
		       </div>
		    
			     <div class="form-group reg_required">
			         <label for="wpsc_register_pass" style="color:<?php echo $general_appearance['wpsc_text_color']?> !important;"><?php _e('Password','supportcandy');?></label>
			         <input type="password" id="password" class="form-control" name="password" />      
			     </div>

			     <div class="form-group reg_required">
			         <label for="wpsc_register_confirmpass" style="color:<?php echo $general_appearance['wpsc_text_color']?> !important;" ><?php _e('Confirm Password','supportcandy');?></label>
			         <input type="password" id="confirmpassword" class="form-control" name="confirmpassword"  />      
			     </div>
		    	
					<?php
						if($wpsc_registration_captcha && $wpsc_recaptcha_type){
							?>
							<div class="col-md-12 captcha_container" style="margin-bottom:10px;margin-left:0px; display:flex; background-color:<?php echo $wpsc_appearance_create_ticket['wpsc_captcha_bg_color']?> !important;color:<?php echo $wpsc_appearance_create_ticket['wpsc_captcha_text_color']?> !important;">
								<div style="width:25px;">
									<input type="checkbox" onchange ="get_captcha_code(this);" class="wpsc_checkbox" value="1">
									<img id="captcha_wait" style="width:16px;display:none;" src="<?php echo WPSC_PLUGIN_URL.'asset/images/ajax-loader@2x.gif'?>" alt="">
								</div>
								<div style="padding-top:3px;"><?php _e("I'm not a robot",'supportcandy')?></div>
							</div>
							<?php
						}elseif($wpsc_registration_captcha && !$wpsc_recaptcha_type){
							?>
							<div class="col-sm-12" style="margin-bottom:10px;margin-left:0px;display:flex;padding:0px;">
								<div style="width:25px;">
									<div class="g-recaptcha" data-sitekey=<?php echo $wpsc_get_site_key ?>></div>
								</div>
							</div>
							<?php
						}
					?>

			     <div class="form-group">
			         <button type="submit" class="btn btn-sm" name='btnsubmit' onclick="javascript:wpsc_register_user(event);" style="background-color:<?php echo $wpsc_appearance_signup['wpsc_appearance_signup_button_bg_color']?> !important; color:<?php echo $wpsc_appearance_signup['wpsc_appearance_signup_button_text_color']?> !important; border-color:<?php echo $wpsc_appearance_signup['wpsc_appearance_signup_button_border_color']?> !important;"><?php _e('Register Now','supportcandy')?></button>
						   <button type="cancel" class="btn btn-sm" name='btncancel' onclick="javascript:wpsc_sign_in();" style="background-color:<?php echo $wpsc_appearance_signup['wpsc_appearance_cancel_button_bg_color']?> !important;color:<?php echo $wpsc_appearance_signup['wpsc_appearance_cancel_button_text_color']?> !important; border-color:<?php echo $wpsc_appearance_signup['wpsc_appearance_cancel_button_border_color']?> !important;"><?php _e('Cancel','supportcandy');?></button>
	         </div>
					 
					 <input type="hidden" name="action" value="wpsc_tickets" />
			 		 <input type="hidden" name="setting_action" value="submit_user" />
					 <input type="hidden" id="captcha_code" name = "captcha_code" value="">
					 
	  </form>
 </div>
 

<script type="text/javascript">

function wpsc_register_user(event) {
	event.preventDefault();
	jQuery('#wpsc_register_email_error').html('');
	jQuery('#wpsc_register_username_error').html('');
	
	var validation = true;
	
	jQuery('.reg_required').each(function(e){
		if(jQuery(this).find('input').val()=='') validation = false;
	});
	
	if (!validation) {
		alert("<?php _e('Required fields can not be empty!','supportcandy')?>");
		return ;
	}
	
	var email       = jQuery('#email').val();
  var password   	= jQuery('#password').val();
	var confirmpass = jQuery('#confirmpassword').val();
	
	if(!validateEmail(email)) {
		validation = false;
	 	alert('<?php _e('Please enter correct email address!','supportcandy')?>');
		return;
	}
	
	if(password!=confirmpass) {
		validation = false;
		alert('<?php _e('Password and confirm password does not match.','supportcandy')?>');
		return;
	}else{
		<?php
		if($wpsc_registration_captcha && $wpsc_recaptcha_type){
			?>
			if (jQuery('#captcha_code').val().trim().length==0) {
				alert("<?php _e('Please confirm you are not a robot!','supportcandy')?>");
				validation = false;
				return;
			}
			<?php
		}elseif($wpsc_registration_captcha && !$wpsc_recaptcha_type){
			?>
			var recaptcha = jQuery("#g-recaptcha-response").val();
			if (recaptcha === "") {
				alert("<?php _e('Please confirm you are not a robot!','supportcandy')?>");
				validation = false;
				return;
			}
			<?php
		}
		?>
	}
	
	if(validation){
		var dataform = new FormData(jQuery('#wpsc_frm_signup_user')[0]);
		jQuery.ajax({
		    url: wpsc_admin.ajax_url,
		    type: 'POST',
		    data: dataform,
		    processData: false,
		    contentType: false
		  })
		.done(function (response_str) {
			var response=JSON.parse(response_str);
			if (response.error == '1'){
				jQuery('#wpsc_register_email_error').html("<?php _e('This email is already registered, please choose another one.','supportcandy')?>");
			} else if(response.error == '2'){
				jQuery('#wpsc_register_username_error').html("<?php _e('This username is already registered. Please choose another one.','supportcandy')?>");
			} else {
				jQuery('#wpsc_register_email_error').hide();
				location.reload(true);
			}
		});	
	}
	
}

function get_captcha_code(e){
	jQuery(e).hide();
	jQuery('#captcha_wait').show();
	var data = {
		action: 'wpsc_tickets',
		setting_action : 'get_captcha_code'
	};
	jQuery.post(wpsc_admin.ajax_url, data, function(response) {
		jQuery('#captcha_code').val(response);
		jQuery('#captcha_wait').hide();
		jQuery(e).show();
		jQuery(e).prop('disabled',true);
	});
}
</script>

