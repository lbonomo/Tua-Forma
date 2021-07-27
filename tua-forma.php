<?php
/**
 * Main pluing file.
 *
 * @package Tua_Forma
 * @version 1.1.2
 */

/*
 * Plugin Name:       Tua Forma
 * Plugin URI:        https://lucasbonomo.com/wordpress/plugins/
 * Description:       This shortcode just put the tags (&lt;form&gt; y &lt;/form&gt;), somes nonce fields and send his content by email
 * Author:            Lucas Bonomo
 * Author URI:        https://lucasbonomo.com
 * Text Domain:       tua-forma
 * Domain Path:       /languages
 * Version:           1.1.2
 * Stable tag:        1.1.2
 * Tested up to:      5.8.0
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            Lucas Bonomo
 * Author URI:        https://lucasbonomo.com
 * License:           GPL-2.0+

 */

require_once 'classes/class-tuaforma.php';

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Activator.
register_activation_hook(
	__FILE__,
	array(
		'TuaForma',
		'tua_forma_activate',
	)
);

// Deactivator.
register_deactivation_hook(
	__FILE__,
	array(
		'TuaForma',
		'tua_forma_deactivation',
	)
);

// Uninstall.
register_uninstall_hook(
	__FILE__,
	array(
		'TuaForma',
		'tua_forma_uninstall',
	)
);

/**
 * Main function
 */
function tua_forma_run() {
	$plugin = new TuaForma();
}

tua_forma_run();
