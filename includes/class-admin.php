<?php

class Tua_Forma_Admin {


    public function __construct() {
        add_action('admin_init', array($this, 'register_setting'));
        add_action('admin_menu', array($this, 'option_page'));
    }

    # Registro el Muenu
    function option_page() {
        add_menu_page(
            'Tua Forma config',
            'Tua Forma', # $menu_title
            'manage_options',
            'tua-forma-settings',
            array( 'Tua_Forma_Admin', 'options_page_display'), # $function,
            'dashicons-forms', # $icon_url - Ver en https://developer.wordpress.org/resource/dashicons/
            15 # $position
        );

    }

    function register_setting() {
        // Section
        add_settings_section(
            'tua-forma-options-section',
            'SMTP options',
            array($this, 'section_callback'),
            'tua-forma-settings');

        // Registro en la tabla y agrego el campo a la Seccion

        // TODO - Reply-To
        register_setting(
            'tua-forma-settings',  # $option_group,
            'tua-forma-smtp-reply-to', # $option_name,
            null                   # $sanitize_callback
        );
        add_settings_field(
            'tua-forma-smtp-reply-to',            # $id
            'Responder a',                           # $title
            array($this,'text_callback'), # $callback
            'tua-forma-settings',                 # $page
            'tua-forma-options-section',          # $section
            [
                'label_for' => 'tua-forma-smtp-reply-to',
                'class' => 'regular-text',
                'description' => null,
            ]
        );        
        
        // TODO - From
        register_setting(
            'tua-forma-settings',  # $option_group,
            'tua-forma-smtp-from', # $option_name,
            null                   # $sanitize_callback
        );
        add_settings_field(
            'tua-forma-smtp-from',                # $id
            'Enviar a',                           # $title
            array($this,'text_callback'), # $callback
            'tua-forma-settings',                 # $page
            'tua-forma-options-section',          # $section
            [
                'label_for' => 'tua-forma-smtp-from',
                'class' => 'regular-text',
                'description' => null,
            ]
        );        

        /**** SMTP USERNAME  ****/
        register_setting(
            'tua-forma-settings',  # $option_group,
            'tua-forma-smtp-user', # $option_name,
            null                   # $sanitize_callback
        );
        add_settings_field(
            'tua-forma-smtp-user',                # $id
            'Username',                           # $title
            array($this,'text_callback'), # $callback
            'tua-forma-settings',                 # $page
            'tua-forma-options-section',          # $section
            [
                'label_for' => 'tua-forma-smtp-user',
                'class' => 'regular-text',
                'description' => null,
            ]
        );

        /**** SMTP PASSWORD  ****/
        register_setting('tua-forma-settings', 'tua-forma-smtp-pass');
        add_settings_field(
            'tua-forma-smtp-pass',            # $id
            'Password',                       # $title
            array($this,'password_callback'), # $callback
            'tua-forma-settings',             # $page
            'tua-forma-options-section',      # $section
            [                                 # $args
                'label_for' => 'tua-forma-smtp-pass',
                'description' => null,
                'class' => 'regular-text',
            ]
        );

        /**** SMTP SERVER  ****/
        register_setting('tua-forma-settings', 'tua-forma-smtp-server');
        add_settings_field(
            'tua-forma-smtp-server',
            'Server',
            array($this,'text_callback'),
            'tua-forma-settings',
            'tua-forma-options-section',
            [
                'label_for' => 'tua-forma-smtp-server',
                'description' => "Ej: Gmail = smtp.gmail.com. https://support.google.com/a/answer/176600?hl=en",
                'class' => 'regular-text',
            ]
        );

        /**** SMTP PORT  ****/
        register_setting('tua-forma-settings' ,'tua-forma-smtp-port');
        add_settings_field(
            'tua-forma-smtp-port',
            'Port',
            array($this,'number_callback'),
            'tua-forma-settings',
            'tua-forma-options-section',
            [
                'label_for' => 'tua-forma-smtp-port',
                'description' => 'Ej: Gmail = 587',
                'class' => 'regular-text',
                'max' => '65535',
                'min' => '1',
            ]
        );

        /**** SMTP PROTOCOLO  ****/
        register_setting('tua-forma-settings', 'tua-forma-smtp-protocol');
        add_settings_field(
            'tua-forma-smtp-protocol',
            'Protocol',
            array($this,'selects_callback'),
            'tua-forma-settings',
            'tua-forma-options-section',
            [
                'label_for' => 'tua-forma-smtp-protocol',
                'description' => 'Ej: Gmail = TLS',
                'class' => 'regular-text',
                'options' => [
                    'no' => 'No',
                    'ssl' => 'SSL',
                    'tls' => 'TLS'
                ]
            ]
        );

        /**** SMTP Recipients  ****/
        register_setting('tua-forma-settings', 'tua-forma-smtp-recipients');
        add_settings_field(
            'tua-forma-smtp-recipients',
            'Destinatarios',
            array($this,'text_callback'),
            'tua-forma-settings',
            'tua-forma-options-section',
            [
                'label_for' => 'tua-forma-smtp-recipients',
                'description' => 'Uno o más destinatario, separado por coma',
                'class' => 'regular-text',
            ]
        );

        /**** SMTP AUTHENTICATION ****/
        register_setting('tua-forma-settings', 'tua-forma-smtp-authentication');
        add_settings_field(
            'tua-forma-smtp-authentication',
            'Autenticación',
            array($this,'radio_callback'),
            'tua-forma-settings',
            'tua-forma-options-section',
            [
                'label_for' => 'tua-forma-smtp-authentication',
                'description' => 'Deja esta opción en Sí. Sólo una pequeña parte de los servicios SMTP requerirán que se desactive la Autenticación.',
                'class' => '',
                'radios' => [
                    '1' => 'Si',
                    '0' => 'No',
                ],
            ]
        );

        /**** Mensaje de error ****/
        register_setting('tua-forma-settings', 'tua-forma-error-message');
        add_settings_field(
            'tua-forma-error-message',
            'Mensaje de error',
            array($this,'text_callback'),
            'tua-forma-settings',
            'tua-forma-options-section',
            [
                'label_for' => 'tua-forma-error-message',
                'description' => null,
                'class' => 'regular-text',
            ]
        );

        /**** Mensaje de exito ****/
        register_setting('tua-forma-settings', 'tua-forma-successful-message');
        add_settings_field(
            'tua-forma-successful-message',
            'Mensaje de exito',
            array($this,'text_callback'),
            'tua-forma-settings',
            'tua-forma-options-section',
            [
                'label_for' => 'tua-forma-successful-message',
                'description' => null,
                'class' => 'regular-text',
            ]
        );

        /**** Datos adicionales ****/
        register_setting('tua-forma-settings', 'tua-forma-metadata');
        add_settings_field(
            'tua-forma-metadata',
            'Incluir datos adicionales',
            array($this,'checkbox_callback'),
            'tua-forma-settings',
            'tua-forma-options-section',
            [
                'label_for' => 'tua-forma-metadata',
                'description' => "Incluir datos adicionales en el email",
                'class' => '',
            ]
        );

        /**** Asunto del mail ****/
        register_setting('tua-forma-settings', 'tua-forma-subject');
        add_settings_field(
            'tua-forma-subject',
            'Asunto del mail',
            array($this,'text_callback'),
            'tua-forma-settings',
            'tua-forma-options-section',
            [
                'label_for' => 'tua-forma-subject',
                'description' => null,
                'class' => 'regular-text',
            ]
        );        

    }

