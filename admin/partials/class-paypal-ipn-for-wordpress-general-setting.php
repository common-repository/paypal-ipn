<?php

/**
 * This class defines all code necessary to General Setting from admin side
 * @class       AngellEYE_Paypal_Ipn_For_Wordpress_General_Setting
 * @version	1.0.0
 * @package	paypal-ipn-for-wordpress/includes
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_Paypal_Ipn_For_Wordpress_General_Setting {

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public static function init() {

        add_action('paypal_ipn_for_wordpress_general_setting', array(__CLASS__, 'paypal_ipn_for_wordpress_general_setting'));
        add_action('paypal_ipn_for_wordpress_general_setting_save_field', array(__CLASS__, 'paypal_ipn_for_wordpress_general_setting_save_field'));
    }

    /**
     * paypal_ipn_for_wordpress_general_setting_save_field function used for save general setting field value
     * @since 1.0.0
     * @access public static
     * 
     */
    public static function paypal_ipn_for_wordpress_general_setting_save_field() {
        global $wpdb;
        if (isset($_POST['general_setting_integration']) && !empty($_POST['general_setting_integration'])) {
            $paypal_ipn_for_wordpress_paypal_debug = (isset($_POST['paypal_ipn_for_wordpress_paypal_debug'])) ? stripslashes_deep($_POST['paypal_ipn_for_wordpress_paypal_debug']) : '';
            update_option('paypal_ipn_for_wordpress_paypal_debug', $paypal_ipn_for_wordpress_paypal_debug);
            $remove_all_plugin_data_on_uninstall = (isset($_POST['remove_all_plugin_data_on_uninstall'])) ? stripslashes_deep($_POST['remove_all_plugin_data_on_uninstall']) : '';
            update_option('remove_all_plugin_data_on_uninstall', $remove_all_plugin_data_on_uninstall);
            if (empty($_POST['delete_paypal_ipn']) || empty($_POST['delete_paypal_ipn_forwarder_data'])) {
                echo sprintf('<div class=" notice notice-success is-dismissible"><p>%1$s</p></div>', __('Your settings have been saved.', 'paypal-ipn'));
            } 
            if (isset($_POST['delete_paypal_ipn']) && !empty($_POST['delete_paypal_ipn'])) {
                try {
                    if ('all' == $_POST['delete_paypal_ipn']) {
                        $total_deleted_row = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->posts WHERE post_type = %s", 'paypal_ipn'));
                    } else {
                        $total_deleted_row = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->posts WHERE post_type = %s AND DATEDIFF(NOW(), `post_date`) > %d", 'paypal_ipn', $_POST['delete_paypal_ipn']));
                    }
                    if ($total_deleted_row === false) {
                        
                    } else {
                        if ($total_deleted_row > 0) {
                            if($total_deleted_row == 1) {
                                $delete_row_msg = sprintf(__('%s PayPal IPN Record Permanently Deleted.', 'paypal_ipn'), $total_deleted_row);
                            } else {
                                $delete_row_msg = sprintf(__('%s PayPal IPN Records Permanently Deleted.', 'paypal_ipn'), $total_deleted_row);
                            }
                            echo sprintf('<div class=" notice notice-success is-dismissible"><p> %1$s</p></div>', $delete_row_msg);
                            $wpdb->query("DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;");
                        } else {
                            echo sprintf('<div class=" notice notice-success is-dismissible"><p> %1$s</p></div>', __('No PayPal IPN Record Found.', 'paypal-ipn'));
                        }
                    }
                } catch (Exception $ex) {
                    echo sprintf('<div class=" notice notice-error is-dismissible"><p>%1$s</p></div>', $ex->getMessage());
                }
            } 
            if (isset($_POST['delete_paypal_ipn_forwarder_data']) && !empty($_POST['delete_paypal_ipn_forwarder_data'])) {
                try {
                    if ('all' == $_POST['delete_paypal_ipn_forwarder_data']) {
                        $total_forwarder_deleted_row = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->posts WHERE post_type = %s", 'ipn_history'));
                    } else {
                        $total_forwarder_deleted_row = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->posts WHERE post_type = %s AND DATEDIFF(NOW(), `post_date`) > %d", 'ipn_history', $_POST['delete_paypal_ipn_forwarder_data']));
                    }
                    if ($total_forwarder_deleted_row === false) {
                        
                    } else {
                        if ($total_forwarder_deleted_row > 0) {
                            if($total_forwarder_deleted_row == 1) {
                                $delete_forwarder_row_msg = sprintf(__('%s PayPal IPN Forwarder Record Permanently Deleted.', 'paypal_ipn'), $total_forwarder_deleted_row);
                            } else {
                                $delete_forwarder_row_msg = sprintf(__('%s PayPal IPN Forwarder Record Permanently Deleted.', 'paypal_ipn'), $total_forwarder_deleted_row);
                            }
                            echo sprintf('<div class=" notice notice-success is-dismissible"><p> %1$s</p></div>', $delete_forwarder_row_msg);
                            $wpdb->query("DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;");
                        } else {
                            echo sprintf('<div class=" notice notice-success is-dismissible"><p> %1$s</p></div>', __('No PayPal IPN Forwarder Record Found.', 'paypal-ipn'));
                        }
                    }
                } catch (Exception $ex) {
                    echo sprintf('<div class=" notice notice-error is-dismissible"><p>%1$s</p></div>', $ex->getMessage());
                }
            }
        }
    }

    /**
     * paypal_ipn_for_wordpress_general_setting function used for display general setting block from admin side
     * @since    1.0.0
     * @access   public
     */
    public static function paypal_ipn_for_wordpress_general_setting() {
        echo '<div class="wrap">';
        ?>
        <form id="mailChimp_integration_form" enctype="multipart/form-data" action="" method="post">
            <table class="form-table" id="paypal_ipn_primary_url">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label for="paypal_ipn_primary_url"><?php echo __('PayPal IPN Primary URL', 'paypal-ipn') ?></label></th>
                        <td class="forminp forminp-text">
                            <input type="text" class="large-text code" name="paypal_ipn_primary_url" value="<?php echo site_url('?AngellEYE_Paypal_Ipn_For_Wordpress&action=ipn_handler'); ?>" readonly>
                            <p class="description">
                            <?php
                            $url = 'https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNSetup/#id089EG030E5Z';
                            echo sprintf(wp_kses(__('Take a look at the <a target="_blank" href="%s">PayPal IPN Configuration Guide</a> for details on setting up IPN with this URL.', 'paypal-ipn'), array('a' => array('href' => array(), 'target' => array('_blank', '_top')))), esc_url($url));
                            ?>
                            </p>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="paypal_ipn_for_wordpress_paypal_debug"><?php _e('Debug Log', 'paypal-ipn'); ?></label>
                        </th>
                        <td class="forminp">
                                <?php if (defined('PAYPAL_IPN_FOR_WORDPRESS_LOG_DIR')) { ?>
                                <?php if (@fopen(PAYPAL_IPN_FOR_WORDPRESS_LOG_DIR . 'test-log.log', 'a')) { ?>
                                    <fieldset>
                                        <legend class="screen-reader-text"><span><?php echo __('Debug Log', 'paypal-ipn'); ?></span></legend>
                                        <label for="paypal_ipn_for_wordpress_paypal_debug">
                                            <input type="checkbox" <?php echo (get_option('paypal_ipn_for_wordpress_paypal_debug') == '1') ? 'checked="checked"' : '' ?> value="1" id="paypal_ipn_for_wordpress_paypal_debug" name="paypal_ipn_for_wordpress_paypal_debug" class=""><?php echo __('Enable logging.', 'paypal-ipn'); ?></label><br>
                                        <p class="description"><?php echo __('Log PayPal events, such as IPN requests, inside', 'paypal-ipn'); ?> <code><?php echo PAYPAL_IPN_FOR_WORDPRESS_LOG_DIR; ?> </code></p>
                                    </fieldset>
                                <?php } else { ?>
                                    <p><?php printf('<mark class="error">' . __('Log directory (<code>%s</code>) is not writable. To allow logging, make this writable or define a custom <code>PAYPAL_IPN_FOR_WORDPRESS_LOG_DIR</code>.', 'paypal-ipn') . '</mark>', PAYPAL_IPN_FOR_WORDPRESS_LOG_DIR); ?></p>
                                    <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="remove_all_plugin_data_on_uninstall"><?php _e('Remove all plugin data on uninstall.', 'paypal-ipn'); ?></label>
                        </th>
                        <td class="forminp">
                        <fieldset>
                            <label for="remove_all_plugin_data_on_uninstall">
                                <input type="checkbox" <?php echo (get_option('remove_all_plugin_data_on_uninstall') == '1') ? 'checked="checked"' : '' ?> value="1" id="remove_all_plugin_data_on_uninstall" name="remove_all_plugin_data_on_uninstall" class=""><?php echo __('Enable Remove all plugin data on uninstall', 'paypal-ipn'); ?></label><br>
                        </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="delete_paypal_ipn"><?php echo __('Delete PayPal IPN Data', 'paypal-ipn'); ?></label></th>
                        <td>
                            <select name="delete_paypal_ipn">
                                <option value=""><?php echo __('Select option', 'paypal-ipn'); ?></option>
                                <option value="all"><?php echo __('All', 'paypal-ipn'); ?></option>
                                <option value="30"><?php echo __('Older than 30 Days', 'paypal-ipn'); ?></option>
                                <option value="60"><?php echo __('Older than 60 Days', 'paypal-ipn'); ?></option>
                                <option value="90"><?php echo __('Older than 90 Days', 'paypal-ipn'); ?></option>
                                <option value="180"><?php echo __('Older than 6 months', 'paypal-ipn'); ?></option>
                                <option value="365"><?php echo __('Older than 1 Year', 'paypal-ipn'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <?php  if (defined('PIWF_PLUGIN_DIR')) { ?>
                    <tr>
                        <th scope="row"><label for="delete_paypal_ipn_forwarder_data"><?php echo __('Delete PayPal IPN Forwarder Data', 'paypal-ipn'); ?></label></th>
                        <td>
                            <select name="delete_paypal_ipn_forwarder_data">
                                <option value=""><?php echo __('Select option', 'paypal-ipn'); ?></option>
                                <option value="all"><?php echo __('All', 'paypal-ipn'); ?></option>
                                <option value="30"><?php echo __('Older than 30 Days', 'paypal-ipn'); ?></option>
                                <option value="60"><?php echo __('Older than 60 Days', 'paypal-ipn'); ?></option>
                                <option value="90"><?php echo __('Older than 90 Days', 'paypal-ipn'); ?></option>
                                <option value="180"><?php echo __('Older than 6 months', 'paypal-ipn'); ?></option>
                                <option value="365"><?php echo __('Older than 1 Year', 'paypal-ipn'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p class="submit">
                <input type="submit" name="general_setting_integration" class="button-primary" value="<?php esc_attr_e('Save changes', 'paypal-ipn'); ?>" />
            </p>
        </form>
        <?php
        echo '</div>';
    }

}

AngellEYE_Paypal_Ipn_For_Wordpress_General_Setting::init();
