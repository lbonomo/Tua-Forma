<?php



class Tua_Forma {
    
    public function __construct() {
        require 'class-admin.php';
        require 'class-shortcode.php';
        $admin_page = new Tua_Forma_Admin();
        $admin_page = new Tua_Forma_ShortCode();
    }

    static function activate() {
        add_option('tua-forma-smtp-enabled','false');
    }

    static function deactivation() {
    }

    function uninstall() {
    }

}