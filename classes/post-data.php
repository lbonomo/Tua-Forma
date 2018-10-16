<?php

class TuaFormaPost {

    function validate_data($post_data) {
        $data = $post_data;
       
        /*
        $nonce (string) (required) Nonce to verify. Default: None
        $action (string/int) (optional) Action name. Should give the context to what is taking place and be the same when the nonce was created. Default: -1
        */
        $nonce = wp_verify_nonce( $data['tua-forma-nonce'], 'tua-forma-nonce-'.$data['rand'] );

        $reference =  get_site_url().$data['_wp_http_referer'];

        if ( $nonce && ( $reference === $_SERVER['HTTP_REFERER'] ) ) {
            $hidden = ['rand', 'tua-forma-nonce', '_wp_http_referer'];

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
                'load' => $data,
                'next' => $reference
            ];

        } else {
            return [
                'error' => true,
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
        return $html;
    }
}


if ( $_POST ) {

    $tosend = new TuaFormaPost();
    
    $data = $tosend->validate_data($_POST);
    var_dump($data);
    
    if ( ! $data['error'] ) {   

        $headers = array('Content-Type: text/html; charset=UTF-8');        
        // $from = get_option('tua-forma-smtp-from');
        // $reply_to = get_option('tua-forma-smtp-reply-to');
        $recipients = explode(',',get_option('tua-forma-smtp-recipients'));
        $subject = get_option('tua-forma-subject');       
        $body = $tosend->email_body($data['load']);
            
        $wp_mail_return = wp_mail( $recipients, $subject, $body, $headers );

        if ( $wp_mail_return ) {
            // wp_safe_redirect()
            $next = add_query_arg('tua-forma-message', 'successful', $data['next']);
            header('Location: '.$next);
        } else {
            $next = add_query_arg('tua-forma-message', 'error', $data['next']);
            header('Location: '.$next);
        }
    } else {
        $next = add_query_arg('tua-forma-message', 'error', $data['next']);
        header('Location: '.$next);
    }
} else {
    error_log("Nada por aquí!");
    print_r("Nada por aquí!");
}

?>