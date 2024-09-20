<?php
// Segurança - impedir acesso direto
if (!defined('ABSPATH')) exit;

// Função para exportar dados para CSV
function lead_capture_export_csv() {
    if (!current_user_can('manage_options')) return;

    global $wpdb;
    $table_name = $wpdb->prefix . 'lead_capture';
    $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=leads.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['email', 'created_at'], ';');

    foreach ($results as $row) {
        fputcsv($output, $row, ';');
    }
    fclose($output);
    exit;
}
add_action('admin_post_export_leads', 'lead_capture_export_csv');
