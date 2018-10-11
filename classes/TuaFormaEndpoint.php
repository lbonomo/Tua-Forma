<?php

// namespace classes;

class TuaFormaEndpoint {
  
    function __construct() {
        add_action( 'init', array($this, 'register_endpoint'));
        add_action( 'template_redirect', array($this, 'template'));
    }

    function register_endpoint() {
        // add_rewrite_endpoint( 'send', EP_PERMALINK | EP_PAGES );
        add_rewrite_endpoint( 'tua-forma-send', EP_ALL );
    }


    function template() {
        global $wp_query;

        if ( ! isset( $wp_query->query_vars['tua-forma-send'] ) ) {
            return;
        } else {
            error_log($wp_query->query_vars['tua-forma-send']);
            include plugin_dir_path( __FILE__ ) . 'post-data.php';
        }      
        die;
    }

}