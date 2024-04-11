<?php
namespace Krokedil\WooCommerce\Compatibility\Giftcards;

use Krokedil\WooCommerce\Compatibility\Abstracts\AbstractGiftCardCompatibility;
use Krokedil\WooCommerce\KrokedilWooCommerce;
use Krokedil\WooCommerce\OrderLineData;

/**
 * Class to handle compatibility with Smart Coupons.
 */
class SmartCoupons extends AbstractGiftCardCompatibility {
	/**
	 * Initialize the class.
	 *
	 * @param KrokedilWooCommerce $package The main package class.
	 */
	public function __construct( $package ) {
		parent::__construct( $package );

		$this->name = 'Discount';
		$this->type = 'discount';
	}

	/**
	 * Get the giftcards applied to the current cart.
	 *
	 * @return OrderLineData[]
	 */
	public function get_cart_giftcards() {
		$coupons = array();

		foreach ( WC()->cart->get_coupons() as $code => $cart_coupon ) {
			if ( 'smart_coupon' !== $cart_coupon->get_discount_type() && 'store_credit' !== $cart_coupon->get_discount_type() ) {
				continue;
			}

			$amount = WC()->cart->get_coupon_discount_amount( $code ) * -1;
			$sku    = substr( strval( $code ), 0, 64 );

			$coupons[] = $this->create_gift_card( "$this->name $code", $sku, $this->type, $amount );
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
		 * @var \WC_Order_Item_Coupon $order_coupon
		 */
		foreach ( $order->get_items( 'coupon' ) as $order_coupon ) {
			$discount_type = $order_coupon->meta_exists( 'coupon_data' ) ? $order_coupon->get_meta( 'coupon_data' )['discount_type'] : ( new \WC_Coupon( $order_coupon->get_name() ) )->get_discount_type();

			if ( 'smart_coupon' !== $discount_type && 'store_credit' !== $discount_type ) {
				continue;
			}

			$code   = $order_coupon->get_code();
			$amount = $order_coupon->get_discount() * -1;
			$sku    = substr( strval( $code ), 0, 64 );

			$coupons[] = $this->create_gift_card( "$this->name $code", $sku, $this->type, $amount );
		}

		return $coupons;
	}
}