    function section_callback() { }

    function checkbox_callback($args) {
        $value = get_option($args['label_for']);
        $value = isset($value) ? esc_attr($value) : '0'; 
        $name = $args['label_for'];
        $description = $args['description'];
        $class = $args['class'];
        $html = "<label for='$name'>";
        if ($value === '1' ) {
            $html .= "<input type='checkbox' name='$name' id='$value' value='1' class='$class' checked='checked'>";
        } else { 
            $html .= "<input type='checkbox' name='$name' id='$value' value='1' class='$class'>";
        }
        if ($description !== null ) { $html .= $description; }
        $html .= "</label>";
        echo $html;
    }

    function text_callback($args) {
        $value = get_option($args['label_for']);
        $value = isset($value) ? esc_attr($value) : '';
        $name = $args['label_for'];
        $description = $args['description'];
        $class = $args['class'];
        $html = "<input type='text' name='$name' value='$value' class='$class'>";
        if ($description !== null ) { $html .= "<p class='description'>$description</p>"; }
        echo $html;
    }
    
    function password_callback($args) {
        $value = get_option($args['label_for']);
        $value = isset($value) ? esc_attr($value) : '';
        $name = $args['label_for'];
        $description = $args['description'];
        $class = $args['class'];
        $html = "<input type='password' name='$name' value='$value' class='$class'>";
        if ($description !== null ) { $html .= "<p class='description'>$description</p>"; }
        echo $html;
    }

