<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$enable_login_settings = get_option('wpsc_default_login_setting');
$custom_login_url      = get_option('wpsc_custom_login_url');
$wpsc_appearance_login_form=get_option('wpsc_appearance_login_form');

$general_appearance = get_option('wpsc_appearance_general_settings');

$signin_button_css = 'background-color:'.$wpsc_appearance_login_form['wpsc_signin_button_bg_color'].' !important;color:'.$wpsc_appearance_login_form['wpsc_signin_button_text_color'].' !important;border-color:'.$wpsc_appearance_login_form['wpsc_signin_button_border_color'].' !important;';

?>
<div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4" style="margin-bottom:20px;">
<?php 
do_action('wpsc_before_signin_module');
if($enable_login_settings=='1') {?>
	
		<h2 class="form-signin-heading"><?php echo __('Please sign in','supportcandy')?></h2>
		
		<form id="frm_wpsc_sign_in" action="javascript:wpsc_sign_in();" method="post" style="margin-bottom:5px;">
			<p id="wpsc_message_login" class="bg-success" style="display:none;"></p>
			<label class="sr-only"><?php echo __('Username or email','supportcandy')?></label>
			<input id="inputEmail" name="username" class="form-control" placeholder="<?php echo __('Username or email','supportcandy')?>" required="" autofocus="" autocomplete="off" type="text" value="<?php echo isset($_POST['username']) ? esc_attr($_POST['username']) : '';?>">
			<label for="inputPassword" class="sr-only"><?php echo __('Password','supportcandy')?></label>
			<input id="inputPassword" name="password" class="form-control" placeholder="<?php echo __('Password','supportcandy')?>" required="" autocomplete="off" type="password">
			<div class="checkbox">
					<label>
							<input name="remember" value="remember-me" type="checkbox"> <p style="color:<?php echo $general_appearance['wpsc_text_color']?> !important;"><?php echo __('Remember me','supportcandy')?></p>
					</label>
					<div class="pull-right forgot-password">
							<a href="<?php echo wp_lostpassword_url()?>" style="color:<?php echo $general_appearance['wpsc_text_color']?> !important;"><?php echo __('Forgot your password?','supportcandy')?></a>
					</div>
			</div>
			<input type="hidden" name="action" value="wpsc_tickets" />
			<input type="hidden" name="setting_action" value="set_user_login" />
			<input type="hidden" name="nonce" value="<?php echo wp_create_nonce()?>" />
			<button id="wpsc_sign_in_btn" class="btn btn-lg btn-block" type="submit" style="<?php echo $signin_button_css ?>"><?php _e('Sign In','supportcandy')?></button>
		
		</form>
		
	<?php 		
} else {	
		$support_page_id  = get_option('wpsc_support_page_id');
		$support_page_url = get_permalink($support_page_id);
		
		$login_url = wp_login_url($support_page_url);
		if( $enable_login_settings == '3' ) {
			if (!preg_match("~^(?:f|ht)tps?://~i", $custom_login_url)) {				 
				$login_url = "http://" . $custom_login_url;					 
			} else {
				$login_url = $custom_login_url;
			}
		}
		
		?>	
		<input class="btn btn-lg btn-block" type="button" id="wpsc_login_link" onclick="window.location.href='<?php echo $login_url; ?>'" value="<?php _e('Sign In','supportcandy');?>" style="margin-top:80px; margin-bottom: 5px; <?php echo $signin_button_css ?>"/>
		<?php
}

if(get_option('wpsc_user_registration')){
	$enable_user_registration_settings =get_option('wpsc_user_registration_method');
	$wpsc_custom_registration_url=get_option('wpsc_custom_registration_url');
	if($enable_user_registration_settings == '1'){	
	?>
		<button  class="btn btn-lg btn-block" onclick="wpsc_signup_user();" type="button" style="background-color:<?php echo $wpsc_appearance_login_form['wpsc_register_now_button_bg_color']?> !important; color:<?php echo $wpsc_appearance_login_form['wpsc_register_now_text_color']?> !important;border-color:<?php echo $wpsc_appearance_login_form['wpsc_register_now_button_border_color']?> !important;"><?php _e('Register Now','supportcandy')?></button>
	<?php 
	} else {
		$registration_url = wp_registration_url();
		if( $enable_user_registration_settings == '3' ) {
			if (!preg_match("~^(?:f|ht)tps?://~i", $wpsc_custom_registration_url)) {				 
				$registration_url = "http://" . $wpsc_custom_registration_url;					 
			} else {
				$registration_url = $wpsc_custom_registration_url;
			}
		}
	?>
		<a href="<?php echo $registration_url; ?>" class="btn btn-lg btn-block" style="background-color:<?php echo $wpsc_appearance_login_form['wpsc_register_now_button_bg_color']?> !important; color:<?php echo $wpsc_appearance_login_form['wpsc_register_now_text_color']?> !important;border-color:<?php echo $wpsc_appearance_login_form['wpsc_register_now_button_border_color']?> !important;"><?php _e('Register Now','supportcandy')?></a>
	<?php	
	}

}
do_action('wpsc_after_signin_module');
?>	
<?php 
if(get_option('wpsc_allow_guest_ticket')):?>

	<button id="wpsc_login_continue_as_guest" class="btn btn-lg btn-block" onclick="wpsc_get_create_ticket();" type="button" style="background-color:<?php echo $wpsc_appearance_login_form['wpsc_continue_as_guest_button_bg_color']?> !important; color:<?php echo $wpsc_appearance_login_form['wpsc_continue_as_guest_button_text_color']?> !important; border-color:<?php echo $wpsc_appearance_login_form['wpsc_continue_as_guest_button_border_color']?> !important;"><?php _e('Continue as Guest','supportcandy')?></button>
<?php 
endif;?>
</div>
<?php do_action('wpsc_after_guest_module'); ?>