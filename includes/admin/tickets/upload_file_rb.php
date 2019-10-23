<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$extensions    = array('jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF');
$tempExtension = explode('.', $_FILES['file']['name']);
$tempExtension = strtolower($tempExtension[count($tempExtension)-1]);

$wpsc_allow_attachment_type = get_option('wpsc_allow_attachment_type');
$wpsc_attachment_type       = explode(',',$wpsc_allow_attachment_type);
$wpsc_attachment_type       = array_map('trim', $wpsc_attachment_type);
$wpsc_attachment_type       = array_map('strtolower', $wpsc_attachment_type);

$file_url = '';
if (in_array($tempExtension,$extensions) && in_array($tempExtension,$wpsc_attachment_type)) {
  $upload_dir     = wp_upload_dir();
  $save_file_name = $_FILES['file']['name'];
  $save_file_name = str_replace(' ','_',$save_file_name);
  $save_file_name = str_replace(',','_',$save_file_name);
  $save_file_name = time().'_'. $save_file_name;

  $save_directory = $upload_dir['basedir'] . '/wpsc/'.$save_file_name;
  $file_url       = $upload_dir['baseurl'] . '/wpsc/'.$save_file_name;
  move_uploaded_file( $_FILES['file']['tmp_name'], $save_directory );
}
echo json_encode($file_url);
