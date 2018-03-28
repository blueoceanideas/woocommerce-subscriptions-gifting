<?php
/**
 * Order details table shown in emails.
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

echo sprintf( __( 'Order number: %s', 'woocommerce-subscriptions' ), $order->get_order_number() ) . "\n";
echo sprintf( __( 'Order date: %s', 'woocommerce-subscriptions' ), wcs_format_datetime( wcs_get_objects_property( $order, 'date_created' ) ) ) . "\n";

echo "\n";

echo wp_kses_post( WCSG_Email::recipient_email_order_items_table( $order, $order_items_table_args ) );

echo "----------\n\n";
