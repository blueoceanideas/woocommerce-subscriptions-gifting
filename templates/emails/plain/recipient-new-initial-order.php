<?php
/**
 * Recipient customer new account email
 *
 * @author James Allan
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
echo '= ' . $email_heading . " =\n\n";
echo sprintf( __( 'Hi there,', 'woocommerce-subscriptions-gifting' ) ) . "\n";
// translators: 1$: Purchaser's name and email, 2$ The name of the site.
echo sprintf( __( '%1$s just purchased %2$s for you at %3$s.', 'woocommerce-subscriptions-gifting' ), wp_kses( $subscription_purchaser, wp_kses_allowed_html( 'user_description' ) ), _n( 'a subscription', 'subscriptions', count( $subscriptions ), 'woocommerce-subscriptions-gifting' ), esc_html( $blogname ) );
echo sprintf( __( ' Details of the %s are shown below.', 'woocommerce-subscriptions-gifting' ), _n( 'subscription', 'subscriptions', count( $subscriptions ), 'woocommerce-subscriptions-gifting' ) ) . "\n\n";

$new_recipient = get_user_meta( $recipient_user->ID, 'wcsg_update_account', true );

if ( 'true' == $new_recipient ) {
	echo esc_html__( 'We noticed you didn\'t have an account so we created one for you. Your account login details will have been sent to you in a separate email.' ) . "\n\n";
} else {
	echo sprintf( __( 'You may access your account area to view your new %1$s here: %2$s.', 'woocommerce-subscriptions-gifting' ), _n( 'subscription', 'subscriptions', count( $subscriptions ), 'woocommerce-subscriptions-gifting' ), esc_url( wc_get_page_permalink( 'myaccount' ) ) ) . "\n\n";
}

foreach ( $subscriptions as $subscription_id ) {
	$subscription = wcs_get_subscription( $subscription_id );

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

	if ( is_callable( array( 'WC_Subscriptions_Email', 'order_download_details' ) ) ) {
		WC_Subscriptions_Email::order_download_details( $subscription, $sent_to_admin, $plain_text, $email );
	}

	echo "----------\n\n";
}

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
