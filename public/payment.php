<?php

require_once(ABSPATH . 'wp-content/plugins/woocommerce/woocommerce.php');

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (class_exists('WC_Payment_Gateway')) {
    return;
}

class Custom_Payment_Gateway extends WC_Payment_Gateway
{
    public function __construct()
    {
        $this->id                 = 'custom_payment_gateway'; // Your payment gateway ID
        $this->method_title       = __('Custom Payment Gateway', 'custom-payment-gateway');
        $this->method_description = __('Pay with Custom Payment Gateway', 'custom-payment-gateway');
        $this->supports           = array('products', 'subscription');

        // Load settings
        $this->init_form_fields();
        $this->init_settings();

        // Define gateway settings
        $this->title              = $this->get_option('title');
        $this->description        = $this->get_option('description');


        // Hooks
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }
    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title'   => __('Enable/Disable', 'custom-payment-gateway'),
                'type'    => 'checkbox',
                'label'   => __('Enable Custom Payment Gateway', 'custom-payment-gateway'),
                'default' => 'no',
            ),
            'title' => array(
                'title'       => __('Title', 'custom-payment-gateway'),
                'type'        => 'text',
                'description' => __('Title shown during checkout', 'custom-payment-gateway'),
                'default'     => __('Custom Payment Gateway', 'custom-payment-gateway'),
            ),
            'description' => array(
                'title'       => __('Description', 'custom-payment-gateway'),
                'type'        => 'textarea',
                'description' => __('Description shown during checkout', 'custom-payment-gateway'),
                'default'     => __('Pay with Custom Payment Gateway', 'custom-payment-gateway'),
            ),
            'payment_methods' => array(
                'title'       => __('Accepted Payment Methods', 'custom-payment-gateway'),
                'type'        => 'multiselect', // Use multiselect type for multiple options
                'description' => __('List the accepted payment methods for this gateway.', 'custom-payment-gateway'),
                'default'     => array('visa', 'mastercard', 'amex', 'discover'), // Set default methods here
                'options'     => array(
                    'visa'       => __('Visa', 'custom-payment-gateway'),
                    'mastercard' => __('MasterCard', 'custom-payment-gateway'),
                    'amex'       => __('American Express', 'custom-payment-gateway'),
                    'discover'   => __('Discover', 'custom-payment-gateway'),
                ),
            ),
        );
    }



    public function add_custom_payment_fields()
    {
        if ($this->is_available()) {
            echo '<div id="custom-payment-fields">';
            echo '<h3>' . __('Custom Payment', 'custom-payment-gateway') . '</h3>';

            echo '<p>';
            woocommerce_form_field('custom-card-number', array(
                'type'        => 'text',
                'class'       => array('input-text', 'custom-card-number'),
                'label'       => __('Credit Card Number', 'custom-payment-gateway'),
                'required'    => true,
            ), '');
            echo '</p>';
            echo '<p>';
            woocommerce_form_field('custom-card-expiry', array(
                'type'        => 'text',
                'class'       => array('input-text', 'custom-card-expiry'),
                'label'       => __('Expiry Date', 'custom-payment-gateway'),
                'placeholder' => 'MM/YY',
                'required'    => true,
            ), '');
            echo '</p>';

            echo '<p>';
            woocommerce_form_field('custom-card-cvc', array(
                'type'        => 'text',
                'class'       => array('input-text', 'custom-card-cvc'),
                'label'       => __('CVV/CVC Code', 'custom-payment-gateway'),
                'required'    => true,
            ), '');
            echo '</p>';

            echo '<p>';
            woocommerce_form_field('custom-card-name', array(
                'type'        => 'text',
                'class'       => array('input-text', 'custom-card-name'),
                'label'       => __('Cardholder Name', 'custom-payment-gateway'),
                'required'    => true,
            ), '');
            echo '</p>';






            echo '</div>';
        }
    }

    public function process_payment($order_id)
    {
        $order = wc_get_order($order_id);
        if (!$order) {
            wc_add_notice(__('Invalid Order.', 'custom-payment-gateway'), 'error');
            return;
        }

        // Initialize the Authorize.Net API credentials
        $api_login_id = '4f2C4xrGw';
        $api_transaction_key = '5um5B5kz9E7VC9ur';

        // Retrieve credit card number from the POST data
        $credit_card_number = isset($_POST['custom-card-number']) ? sanitize_text_field($_POST['custom-card-number']) : '';

        // Prepare the payment data
        $payment_data = array(
            'x_amount'           => $order->get_total(),
            'x_invoice_num'      => $order_id,
            'x_test_request'     => 'true', // Set to 'true' for testing
            'x_login'            => $api_login_id,
            'x_tran_key'         => $api_transaction_key,
            'x_version'          => '3.1',
            'x_card_num'         => $credit_card_number,

        );

        // Send the payment data to Authorize.Net API using cURL or other method
        $response = send_payment_request_to_authorize_net($payment_data); // You need to define this function

        // Process the response from Authorize.Net
        if ($response === 'APPROVED') {
            // Transaction was successful
            $payment_status = 'completed';
            $order->update_status($payment_status);

            // Reduce stock and empty cart
            wc_reduce_stock_levels($order_id);
            WC()->cart->empty_cart();

            // Redirect to the thank you page
            return array(
                'result'   => 'success',
                'redirect' => $this->get_return_url($order),
            );
        } else {
            // Transaction failed
            $order->update_status('failed', __('Payment failed', 'custom-payment-gateway'));
            wc_add_notice(__('Payment error:', 'custom-payment-gateway') . ' ' . $response, 'error');
            return;
        }
        $this->add_custom_payment_fields();
    }
    function send_payment_request_to_authorize_net($payment_data)
    {
        // Authorize.Net API endpoint
        $api_url = 'https://apitest.authorize.net/json/v1/request.api';

        // Build the JSON request
        $json_request = json_encode([
            'createTransactionRequest' => [
                'merchantAuthentication' => [
                    'name' => '4f2C4xrGw',
                    'transactionKey' => '5um5B5kz9E7VC9ur',
                ],
                'transactionRequest' => [
                    'transactionType' => 'authCaptureTransaction',
                    'amount' => $payment_data['x_amount'],
                    'order' => [
                        'invoiceNumber' => $payment_data['x_invoice_num'],
                    ],
                ],
            ],
        ]);

        // Initialize cURL session
        $curl = curl_init();

        // Set cURL options

        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer YourAPIToken',
        ]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Remove in production

        // Execute cURL session
        $response = curl_exec($curl);

        // Close cURL session
        curl_close($curl);

        // Decode the JSON response
        $json_response = json_decode($response, true);

        if (isset($json_response['transactionResponse']['responseCode'])) {
            $response_code = (int)$json_response['transactionResponse']['responseCode'];
            if ($response_code === 1) {
                // Transaction approved
                return 'APPROVED';
            } else {
                // Transaction declined
                return 'DECLINED';
            }
        }

        // Unable to parse response or unknown error
        return 'ERROR';
    }
}

