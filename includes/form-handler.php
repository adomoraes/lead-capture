<?php
// Segurança - impedir acesso direto
if (!defined('ABSPATH')) exit;

// Registrar shortcode para o formulário
function lead_capture_form_shortcode() {
    ob_start(); ?>
    <form id="lead-capture-form" method="POST">
        <input type="email" name="lead_email" placeholder="Seu e-mail" required>
        <button type="submit">Enviar</button>
        <div id="lead-capture-message"></div>
    </form>
    <?php return ob_get_clean();
}
add_shortcode('capture_lead_form', 'lead_capture_form_shortcode');

// Processar envio do formulário via AJAX
function lead_capture_form_handler_ajax() {
    // Verificar se o e-mail foi enviado e validar
    if (isset($_POST['lead_email']) && is_email($_POST['lead_email'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'lead_capture';
        $email = sanitize_email($_POST['lead_email']);
        
        // Verificar se o e-mail já existe
        $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE email = %s", $email));

        if ($exists) {
            wp_send_json_error(['message' => 'Este e-mail já está cadastrado.']);
        } else {
            // Inserir no banco de dados
            $wpdb->insert($table_name, [
                'email' => $email,
                'created_at' => current_time('mysql')
            ]);
            wp_send_json_success(['message' => 'E-mail cadastrado com sucesso!']);
        }
    } else {
        wp_send_json_error(['message' => 'Por favor, insira um e-mail válido.']);
    }
}
add_action('wp_ajax_nopriv_submit_lead_ajax', 'lead_capture_form_handler_ajax');
add_action('wp_ajax_submit_lead_ajax', 'lead_capture_form_handler_ajax');

