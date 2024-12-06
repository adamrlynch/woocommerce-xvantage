<?php
/**
 * Plugin Name: WooCommerce Xvantage
 * Description: Populates WooCommerce product catalog from an external source using the Xvantage API.
 * Version: 0.1.0
 * Author: Adam Lynch
 * Text Domain: woocommerce-xvantage
 */

if (!defined('ABSPATH')) {
    exit;
}

// Add a settings page under the WooCommerce menu.
add_action('admin_menu', 'wrs_add_settings_page');

function wrs_add_settings_page() {
    add_submenu_page(
        'woocommerce',
        __('Xvantage Settings', 'woocommerce-xvantage'),
        __('Xvantage Settings', 'woocommerce-xvantage'),
        'manage_options',
        'xvantage-settings',
        'wrs_render_settings_page'
    );
}

function wrs_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Xvantage Settings', 'woocommerce-xvantage'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wrs_settings_group');
            do_settings_sections('xvantage-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_init', 'wrs_register_settings');

function wrs_register_settings() {
    register_setting('wrs_settings_group', 'wrs_api_id');
    register_setting('wrs_settings_group', 'wrs_api_secret');
    register_setting('wrs_settings_group', 'wrs_im_customer_number');
    register_setting('wrs_settings_group', 'wrs_im_correlation_id');
    register_setting('wrs_settings_group', 'wrs_im_country_code');
    register_setting('wrs_settings_group', 'wrs_product_numbers');

    add_settings_section(
        'wrs_settings_section',
        __('API Settings', 'woocommerce-xvantage'),
        null,
        'xvantage-settings'
    );

    add_settings_field(
        'wrs_api_id',
        __('API ID', 'woocommerce-xvantage'),
        'wrs_api_id_callback',
        'xvantage-settings',
        'wrs_settings_section'
    );

    add_settings_field(
        'wrs_api_secret',
        __('API Secret', 'woocommerce-xvantage'),
        'wrs_api_secret_callback',
        'xvantage-settings',
        'wrs_settings_section'
    );

    add_settings_field(
        'wrs_im_customer_number',
        __('IM Customer Number', 'woocommerce-xvantage'),
        'wrs_im_customer_number_callback',
        'xvantage-settings',
        'wrs_settings_section'
    );

    add_settings_field(
        'wrs_im_correlation_id',
        __('IM Correlation ID', 'woocommerce-xvantage'),
        'wrs_im_correlation_id_callback',
        'xvantage-settings',
        'wrs_settings_section'
    );

    add_settings_field(
        'wrs_im_country_code',
        __('IM Country Code', 'woocommerce-xvantage'),
        'wrs_im_country_code_callback',
        'xvantage-settings',
        'wrs_settings_section'
    );

    add_settings_field(
        'wrs_product_numbers',
        __('Product Numbers', 'woocommerce-xvantage'),
        'wrs_product_numbers_callback',
        'xvantage-settings',
        'wrs_settings_section'
    );
}

function wrs_api_id_callback() {
    $api_id = get_option('wrs_api_id');
    echo '<input type="text" name="wrs_api_id" value="' . esc_attr($api_id) . '" class="regular-text">';
}

function wrs_api_secret_callback() {
    $api_secret = get_option('wrs_api_secret');
    echo '<input type="password" name="wrs_api_secret" value="' . esc_attr($api_secret) . '" class="regular-text">';
}

function wrs_im_customer_number_callback() {
    $customer_number = get_option('wrs_im_customer_number');
    echo '<input type="text" name="wrs_im_customer_number" value="' . esc_attr($customer_number) . '" class="regular-text">';
}

function wrs_im_correlation_id_callback() {
    $correlation_id = get_option('wrs_im_correlation_id');
    echo '<input type="text" name="wrs_im_correlation_id" value="' . esc_attr($correlation_id) . '" class="regular-text">';
}

function wrs_im_country_code_callback() {
    $country_code = get_option('wrs_im_country_code');
    echo '<input type="text" name="wrs_im_country_code" value="' . esc_attr($country_code) . '" class="regular-text">';
}

function wrs_product_numbers_callback() {
    $product_numbers = get_option('wrs_product_numbers');
    echo '<textarea name="wrs_product_numbers" rows="10" class="large-text">' . esc_textarea($product_numbers) . '</textarea>';
}

add_action('plugins_loaded', 'wrs_xvantage_init');

function wrs_xvantage_init() {
    if (class_exists('WooCommerce')) {
        include_once dirname(__FILE__) . '/includes/class-wrs-xvantage.php';
    }
}
