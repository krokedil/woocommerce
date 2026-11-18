<?php
namespace Krokedil\WooCommerce\Compatibility\Giftcards;

use Krokedil\WooCommerce\Compatibility\Abstracts\AbstractGiftCardCompatibility;
use Krokedil\WooCommerce\Order\OrderLineCoupon;

/**
 * Class to handle compatibility with PW Gift Cards.
 */
class PWGiftCards extends AbstractGiftCardCompatibility {
	/**
	 * Get the giftcards applied to the current cart.
	 *
	 * @return OrderLineCoupon[]
	 */
	public function get_cart_giftcards() {
		$coupons           = array();
		$pw_gift_card_data = WC()->session->get( 'pw-gift-card-data', array() );

		if ( ! isset( $pw_gift_card_data['gift_cards'] ) ) {
			return $coupons;
		}

		foreach ( $pw_gift_card_data['gift_cards'] as $code => $value ) {
			$amount    = $value * -1;
			$coupons[] = $this->create_gift_card( "$this->name $code", $this->sku, $this->type, $amount, $code, $this->get_balance( $code ) );
		}

		return $coupons;
	}

	/**
	 * Get the balance available on a gift card, in the current currency.
	 *
	 * @param string $code The gift card number.
	 *
	 * @return float|null The balance, or null when it can not be read.
	 */
	private function get_balance( $code ) {
		// Kept in a variable so a consuming plugin's PHP-Scoper does not prefix the class name.
		$class_name = 'PW_Gift_Card';
		if ( ! class_exists( $class_name ) ) {
			return null;
		}

		$gift_card = new $class_name( $code );
		if ( ! $gift_card->get_id() ) {
			return null;
		}

		// PW Gift Cards applies nothing from an expired gift card.
		if ( $gift_card->has_expired() ) {
			return 0.0;
		}

		return floatval( apply_filters( 'pwgc_to_current_currency', $gift_card->get_balance() ) );
	}

	/**
	 * Get the giftcards applied to an order.
	 *
	 * @param \WC_Order $order The WooCommerce order.
	 *
	 * @return OrderLineCoupon[]
	 */
	public function get_order_giftcards( $order ) {
		$coupons = array();

		/** $var WC_Order_Item_PW_Gift_Card $order_item */
		foreach ( $order->get_items( 'pw_gift_card' ) as $id => $order_item ) {
			$amount    = $order_item->get_amount() * -1;
			$coupons[] = $this->create_gift_card( "{$this->name} {$order_item->get_name()}", $this->sku, $this->type, $amount );
		}

		return $coupons;
	}
}
