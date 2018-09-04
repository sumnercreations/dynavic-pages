<?php
/**
 * @package DynavicPages
 */
/*
Plugin Name: Dynavic Pages
Plugin URI: https://www.nav.com/wp/plugins/dynavic-pages
Description: Creates a dynamic page that has customizable content. The content can be shown or hidden based on the value in a user cookie or url parameter.
Version: 0.0.1
Author: Ammon Lockwood
Author URI: http://www.plaidtie.net/author/ammon
License: TBD (GPLv3)
Text Domain: dynavic-pages
*/

// Ensure that we don't expose anything if called directly
defined( 'ABSPATH' ) or die( 'This is a plugin and should not be called directly.' );

// This is a fairly straigt-forward plugin, so we'll define the class
// in this file. We could also create a separate class and load it.
class DynavicPages {
    // Use the construct to hook into the wordpress init function
    function __construct() {
        // set up plugin will hook into all the other wordpress function
        // this way we don't have a lot of add_actions to the init function
        add_action( 'init', array( $this, 'setup_plugin' ) );
    }

    /**
     * Setup the plugin
     */
    public function setup_plugin() {
        $this->custom_post_type();
        $this->register_shortcode();
        $this->add_mce_shortcode_button();
    }

    /**
     * Call the wordpress function to register the custom post type
     * This creates a specific post type and as such pages that can
     * be targeted in marketing campaigns since they have a specific
     * section. (dynavic)
     */
    public function custom_post_type() {
        register_post_type( 'dynavic', ['public' => true, 'label' => 'Dynavic Pages'] );
    }

    /**
     * Register a shortcode to be used in posts, widgets and excerpts
     * 
     * This is the real magic of the plugin because it's what will show
     * content based on the value in the cookie.
     */
    public function register_shortcode() {
        add_shortcode( 'dynavic-content', array( $this, 'parse_dynavic_content' ) );
        add_filter( 'widget_text', 'do_shortcode' );
        add_filter( 'the_excerpt', 'do_shortcode');
    }

    /**
     * Determines if the content between the shortcode should be shown
     * based on what the value in the 
     * 
     * @param Attributes $atts
     * @param Content string $content
     */
    public function parse_dynavic_content( $atts, $content ) {
        // checks for the cookie
        if( isset($_COOKIE['dynavic-content']) ) {
            // if the cookie exists then set the cookieFilter to compare later
            $cookieFilter = $_COOKIE['dynavic-content'];

            // extract the attributes in the shortcode
            // if the filter isn't there, then a blank string will be used.
            extract( shortcode_atts( array(
                'filter' => '',
            ), $atts ) );
            
            // if the two filters are the same, then we want to show this content, 
            // otherwise, we should return an empty string
            if ( $cookieFilter == $filter ) {
                // return the content inside the shortcode tags
                return $content;
            }else{
                return '';
            }
        }else{
            // if the cookie isn't set then we don't need to do anything so just return.
            return;
        }
    }

    /**
     * Add the the shortcode button to tiny mce if the user has edit rights
     */
    public function add_mce_shortcode_button() {
        // only if the user can edit.
        if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
            return;
        }
        
        // if we are using the visual editor add the filters to add the plugin and the button
        if ( get_user_option('rich_editing') == 'true' ) {
            add_filter( 'mce_external_plugins', array( $this, 'add_tiny_mce_plugin' ) );
            add_filter( 'mce_buttons', array( $this, 'register_tiny_mce_shortcode_button' ) );
        }
    }

    /**
     * Register the buttons for the tiny mce editor
     * this will allow the user to quickly create
     * content and filter it without having to
     * write out the shortcode each time.
     * 
     * @param array buttons $buttons
     */
    public function register_tiny_mce_shortcode_button( $buttons ) {
        array_push( $buttons, "|", "dynavic_content");
        return $buttons;
    }

    /**
     * Register the plugin with wordpress tinyMCE and add the js file that inits the plugin
     * 
     * @param array plugins $plugin_array
     */
    public function add_tiny_mce_plugin( $plugin_array ) {
        $plugin_array['dynavic_content'] = plugins_url( '/assets/dynavic-pages.js', __FILE__ );
        return $plugin_array;
    }

    /**
     * Activate the plugin
     * 
     * called when when plugin is activated
     */
    public static function plugin_activation() {
        // flush rewrite rules will refresh the DB after adding the custom post type
        flush_rewrite_rules();
    }

    /**
     * Deactivate the plugin
     * 
     * called when when plugin is deactivated
     */
    public static function plugin_deactivation() {
        // clean up the custom post type (CPT)
        // flush rewrite rules will refresh the DB
        flush_rewrite_rules();
    }
}

// instantiate the class to register the activation and deactivation hooks.
$dynavic = new DynavicPages();

// register hooks for activating and deactivating the plugin
register_activation_hook( __FILE__, array( $dynavic, 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( $dynavic, 'plugin_deactivation' ) );