<?php
namespace Krokedil\WooCommerce\Compatibility\Giftcards;

use Krokedil\WooCommerce\Cart\CartLineCoupon;
use Krokedil\WooCommerce\Interfaces\GiftCardCompatibilityInterface;
use Krokedil\WooCommerce\KrokedilWooCommerce;
use Krokedil\WooCommerce\Order\OrderLineCoupon;
use Krokedil\WooCommerce\OrderLineData;

/**
 * Class to handle compatibility with YITH Gift Cards.
 */
class YITHGiftCards implements GiftCardCompatibilityInterface {
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

		// If no gift cards are applied to the cart, return an empty array.
		if ( ! isset( WC()->cart->applied_gift_cards ) ) {
			return $coupons;
		}

		foreach ( WC()->cart->applied_gift_cards as $code ) {
			$coupon = new CartLineCoupon( $this->package->config() );
			$amount = isset( WC()->cart->applied_gift_cards_amounts[ $code ] ) ?
				WC()->cart->applied_gift_cards_amounts[ $code ] : 0 * -1;

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
		$coupons   = array();
		$yith_meta = $order->get_meta( '_ywgc_applied_gift_cards', true );

		// If no YITH meta data can be found, return an empty array.
		if ( empty( $yith_meta ) ) {
			return $coupons;
		}

		foreach ( $yith_meta as $code => $amount ) {
			$amount = $amount * -1;
			$coupon = new OrderLineCoupon( $this->package->config() );

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
