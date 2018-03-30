<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

foreach ( $subscriptions as $subscription ) {
	echo sprintf( __( 'Subscription #%s', 'woocommerce-subscriptions-gifting' ), $subscription->get_order_number() ) . "\n";

	echo sprintf( __( 'Start Date: %s', 'woocommerce-subscriptions-gifting' ), date_i18n( wc_date_format(), $subscription->get_time( 'date_created', 'site' ) ) ) . "\n";

	echo sprintf( __( 'End Date: %s', 'woocommerce-subscriptions-gifting' ), ( 0 < $subscription->get_time( 'end' ) ) ? date_i18n( wc_date_format(), $subscription->get_time( 'end', 'site' ) ) : _x( 'When Cancelled', 'Used as end date for an indefinite subscription', 'woocommerce-subscriptions-gifting' ) ) . "\n";

	$subscription_details = array(
		'recurring_amount'            => '',
		'subscription_period'         => $subscription->get_billing_period(),
		'subscription_interval'       => $subscription->get_billing_interval(),
		'initial_amount'              => '',
		'use_per_slash'               => false,
	);
	$subscription_details = apply_filters( 'woocommerce_subscription_price_string_details', $subscription_details, $subscription );
	echo sprintf( __( 'Period: %s', 'woocommerce-subscriptions-gifting' ), wp_kses_post( wcs_price_string( $subscription_details ) ) ) . "\n";

	echo "----------\n";
}
