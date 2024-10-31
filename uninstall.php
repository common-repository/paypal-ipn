<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 *
 * @since      1.0.0
 *
 * @package    paypal-ipn-for-wordpress
 */

// If uninstall not called from WordPress, then exit.

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}
global $wpdb, $wp_version;
$remove_all_plugin_data_on_uninstall = get_option('remove_all_plugin_data_on_uninstall', false);
if($remove_all_plugin_data_on_uninstall == true) {
    $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->posts WHERE post_type = %s", 'paypal_ipn'));
    $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->posts WHERE post_type = %s", 'ipn_history'));
    delete_option('paypal_ipn_for_wordpress_paypal_debug');
    delete_option('remove_all_plugin_data_on_uninstall');
}
