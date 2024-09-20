<?php
// Segurança - impedir acesso direto
if (!defined('ABSPATH')) exit;

// Criar página de administração
function lead_capture_admin_menu() {
    add_menu_page(
        'Leads Capturados',
        'Leads',
        'manage_options',
        'lead-capture-admin',
        'lead_capture_admin_page',
        'dashicons-email'
    );
}
add_action('admin_menu', 'lead_capture_admin_menu');

// Conteúdo da página de administração
function lead_capture_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lead_capture';
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    echo '<div class="wrap">';
    echo '<h1>Leads Capturados</h1>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Email</th><th>Data</th></tr></thead>';
    echo '<tbody>';
    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . $row->id . '</td>';
        echo '<td>' . $row->email . '</td>';
        echo '<td>' . $row->created_at . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
    echo '<a href="' . admin_url('admin-post.php?action=export_leads') . '" class="button button-primary">Exportar CSV</a>';
    echo '</div>';
}
