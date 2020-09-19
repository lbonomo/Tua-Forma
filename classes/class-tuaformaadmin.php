<?php
/**
 * Admin class
 *
 * @package         Tua_Forma
 */

/**
 * TuaFormaPost Class.
 */
class TuaFormaAdmin {
	/**
	 * Constructorn.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'tua_forma_register_setting' ) );
		add_action( 'admin_menu', array( $this, 'tua_forma_option_page' ) );
	}

	/**
	 * Allowed tags.
	 *
	 * @var array self::ALLOWED_HTML Array of html tags allowed.
	 */
	const ALLOWED_HTML = array(
		'input' => array(
			'type'    => array(),
			'value'   => array(),
			'name'    => array(),
			'class'   => array(),
			'id'      => array(),
			'checked' => array(),
		),
		'p'     => array(
			'class' => array(),
		),
		'label' => array(
			'for' => array(),
		),
	);

	/**
	 * Registro el Muenu.
	 */
	public function tua_forma_option_page() {
		add_options_page(
			'Tua Forma config',
			'Tua Forma',
			'manage_options',
			'tua-forma-settings',
			array( 'TuaFormaAdmin', 'tua_forma_options_page_display' )
		);
	}

	/**
	 * Section.
	 */
	public function tua_forma_register_setting() {
		add_settings_section(
			'tua-forma-options-section',
			'Opciones de Tua Forma',
			array( $this, 'tua_forma_section_callback' ),
			'tua-forma-settings'
		);

		// SMTP Recipients.
		register_setting( 'tua-forma-settings', 'tua-forma-smtp-recipients' );
		add_settings_field(
			'tua-forma-smtp-recipients',
			'Destinatarios:',
			array( $this, 'tua_forma_text_callback' ),
			'tua-forma-settings',
			'tua-forma-options-section',
			array(
				'label_for'   => 'tua-forma-smtp-recipients',
				'description' => 'Uno o más destinatario, separado por coma',
				'class'       => 'regular-text',
			)
		);

		// Mensaje de error.
		register_setting( 'tua-forma-settings', 'tua-forma-error-message' );
		add_settings_field(
			'tua-forma-error-message',
			'Mensaje de error:',
			array( $this, 'tua_forma_text_callback' ),
			'tua-forma-settings',
			'tua-forma-options-section',
			array(
				'label_for'   => 'tua-forma-error-message',
				'description' => null,
				'class'       => 'regular-text',
			)
		);

		// Mensaje de exito.
		register_setting( 'tua-forma-settings', 'tua-forma-successful-message' );
		add_settings_field(
			'tua-forma-successful-message',
			'Mensaje de exito:',
			array( $this, 'tua_forma_text_callback' ),
			'tua-forma-settings',
			'tua-forma-options-section',
			array(
				'label_for'   => 'tua-forma-successful-message',
				'description' => null,
				'class'       => 'regular-text',
			)
		);

		// Datos adicionales.
		register_setting( 'tua-forma-settings', 'tua-forma-metadata' );
		add_settings_field(
			'tua-forma-metadata',
			'Incluir datos adicionales:',
			array( $this, 'tua_forma_checkbox_callback' ),
			'tua-forma-settings',
			'tua-forma-options-section',
			array(
				'label_for'   => 'tua-forma-metadata',
				'description' => 'Incluir datos adicionales en el email',
				'class'       => '',
			)
		);

		// Asunto del mail.
		register_setting( 'tua-forma-settings', 'tua-forma-subject' );
		add_settings_field(
			'tua-forma-subject',
			'Asunto del mail:',
			array( $this, 'tua_forma_text_callback' ),
			'tua-forma-settings',
			'tua-forma-options-section',
			array(
				'label_for'   => 'tua-forma-subject',
				'description' => null,
				'class'       => 'regular-text',
			)
		);

		// Campo para mitigar el spam.
		register_setting( 'tua-forma-settings', 'tua-forma-honeypot' );
		add_settings_field(
			'tua-forma-honeypot',
			'Nombre del campo (honeypot):',
			array( $this, 'tua_forma_text_callback' ),
			'tua-forma-settings',
			'tua-forma-options-section',
			array(
				'label_for'   => 'tua-forma-honeypot',
				'description' => null,
				'class'       => 'regular-text',
			)
		);

	}

	/**
	 * Section callback
	 */
	public function tua_forma_section_callback() { }

	/**
	 * Checkbox callback
	 *
	 * @param array $args Checkbox args.
	 */
	public function tua_forma_checkbox_callback( $args ) {
		$value       = get_option( $args['label_for'] );
		$value       = isset( $value ) ? esc_attr( $value ) : '0';
		$name        = $args['label_for'];
		$description = $args['description'];
		$class       = $args['class'];
		$html        = "<label for='$name'>";
		if ( '1' === $value ) {
			$html .= "<input type='checkbox' name='$name' id='$name' value='1' class='$class' checked='checked'>";
		} else {
			$html .= "<input type='checkbox' name='$name' id='$name' value='1' class='$class'>";
		}
		if ( null !== $description ) {
			$html .= $description; }
		$html .= '</label>';
		echo wp_kses( $html, self::ALLOWED_HTML );
	}