// code for subscription payments 

class Custom_Payment_Gateway extends WC_Payment_Gateway_Subscription_Support
{

    public function __construct()
    {
        $this->id                 = 'custom_payment_gateway'; // Your payment gateway ID
        $this->method_title       = __('Custom Payment Gateway', 'custom-payment-gateway');
        $this->method_description = __('Pay with Custom Payment Gateway', 'custom-payment-gateway');
        $this->supports           = array('products', 'subscription');

        // Load settings
        $this->init_form_fields();
        $this->init_settings();

        // Define gateway settings
        $this->title              = $this->get_option('title');
        $this->description        = $this->get_option('description');


        // Hooks
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }
    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title'   => __('Enable/Disable', 'custom-payment-gateway'),
                'type'    => 'checkbox',
                'label'   => __('Enable Custom Payment Gateway', 'custom-payment-gateway'),
                'default' => 'no',
            ),
            'title' => array(
                'title'       => __('Title', 'custom-payment-gateway'),
                'type'        => 'text',
                'description' => __('Title shown during checkout', 'custom-payment-gateway'),
                'default'     => __('Custom Payment Gateway', 'custom-payment-gateway'),
            ),
            'description' => array(
                'title'       => __('Description', 'custom-payment-gateway'),
                'type'        => 'textarea',
                'description' => __('Description shown during checkout', 'custom-payment-gateway'),
                'default'     => __('Pay with Custom Payment Gateway', 'custom-payment-gateway'),
            ),
            'payment_methods' => array(
                'title'       => __('Accepted Payment Methods', 'custom-payment-gateway'),
                'type'        => 'multiselect', // Use multiselect type for multiple options
                'description' => __('List the accepted payment methods for this gateway.', 'custom-payment-gateway'),
                'default'     => array('visa', 'mastercard', 'amex', 'discover'), // Set default methods here
                'options'     => array(
                    'visa'       => __('Visa', 'custom-payment-gateway'),
                    'mastercard' => __('MasterCard', 'custom-payment-gateway'),
                    'amex'       => __('American Express', 'custom-payment-gateway'),
                    'discover'   => __('Discover', 'custom-payment-gateway'),
                ),
            ),
        );
    }



    public function add_custom_payment_fields()
    {
        if ($this->is_available()) {
            echo '<div id="custom-payment-fields">';
            echo '<h3>' . __('Custom Payment', 'custom-payment-gateway') . '</h3>';

            echo '<p>';
            woocommerce_form_field('custom-card-number', array(
                'type'        => 'text',
                'class'       => array('input-text', 'custom-card-number'),
                'label'       => __('Credit Card Number', 'custom-payment-gateway'),
                'required'    => true,
            ), '');
            echo '</p>';
            echo '<p>';
            woocommerce_form_field('custom-card-expiry', array(
                'type'        => 'text',
                'class'       => array('input-text', 'custom-card-expiry'),
                'label'       => __('Expiry Date', 'custom-payment-gateway'),
                'placeholder' => 'MM/YY',
                'required'    => true,
            ), '');
            echo '</p>';

            echo '<p>';
            woocommerce_form_field('custom-card-cvc', array(
                'type'        => 'text',
                'class'       => array('input-text', 'custom-card-cvc'),
                'label'       => __('CVV/CVC Code', 'custom-payment-gateway'),
                'required'    => true,
            ), '');
            echo '</p>';

            echo '<p>';
            woocommerce_form_field('custom-card-name', array(
                'type'        => 'text',
                'class'       => array('input-text', 'custom-card-name'),
                'label'       => __('Cardholder Name', 'custom-payment-gateway'),
                'required'    => true,
            ), '');
            echo '</p>';






            echo '</div>';
        }
    }

    public function process_payment($order_id)
    {
        $order = wc_get_order($order_id);
        if (!$order) {
            wc_add_notice(__('Invalid Order.', 'custom-payment-gateway'), 'error');
            return;
        }

        // Initialize the Authorize.Net API credentials
        $api_login_id = '4f2C4xrGw';
        $api_transaction_key = '5um5B5kz9E7VC9ur';

        // Retrieve credit card number from the POST data
        $credit_card_number = isset($_POST['custom-card-number']) ? sanitize_text_field($_POST['custom-card-number']) : '';

        // Prepare the payment data
        $payment_data = array(
            'x_amount'           => $order->get_total(),
            'x_invoice_num'      => $order_id,
            'x_test_request'     => 'true', // Set to 'true' for testing
            'x_login'            => $api_login_id,
            'x_tran_key'         => $api_transaction_key,
            'x_version'          => '3.1',
            'x_card_num'         => $credit_card_number,

        );

        // Send the payment data to Authorize.Net API using cURL or other method
        $response = send_payment_request_to_authorize_net($payment_data); // You need to define this function

        // Process the response from Authorize.Net
        if ($response === 'APPROVED') {
            // Transaction was successful
            $payment_status = 'completed';
            $order->update_status($payment_status);

            // Reduce stock and empty cart
            wc_reduce_stock_levels($order_id);
            WC()->cart->empty_cart();

            // Redirect to the thank you page
            return array(
                'result'   => 'success',
                'redirect' => $this->get_return_url($order),
            );
        } else {
            // Transaction failed
            $order->update_status('failed', __('Payment failed', 'custom-payment-gateway'));
            wc_add_notice(__('Payment error:', 'custom-payment-gateway') . ' ' . $response, 'error');
            return;
        }
        $this->add_custom_payment_fields();
    }
    function send_payment_request_to_authorize_net($payment_data)
    {
        // Authorize.Net API endpoint
        $api_url = 'https://apitest.authorize.net/json/v1/request.api';

        // Build the JSON request
        $json_request = json_encode([
            'createTransactionRequest' => [
                'merchantAuthentication' => [
                    'name' => '4f2C4xrGw',
                    'transactionKey' => '5um5B5kz9E7VC9ur',
                ],
                'transactionRequest' => [
                    'transactionType' => 'authCaptureTransaction',
                    'amount' => $payment_data['x_amount'],
                    'order' => [
                        'invoiceNumber' => $payment_data['x_invoice_num'],
                    ],
                ],
            ],
        ]);

        // Initialize cURL session
        $curl = curl_init();

        // Set cURL options

        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer YourAPIToken',
        ]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Remove in production

        // Execute cURL session
        $response = curl_exec($curl);

        // Close cURL session
        curl_close($curl);

        // Decode the JSON response
        $json_response = json_decode($response, true);

        if (isset($json_response['transactionResponse']['responseCode'])) {
            $response_code = (int)$json_response['transactionResponse']['responseCode'];
            if ($response_code === 1) {
                // Transaction approved
                return 'APPROVED';
            } else {
                // Transaction declined
                return 'DECLINED';
            }
        }

        // Unable to parse response or unknown error
        return 'ERROR';
    }
}



// Add this action hook to display custom payment fields on checkout page
add_action('woocommerce_checkout_before_terms_and_conditions', 'add_custom_payment_fields_hooked');

function add_custom_payment_fields_hooked()
{
    if (is_checkout() && WC()->payment_gateways->selected_payment_method === 'custom_payment_gateway') {
        $gateway = WC()->payment_gateways->get_gateway('custom_payment_gateway');
        $gateway->add_custom_payment_fields();
    }
}

function add_custom_payment_gateway($methods)
{
    $methods[] = 'Custom_Payment_Gateway';
    $methods[] = 'Custom_Subscription_Payment_Gateway';
    return $methods;
}
// Add this action hook to display custom payment fields on checkout page
add_action('woocommerce_checkout_before_terms_and_conditions', 'add_custom_payment_fields_hooked');
