<?php
/**
 * PayTomorrow Standard Payment Gateway.
 *
 * Provides a PayTomorrow Standard Payment Gateway.
 *
 * @class       WC_Gateway_Paytomorrow
 * @extends     WC_Payment_Gateway
 * @version     2.1.5
 * @package     WooCommerce/Classes/Payment
 * @author      WooThemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Gateway_Paytomorrow Class.
 */
class WC_Gateway_Paytomorrow extends WC_Payment_Gateway {

	/**
	 * Whether or not logging is enabled
	 *
	 * @var bool
	 */
	public static $log_enabled = false;

	/**
	 * Logger Instance
	 *
	 * @var WC_Logger
	 */
	public static $log = false;

	/**
	 * OAuth API endpoint
	 *
	 * @var string
	 */
	public static $oauth_postfix = '/api/uaa/oauth/token';

	/**
	 * WooCommerce Endpoint
	 *
	 * @var string
	 */
	public static $checkout_postfix = '/api/application/ecommerce/orders';

	/**
	 * IPN Validation Endpoint
	 *
	 * @var string
	 */
	public static $validateipn_postfix = '/api/application-validation/validateipn';


	/**
	 * API URL
	 *
	 * @var string
	 */
	public static $api_url;

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {

		$this->id                = 'paytomorrow';
		$this->has_fields        = true;
		$this->order_button_text = __( 'Proceed to PayTomorrow', 'wc_paytomorrow' );
		$this->method_title      = __( 'PayTomorrow', 'wc_paytomorrow' );
		/* translators: %1$s: '<a href="' . admin_url( 'admin.php?page=wc-status' ) . '">' %2$s: '</a>' */
		$this->method_description = __( 'PayTomorrow standard sends customers to PayTomorrow to enter their payment information. PayTomorrow IPN requires fsockopen/cURL support to update order statuses after payment. Check the %1$ssystem status%2$s page for more details.', 'wc_paytomorrow' );
		$this->supports           = array(
			'products',
		);

		$this->method_description = sprintf( $this->method_description, '<a href="' . admin_url( 'admin.php?page=wc-status' ) . '">', '</a>' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title       =  $this->get_option( 'pt_title' );
		self::$api_url     = $this->get_option( 'api_url' );
		$this->description = '';

		$this->debug          = true;
		$this->email          = $this->get_option( 'email' );
		$this->receiver_email = $this->get_option( 'receiver_email', $this->email );
		self::$log_enabled = $this->debug;

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

		if ( ! $this->is_valid_for_use() ) {
			$this->enabled = 'no';
		} else {
			include_once dirname(__FILE__) . '/class-wc-gateway-paytomorrow-ipn-handler.php';
			new WC_Gateway_Paytomorrow_IPN_Handler( $this->receiver_email, self::$api_url, self::$validateipn_postfix );
		}
	}

	/**
	 * Logging method.
	 *
	 * @param string $message Message to log.
	 */
	public static function log( $message ) {
		if ( self::$log_enabled ) {
			if ( empty( self::$log ) ) {
				self::$log = new WC_Logger();
			}
			self::$log->add( 'paytomorrow', $message );
		}
	}

	/**
	 * Get gateway icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		$icon_html = '';
		$icon      = (array) $this->get_icon_image( WC()->countries->get_base_country() );
		
		$icon_html = '<a target="_blank">';
		foreach ( $icon as $i ) {
			$icon_html .= '<img src="https://cdn.paytomorrow.com/image/paytomorrow_checkout.svg" alt="' . esc_attr__( 'PayTomorrow Acceptance Mark', 'wc_paytomorrow' ) .'" style = "max-height : none ; height : 25px " />';
		}
		$icon_html .='</a>';

		return apply_filters( 'woocommerce_gateway_icon', $icon_html, $this->id );
	}

    /**
     * Get gateway fields.
     *
     * @return string
     */
    public function payment_fields()
    {

        $description_html = '<p> <span id="pt-description" style="font-weight: normal; font-size: 14px; display: block; margin-top: 15px;"> PayTomorrow offers <strong>Fair Financing for All Credit Types</strong>. Simply select PayTomorrow, supply some basic information via our secure application process and get instantly approved to complete your purchase. Applying to PayTomorrow will not affect your credit score.</span> </p>';

        echo $description_html;

    }

	/**
	 * Get the link for an icon based on country.
	 *
	 * @param  string $country Country.
	 * @return string
	 */
	protected function get_icon_url( $country ) {
		$url = 'https://www.paytomorrow.com/';

		return $url;
	}

	/**
	 * Get PayTomorrow images for a country.
	 *
	 * @param  string $country Country.
	 * @return array of image URLs
	 */
	protected function get_icon_image( $country ) {
		$icon = WC_HTTPS::force_https_url( '/wp-content/plugins/paytomorrow/assets/images/pay-tomorrow.png' );
		return apply_filters( 'woocommerce_paytomorrow_icon', $icon );
	}

	/**
	 * Check if this gateway is enabled and available in the user's country.
	 *
	 * @return bool
	 */
	public function is_valid_for_use() {
		return in_array( get_woocommerce_currency(), apply_filters( 'woocommerce_paytomorrow_supported_currencies', array( 'USD' ) ) );
	}

	/**
	 * Admin Panel Options.
	 * - Options for bits like 'title' and availability on a country-by-country basis.
	 *
	 * @since 1.0.0
	 */
	public function admin_options() {
		if ( $this->is_valid_for_use() ) {
			parent::admin_options();
		} else {
			esc_html_e( '<div class="inline error"><p><strong>Gateway Disabled</strong>: PayTomorrow does not support your store currency.</p></div>', 'wc_paytomorrow' );
		}
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = include 'settings-paytomorrow.php';
	}

	/**
	 * Get the transaction URL.
	 *
	 * @param  WC_Order $order The order.
	 * @return string
	 */
	public function get_transaction_url( $order ) {

		$this->view_transaction_url = 'https://www.paytomorrow.com/cgi-bin/webscr?cmd=_view-a-trans&id=%s';
		return parent::get_transaction_url( $order );
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @param  int $order_id Order ID.
	 * @return array
	 */
	public function process_payment( $order_id ) {
		paytomorrow_log( 'ENTERING process_payment' );
		$this->init_api();

		include_once dirname(__FILE__) . '/class-wc-gateway-paytomorrow-request.php';
		include_once dirname(__FILE__) . '/class-wc-gateway-paytomorrow-api-handler.php';

		$order               = wc_get_order( $order_id );
		$paytomorrow_request = new WC_Gateway_Paytomorrow_Request( $this );

		// $request_url = 'http://localhost:9000/api/application/checkWoo';
		$request_url       = self::$api_url . self::$checkout_postfix;
		$ecommerceresponse = array(
			'url'   => '',
			'token' => '',
		);
		$body_request      = array_merge( WC_Gateway_Paytomorrow_API_Handler::get_capture_request( $order ), $paytomorrow_request->get_paytomorrow_order_body_args( $order ) );
		$request           = array(
			'method'      => 'POST',
			'headers'     => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'bearer ' . WC_Gateway_Paytomorrow_API_Handler::$api_token,
			),
			'body'        => wp_json_encode( $body_request ),
			'timeout'     => 70,
			'user-agent'  => 'WooCommerce/' . WC()->version,
			'httpversion' => '1.1',
			'sslverify'   => false,
		);

		paytomorrow_log(
            (string)array(
                'requestUrl' => $request_url,
                'request' => $request,
                '$bodyRequest' => wp_json_encode($body_request),
            )
		);

		if ( is_ssl() ) {
			paytomorrow_log( 'ssl call' );
			$raw_response = wp_safe_remote_post( $request_url, $request );
		} else {
			paytomorrow_log( 'NO ssl call' );
			$raw_response = wp_remote_post( $request_url, $request );
		}

		paytomorrow_log( array( 'raw_response' => $raw_response ) );

		if ( is_wp_error( $raw_response ) ) {
			paytomorrow_log( 'We are currently experiencing problems trying to connect to this payment gateway. Sorry for the inconvenience.' );
		} else {
			$ecommerceresponse = json_decode( wp_remote_retrieve_body( $raw_response ) );
			update_post_meta( $order_id, PT_META_KEY, $ecommerceresponse->token );
		}

		return array(
			'result'   => 'success',
			'redirect' => $ecommerceresponse->url,
		);
	}

	/**
	 * Can the order be refunded via PayTomorrow?
	 *
	 * @param  WC_Order $order Order object.
	 * @return bool
	 */
	public function can_refund_order( $order ) {
		return $order && $order->get_transaction_id();
	}

	/**
	 * Init the API class and set the username/password etc.
	 */
	protected function init_api() {
		include_once dirname(__FILE__) . '/class-wc-gateway-paytomorrow-api-handler.php';

		WC_Gateway_Paytomorrow_API_Handler::$api_username     = $this->get_option( 'api_username' );
		WC_Gateway_Paytomorrow_API_Handler::$api_password     = $this->get_option( 'api_password' );
		WC_Gateway_Paytomorrow_API_Handler::$api_signature    = $this->get_option( 'api_signature' );
		WC_Gateway_Paytomorrow_API_Handler::$api_url          = $this->get_option( 'api_url' );
		WC_Gateway_Paytomorrow_API_Handler::$checkout_postfix = self::$checkout_postfix;
		WC_Gateway_Paytomorrow_API_Handler::$oauth_postfix    = self::$oauth_postfix;

		WC_Gateway_Paytomorrow_API_Handler::do_authorize();
		paytomorrow_log( 'sig: ' . WC_Gateway_Paytomorrow_API_Handler::$api_signature );
		paytomorrow_log( 'token: ' . WC_Gateway_Paytomorrow_API_Handler::$api_token );

	}
	// @codingStandardsIgnoreStart
	/**
	 * Process a refund if supported.
	 *
	 * @param  int    $order_id The order id.
	 * @param  float  $amount The order amount.
	 * @param  string $reason The reason for the refund.
	 * @return bool True or false based on success, or a WP_Error object
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );

		if ( ! $this->can_refund_order( $order ) ) {
			$this->log( 'Refund Failed: No transaction ID' );
			return new WP_Error( 'error', __( 'Refund Failed: No transaction ID', 'wc_paytomorrow' ) );
		}

		$this->init_api();

		$result = WC_Gateway_Paytomorrow_API_Handler::refund_transaction( $order, $amount, $reason );

		if ( is_wp_error( $result ) ) {
			$this->log( 'Refund Failed: ' . $result->get_error_message() );
			return new WP_Error( 'error', $result->get_error_message() );
		}

		$this->log( 'Refund Result: ' . print_r( $result, true ) );
		
		switch ( strtolower( $result->ACK ) ) {
			case 'success':
			case 'successwithwarning':
				/* translators: %1$s: GROSSREFUNDAMT %2$s: REFUNDTRANSACTIONID */
				$refund_text = __( 'Refunded %1$s - Refund ID: %2$s', 'wc_paytomorrow' );
				$order->add_order_note( sprintf( $refund_text, $result->GROSSREFUNDAMT, $result->REFUNDTRANSACTIONID ) );
				return true;
			break;
		}

