<?php
/**
 * Recipient new subscription(s) notification email
 *
 * @author James Allan
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php printf( esc_html__( 'Hi there,', 'woocommerce-subscriptions-gifting' ) ); ?></p>
<p><?php printf( esc_html__( '%1$s just purchased %2$s for you at %3$s.', 'woocommerce-subscriptions-gifting' ), wp_kses( $subscription_purchaser, wp_kses_allowed_html( 'user_description' ) ), esc_html( _n( 'a subscription', 'subscriptions', count( $subscriptions ), 'woocommerce-subscriptions-gifting' ) ), esc_html( $blogname ) ); ?>
<?php printf( esc_html__( ' Details of the %s are shown below.', 'woocommerce-subscriptions-gifting' ), esc_html( _n( 'subscription', 'subscriptions', count( $subscriptions ), 'woocommerce-subscriptions-gifting' ) ) ); ?>
</p>
<?php

$new_recipient = get_user_meta( $recipient_user->ID, 'wcsg_update_account', true );

if ( 'true' == $new_recipient ) : ?>

<p><?php esc_html_e( 'We noticed you didn\'t have an account so we created one for you. Your account login details will have been sent to you in a separate email.', 'woocommerce-subscriptions-gifting' ); ?></p>

<?php else : ?>

<p><?php printf( esc_html__( 'You may access your account area to view your new %1$s here: %2$sMy Account%3$s.', 'woocommerce-subscriptions-gifting' ),
	esc_html( _n( 'subscription', 'subscriptions', count( $subscriptions ), 'woocommerce-subscriptions-gifting' ) ),
	'<a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '">',
	'</a>'
); ?></p>

<?php endif; ?>

<?php if ( ! empty( $subscriptions ) ) : ?>
<h2><?php esc_html_e( 'Subscription Information:', 'woocommerce-subscriptions-gifting' ); ?></h2>
<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
	<thead>
		<tr>
			<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Subscription', 'woocommerce-subscriptions-gifting' ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php echo esc_html_x( 'Start Date', 'table heading',  'woocommerce-subscriptions-gifting' ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php echo esc_html_x( 'End Date', 'table heading',  'woocommerce-subscriptions-gifting' ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php echo esc_html_x( 'Period',  'table heading', 'woocommerce-subscriptions-gifting' ); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ( $subscriptions as $subscription_id ) : ?>
		<?php $subscription = wcs_get_subscription( $subscription_id ); ?>
		<tr>
			<td class="td" scope="row" style="text-align:left;"><a href="<?php echo esc_url( $subscription->get_view_order_url() ); ?>"><?php echo sprintf( esc_html_x( '#%s', 'subscription number in email table. (eg: #106)', 'woocommerce-subscriptions-gifting' ), esc_html( $subscription->get_order_number() ) ); ?></a></td>
			<td class="td" scope="row" style="text-align:left;"><?php echo esc_html( date_i18n( wc_date_format(), $subscription->get_time( 'date_created', 'site' ) ) ); ?></td>
			<td class="td" scope="row" style="text-align:left;"><?php echo esc_html( ( 0 < $subscription->get_time( 'end' ) ) ? date_i18n( wc_date_format(), $subscription->get_time( 'end', 'site' ) ) : _x( 'When Cancelled', 'Used as end date for an indefinite subscription', 'woocommerce-subscriptions-gifting' ) ); ?></td>
			<td class="td" scope="row" style="text-align:left;">
				<?php
				$subscription_details = array(
					'recurring_amount'            => '',
					'subscription_period'         => $subscription->get_billing_period(),
					'subscription_interval'       => $subscription->get_billing_interval(),
					'initial_amount'              => '',
					'use_per_slash'               => false,
				);
				$subscription_details = apply_filters( 'woocommerce_subscription_price_string_details', $subscription_details, $subscription );
				echo wp_kses_post( wcs_price_string( $subscription_details ) );?>
			</td>
		</tr>
	</tbody>
</table>
<table>
	<tbody>
		<?php if ( is_callable( array( 'WC_Subscriptions_Email', 'order_download_details' ) ) ) { ?>
		<tr>
			<td style="padding: 0" colspan="3"><p><?php WC_Subscriptions_Email::order_download_details( $subscription, $sent_to_admin, $plain_text, $email ); ?></p></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
	<?php endforeach; ?>
<?php endif; ?>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
