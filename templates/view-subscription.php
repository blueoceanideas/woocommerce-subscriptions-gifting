<?php
/**
 * View Subscription
 *
 * Shows the details of a particular subscription on the account page
 *
 * @author  Prospress
 * @package WooCommerce_Subscription_Gifting/Templates
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( empty( $subscription ) ) {
	global $wp;

	if ( ! isset( $wp->query_vars['view-subscription'] ) || 'shop_subscription' != get_post_type( absint( $wp->query_vars['view-subscription'] ) ) || ! current_user_can( 'view_order', absint( $wp->query_vars['view-subscription'] ) ) ) {
		echo '<div class="woocommerce-error">' . esc_html__( 'Invalid Subscription.', 'woocommerce-subscriptions-gifting' ) . ' <a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '" class="wc-forward">'. esc_html__( 'My Account', 'woocommerce-subscriptions-gifting' ) .'</a>' . '</div>';
		return;
	}

	$subscription = wcs_get_subscription( $wp->query_vars['view-subscription'] );
}

wc_print_notices();
?>

<table class="shop_table subscription_details">
	<tr>
		<td><?php esc_html_e( 'Status', 'woocommerce-subscriptions-gifting' ); ?></td>
		<td><?php echo esc_html( wcs_get_subscription_status_name( $subscription->get_status() ) ); ?></td>
	</tr>
	<tr>
		<td><?php echo esc_html_x( 'Start Date', 'table heading',  'woocommerce-subscriptions-gifting' ); ?></td>
		<td><?php echo esc_html( $subscription->get_date_to_display( 'date_created' ) ); ?></td>
	</tr>
	<?php foreach ( array(
		'last_order_date_created' => _x( 'Last Order Date', 'admin subscription table header', 'woocommerce-subscriptions-gifting' ),
		'next_payment'            => _x( 'Next Payment Date', 'admin subscription table header', 'woocommerce-subscriptions-gifting' ),
		'end'                     => _x( 'End Date', 'table heading', 'woocommerce-subscriptions-gifting' ),
		'trial_end'               => _x( 'Trial End Date', 'admin subscription table header', 'woocommerce-subscriptions-gifting' ),
		) as $date_type => $date_title ) : ?>
		<?php $date = $subscription->get_date( $date_type ); ?>
		<?php if ( ! empty( $date ) ) : ?>
			<tr>
				<td><?php echo esc_html( $date_title ); ?></td>
				<td><?php echo esc_html( $subscription->get_date_to_display( $date_type ) ); ?></td>
			</tr>
		<?php endif; ?>
	<?php endforeach; ?>
	<?php do_action( 'woocommerce_subscription_before_actions', $subscription ); ?>
	<?php $actions = wcs_get_all_user_actions_for_subscription( $subscription, get_current_user_id() ); ?>
	<?php if ( ! empty( $actions ) ) : ?>
		<tr>
			<td><?php esc_html_e( 'Actions', 'woocommerce-subscriptions-gifting' ); ?></td>
			<td>
				<?php foreach ( $actions as $key => $action ) : ?>
					<a href="<?php echo esc_url( $action['url'] ); ?>" class="button <?php echo sanitize_html_class( $key ) ?>"><?php echo esc_html( $action['name'] ); ?></a>
				<?php endforeach; ?>
			</td>
		</tr>
	<?php endif; ?>
	<?php do_action( 'woocommerce_subscription_after_actions', $subscription ); ?>
</table>
<?php if ( $notes = $subscription->get_customer_order_notes() ) :
	?>
	<h2><?php esc_html_e( 'Subscription Updates', 'woocommerce-subscriptions-gifting' ); ?></h2>
	<ol class="commentlist notes">
		<?php foreach ( $notes as $note ) : ?>
		<li class="comment note">
			<div class="comment_container">
				<div class="comment-text">
					<p class="meta"><?php echo esc_html( date_i18n( _x( 'l jS \o\f F Y, h:ia', 'date on subscription updates list. Will be localized', 'woocommerce-subscriptions-gifting' ), wcs_date_to_time( $note->comment_date ) ) ); ?></p>
					<div class="description">
						<?php echo wp_kses_post( wpautop( wptexturize( $note->comment_content ) ) ); ?>
					</div>
	  				<div class="clear"></div>
	  			</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php endforeach; ?>
	</ol>
<?php endif; ?>
<?php $allow_remove_items = wcs_can_items_be_removed( $subscription ); ?>
<h2><?php esc_html_e( 'Subscription Info', 'woocommerce-subscriptions-gifting' ); ?></h2>
<table class="shop_table order_details">
	<thead>
		<tr>
			<?php if ( $allow_remove_items ) : ?>
			<th class="product-remove" style="width: 3em;">&nbsp;</th>
			<?php endif; ?>
			<th class="product-name"><?php echo esc_html_x( 'Product', 'table headings in notification email', 'woocommerce-subscriptions-gifting' ); ?></th>
			<th class="product-total"><?php echo esc_html_x( 'Period', 'table heading', 'woocommerce-subscriptions-gifting' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ( sizeof( $subscription_items = $subscription->get_items() ) > 0 ) {

			foreach ( $subscription_items as $item_id => $item ) {
				$_product  = apply_filters( 'woocommerce_subscriptions_order_item_product', $subscription->get_product_from_item( $item ), $item );
				if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
					?>
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $subscription ) ); ?>">
						<?php if ( $allow_remove_items ) : ?>
							<td class="remove_item">
								<?php if ( wcs_can_item_be_removed( $item, $subscription ) ) : ?>
									<?php $confirm_notice = apply_filters( 'woocommerce_subscriptions_order_item_remove_confirmation_text', __( 'Are you sure you want remove this item from your subscription?', 'woocommerce-subscriptions-gifting' ), $item, $_product, $subscription );?>
									<a href="<?php echo esc_url( WCS_Remove_Item::get_remove_url( $subscription->get_id(), $item_id ) );?>" class="remove" onclick="return confirm('<?php printf( esc_html( $confirm_notice ) ); ?>');">&times;</a>
								<?php endif; ?>
							</td>
						<?php endif; ?>
						<td class="product-name">
							<?php
							if ( $_product && ! $_product->is_visible() ) {
								echo esc_html( apply_filters( 'woocommerce_order_item_name', $item['name'], $item, false ) );
							} else {
								echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', sprintf( '<a href="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] ), $item, false ) );
							}

							echo wp_kses_post( apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['qty'] ) . '</strong>', $item ) );

							// Allow other plugins to add additional product information here
							do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $subscription );

							wcs_display_item_meta( $item, $subscription );

							wcs_display_item_downloads( $item, $subscription );

							// Allow other plugins to add additional product information here
							do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $subscription );
							?>
						</td>
						<td class="product-period">
							<?php
							$subscription_details = array(
								'recurring_amount'            => '',
								'subscription_period'         => $subscription->get_billing_period(),
								'subscription_interval'       => $subscription->get_billing_interval(),
								'initial_amount'              => '',
								'use_per_slash'               => false,
							);
							$subscription_details = apply_filters( 'woocommerce_subscription_price_string_details', $subscription_details, $subscription );
							echo wp_kses_post( wcs_price_string( $subscription_details ) );
							?>
						</td>
					</tr>
					<?php
				}

				if ( $subscription->has_status( array( 'completed', 'processing' ) ) && ( $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) ) {
					?>
					<tr class="product-purchase-note">
						<td colspan="3"><?php echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) ); ?></td>
					</tr>
					<?php
				}
			}
		}
		?>
	</tbody>
</table>

<?php do_action( 'woocommerce_subscription_details_after_subscription_table', $subscription ); ?>

<?php wc_get_template( 'order-details-customer.php', array( 'order' => $subscription ), '', plugin_dir_path( WCS_Gifting::$plugin_file ) . 'templates/'  ); ?>

<div class="clear"></div>
