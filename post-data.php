<?php
// https://gist.github.com/karlazz/4638931
use classes\TuaFormaSendMail;
use classes\TuaFormaBody;

if ( $_POST ) {

    define('WP_USE_THEMES', false);
    require('../../../wp-load.php');
    require('autoload.php');

    error_log($_POST['rand']);
    error_log($_POST['_wpnonce']);
    $nonce = wp_verify_nonce( $_POST['_wpnonce'], 'tua-forma-nonce-'.$_POST['rand'] );
    $reference =  get_site_url().$_POST['_wp_http_referer'];

    if ( $nonce  && ( $reference === $_SERVER['HTTP_REFERER'] ) ) {   
        error_log("Genial! paso el nonce (".$_POST['_wpnonce'].")");
        error_log($_SERVER['HTTP_REFERER']);

        # compongo y envio el mail
        $tua_mail = new TuaFormaSendMail();

        $body = new TuaFormaBody();
        $data = $_POST;
        # Campos que no se envian
        $hidden = ['rand', '_wpnonce', '_wp_http_referer'];
        foreach ($hidden as $h) {
            unset($data[$h]);
        }

        if ( $tua_mail->send($body->body($data)) ) {
            header('Location: '.$reference.'?gutua-forma-successful-message');
        } else {
            header('Location: '.$reference.'?tua-forma-error-message');
        }
    } else {
        header('Location: '.$reference.'?tua-forma-error-message');
    }
}

?>