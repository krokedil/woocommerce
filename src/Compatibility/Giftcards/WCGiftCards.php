<?php
namespace Krokedil\WooCommerce\Compatibility\Giftcards;

use Krokedil\WooCommerce\Cart\CartLineCoupon;
use Krokedil\WooCommerce\Interfaces\GiftCardCompatibilityInterface;
use Krokedil\WooCommerce\KrokedilWooCommerce;
use Krokedil\WooCommerce\Order\OrderLineCoupon;
use Krokedil\WooCommerce\OrderLineData;

/**
 * Class to handle compatibility with WooCommerce Gift Cards.
 *
 * @suppress PHP0417
 * @suppress PHP0413
 */
class WCGiftCards implements GiftCardCompatibilityInterface {
	/**
	 * The instance of the main package class.
	 *
	 * @var KrokedilWooCommerce
	 */
	private $package;

	/**
	 * Initialize the class.
	 *
	 * @param KrokedilWooCommerce $package The main package class.
	 */
	public function __construct( $package ) {
		$this->package = $package;
	}

	/**
	 * Get the giftcards applied to the current cart.
	 *
	 * @return OrderLineData[]
	 */
	public function get_cart_giftcards() {
		$coupons = array();

		foreach ( WC_GC()->cart->get_applied_gift_cards()['giftcards'] as $wc_giftcard ) {
			$code   = $wc_giftcard['giftcard']->get_data()['code'];
			$amount = $wc_giftcard['amount'] * -1;

			$coupon = new CartLineCoupon( $this->package->config() );
			$coupon->set_name( "Gift card $code" )
				->set_sku( 'gift_card' )
				->set_quantity( 1 )
				->set_unit_price( $amount )
				->set_subtotal_unit_price( $amount )
				->set_total_amount( $amount )
				->set_total_tax_amount( 0 )
				->set_tax_rate( 0 )
				->set_type( 'gift_card' );

			$coupons[] = $coupon;
		}

		return $coupons;
	}

	/**
	 * Get the giftcards applied to an order.
	 *
	 * @param \WC_Order $order The WooCommerce order.
	 *
	 * @return OrderLineData[]
	 */
	public function get_order_giftcards( $order ) {
		$coupons = array();

		/**
		 * Loop through the gift cards and add them to the array.
		 *
		 * @var \WC_GC_Order_Item_Gift_Card $wc_giftcard
		 */
		foreach ( $order->get_items( 'gift_card' ) as $wc_giftcard ) {
			$coupon = new OrderLineCoupon( $this->package->config() );
			$amount = $wc_giftcard->get_amount() * -1;
			$code   = $wc_giftcard->get_code();

			$coupon->set_name( "Gift card $code" )
				->set_sku( 'gift_card' )
				->set_quantity( 1 )
				->set_unit_price( $amount )
				->set_subtotal_unit_price( $amount )
				->set_total_amount( $amount )
				->set_total_tax_amount( 0 )
				->set_tax_rate( 0 )
				->set_type( 'gift_card' );

			$coupons[] = $coupon;
		}

		return $coupons;
	}
}
