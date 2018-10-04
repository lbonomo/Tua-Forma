<?php

// namespace classes;

// use classes\TuaFormaAdmin;
// use classes\TuaFormaShortCode;

require_once 'TuaFormaAdmin.php';
require_once 'TuaFormaShortCode.php';

class TuaForma {
    
    public function __construct() {
        $admin_page = new TuaFormaAdmin();
        $admin_page = new TuaFormaShortCode();
    }

    static function activate() {
        add_option('tua-forma-smtp-enabled','false');
    }

    static function deactivation() {
    }

    function uninstall() {
    }

}