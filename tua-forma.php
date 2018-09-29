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

// Your code starts here.


if ( ! defined( 'ABSPATH' ) ) { exit; }

require plugin_dir_path( __FILE__ ) . 'includes/class-tua-forma.php';


/**** Activator ****/
register_activation_hook( __FILE__,  array( 'Tua_Forma', 'activate' ) );

/**** Deactivator ****/
register_deactivation_hook( __FILE__,  array( 'Tua_Forma', 'deactivation' ) );

/**** Uninstall ****/
register_uninstall_hook( __FILE__,  array( 'Tua_Forma', 'uninstall' ) );


function run_tua_forma() {
    $plugin = new Tua_Forma();

}

run_tua_forma();