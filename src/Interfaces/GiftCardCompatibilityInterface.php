<?php
namespace Krokedil\WooCommerce\Interfaces;

use Krokedil\WooCommerce\Order\OrderLineCoupon;

/**
 * Interface GiftCardCompatibilityInterface
 */
interface GiftCardCompatibilityInterface {
	/**
	 * Get the giftcards applied to an order.
	 *
	 * @param \WC_Order $order The WooCommerce order.
	 *
	 * @return OrderLineCoupon[]
	 */
	public function get_order_giftcards( $order );

	/**
	 * Get the giftcards applied to the current cart.
	 *
	 * @return OrderLineCoupon[]
	 */
	public function get_cart_giftcards();
}
