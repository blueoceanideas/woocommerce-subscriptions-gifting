<?php
/**
 * WCS_Unit_Tests_Bootstrap
 *
 * @since 2.0
 */
class WCS_Unit_Tests_Bootstrap {

	/** @var \WCS_Unit_Tests_Bootstrap instance */
	protected static $instance = null;

	/** @var string directory where wordpress-tests-lib is installed */
	public $wp_tests_dir;

	/** @var string testing directory */
	public $tests_dir;

	/** @var string plugin directory */
	public $plugin_dir;

	// directory storing dependency plugins
	public $modules_dir;

	/**
	 * Setup the unit testing environment
	 *
	 * @since 2.0
	 */
	function __construct() {

		ini_set( 'display_errors','on' );
		error_reporting( E_ALL );

		$this->tests_dir    = dirname( __FILE__ );
		$this->plugin_dir   = dirname( $this->tests_dir );
		$this->modules_dir  = dirname( dirname( $this->tests_dir ) );
		$this->wp_tests_dir = getenv( 'WP_TESTS_DIR' ) ? getenv( 'WP_TESTS_DIR' ) : $this->plugin_dir . '/tmp/wordpress-tests-lib';

		$_SERVER['REMOTE_ADDR'] = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '';
		$_SERVER['SERVER_NAME'] = ( isset( $_SERVER['SERVER_NAME'] ) ) ? $_SERVER['SERVER_NAME'] : 'wcsg_test';

		// load test function so tests_add_filter() is available
		require_once( $this->wp_tests_dir  . '/includes/functions.php' );

		// load WC and WCS
		tests_add_filter( 'muplugins_loaded', array( $this, 'load_wc_and_wcs' ) );

		// install WC and WCS
		tests_add_filter( 'setup_theme', array( $this, 'install_wc' ) );
		tests_add_filter( 'setup_theme', array( $this, 'install_wcs' ) );

		$GLOBALS['wp_options'] = array(
			'active_plugins' => array(
				$this->modules_dir . '/woocommerce/woocommerce.php',
				$this->modules_dir . '/woocommerce-subscriptions/woocommerce-subscriptions.php'
			),
		);

		// load the WP testing environment
		require_once( $this->wp_tests_dir . '/includes/bootstrap.php' );

		// load testing framework
		$this->includes();

		// load WooCommerce Subcriptions Gifting
		require_once( $this->plugin_dir . '/woocommerce-subscriptions-gifting.php' );

		// set active and inactive subscriber roles
		update_option( WC_Subscriptions_Admin::$option_prefix . '_subscriber_role', 'subscriber' );
		update_option( WC_Subscriptions_Admin::$option_prefix . '_cancelled_role', 'customer' );

		WC_Subscriptions::register_order_types();
	}

	/**
	 * Load WooCommerce and WooCommerce Subscriptions
	 *
	 * @since 2.0
	 */
	public function load_wc_and_wcs() {
		require_once( $this->modules_dir . '/woocommerce/woocommerce.php' );

		require_once( $this->modules_dir . '/woocommerce-subscriptions/wcs-functions.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wc-subscriptions-coupon.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wc-subscriptions-product.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/admin/class-wc-subscriptions-admin.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wc-subscriptions-manager.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wc-subscriptions-cart.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wc-subscriptions-order.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wc-subscriptions-renewal-order.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wc-subscriptions-checkout.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wc-subscriptions-email.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wc-subscriptions-addresses.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wc-subscriptions-change-payment-gateway.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/gateways/class-wc-subscriptions-payment-gateways.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/gateways/gateway-paypal-standard-subscriptions.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wc-subscriptions-switcher.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wc-subscriptions-synchroniser.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/upgrades/class-wc-subscriptions-upgrader.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/upgrades/class-wcs-upgrade-logger.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/libraries/action-scheduler/action-scheduler.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/abstracts/abstract-wcs-scheduler.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wcs-action-scheduler.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wcs-cart-renewal.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wcs-cart-resubscribe.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wcs-cart-initial-payment.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/abstracts/abstract-wcs-hook-deprecator.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/abstracts/abstract-wcs-dynamic-hook-deprecator.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/deprecated/class-wcs-action-deprecator.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/deprecated/class-wcs-filter-deprecator.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/deprecated/class-wcs-dynamic-action-deprecator.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/deprecated/class-wcs-dynamic-filter-deprecator.php' );

		require_once( $this->modules_dir . '/woocommerce-subscriptions/woocommerce-subscriptions.php' );
	}

