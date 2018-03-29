<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<table cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
	<thead>
		<tr>
			<td style="padding: -6" colspan="3"><h2><?php printf( esc_html__( 'Order #%s', 'woocommerce-subscriptions-gifting' ), esc_attr( $order->get_order_number() ) ) ?></h2></td>
		</tr>
	</thead>
		<tr>
			<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Product', 'woocommerce-subscriptions-gifting' ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Quantity', 'woocommerce-subscriptions-gifting' ); ?></th>
		</tr>
	<tbody>
		<?php echo wp_kses_post( WCSG_Email::recipient_email_order_items_table( $order, $order_items_table_args ) ); ?>
	</tbody>
<?php echo '</table>'; ?>
