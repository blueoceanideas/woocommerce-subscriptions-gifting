<?php
/**
 * WCS Gifting Template Loader
 *
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WCSG_Template_Loader {

	/**
	 * Setup hooks & filters, when the class is initialised.
	 */
	public static function init() {
		add_action( 'wc_get_template', array( __CLASS__, 'get_recent_orders_template' ), 1 , 3 );

		add_action( 'wc_get_template', array( __CLASS__, 'get_subscription_totals_template' ), 1, 3 );

		add_action( 'wc_get_template', array( __CLASS__, 'get_recipient_email_order_items' ), 1, 3 );

		add_action( 'wc_get_template', array( __CLASS__, 'get_customer_details_template' ), 1, 3 );
	}

	/**
	 * Overrides the default recent order template for gifted subscriptions
	 *
	 * @param string $located Path to template
	 * @param string $template_name Template name
	 * @param array $args Arguments
	 * @return string $located Path for including template
	 * @since 2.0.0
	 */
	public static function get_recent_orders_template( $located, $template_name, $args ) {
		if ( 'myaccount/related-orders.php' === $template_name ) {
			$subscription = $args['subscription'];
			if ( WCS_Gifting::is_gifted_subscription( $subscription ) ) {
				$located = wc_locate_template( 'related-orders.php', '', plugin_dir_path( WCS_Gifting::$plugin_file ) . 'templates/' );
			}
		}
		return $located;
	}

	/**
	 * Overrides subscription totals template.
	 *
	 * @param string $located Path to template
	 * @param string $template_name Template name
	 * @param array $args Arguments
	 * @return string $located Path for including template
	 * @since 2.0.0
	 */
	public static function get_subscription_totals_template( $located, $template_name, $args ) {
		if ( ! wcsg_is_wc_subscriptions_pre( '2.2.19' ) && 'myaccount/subscription-totals.php' === $template_name ) {
			$subscription = $args['subscription'];
			if ( WCS_Gifting::is_gifted_subscription( $subscription ) && get_current_user_id() == WCS_Gifting::get_recipient_user( $subscription ) ) {
				$located = wc_locate_template( 'subscription-totals.php', '', plugin_dir_path( WCS_Gifting::$plugin_file ) . 'templates/' );
			}
		}
		return $located;
	}

	/**
	 * Overrides email order items template.
	 *
	 * @param string $located Path to template
	 * @param string $template_name Template name
	 * @param array $args Arguments
	 * @return string $located Path for including template
	 * @since 2.0.0
	 */
	public static function get_recipient_email_order_items( $located, $template_name, $args ) {
		if ( 'emails/email-order-items.php' === $template_name || 'emails/plain/email-order-items.php' === $template_name ) {
			$subscription = $args['order'];

			if ( WCS_Gifting::is_gifted_subscription( $subscription ) && $subscription->get_customer_id() != WCS_Gifting::get_recipient_user( $subscription ) ) {
				$template = ( 'emails/email-order-items.php' === $template_name ) ? 'emails/recipient-email-order-items.php' : 'emails/plain/recipient-email-order-items.php';
				$located = wc_locate_template( $template, '', plugin_dir_path( WCS_Gifting::$plugin_file ) . 'templates/' );
			}
		}
		return $located;
	}

	/**
	 * Overrides the order details customer template on view subscription page for recipient.
	 *
	 * @param string $located Path to template
	 * @param string $template_name Template name
	 * @param array $args Arguments
	 * @return string $located Path for including template
	 * @since 2.0.0
	 */
	public static function get_customer_details_template( $located, $template_name, $args ) {
		if ( ! wcsg_is_wc_subscriptions_pre( '2.2.19' ) && 'order/order-details-customer.php' === $template_name ) {
			$subscription = $args['order'];
			if ( WCS_Gifting::is_gifted_subscription( $subscription ) && get_current_user_id() == WCS_Gifting::get_recipient_user( $subscription ) ) {
				$located = wc_locate_template( 'order-details-customer.php', '', plugin_dir_path( WCS_Gifting::$plugin_file ) . 'templates/' );
			}
		}
		return $located;
	}
}
WCSG_Template_Loader::init();
