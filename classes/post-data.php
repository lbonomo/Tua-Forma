<?php

if ( $_POST ) {
    require('TuaFormaBody.php');

    // error_log($_POST['rand']);
    // error_log($_POST['_wpnonce']);
    $nonce = wp_verify_nonce( $_POST['_wpnonce'], 'tua-forma-nonce-'.$_POST['rand'] );
    $reference =  get_site_url().$_POST['_wp_http_referer'];

    if ( $nonce  && ( $reference === $_SERVER['HTTP_REFERER'] ) ) {   
        // error_log("Genial! paso el nonce (".$_POST['_wpnonce'].")");
        // error_log($_SERVER['HTTP_REFERER']);

        # compongo y envio el mail

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
         
        $wp_mail_return = wp_mail( $recipients, $subject, $body->tua_forma_body($data), $headers );

        if ( $wp_mail_return ) {
            // wp_safe_redirect()
            $next = add_query_arg('tua-forma-message','successful',$reference);
            header('Location: '.$next);
        } else {
            $next = add_query_arg('tua-forma-message','error',$reference);
            header('Location: '.$next);
        }
    } else {
        $next = add_query_arg('tua-forma-message','error',$reference);
        header('Location: '.$next);
    }
} else {
    error_log("Nada por aquí!");
    print_r("Nada por aquí!");
}

?>