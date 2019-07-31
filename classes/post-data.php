<?php

class TuaFormaPost {

   function getClientData() {
      $info = '';

      // foreach($_SERVER as $key => $value) {
      //    $info .= $key.' = '.$value
      // }

      $info .= "HTTP_HOST = ".$_SERVER["HTTP_HOST"]."<br />";
      $info .= "HTTP_USER_AGENT = ".$_SERVER["HTTP_USER_AGENT"]."<br />";
      $info .= "HTTP_REFERER = ".$_SERVER["HTTP_REFERER"]."<br />";
      $info .= "SERVER_ADDR = ".$_SERVER["SERVER_ADDR"]."<br />";
      $info .= "REMOTE_ADDR = ".$_SERVER["REMOTE_ADDR"]."<br />";
      $info .= "REQUEST_SCHEME = ".$_SERVER["REQUEST_SCHEME"]."<br />";
      $info .= "REQUEST_METHOD = ".$_SERVER["REQUEST_METHOD"]."<br />";
      return $info; 
   }

    function validate_data($post_data) {
        $data = $post_data;      

        /*
        $nonce (string) (required) Nonce to verify. Default: None
        $action (string/int) (optional) Action name. Should give the context to what is taking place and be the same when the nonce was created. Default: -1
        */

        /* Verifico el nonce */
        $nonce = wp_verify_nonce( $data['tua-forma-nonce'], 'tua-forma-nonce-'.$data['rand'] );
        $honeypot = false;

        if ( $nonce ) {
            $hidden = [
               'rand', 
               'tua-forma-nonce', 
               '_wp_http_referer'
            ];
            
            # Add Honeypot to Hidden
            $HoneypotField = get_option('tua-forma-honeypot');

            if ( $HoneypotField != "" ) { 
               array_push($hidden, $HoneypotField); 
               # Honeypot
               if ( isset($data[$HoneypotField]) and $data[$HoneypotField] !== "" ) {
                  $honeypot = true;
               }               
            }  

            # Remove Hidden
            foreach ($hidden as $h) {
                if( array_key_exists($h, $data) ) { unset($data[$h]); }
            }

            # SANITIZE
            foreach ($data as $key => $value) {
                $data[$key] = sanitize_text_field($value);
            }

            return [
                'error' => false,
                'honeypot' => $honeypot,
                'load' => $data,
                'next' => $_SERVER['HTTP_REFERER']
            ];

        } else {
            return [
                'error' => true,
                'honeypot' => $honeypot,
                'load' => $data,
                'next' => $_SERVER['HTTP_REFERER']
            ];
        }
    }

    function email_body($data) {
        $html = "<h3>Nuevos datos</h3></br>";
        $html .= "<table><tbody>";
        foreach ($data as $key => $value ) {
            $html .= "<tr><td>" . esc_html( $key ) . "</td><td><b>" . esc_html( $value ) . "</b></td></tr>";
        }
        $html .= "</tbody></table>";
        $html .= "<br>";
        

        if (get_option('tua-forma-metadata') == 1) {
            // var_dump($this->getClientData());
            $html .= '<div style="font-size: 85%; font-family: Arial;">';
            $html .= '<h4>Información adicional</h4>';
            $html .= $this->getClientData();
            $html .= '</div>';
        }

        return $html;
    }
}


if ( $_POST ) {

    $tosend = new TuaFormaPost();
    
    $data = $tosend->validate_data($_POST);
    
    if ( ! $data['error'] ) {

        $headers = array('Content-Type: text/html; charset=UTF-8');        
        // $from = get_option('tua-forma-smtp-from');
        // $reply_to = get_option('tua-forma-smtp-reply-to');
        $recipients = explode(',',get_option('tua-forma-smtp-recipients'));
        
        if ( $data['honeypot'] ) { 
            $subject = "<SPAM> - ".get_option('tua-forma-subject');
        } else {
            $subject = get_option('tua-forma-subject');
        }

        $body = $tosend->email_body($data['load']);
            
        $wp_mail_return = wp_mail( $recipients, $subject, $body, $headers );

        if ( $wp_mail_return ) {
            $next = add_query_arg('tua-forma-message', 'successful', $data['next']);
            wp_redirect($next);
        } else {
            $next = add_query_arg('tua-forma-message', 'smtp-error', $data['next']);
            wp_redirect($next);
        }
    } else {
        $next = add_query_arg('tua-forma-message', 'invalid-data', $data['next']);
        wp_redirect($next);
    }
} else {
    error_log("Nada por aquí!");
    print_r("Nada por aquí!");
}

?>