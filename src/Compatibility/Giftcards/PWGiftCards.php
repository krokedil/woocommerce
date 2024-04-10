<?php
namespace Krokedil\WooCommerce\Compatibility\Giftcards;

use Krokedil\WooCommerce\Cart\CartLineCoupon;
use Krokedil\WooCommerce\Interfaces\GiftCardCompatibilityInterface;
use Krokedil\WooCommerce\KrokedilWooCommerce;
use Krokedil\WooCommerce\Order\OrderLineCoupon;
use Krokedil\WooCommerce\OrderLineData;

/**
 * Class to handle compatibility with PW Gift Cards.
 */
class PWGiftCards implements GiftCardCompatibilityInterface {
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

		$pw_gift_card_data = WC()->session->get( 'pw-gift-card-data' );
		foreach ( $pw_gift_card_data['gift_cards'] as $code => $value ) {
			$amount = $value * -1;
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

		$pw_gift_card_data = WC()->session->get( 'pw-gift-card-data' );
		foreach ( $pw_gift_card_data['gift_cards'] as $code => $value ) {
			$amount = $value * -1;
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