	/**
	 * Load WooCommerce for testing
	 *
	 * @since 2.0
	 */
	function install_wc() {

		echo "Installing WooCommerce..." . PHP_EOL;

		define( 'WP_UNINSTALL_PLUGIN', true );

		include( $this->modules_dir . '/woocommerce/uninstall.php' );

		WC_Install::install();

		// reload capabilities after install, see https://core.trac.wordpress.org/ticket/28374
		$GLOBALS['wp_roles']->reinit();

		WC()->init();

		echo "WooCommerce Finished Installing..." . PHP_EOL;
	}

	/**
	 * Load WooCommerce Subscriptions for testing
	 *
	 * @since 2.0
	 */
	function install_wcs() {

		echo "Installing WooCommerce Subscriptions..." . PHP_EOL;

		WC_Subscriptions::init();

		echo "WooCommerce Subscriptions Finished Installing..." . PHP_EOL;
	}

	/**
	 * Load test cases and factories
	 *
	 * @since 2.0
	 */
	public function includes() {

		// Load WC Helper functions/Frameworks and Factories
		require_once( $this->modules_dir . '/woocommerce/tests/framework/factories/class-wc-unit-test-factory-for-webhook.php' );
		require_once( $this->modules_dir . '/woocommerce/tests/framework/factories/class-wc-unit-test-factory-for-webhook-delivery.php' );

		// Load WC Framework
		require_once( $this->modules_dir . '/woocommerce/tests/framework/class-wc-unit-test-factory.php' );
		require_once( $this->modules_dir . '/woocommerce/tests/framework/class-wc-mock-session-handler.php' );
		require_once( $this->modules_dir . '/woocommerce/tests/framework/class-wc-unit-test-case.php' );
		require_once( $this->modules_dir . '/woocommerce/tests/framework/class-wc-api-unit-test-case.php' );

		// LOAD WC-API Files
		require_once( $this->modules_dir . '/woocommerce/includes/api/class-wc-api-server.php' );
		require_once( $this->modules_dir . '/woocommerce/includes/api/class-wc-api-resource.php' );
		require_once( $this->modules_dir . '/woocommerce/includes/api/class-wc-api-orders.php' );

		// Load WCS required classes
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wc-subscription.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wc-product-subscription.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/includes/class-wcs-api.php' );

		// Load WCS Frameworks
		//require_once( $this->modules_dir . '/woocommerce-subscriptions/tests/framework/class-wcs-unit-test-case.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/tests/framework/class-wcs-unit-test-factory.php' );
		//require_once( $this->modules_dir . '/woocommerce-subscriptions/tests/framework/class-wcs-api-unit-test-case.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/tests/framework/class-wcs-test-subscription-class.php' );

		// Load WC Helper Functions
		require_once( $this->modules_dir . '/woocommerce/tests/framework/helpers/class-wc-helper-product.php' );
		require_once( $this->modules_dir . '/woocommerce/tests/framework/helpers/class-wc-helper-coupon.php' );
		require_once( $this->modules_dir . '/woocommerce/tests/framework/helpers/class-wc-helper-fee.php' );
		require_once( $this->modules_dir . '/woocommerce/tests/framework/helpers/class-wc-helper-shipping.php' );
		require_once( $this->modules_dir . '/woocommerce/tests/framework/helpers/class-wc-helper-customer.php' );

		// Load WCS Helper Functions
		require_once( $this->modules_dir . '/woocommerce-subscriptions/tests/framework/helpers/class-wcs-helper-subscription.php' );
		require_once( $this->modules_dir . '/woocommerce-subscriptions/tests/framework/helpers/class-wcs-helper-product.php' );

		// Load WCSG Frameworks
		require_once( 'framework/class-wcsg-unit-test-case.php' );
		// require_once( 'framework/class-wcsg-unit-test-factory.php' );

		// Load WCSG Helper Functions
		// require_once( 'framework/helpers/class-wcsg-helper-product.php' );
	}

	/**
	 * Get the single class instance
	 *
	 * @since 2.0
	 * @return WCS_Unit_Tests_Bootstrap
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

WCS_Unit_Tests_Bootstrap::instance();

/**
 * Override woothemes_queue_update() and is_active_woocommerce() so that the woocommerce_subscriptions.php
 * will import most of the necessary files without exiting early.
 * 
 * @since 2.0
 */
function is_woocommerce_active() {
	return true;
}

function woothemes_queue_update($file, $file_id, $product_id) {
	return true;
}