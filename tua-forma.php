<?php
/**
* Plugin Name:     Tua Forma
* Plugin URI:      https://lucasbonomo.com/wordpress/plugins/
* Description:     Este shortcode solo se encarga de generar las etiquetas (<form> y </form>) y campos (nonce) necesarios para enviar el contenido de un formulario por mail.
* Author:          Lucas Bonomo
* Author URI:      https://lucasbonomo.com
* Text Domain:     tua-forma
* Domain Path:     /languages
* Version:         0.1.2
*
* @package         Tua_Forma
*/

require_once "classes/TuaForma.php";

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**** Activator ****/
register_activation_hook( __FILE__,  array( 'TuaForma', 'tua_forma_activate' ) );

/**** Deactivator ****/
register_deactivation_hook( __FILE__,  array( 'TuaForma', 'tua_forma_deactivation' ) );

/**** Uninstall ****/
register_uninstall_hook( __FILE__,  array( 'TuaForma', 'tua_forma_uninstall' ) );


function tua_forma_run() {
    $plugin = new TuaForma();
}

tua_forma_run();