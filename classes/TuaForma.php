<?php

require_once 'TuaFormaAdmin.php';
require_once 'TuaFormaShortCode.php';
require_once 'TuaFormaEndpoint.php';

class TuaForma {
    
    public function __construct() {
        $endpoint = new TuaFormaEndpoint();
        $admin_page = new TuaFormaAdmin();
        $shortcode = new TuaFormaShortCode();
    }

    static function tua_forma_activate() {
        flush_rewrite_rules();
    }

    static function tua_forma_deactivation() {
    }

    function tua_forma_uninstall() {
    }

}