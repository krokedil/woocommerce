<?php
namespace Krokedil\WooCommerce\Compatibility\Giftcards;

use Krokedil\WooCommerce\Cart\CartLineCoupon;
use Krokedil\WooCommerce\Interfaces\GiftCardCompatibilityInterface;
use Krokedil\WooCommerce\KrokedilWooCommerce;
use Krokedil\WooCommerce\Order\OrderLineCoupon;
use Krokedil\WooCommerce\OrderLineData;

/**
 * Class to handle compatibility with Smart Coupons.
 */
class SmartCoupons implements GiftCardCompatibilityInterface {
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

		foreach ( WC()->cart->get_coupons() as $code => $cart_coupon ) {
			if ( 'smart_coupon' !== $cart_coupon->get_discount_type() && 'store_credit' !== $cart_coupon->get_discount_type() ) {
				continue;
			}

			$coupon = new CartLineCoupon( $this->package->config() );
			$amount = WC()->cart->get_coupon_discount_amount( $code ) * -1;

			$coupon->set_name( "Discount $code" )
				->set_sku( substr( strval( $code ), 0, 64 ) )
				->set_quantity( 1 )
				->set_unit_price( $amount )
				->set_subtotal_unit_price( $amount )
				->set_total_amount( $amount )
				->set_total_tax_amount( 0 )
				->set_tax_rate( 0 )
				->set_type( 'discount' );

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
		 * @var \WC_Order_Item_Coupon $order_coupon
		 */
		foreach ( $order->get_items( 'coupon' ) as $order_coupon ) {
			$discount_type = $order_coupon->meta_exists( 'coupon_data' ) ? $order_coupon->get_meta( 'coupon_data' )['discount_type'] : ( new \WC_Coupon( $order_coupon->get_name() ) )->get_discount_type();

			if ( 'smart_coupon' !== $discount_type && 'store_credit' !== $discount_type ) {
				continue;
			}

			$code   = $order_coupon->get_code();
			$amount = $order_coupon->get_discount() * -1;

			$coupon = new OrderLineCoupon( $this->package->config() );
			$coupon->set_name( "Discount $code" )
				->set_sku( substr( strval( $code ), 0, 64 ) )
				->set_quantity( 1 )
				->set_unit_price( $amount )
				->set_subtotal_unit_price( $amount )
				->set_total_amount( $amount )
				->set_total_tax_amount( 0 )
				->set_tax_rate( 0 )
				->set_type( 'discount' );

			$coupons[] = $coupon;
		}

		return $coupons;
	}
}
