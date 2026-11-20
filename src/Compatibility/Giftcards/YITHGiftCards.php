<?php
namespace Krokedil\WooCommerce\Compatibility\Giftcards;

use Krokedil\WooCommerce\Compatibility\Abstracts\AbstractGiftCardCompatibility;
use Krokedil\WooCommerce\Order\OrderLineCoupon;

/**
 * Class to handle compatibility with YITH Gift Cards.
 */
class YITHGiftCards extends AbstractGiftCardCompatibility {
	/**
	 * Get the giftcards applied to the current cart.
	 *
	 * @return OrderLineCoupon[]
	 */
	public function get_cart_giftcards() {
		$coupons = array();

		// If no gift cards are applied to the cart, return an empty array.
		if ( ! isset( WC()->cart->applied_gift_cards ) ) {
			return $coupons;
		}

		foreach ( WC()->cart->applied_gift_cards as $code ) {
			$amount = ( isset( WC()->cart->applied_gift_cards_amounts[ $code ] ) ?
				WC()->cart->applied_gift_cards_amounts[ $code ] : 0 ) * -1;

			$coupons[] = $this->create_gift_card( "$this->name $code", $this->sku, $this->type, $amount, $code, $this->get_balance( $code ) );
		}

		return $coupons;
	}

	/**
	 * Get the balance available on a gift card, the same way YITH reads it when applying it to the cart.
	 *
	 * @param string $code The gift card code.
	 *
	 * @return float|null The balance, or null when it can not be read.
	 */
	private function get_balance( $code ) {
		// Kept in a variable so a consuming plugin's PHP-Scoper does not prefix the function name.
		$yith = 'YITH_YWGC';
		if ( ! function_exists( $yith ) ) {
			return null;
		}

		$gift_card = $yith()->get_gift_card_by_code( $code );
		if ( ! is_object( $gift_card ) ) {
			return null;
		}

		return floatval( apply_filters( 'yith_ywgc_gift_card_coupon_amount', $gift_card->get_balance(), $gift_card ) );
	}

	/**
	 * Get the giftcards applied to an order.
	 *
	 * Returns nothing once YITH has added the gift cards to the order as a fee,
	 * since the fee line already carries the amount.
	 *
	 * @param \WC_Order $order The WooCommerce order.
	 *
	 * @return OrderLineCoupon[]
	 */
	public function get_order_giftcards( $order ) {
		$coupons   = array();
		$yith_meta = $order->get_meta( '_ywgc_applied_gift_cards', true );

		// If no YITH meta data can be found, return an empty array.
		if ( empty( $yith_meta ) ) {
			return $coupons;
		}

		// YITH sets this flag when it adds the gift cards as a negative fee.
		if ( ! empty( $order->get_meta( 'ywgc_gift_card_updated_as_fee', true ) ) ) {
			return $coupons;
		}

		foreach ( $yith_meta as $code => $amount ) {
			$amount    = $amount * -1;
			$coupons[] = $this->create_gift_card( "$this->name $code", $this->sku, $this->type, $amount );
		}

		return $coupons;
	}
}
