<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
$theme_name = $active_theme->get( 'Name' );
//error_log(print_r($theme_name,true));
if($theme_name == 'Dazzling'){
  ?>
  <style>
  .bootstrap-iso ul, .bootstrap-iso ol {z-index:1000 !important;}
  ul.wpsp_filter_display_container {padding:0;}
  </style>
  <?php
}elseif ($theme_name == 'Start') {
  ?>
  <style>
  .bootstrap-iso ul, .bootstrap-iso ol {z-index:1000 !important;}
  ul.wpsp_filter_display_container {padding:0;}
  </style>
  <?php
}elseif($theme_name == 'Avada' || $theme_name == 'Avada Child'){
  ?>
  <script>
  var wpsc_width = jQuery('.bootstrap-iso').width();
  if( wpsc_width < 768 ){
    jQuery('html').append("<style>.col-sm-8{width:100% !important; float: none!important;} .col-sm-4{width:100% !important;}</style>");
  }
  </script>
  <?php
}
