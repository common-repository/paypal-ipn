<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    paypal-ipn-for-wordpress
 * @subpackage paypal-ipn-for-wordpress/includes
 * @author     Angell EYE <service@angelleye.com>
 */
class AngellEYE_Paypal_Ipn_For_Wordpress_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.6.7
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.6.7
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.6.7
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_dependencies();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.6.7
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Paypal_Ipn_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Paypal_Ipn_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name . 'publicDataTablecss', plugin_dir_url(__FILE__) . 'css/jquery.dataTables.css' , array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . 'publicDataTable', plugin_dir_url(__FILE__) . 'css/dataTables.responsive.css', array(), $this->version, 'all');
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.6.7
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Paypal_Ipn_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Paypal_Ipn_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name . 'public-bn', plugin_dir_url(__FILE__) . 'js/paypal-ipn-for-wordpress-public-bn.js', array('jquery'), $this->version, true);
    }

    public function enqueue_scripts_for_shortcode() {
        wp_enqueue_script($this->plugin_name . 'DataTablejs', plugin_dir_url(__FILE__) . 'js/jquery.dataTables.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name . 'DataTable', plugin_dir_url(__FILE__) . 'js/dataTables.responsive.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name . 'public', plugin_dir_url(__FILE__) . 'js/paypal-ipn-for-wordpress-public.js', array('jquery'), $this->version, true);
        if (wp_script_is($this->plugin_name . '-plugin-script')) {
            wp_localize_script($this->plugin_name . 'DataTablejs', 'paypal_ipn_for_wordpress_datatable', 'true');
        }
    }

    private function load_dependencies() {

        /**
         * The class responsible for defining all actions that occur in the FrontEnd
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/paypal-ipn-for-wordpress--public-display.php';
    }

    public function paypal_ipn_for_wordpress_load_shortcode_asset($posts) {
        if (empty($posts)) {
            return $posts;
        }

        $found = false;

        foreach ($posts as $post) {
            if (strpos($post->post_content, '[paypal_ipn_list') !== false || strpos($post->post_content, '[paypal_ipn_data') !== false) {
                $found = true;
                break;
            }
        }

        if ($found) {
            $this->enqueue_scripts_for_shortcode();
            $this->enqueue_styles();
        }
        return $posts;
    }
    
    public function paypal_ipn_for_wordpress_private_ipn_post() {
        try {
            if ( !is_admin() && ( is_post_type_archive( 'paypal_ipn' ) ||  is_tax( 'paypal_ipn_type' ) ) ) {
                global $wp_query;
                $wp_query->set_404();
                status_header( 404 );
                nocache_headers();
                wp_redirect( home_url() );
                exit();
            }
        } catch (Exception $ex) {

        }
    }
}