<?php
/**
 * Main class file
 *
 * @package Tua_Forma
 */

/**
 * Main class file
 */

require_once 'class-tuaformaadmin.php';
require_once 'class-tuaformashortcode.php';
require_once 'class-tuaformaendpoint.php';

/**
 * TuaFormaPost Class.
 */
class TuaForma {

	/**
	 * TuaFormaPost Class.
	 */
	public function __construct() {
		$endpoint   = new TuaFormaEndpoint();
		$admin_page = new TuaFormaAdmin();
		$shortcode  = new TuaFormaShortCode();
	}

	/**
	 * Activation.
	 */
	public static function tua_forma_activate() {
		// Set default options.
		if ( ! get_option( 'tua-forma-smtp-recipients' ) ) {
			add_option( 'tua-forma-smtp-recipients', 'you-mail@example.com' );
		}

		if ( ! get_option( 'tua-forma-error-message' ) ) {
			add_option( 'tua-forma-error-message', 'Your data can not send' );
		}

		if ( ! get_option( 'tua-forma-successful-message' ) ) {
			add_option( 'tua-forma-successful-message', 'Your data was sent successfully' );
		}

		if ( ! get_option( 'tua-forma-metadata' ) ) {
			add_option( 'tua-forma-metadata', '1' );
		}
		if ( ! get_option( 'tua-forma-subject' ) ) {
			add_option( 'tua-forma-subject', ( 'Data send from ' . get_home_url() ) );
		}

		if ( ! get_option( 'tua-forma-honeypot' ) ) {
			add_option( 'tua-forma-honeypot', 'honeypot-field' );
		}

		flush_rewrite_rules();
	}

	/**
	 * Deactivation.
	 */
	public static function tua_forma_deactivation() {
	}

	/**
	 * Uninstall.
	 */
	public static function tua_forma_uninstall() {
		// Delete options.
		delete_option( 'tua-forma-smtp-recipients' );
		delete_option( 'tua-forma-error-message' );
		delete_option( 'tua-forma-successful-message' );
		delete_option( 'tua-forma-metadata' );
		delete_option( 'tua-forma-subject' );
		delete_option( 'tua-forma-honeypot' );
	}

}
