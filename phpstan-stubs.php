<?php
/**
 * Class and function stubs for PHPStan static analysis only.
 *
 * Declares the WooCommerce Gift Cards symbols the gift card compatibility uses, which no stubs package covers.
 *
 * @package Krokedil/WooCommerce
 *
 * @phpcs:disable
 */

class WC_Gift_Cards {
	/**
	 * @var WC_GC_Cart
	 */
	public $cart;
}

class WC_GC_Cart {
	/**
	 * @return array
	 */
	public function get_applied_gift_cards() {}
}

class WC_GC_Order_Item_Gift_Card extends WC_Order_Item {
	/**
	 * @param string $context What the value is for.
	 * @return string
	 */
	public function get_code( $context = 'view' ) {}

	/**
	 * @param string $context What the value is for.
	 * @return float
	 */
	public function get_amount( $context = 'view' ) {}
}

/**
 * @return WC_Gift_Cards
 */
function WC_GC() {}
