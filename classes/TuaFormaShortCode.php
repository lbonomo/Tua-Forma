<?php

// namespace classes;

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

    function form($form){
        // Muestro el formulario
        $nonce_rand = rand();
        $action = 'tua-forma-send';
        $body = "<form accept-charset='UTF-8' action='$action' autocomplete='off' enctype='multipart/form-data' method='POST'>";
        $body .= "<input type='hidden' id='rand' name='rand' value='$nonce_rand'>";
        $body .= wp_nonce_field( 'tua-forma-nonce-'.$nonce_rand );
        $body .= $form;
        $body .= '</form>';

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

    function send_email() {
        $headers = array('Content-Type: text/html; charset=UTF-8');        
        $from = get_option('tua-forma-smtp-from');
        $reply_to = get_option('tua-forma-smtp-reply-to');
        $recipients = explode(',',get_option('tua-forma-smtp-recipients'));
        $subject = get_option('tua-forma-subject');
       
        $body = new TuaFormaBody();
        $data = $_POST;
        # Campos que no se envian
        $hidden = ['rand', '_wpnonce', '_wp_http_referer'];
        foreach ($hidden as $h) {
            unset($data[$h]);
        }
         
        $wp_mail_return = wp_mail( $recipients, $subject, $body->body($data), $headers );

        if ( $wp_mail_return ) {
            // wp_safe_redirect()
            $next = add_query_arg('tua-forma-message','successful',$reference);
            header('Location: '.$next);
        } else {
            $next = add_query_arg('tua-forma-message','error',$reference);
            header('Location: '.$next);
        }
    }

}