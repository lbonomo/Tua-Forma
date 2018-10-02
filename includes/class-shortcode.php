<?php

class Tua_Forma_ShortCode {

    function __construct() {
        add_shortcode('tua-forma',array($this,'add_shortcode'));
    }

    function add_shortcode($atts, $form ) {        
        // TODO - successful_message and failure message
        $successful_message = get_option('tua-forma-successful-message');
        $error_message = get_option('tua-forma-error-message');
        $nonce_rand = rand();
        // https://codex.wordpress.org/WordPress_Nonces
        // $complete_url = wp_nonce_url( $bare_url, 'trash-post_'.$post->ID );
        $action =  plugins_url( '../post-data.php', __FILE__ );
        
        if ( isset($_GET['tua-forma-successful-message'] ) ) {
            $body = "<div class='tua-forma-successful-message'>$successful_message</div>";
        } elseif ( isset($_GET['tua-forma-error-message'] ) ) {
            $obj_id = get_queried_object_id();
            $current_url = get_permalink( $obj_id );
            $body = "<div class='tua-forma-message'>";
            $body .= "<div class='tua-forma-error-message'>$error_message</div>";
            $body .= "<a class='tua-forma-link' href='$current_url'>Volver</a>";
            $body .= "</div>";
        } else {
            $body = "<form accept-charset='UTF-8' action='$action' autocomplete='off' enctype='multipart/form-data' method='POST'>";
            $body .= "<input type='hidden' id='rand' name='rand' value='$nonce_rand'>";
            $body .= wp_nonce_field( 'tua-forma-nonce-'.$nonce_rand );
            $body .= $form;
            $body .= '</form>';
        }      
        return $body;
    }  

}