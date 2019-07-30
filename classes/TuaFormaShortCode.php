<?php

class TuaFormaShortCode {
  
    function __construct() {
        add_shortcode('tua-forma',array($this,'add_shortcode'));
    }

    function add_shortcode($attr, $form) {     
       
        if ( isset($_GET['tua-forma-message'] ) ) {
            $message = $_GET['tua-forma-message'];
            switch($message) {
                
                case 'successful':
                $body = $this::successful();
                break;

                case 'error':
                $body = $this::error();
                break;

                default;
                $body = $this::error();
            }
            
        } else {
            $body = $this::form($form);
            
        }      
        return $body;
    }  

    function HiddenField($field){
      return "\n<script> document.getElementById('$field').style.display = 'none'</script>\n";
    }

    function form($form){
        // Muestro el formulario
        $HoneypotField = get_option('tua-forma-honeypot');
        $nonce_rand = rand();
        // $form_id =  // TODO
        $action = '/tua-forma-send'; // Ver TuaFormaEndpoint.php - add_rewrite_endpoint( 'tua-forma-send', EP_ALL );
        $body  = "\n<form accept-charset='UTF-8' action='$action' autocomplete='off' enctype='multipart/form-data' method='POST'>\n";       
        $body .= "<input type='hidden' id='rand' name='rand' value='$nonce_rand'>\n";
        // Honeypot
        if ( $HoneypotField != "" ) {            
            $body .= "<input type='text' id='$HoneypotField' name='$HoneypotField' tabindex='-1' autocomplete='off'>\n";
            $body .= "<label class='' for='$HoneypotField' id='$HoneypotField-label'>Value:</label>\n";
        }
        /*         
        wp_nonce_field( $action, $name, $referer, $echo ); 
        $action: (string) (optional) Action name. Should give the context to what is taking place. Optional but recommended. Default: -1
        $name: (string) (optional) Nonce name. This is the name of the nonce hidden form field to be created. Once the form is submitted, 
            you can access the generated nonce via $_POST[$name]. Default: '_wpnonce'
        $referer: (boolean) (optional) Whether also the referer hidden form field should be created with the wp_referer_field() function.
            Default: true
        $echo: (boolean) (optional) Whether to display or return the nonce hidden form field, and also the referer hidden form field if
            the $referer argument is set to true. Default: true
        */
        $body .= wp_nonce_field( 'tua-forma-nonce-'.$nonce_rand, 'tua-forma-nonce', true, false );
        $body .= $form;
        $body .= "\n</form>\n";
        
        $body .= $this::HiddenField($HoneypotField); /* Oculto el campo con JS*/
        $body .= $this::HiddenField($HoneypotField.'-label'); /* Oculto el campo con JS*/

        return $body;
    }
    
    function error(){
        $error_message = get_option('tua-forma-error-message');
        $body = "<div class='tua-forma-message'>";
        $body .= "<script> function goBack() { window.history.back(); } </script>";
        $body .= "<div class='tua-forma-error-message'>$error_message</div>";
        $body .= "<a class='tua-forma-link' onclick='goBack()' href=''>Volver</a>";
        $body .= "</div>";
        return $body;
    }

    function successful(){
        $successful_message = get_option('tua-forma-successful-message');
        $body = "<div class='tua-forma-successful-message'>$successful_message</div>";
        return $body;  
    }



}