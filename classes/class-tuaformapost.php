<?php
/**
 * Process post Data.
 *
 * @package Tua_Forma
 */

/**
 * TuaFormaPost Class.
 */
class TuaFormaPost {

	/**
	 * Client data.
	 */
	public function get_client_data() {
		$info = '';

		if ( isset( $_SERVER['REQUEST_TIME'] ) ) {
			$fecha = new DateTime();
			$fecha->setTimestamp( wp_unslash( $_SERVER['REQUEST_TIME'] ) );
			$info .= 'DATETIME         = ' . $fecha->format( 'Y-m-d H:i:s' ) . '<br />';
		}

		if ( isset( $_SERVER['HTTP_HOST'] ) ) {
			$info .= 'HTTP_HOST        = ' . wp_unslash( $_SERVER['HTTP_HOST'] ) . '<br />';  // phpcs:ignore sanitization okay
		};

		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$info .= ' HTTP_USER_AGENT = ' . wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) . '<br />';  // phpcs:ignore sanitization okay
		};

		$referer = null;
		if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
			$referer = wp_unslash( $_SERVER['HTTP_REFERER'] );  // phpcs:ignore sanitization okay
			$info   .= ' HTTP_REFERER   = ' . $referer . '<br />';
		};

		if ( isset( $_SERVER['SERVER_ADDR'] ) ) {
			$info .= ' SERVER_ADDR    = ' . wp_unslash( $_SERVER['SERVER_ADDR'] ) . '<br />';  // phpcs:ignore sanitization okay
		};

		if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$info .= ' REMOTE_ADDR    = ' . wp_unslash( $_SERVER['REMOTE_ADDR'] ) . '<br />';  // phpcs:ignore sanitization okay
		};

		if ( isset( $_SERVER['REQUEST_SCHEME'] ) ) {
			$info .= ' REQUEST_SCHEME = ' . wp_unslash( $_SERVER['REQUEST_SCHEME'] ) . '<br />';  // phpcs:ignore sanitization okay
		};

		if ( isset( $_SERVER['REQUEST_METHOD'] ) ) {
			$info .= ' REQUEST_METHOD = ' . wp_unslash( $_SERVER['REQUEST_METHOD'] ) . '<br />';  // phpcs:ignore sanitization okay
		};

		return $info;
	}

	/**
	 * Validate post data
	 *
	 * @param array $post_data Form data.
	 */
	public function validate_data( $post_data ) {
		$data     = $post_data;
		$referer  = $data['_wp_http_referer'];
		$honeypot = false;

		// This fields do not send in the email.
		$hidden = array(
			'rand',
			'tua-forma-nonce',
			'_wp_http_referer',
		);

		// Add Honeypot to Hidden.
		$honeypot_field = get_option( 'tua-forma-honeypot' );
		if ( '' !== $honeypot_field ) {
			array_push( $hidden, $honeypot_field );
			// Honeypot.
			if ( isset( $data[ $honeypot_field ] ) && '' !== $data[ $honeypot_field ] ) {
				$honeypot = true;
			}
		}

		// Remove Hidden.
		foreach ( $hidden as $h ) {
			if ( array_key_exists( $h, $data ) ) {
				unset( $data[ $h ] );
			}
		}

		// SANITIZE.
		foreach ( $data as $key => $value ) {
			$data[ $key ] = sanitize_text_field( $value );
		}

		return array(
			'error'    => false,
			'honeypot' => $honeypot,
			'load'     => $data,
			'next'     => $referer,
		);

	}

	/**
	 * Make body email
	 *
	 * @param array $data Information to send by email.
	 */
	public function email_body( $data ) {
		$html  = '<h3 style="font-family: Arial;">Nuevos datos</h3></br>';
		$html .= '<table style="font-family: Arial; background-color: dimgrey;">';
		$html .= '<thead style="color: white;"><tr><th>Name</th><th>Value</th></tr></thead>';
		$html .= '<tbody>';
		foreach ( $data as $key => $value ) {
			$html .= '<tr>';
			$html .= '<td style="background-color: white; padding: 0.2rem 1rem;">' . esc_html( $key ) . '</td>';
			$html .= '<td style="background-color: white; padding: 0.2rem 1rem;">' . esc_html( $value ) . '</td>';
			$html .= '</tr>';
		}

		$html .= '</tbody></table>';
		$html .= '<br>';

		if ( '1' === get_option( 'tua-forma-metadata' ) ) {
			$html .= '<div style="font-size: 85%; font-family: Arial;">';
			$html .= '<h4>Información adicional</h4>';
			$html .= $this->get_client_data();
			$html .= '</div>';
		}

		return $html;
	}
}

// if POST.
if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {

	if ( isset( $_REQUEST['tua-forma-nonce'] ) ) {
		$nonce = wp_unslash( $_REQUEST['tua-forma-nonce'] );
	}

	if ( ! wp_verify_nonce( $nonce, 'tua-forma-nonce-' . wp_unslash( $_REQUEST['rand'] ) ) ) {
		print( 'Nada por aquí!' );
	} else {
		$tosend  = new TuaFormaPost();
		$referer = $_REQUEST['_wp_http_referer'];
		$data    = $tosend->validate_data( $_REQUEST );

		if ( isset( $data['error'] ) && ! $data['error'] ) {
			$headers    = array( 'Content-Type: text/html; charset=UTF-8' );
			$recipients = explode( ',', get_option( 'tua-forma-smtp-recipients' ) );

			if ( $data['honeypot'] ) {
				$subject = '<SPAM> - ' . get_option( 'tua-forma-subject' );
			} else {
				$subject = get_option( 'tua-forma-subject' );
			};

			$body = $tosend->email_body( $data['load'] );

			$wp_mail_return = wp_mail( $recipients, $subject, $body, $headers );

			if ( $wp_mail_return ) {
				$next = add_query_arg( 'tua-forma-message', 'successful', $referer );
				wp_safe_redirect( $next );
			} else {
				$next = add_query_arg( 'tua-forma-message', 'smtp-error', $referer );
				wp_safe_redirect( $next );
			};
		} else {
			$next = add_query_arg( 'tua-forma-message', 'invalid-data', $referer );
			wp_safe_redirect( $next );
		}
	}
}
