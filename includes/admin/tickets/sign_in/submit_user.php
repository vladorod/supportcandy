<?php
if ( ! defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

$username = isset($_POST) && isset($_POST['username']) ? sanitize_text_field($_POST['username']) : '';
if (!$username) {exit;}
 
$email = isset($_POST) && isset($_POST['email']) ? sanitize_text_field($_POST['email']) : '';
if (!$email) {exit;}
	
$password = isset($_POST) && isset($_POST['password']) ? sanitize_text_field($_POST['password']) : '';
if (!$password) {exit;}

$firstname = isset($_POST) && isset($_POST['firstname']) ? sanitize_text_field($_POST['firstname']) : '';

$lastname = isset($_POST) && isset($_POST['lastname']) ? sanitize_text_field($_POST['lastname']) : '';

$wpsc_captcha        = get_option('wpsc_registration_captcha',0);
$wpsc_recaptcha_type = get_option('wpsc_recaptcha_type');
$wpsc_get_secret_key = get_option('wpsc_get_secret_key');
if($wpsc_captcha){
	if($wpsc_recaptcha_type){
		$captcha_key =  isset($_COOKIE) && isset($_COOKIE['wpsc_secure_code']) ? intval($_COOKIE['wpsc_secure_code']) : 0;
		if(!isset($_POST['captcha_code']) || !wp_verify_nonce($_POST['captcha_code'],$captcha_key)){
		    die(__('Cheating huh?', 'supportcandy'));
		}
	}
	else {
		if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
  {
        $secret = $wpsc_get_secret_key;
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if($responseData->success === false)
        {
					die(__('Cheating huh?', 'supportcandy'));
            
        }
        
   }
	
	}
	setcookie('wpsc_secure_code','123');
}

$response=array();
if ( email_exists($email) ) {
	$response['error'] = '1';
} else if( username_exists($username) ){
	$response['error'] = '2';
}else {
 	$user_id = wp_create_user($username,$password,$email);
  $response['error'] = '0';	
	$creds = array(
	  'user_login'    => $username,
	  'user_password' => $password,
	);
  wp_signon( $creds, false );
	
	if($firstname){
		update_user_meta($user_id,'first_name', $firstname);	
	}
	if($lastname){
		update_user_meta($user_id,'last_name', $lastname);
	}
}



echo json_encode($response);
  
?>