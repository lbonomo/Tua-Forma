<?php



class Tua_Forma {
    
    public function __construct() {
        require 'class-admin.php';
        $admin_page = new Tua_Forma_Admin();

    }

    function activate() {
        add_option( 'smtp_user', '');
        add_option( 'smtp_password', '');
        add_option( 'smtp_server', '');
        add_option( 'smtp_port', '');        
        add_option( 'smtp_protocol', '');
        add_option( 'to', '');
    }

    function deactivation() {

    }

    function uninstall() {
        delete_option( 'smtp_user');
        delete_option( 'smtp_password');
        delete_option( 'smtp_server');
        delete_option( 'smtp_port');        
        delete_option( 'smtp_protocol', '');
        delete_option( 'to', '');
    }

}