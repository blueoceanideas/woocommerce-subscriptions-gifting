<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && ( $shipping = $order->get_formatted_shipping_address() ) ) {
	echo "\n" . strtoupper( __( 'Shipping address', 'woocommerce-subscriptions-gifting' ) ) . "\n\n";
	echo preg_replace( '#<br\s*/?>#i', "\n", $shipping ) . "\n";
}
