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

class PW_Gift_Card {
	/**
	 * @param string $number The gift card number.
	 */
	public function __construct( $number ) {}

	/**
	 * @return int|null
	 */
	public function get_id() {}

	/**
	 * @return bool
	 */
	public function has_expired() {}

	/**
	 * @param bool $force_refresh Whether to skip the cached balance.
	 * @return float
	 */
	public function get_balance( $force_refresh = false ) {}
}

class YITH_WooCommerce_Gift_Cards {
	/**
	 * @param string $code The gift card code.
	 * @return YITH_YWGC_Gift_Card|null
	 */
	public function get_gift_card_by_code( $code ) {}
}

class YITH_YWGC_Gift_Card {
	/**
	 * @return float
	 */
	public function get_balance() {}
}

/**
 * @return YITH_WooCommerce_Gift_Cards
 */
function YITH_YWGC() {}
