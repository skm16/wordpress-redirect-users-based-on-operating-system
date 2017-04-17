<?php
/**
 * Plugin Name: Redirect Users Based on Operating System
 * Plugin URI: https://seanroberts.me/wordpress-redirect-users-based-operating-system-plugin/
 * Description: Allows admin to create custom redirects based the user's operating system for posts and pages
 * Version: 1.0.1
 * Author: Sean Roberts
 * License: GPL2
 */

 // add styles and scripts
 add_action('admin_enqueue_scripts', 'skm_os_redirect_scripts');
 function skm_os_redirect_scripts()
 {
 			wp_enqueue_style('skm-os-redirect-css', plugin_dir_url(__FILE__) . '/assets/css/skm-os-redirect-styles.css');
 			wp_enqueue_script('skm-os-redirect-js', plugin_dir_url(__FILE__) . '/assets/js/skm-os-redirect-app.js', array( 'jquery' ), '1.0', true);
 }

function skm_redirect_windows() {
 global $post;
 $windows_redirect_output = '';
 $skm_windows_redirect_url = get_post_meta( $post->ID, '_skm_os_redirect_windows_redirect_value', true );
 if(!EMPTY($skm_windows_redirect_url)):
  $windows_redirect_output = '<script>if (navigator.userAgent.indexOf("Win")!=-1) { window.location = "'.$skm_windows_redirect_url.'"; };</script>';
 endif;
 echo $windows_redirect_output;
}
add_action('wp_head', 'skm_redirect_windows');

function skm_redirect_mac() {
 global $post;
 $mac_rediect_output = '';
 $skm_mac_redirect_url = get_post_meta( $post->ID, '_skm_os_redirect_mac_redirect_value', true );
 if(!EMPTY($skm_mac_redirect_url)):
  $mac_rediect_output = '<script>if (navigator.userAgent.indexOf("Mac")!=-1){ window.location = "'.$skm_mac_redirect_url.'"; }</script>';
 endif;
 echo $mac_rediect_output;
}
add_action('wp_head', 'skm_redirect_mac');

function skm_redirect_linux() {
 global $post;
 $linux_redirect_output = '';
 $skm_linux_redirect_url = get_post_meta( $post->ID, '_skm_os_redirect_linux_redirect_value', true );
 if(!EMPTY($skm_linux_redirect_url)):
  $linux_redirect_output = '<script>if (navigator.userAgent.indexOf("Linux")!=-1){ window.location = "'.$skm_linux_redirect_url.'"; }</script>';
 endif;
 echo $linux_redirect_output;
}
add_action('wp_head', 'skm_redirect_linux');

// register admin meta fields
include('lib/skm-os-redirect-register-meta-fields.php');

function skm_os_redirect_credits() {
 $credits_output = '<!-- SKM OS REDIRECT PLUGIN by <a href="https://seanroberts.me" target="_blank" title="Sean Roberts WordPress Developer NYC">Sean Roberts</a> -->';
 echo $credits_output;
}
add_action('wp_footer', 'skm_os_redirect_credits');
