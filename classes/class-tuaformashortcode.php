<?php
/**
 * Main pluing file.
 *
 * @package         Tua_Forma
 */

/**
 * TuaFormaPost Class.
 */
class TuaFormaShortCode {

	/**
	 * TuaFormaPost Class.
	 */
	public function __construct() {
		add_shortcode( 'tua-forma', array( $this, 'add_shortcode' ) );
	}

	/**
	 * TuaFormaPost Class.
	 *
	 * @param stinrg $attr Atributos.
	 * @param strint $form Formulario.
	 */
	public function add_shortcode( $attr, $form ) {

// if(wp_verify_nonce($_REQUEST['name_of_your_nonce_field'], 'name_of_your_action')){
//      } else {
// }

// Only show de shortcode in GET method (

// if ( isset( $_REQUEST['REQUEST_METHOD'] ) && 'GET' === $_REQUEST['REQUEST_METHOD'] ) {}

		if ( isset( $_GET['tua-forma-message'] ) ) {

			// After form send (POST) redirected to the original URL with result and nonce.
			$message = wp_unslash( $_GET['tua-forma-message'] );
			switch ( $message ) {
				case 'successful':
					$body = $this::successful();
					break;
				case 'error':
					$body = $this::error();
					break;
				default:
					$body = $this::error();
					break;
			}
		} else {
			$body = $this::form( $form );
		}
		return $body;
	}

	/**
	 * TuaFormaPost Class.
	 *
	 * @param stinrg $field Campo.
	 */
	public function hidden_field( $field ) {
		// I hide it to try to catch the spam.
		return "\n<script> document.getElementById('$field').style.display = 'none'</script>\n";
	}

	/**
	 * TuaFormaPost Class.
	 *
	 * @param stinrg $form Formulario.
	 */
	public function form( $form ) {
		$honeypot_field = get_option( 'tua-forma-honeypot' );
		$nonce_rand     = wp_rand();
		$action         = '/tua-forma-send'; // Ver TuaFormaEndpoint.php.
		$body           = "\n<form accept-charset='UTF-8' action='$action' autocomplete='off' enctype='multipart/form-data' method='POST'>\n";
		$body          .= "<input type='hidden' id='rand' name='rand' value='$nonce_rand'>\n";
		// Honeypot.
		if ( '' !== $honeypot_field ) {
			$body .= "<input type='text' id='$honeypot_field' name='$honeypot_field' tabindex='-1' autocomplete='off'>\n";
			$body .= "<label class='' for='$honeypot_field' id='$honeypot_field-label'>Value:</label>\n";
		}
		$body .= wp_nonce_field( 'tua-forma-nonce-' . $nonce_rand, 'tua-forma-nonce', true, false );
		$body .= $form;
		$body .= "\n</form>\n";
		$body .= $this::hidden_field( $honeypot_field ); /* Oculto el campo con JS*/
		$body .= $this::hidden_field( $honeypot_field . '-label' ); /* Oculto el campo con JS*/

		return $body;
	}

	/**
	 * Shwo error message.
	 */
	public function error() {
		$error_message = get_option( 'tua-forma-error-message' );
		$body          = "<div class='tua-forma-message'>";
		$body         .= '<script> function goBack() { window.history.back(); } </script>';
		$body         .= "<div class='tua-forma-error-message'>$error_message</div>";
		$body         .= "<a class='tua-forma-link' onclick='goBack()' href=''>Volver</a>";
		$body         .= '</div>';
		return $body;
	}

	/**
	 * Shwo succesful message.
	 */
	public function successful() {
		$successful_message = get_option( 'tua-forma-successful-message' );
		$body               = "<div class='tua-forma-successful-message'>$successful_message</div>";
		return $body;
	}

}
