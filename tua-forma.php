<?php
/**
* Plugin Name:     Tua Forma
* Plugin URI:      https://lucasbonomo.com/wordpress/plugins/
* Description:     
* Author:          Lucas Bonomo
* Author URI:      https://lucasbonomo.com
* Text Domain:     tua-forma
* Domain Path:     /languages
* Version:         0.1.0
*
* @package         Tua_Forma
*/

require_once "classes/TuaForma.php";

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**** Activator ****/
register_activation_hook( __FILE__,  array( 'TuaForma', 'activate' ) );

/**** Deactivator ****/
register_deactivation_hook( __FILE__,  array( 'TuaForma', 'deactivation' ) );

/**** Uninstall ****/
register_uninstall_hook( __FILE__,  array( 'TuaForma', 'uninstall' ) );


function run_tua_forma() {
    $plugin = new TuaForma();
}

run_tua_forma();