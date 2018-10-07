<?php

require_once 'TuaFormaAdmin.php';
require_once 'TuaFormaShortCode.php';

class TuaForma {
    
    public function __construct() {
        $admin_page = new TuaFormaAdmin();
        $admin_page = new TuaFormaShortCode();
    }

    static function tua_forma_activate() {
    }

    static function tua_forma_deactivation() {
    }

    function tua_forma_uninstall() {
    }

}