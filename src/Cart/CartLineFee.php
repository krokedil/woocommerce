<?php
/**
 * Class to generate a cart line item from the WooCommerce cart fee.
 */

namespace Krokedil\WooCommerce\Cart;

/**
 * Class to generate a cart line item from the WooCommerce cart fee.
 */
class CartLineFee extends CartLineItem {
	/**
	 * Filter prefix.
	 *
	 * @var mixed
	 */
	public $filter_prefix = 'cart_line_fee';

	/**
	 * Abstract function to set product sku
	 * @return void
	 */
	public function set_sku() {
		$this->sku = apply_filters( $this->get_filter_name( 'sku' ), $this->cart_item['name'], $this->cart_item );
	}

	/**
	 * Abstract function to set product quantity
	 * @return void
	 */
	public function set_quantity() {
		$this->quantity = apply_filters( $this->get_filter_name( 'quantity' ), 1, $this->cart_item );
	}

	/**
	 * Abstract function to set product type
	 * @return void
	 */
	public function set_type() {
		$this->type = apply_filters( $this->get_filter_name( 'type' ), 'fee', $this->cart_item );
	}

	/**
	 * Abstract function to set product url
	 * @return void
	 */
	public function set_product_url() {
		$this->product_url = apply_filters( $this->get_filter_name( 'product_url' ), null, $this->cart_item );
	}

	/**
	 * Abstract function to set product image url
	 * @return void
	 */
	public function set_image_url() {
		$this->image_url = apply_filters( $this->get_filter_name( 'image_url' ), null, $this->cart_item );
	}
}
