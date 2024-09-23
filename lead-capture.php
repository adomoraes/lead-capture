<?php
/*
Plugin Name: Lead Capture
Description: Plugin para captação de leads via formulário no rodapé.
Version: 1.0
Author: Ado Moraes
*/

// Segurança - impedir acesso direto
if (!defined('ABSPATH')) exit;

// Definindo constantes do plugin
define('LEAD_CAPTURE_DIR', plugin_dir_path(__FILE__));
define('LEAD_CAPTURE_URL', plugin_dir_url(__FILE__));

// Carregar scripts e estilos do plugin
function lead_capture_enqueue_scripts() {
    wp_enqueue_style('lead-capture-css', LEAD_CAPTURE_URL . 'assets/style.css');
    
    // Registrar e incluir script JS para AJAX
    wp_enqueue_script('lead-capture-js', LEAD_CAPTURE_URL . 'assets/script.js', ['jquery'], null, true);
    wp_localize_script('lead-capture-js', 'leadCapture', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'lead_capture_enqueue_scripts');

// Registrar a tabela personalizada no banco de dados
function lead_capture_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lead_capture';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE (email)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'lead_capture_create_table');

// Registrar rotas customizadas para API REST
function register_lead_capture_routes() {
    register_rest_route('lead-capture/v1', '/subscribers', array(
        'methods' => WP_REST_Server::READABLE, // Método GET
        'callback' => 'handle_get_subscribers',
        'permission_callback' => '__return_true'
    ));

    register_rest_route('lead-capture/v1', '/subscribers', array(
        'methods' => WP_REST_Server::CREATABLE, // Método POST
        'callback' => 'handle_post_subscriber',
        'permission_callback' => '__return_true' // Permite acesso público ao POST.
    ));
}
add_action('rest_api_init', 'register_lead_capture_routes');

// Função para processar o POST - adicionar novos inscritos
function handle_post_subscriber($request) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lead_capture';
    $params = $request->get_json_params();
    $email = isset($params['email']) ? sanitize_email($params['email']) : '';

    // Validação do e-mail
    if (!is_email($email)) {
        return new WP_Error('invalid_email', 'Por favor, insira um e-mail válido.', array('status' => 400));
    }

    // Verificar se o e-mail já está cadastrado
    $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE email = %s", $email));
    if ($exists) {
        return new WP_Error('email_exists', 'E-mail já cadastrado.', array('status' => 400));
    }

    // Inserir no banco de dados
    $result = $wpdb->insert($table_name, array(
        'email' => $email,
        'created_at' => current_time('mysql')
    ));

    if ($result) {
        $response = rest_ensure_response(array(
            'message' => 'Inscrição realizada com sucesso!',
            'success' => true, // Certifique-se de incluir a propriedade 'success'
        ));
        $response->set_status(201); // Definir código de status HTTP para criação bem-sucedida
        return $response;
    } else {
        return new WP_Error('db_error', 'Erro ao salvar a inscrição. Tente novamente mais tarde.', array('status' => 500));
    }
}

// Função para processar o GET - retornar todos os inscritos
function handle_get_subscribers($request) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lead_capture';

    // Obter todos os inscritos do banco de dados
    $subscribers = $wpdb->get_results("SELECT * FROM $table_name");

    // Retornar a lista de inscritos
    $response = rest_ensure_response($subscribers);
    $response->set_status(200); // Definir código de status HTTP para sucesso
    return $response;
}

// Incluir arquivos de funcionalidades adicionais
include(LEAD_CAPTURE_DIR . 'includes/form-handler.php');
include(LEAD_CAPTURE_DIR . 'includes/admin-page.php');
include(LEAD_CAPTURE_DIR . 'includes/export.php');
