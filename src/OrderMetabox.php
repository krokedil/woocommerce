<?php
namespace Krokedil\WooCommerce;

use Krokedil\WooCommerce\Interfaces\MetaboxInterface;

defined( 'ABSPATH' ) || exit;

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

/**
 * Class to handle metabox functionality for WooCommerce order pages.
 *
 * @package Krokedil\WooCommerce
 */
abstract class OrderMetabox implements MetaboxInterface {
	/**
	 * Metabox id.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Metabox title.
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * Payment method ID.
	 *
	 * @var string
	 */
	protected $payment_method_id;

	/**
	 * Constructor
	 *
	 * @param string $id Metabox id.
	 * @param string $title Metabox title.
	 * @param string $payment_method_id Payment method ID.
	 *
	 * @return void
	 */
	public function __construct( $id, $title, $payment_method_id ) {
		$this->id                = $id;
		$this->title             = $title;
		$this->payment_method_id = $payment_method_id;

		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
	}

	/**
	 * Render the metabox.
	 *
	 * @param \WP_Post|\WC_Order $post The post object or a WC Order for later versions of WooCommerce.
	 *
	 * @return void
	 */
	abstract public function render_metabox( $post );

	/**
	 * Add metabox to order edit screen.
	 *
	 * @param string $post_type The post type for the current screen.
	 *
	 * @return void
	 */
	public function add_metabox( $post_type ) {
		if ( ! $this->is_edit_order_screen( $post_type ) ) {
			return;
		}

		// Ensure we are on a order page.
		$order_id = $this->get_id();
		$order    = $order_id ? wc_get_order( $order_id ) : false;
		if ( ! $order_id || ! $order ) {
			return;
		}

		// Ensure the order has the correct payment method id.
		$payment_method = $order->get_payment_method();
		if ( ! empty( $this->payment_method_id ) && $this->payment_method_id !== $payment_method ) {
			return;
		}

		add_meta_box(
			$this->id,
			$this->title,
			array( $this, 'render_metabox' ),
			$post_type,
			'side',
			'core'
		);
	}

	/**
	 * Is HPOS enabled.
	 *
	 * @return bool
	 */
	public function is_hpos_enabled() {
		if ( class_exists( CustomOrdersTableController::class ) ) {
			return wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled();
		}

		return false;
	}

	/**
	 * Check if the current screen is the edit order screen.
	 *
	 * @param string $post_type The post type to check.
	 *
	 * @return bool
	 */
	public function is_edit_order_screen( $post_type ) {
		$valid_screens = array( 'shop_order', 'woocommerce_page_wc-orders' );

		return in_array( $post_type, $valid_screens, true );
	}

	/**
	 * Get the order ID from the current screen.
	 *
	 * @return int|null
	 */
	public function get_id() {
		$hpos_enabled = $this->is_hpos_enabled();
		$order_id     = $hpos_enabled ? filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT ) : get_the_ID();
		if ( empty( $order_id ) ) {
			return false;
		}

		return $order_id;
	}

	/**
	 * Print a error message into the metabox.
	 *
	 * @param string $message Error message to output.
	 *
	 * @return void
	 */
	private static function output_error( $message ) {
		?>
		<p class="krokedil_metabox krokedil_metabox__error">
			<?php echo esc_html( $message ); ?>
		</p>
		<?php
	}

	/**
	 * Output labeled text info for the metabox.
	 *
	 * @param string $label Label for the text.
	 * @param string $text Text to output.
	 *
	 * @return void
	 */
	private static function output_info( $label, $text ) {
		?>
		<p class="krokedil_metabox">
			<strong><?php echo esc_html( $label ); ?>:</strong>
			<span><?php echo wp_kses_post( $text ); ?></span>
		</p>
		<?php
	}
}