		return isset( $result->L_LONGMESSAGE0 ) ? new WP_Error( 'error', $result->L_LONGMESSAGE0 ) : false;
	}

	/**
	 * Capture payment when the order is changed from on-hold to complete or processing
	 *
	 * @param  int $order_id Order id.
	 */
	public function capture_payment( $order_id ) {
		paytomorrow_log( 'ENTERING capture_payment' );

		$order = wc_get_order( $order_id );

		paytomorrow_log(
			array(
				'paytomorrow'        => $order->get_payment_method(),
				'pending'            => get_post_meta( $order->get_id(), '_paytomorrow_status', true ),
				'get_transaction_id' => $order->get_transaction_id(),
			)
		);

		if ( 'paytomorrow' === $order->get_payment_method() && 'pending' === get_post_meta( $order->get_id(), '_paytomorrow_status', true ) && $order->get_transaction_id() ) {
			$this->init_api();
			paytomorrow_log( 'ENTERING do_capture (capture_payment)' );
			$result = WC_Gateway_Paytomorrow_API_Handler::do_capture( $order );

			if ( is_wp_error( $result ) ) {
				$this->log( 'Capture Failed: ' . $result->get_error_message() );
				$order->add_order_note( sprintf( __( 'Payment could not captured: %s', 'wc_paytomorrow' ), $result->get_error_message() ) );
				return;
			}

			$this->log( 'Capture Result: ' . print_r( $result, true ) );

			if ( ! empty( $result->PAYMENTSTATUS ) ) {
				switch ( $result->PAYMENTSTATUS ) {
					case 'Completed':
						/* translators: %1$s: AUTHORIZATIONID %2$s: TRANSACTIONID */
						$completed_text = __( 'Payment of %1$s was captured - Auth ID: %2$s, Transaction ID: %3$s', 'wc_paytomorrow' );
						$order->add_order_note( sprintf( $completed_text, $result->AMT, $result->AUTHORIZATIONID, $result->TRANSACTIONID ) );
						update_post_meta( $order->get_id(), '_paytomorrow_status', $result->PAYMENTSTATUS );
						update_post_meta( $order->get_id(), '_transaction_id', $result->TRANSACTIONID );
						break;
					default:
						/* translators: %1$s: AUTHORIZATIONID %2$s: PAYMENTSTATUS */
						$default_text = __( 'Payment could not captured - Auth ID: %1$s, Status: %2$s', 'wc_paytomorrow' );
						$order->add_order_note( sprintf( $default_text, $result->AUTHORIZATIONID, $result->PAYMENTSTATUS ) );
						break;
				}
			}
		}
	}
	// @codingStandardsIgnoreEnd
}