	/**
	 * Text callback
	 *
	 * @param array $args Text args.
	 */
	public function tua_forma_text_callback( $args ) {
		$value       = get_option( $args['label_for'] );
		$value       = isset( $value ) ? esc_attr( $value ) : '';
		$name        = $args['label_for'];
		$description = $args['description'];
		$class       = $args['class'];
		$html        = "<input type='text' name='$name' value='$value' class='$class'>";
		if ( null !== $description ) {
			$html .= "<p class='description'>$description</p>"; }
		echo wp_kses( $html, self::ALLOWED_HTML );
	}

	/**
	 * Password callback
	 *
	 * @param array $args Password args.
	 */
	public function tua_forma_password_callback( $args ) {
		$value       = get_option( $args['label_for'] );
		$value       = isset( $value ) ? esc_attr( $value ) : '';
		$name        = $args['label_for'];
		$description = $args['description'];
		$class       = $args['class'];
		$html        = "<input type='password' name='$name' value='$value' class='$class'>";
		if ( null !== $description ) {
			$html .= "<p class='description'>$description</p>"; }
		echo wp_kses( $html, self::ALLOWED_HTML );
	}

	/**
	 * Number callback
	 *
	 * @param array $args Number args.
	 */
	public function tua_forma_number_callback( $args ) {
		$value       = get_option( $args['label_for'] );
		$value       = isset( $value ) ? esc_attr( $value ) : '';
		$name        = $args['label_for'];
		$description = $args['description'];
		$class       = $args['class'];
		$max         = $args['max'];
		$min         = $args['min'];
		$html        = "<input type='number' max='$max' min='$min' id='$name' name='$name' value='$value' style='width:5em;'>";
		if ( null !== $description ) {
			$html .= "<p class='description'>$description</p>"; }
		echo wp_kses( $html, self::ALLOWED_HTML );
	}

	/**
	 * Select callback
	 *
	 * @param array $args Select args.
	 */
	public function tua_forma_selects_callback( $args ) {
		$value       = get_option( $args['label_for'] );
		$value       = isset( $value ) ? esc_attr( $value ) : '';
		$description = $args['description'];
		$name        = $args['label_for'];
		$class       = $args['class'];
		$options     = $args['options'];
		$html        = "<select name='$name' id='$name' >";
		foreach ( array_keys( $options ) as $key ) {
			if ( $key === $value ) {
				$html .= "<option value='$key' selected='selected'>$options[$key]</option>";
			} else {
				$html .= "<option value='$key'>$options[$key]</option>";
			}
		}
		$html .= '</select>';
		if ( null !== $description ) {
			$html .= "<p class='description'>$description</p>"; }
		echo wp_kses( $html, self::ALLOWED_HTML );
	}

	/**
	 * Radio callback
	 *
	 * @param array $args Radio args.
	 */
	public function tua_forma_radio_callback( $args ) {
		$value       = get_option( $args['label_for'] );
		$value       = isset( $value ) ? (int) esc_attr( $value ) : '';
		$description = $args['description'];
		$name        = $args['label_for'];
		$class       = $args['class'];
		$radios      = $args['radios'];
		$html        = '';
		foreach ( array_keys( $radios ) as $key ) {

			if ( $key === $value ) {
				$html .= "<label><input type='radio' name='$name' value='$key' checked='checked'>$radios[$key]</label>&nbsp;";
			} else {
				$html .= "<label><input type='radio' name='$name' value='$key'>$radios[$key]</label>&nbsp;";
			}
		}
		if ( null !== $description ) {
			$html .= "<p class='description'>$description</p>"; }
		echo wp_kses( $html, self::ALLOWED_HTML );
	}

	/**
	 * Options display
	 */
	public static function tua_forma_options_page_display() {

		if ( current_user_can( 'manage_options' ) ) {
			settings_errors( 'tua-forma-settings' );
			echo '<form action="options.php" method="post">';
			settings_fields( 'tua-forma-settings' );
			do_settings_sections( 'tua-forma-settings' );

			// Boton probar.
			submit_button( 'Grabar' );
			echo '</form>';

			// Boton de prueba de envio.
			$action     = '/tua-forma-send';
			$nonce_rand = wp_rand();
			$body       = "\n<form accept-charset='UTF-8' action='$action' method='POST'>\n";
			$body      .= "<input type='hidden' id='rand' name='rand' value='$nonce_rand'>\n";
			$body      .= "<input type='hidden' id='test' name='test' value='Solo una prueba'>\n";
			$body      .= wp_nonce_field( 'tua-forma-nonce-' . $nonce_rand, 'tua-forma-nonce', true, false );
			$body      .= '<input type="submit" value="Enviar prueba"></input>' . "\n";
			$body      .= '<span id="swpsmtp-spinner" class="spinner"></span>';
			$body      .= "\n</form>\n";
		}
	}
}
