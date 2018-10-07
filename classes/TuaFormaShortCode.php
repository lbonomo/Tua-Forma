<?php

// namespace classes;

class TuaFormaShortCode {
  
    function __construct() {
        add_shortcode('tua-forma',array($this,'tua_forma_add_shortcode'));
    }

    function tua_forma_add_shortcode($attr, $form) {
       
        if ( isset($_GET['tua-forma-message'] ) ) {
            $message = $_GET['tua-forma-message'];
            switch($message) {
                
                case 'successful':
                $body = $this::tua_forma_successful();
                break;

                case 'error':
                $body = $this::tua_forma_error();
                break;

                default;
                $body = $this::tua_forma_error();
            }
            
        } else {
            $body = $this::tua_forma_form($form);
        }      
        return $body;
    }  

    function tua_forma_form($form){
        // Muestro el formulario
        $nonce_rand = rand();
        $action =  plugins_url( '../post-data.php', __FILE__ );
        
        $body = "<form accept-charset='UTF-8' action='$action' autocomplete='off' enctype='multipart/form-data' method='POST'>";
        $body .= "<input type='hidden' id='rand' name='rand' value='$nonce_rand'>";
        $body .= wp_nonce_field( 'tua-forma-nonce-'.$nonce_rand );
        $body .= $form;
        $body .= '</form>';

        return $body;
    }
    
    function tua_forma_error(){
        $error_message = get_option('tua-forma-error-message');
        $body = "<div class='tua-forma-message'>";
        $body .= "<script> function goBack() { window.history.back(); } </script>";
        $body .= "<div class='tua-forma-error-message'>$error_message</div>";
        $body .= "<a class='tua-forma-link' onclick='goBack()' href=''>Volver</a>";
        $body .= "</div>";
        return $body;
    }

    function tua_forma_successful(){
        $successful_message = get_option('tua-forma-successful-message');
        $body = "<div class='tua-forma-successful-message'>$successful_message</div>";
        return $body;  
    }
}