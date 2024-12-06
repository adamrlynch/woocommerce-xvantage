<?php

if (!defined('ABSPATH')) {
    exit;
}

class WRS_Xvantage {

    private $api_id;
    private $api_secret;
    private $im_customer_number;
    private $im_correlation_id;
    private $im_country_code;
    private $product_numbers;

    public function __construct() {
        $this->api_id = get_option('wrs_api_id');
        $this->api_secret = get_option('wrs_api_secret');
        $this->im_customer_number = get_option('wrs_im_customer_number');
        $this->im_correlation_id = get_option('wrs_im_correlation_id');
        $this->im_country_code = get_option('wrs_im_country_code');
        $this->product_numbers = get_option('wrs_product_numbers');

        add_action('init', array($this, 'fetch_xvantage_products'));
    }

    private function get_access_token() {
        $api_url = 'https://api.example.com/token';
        $response = wp_remote_post($api_url, array(
            'body' => array(
                'client_id' => $this->api_id,
                'client_secret' => $this->api_secret,
                'grant_type' => 'client_credentials'
            )
        ));

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        return $data['access_token'] ?? false;
    }

    public function fetch_xvantage_products() {
        $access_token = $this->get_access_token();
        if (!$access_token) {
            return;
        }

        $product_numbers = array_map('trim', explode("\n", $this->product_numbers));

        foreach ($product_numbers as $part_number) {
            $api_url = 'https://api.example.com/resellers/v6/catalog/details/' . $part_number;
            $response = wp_remote_get($api_url, array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $access_token,
                    'IM-CustomerNumber' => $this->im_customer_number,
                    'IM-CorrelationID' => $this->im_correlation_id,
                    'IM-CountryCode' => $this->im_country_code
                )
            ));

            if (is_wp_error($response)) {
                continue;
            }

            $body = wp_remote_retrieve_body($response);
            $product = json_decode($body, true);

            if (!empty($product)) {
                $this->populate_product($product);
            }
        }
    }

    private function populate_product($product) {
        $post_id = wp_insert_post(array(
            'post_title'   => $product['name'],
            'post_content' => $product['description'],
            'post_status'  => 'publish',
            'post_type'    => 'product',
        ));

        if ($post_id) {
            update_post_meta($post_id, '_price', $product['price']);
            update_post_meta($post_id, '_stock_status', 'instock');
        }
    }
}

new WRS_Xvantage();
