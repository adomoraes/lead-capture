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

// Incluir arquivos de funcionalidades
include(LEAD_CAPTURE_DIR . 'includes/form-handler.php');
include(LEAD_CAPTURE_DIR . 'includes/admin-page.php');
include(LEAD_CAPTURE_DIR . 'includes/export.php');
