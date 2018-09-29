<?php

function add_shortcode(

) {
    $tuo_forma_nonce = wp_create_nonce($action);
    $body = '<form accept-charset="UTF-8" action="https://forma.promotore.com.ar/form/34a3be37-fa91-497a-a8ac-66bf44a926a4/" autocomplete="off" enctype="multipart/form-data" method="POST">'.
        '<input type="hidden" id="tuo_forma_nonce" name="tuo_forma_nonce" value="'.$nonce.'" />'.
        '</form>';
    return body;
}

add_shortcode('tua-forma','add_shortcode');