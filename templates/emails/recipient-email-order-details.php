<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<h2><?php printf( esc_html__( 'Order #%s', 'woocommerce-subscriptions-gifting' ), esc_attr( $order->get_order_number() ) ) ?></h2>
<table cellspacing="0" cellpadding="6" style="margin: 0 0 18px; width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
	<thead>
		<tr>
			<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Product', 'woocommerce-subscriptions-gifting' ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Quantity', 'woocommerce-subscriptions-gifting' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo wp_kses_post( WCSG_Email::recipient_email_order_items_table( $order, array(
			'show_sku'            => $sent_to_admin,
			'show_image'          => '',
			'image_size'          => '',
			'plain_text'          => $plain_text,
			'sent_to_admin'       => $sent_to_admin,
		) ) ); ?>
	</tbody>
</table>
