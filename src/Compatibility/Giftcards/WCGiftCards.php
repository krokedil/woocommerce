<?php
namespace Krokedil\WooCommerce\Compatibility\Giftcards;

use Krokedil\WooCommerce\Compatibility\Abstracts\AbstractGiftCardCompatibility;
use Krokedil\WooCommerce\OrderLineData;

/**
 * Class to handle compatibility with WooCommerce Gift Cards.
 *
 * @suppress PHP0417
 * @suppress PHP0413
 */
class WCGiftCards extends AbstractGiftCardCompatibility {
	/**
	 * Get the giftcards applied to the current cart.
	 *
	 * @return OrderLineData[]
	 */
	public function get_cart_giftcards() {
		$coupons = array();

		// Check if the account gift card balance is used.
		$use = isset( $post[ 'use_gift_card_balance' ] ) && 'on' === $post[ 'use_gift_card_balance' ] ? true : false;
		if ( WC_GC()->account->use_balance() !== $use ) {
			$account_giftcard_balance = WC_GC()->giftcards->cover_balance( WC()->cart->get_total() );
			if($account_giftcard_balance) {
				$coupons = array_merge($coupons, $this->create_gift_cards($account_giftcard_balance['giftcards']));
			}
		}

		$applied_giftcards = WC_GC()->cart->get_applied_gift_cards()['giftcards'];
		if($applied_giftcards) {
			$coupons = array_merge($coupons, $this->create_gift_cards($applied_giftcards));
		}

		return $coupons;
	}

	/**
	 * Create gift cards.
	 *
	 * @param array $giftcards The gift cards.
	 *
	 * @return OrderLineData[]
	 */
	private function create_gift_cards($giftcards) {
		$coupons = array();
	
		foreach ($giftcards as $giftcard) {
			$code = $giftcard['giftcard']->get_data()['code'];
			$amount = $giftcard['amount'] * -1;
			error_log('$code: ' . $code . ' $amount: ' . $amount);
			$coupons[] = $this->create_gift_card("$this->name $code", $this->sku, $this->type, $amount);
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
			$amount    = $wc_giftcard->get_amount() * -1;
			$code      = $wc_giftcard->get_code();
			$coupons[] = $this->create_gift_card( "$this->name $code", $this->sku, $this->type, $amount );
		}

		return $coupons;
	}
}
