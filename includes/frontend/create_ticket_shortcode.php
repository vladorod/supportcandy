<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'), '1.8.6');
wp_enqueue_script( 'jquery-ui-datepicker' );
wp_enqueue_editor();

wp_enqueue_style('wpsc-bootstrap-css');
wp_enqueue_style('wpsc-fa-css');
wp_enqueue_style('wpsc-jquery-ui');
wp_enqueue_style('wpsc-public-css');
wp_enqueue_style('wpsc-admin-css');
wp_enqueue_style('wpsc-modal-css');

wp_enqueue_script('wpsc-dtp-js');
wp_enqueue_style('wpsc-dtp-css');
wp_enqueue_script( 'jquery-ui-slider' );

wp_enqueue_script('wpsc-admin');
wp_enqueue_script('wpsc-public');
wp_enqueue_script('wpsc-modal');
?>

<div class="bootstrap-iso">
   <div id="wpsc_tickets_container" class="row" style="border:none;"></div>
</div>

<?php
add_action('wp_footer', 'wpsc_page_inline_script', 999999999999999);
global $attrs;
$attrs = isset($attr['page'])? $attr['page']:'create_ticket';
?>
<script>
    var wpsc_setting_action = '<?php echo $attrs?>';
</script>

<?php
do_action('wpsc_after_wpsc_create_ticket_shortcode_loaded');
if(!function_exists('wpsc_page_inline_script')) {
function wpsc_page_inline_script() {?>
  <script>
    <?php
       $url_attrs = array();
       foreach ($_GET as $key => $value) {
         $url_attrs[] = $key.':"'.$value.'"';
       }
       $url_attrs = apply_filters('wpsc_create_ticket_short_para',$url_attrs);
       $url_attrs = '{'.implode(',',$url_attrs).'}'
    ?>
    var attrs = <?php echo $url_attrs?>;
    jQuery(document).ready(function(){
      wpsc_create_ticket_init(wpsc_setting_action,attrs);
    });
  </script>
  <?php
}
}
?>