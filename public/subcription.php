

class Custom_Subscription_Payment_Gateway extends WC_Payment_Gateway_Subscription_Support
{
    public function __construct()
    {
        $this->id                 = 'custom_subscription_payment_gateway'; // Your payment gateway ID
        $this->method_title       = __('Custom Subscription Payment Gateway', 'custom-subscription-payment-gateway');
        $this->method_description = __('Pay with Custom Subscription Payment Gateway', 'custom-subscription-payment-gateway');
        $this->supports           = array('subscription');

        // Load settings
        $this->init_form_fields();
        $this->init_settings();

        // Define gateway settings
        $this->title       = $this->get_option('title');
        $this->description = $this->get_option('description');

        // Hooks
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title'   => __('Enable/Disable', 'custom-subscription-payment-gateway'),
                'type'    => 'checkbox',
                'label'   => __('Enable Custom Subscription Payment Gateway', 'custom-subscription-payment-gateway'),
                'default' => 'no',
            ),
            'title' => array(
                'title'       => __('Title', 'custom-subscription-payment-gateway'),
                'type'        => 'text',
                'description' => __('Title shown during checkout', 'custom-subscription-payment-gateway'),
                'default'     => __('Custom Subscription Payment Gateway', 'custom-subscription-payment-gateway'),
            ),
            'description' => array(
                'title'       => __('Description', 'custom-subscription-payment-gateway'),
                'type'        => 'textarea',
                'description' => __('Description shown during checkout', 'custom-subscription-payment-gateway'),
                'default'     => __('Pay with Custom Subscription Payment Gateway', 'custom-subscription-payment-gateway'),
            ),
        );
    }

    public function process_subscription_payment($order, $renewal)
    {
        // Subscription payment processing logic here
        // You can access the subscription order details using the $order object

        // Return the subscription status based on payment result
        return array(
            'result'   => 'success', // 'success', 'failure', 'pending', 'processing', etc.
            'redirect' => $this->get_return_url($order),
        );
    }
}

// Register the custom subscription payment gateway
function add_custom_subscription_payment_gateway($methods)
{
    $methods[] = 'Custom_Subscription_Payment_Gateway';
    return $methods;
}
add_filter('woocommerce_payment_gateways', 'add_custom_subscription_payment_gateway');