    function number_callback($args) {
        $value = get_option($args['label_for']);
        $value = isset($value) ? esc_attr($value) : '';
        $name = $args['label_for'];
        $description = $args['description'];
        $class = $args['class'];
        $max = $args['max'];
        $min = $args['min'];
        $html = "<input type='number' max='$max' min='$min' id='$name' name='$name' value='$value' style='width:5em;'>";
        if ($description !== null ) { $html .= "<p class='description'>$description</p>"; }
        echo $html;
    }

    function selects_callback($args) {
        $value = get_option($args['label_for']);
        $value = isset($value) ? esc_attr($value) : '';
        $description = $args['description'];
        $name = $args['label_for'];
        $class = $args['class'];
        $options = $args['options'];
        $html = "<select name='$name' id='$name' >";
        foreach( array_keys($options) as $key ) {
            if ( $key === $value ) {
                $html .= "<option value='$key' selected='selected'>$options[$key]</option>";
            } else {
                $html .= "<option value='$key'>$options[$key]</option>";
            }
        }
        $html .= '</select>';
        if ($description !== null ) { $html .= "<p class='description'>$description</p>"; }
        echo $html;
    }

    function radio_callback($args) {
        $value = get_option($args['label_for']);
        $value = isset($value) ? (int)esc_attr($value) : '';
        $description = $args['description'];
        $name = $args['label_for'];
        $class = $args['class'];
        $radios = $args['radios'];
        $html = "";
        foreach( array_keys($radios) as $key ) {
            
            if ( $key === $value ) {
                $html .= "<label><input type='radio' name='$name' value='$key' checked='checked'>$radios[$key]</label>&nbsp;";
            } else {
                $html .= "<label><input type='radio' name='$name' value='$key'>$radios[$key]</label>&nbsp;";
            }
        }
        if ($description !== null ) { $html .= "<p class='description'>$description</p>"; }
        echo $html;
    }

    static function options_page_display() {
   

        // require_once plugin_dir_path(__File__).'../templates/admin-options-page.php';
        if (current_user_can('manage_options')) {

            /**** Verifico si esta preciente la clase Mail ****/
            if ( get_option('tua-forma-smtp-enabled') != 'true') { 
                echo '<div class="error settings-error notice is-dismissible">
                <p><strong>Es necesario tener instalada la libreria NET SMTP</strong> (sudo apt install php-mail php-net-smtp php-auth-sasl php-net-socket)</strong></p>
                <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Descartar este aviso.</span></button></div>
                ';
            }           

            /**** UPDATED ****/
            if ( isset ( $_GET['settings-updated'] ) ) {
                add_settings_error(
                    'tua-forma-settings',
                    'tua-forma-settings',
                    'Los datos se actualizaron correctamente',
                    'updated'
                );

            }
            settings_errors('tua-forma-settings');

            echo '<form action="options.php" method="post">';
            settings_fields('tua-forma-settings');
            do_settings_sections('tua-forma-settings');
            submit_button("Grabar");
            echo '</form>';

            /**** Boton probar  ****/
            // TODO
            // 
            // <a id="tua-forma-test" class="button-secondary">Enviar un correo electrónico de prueba</a>
            // 
        }
    }
}
