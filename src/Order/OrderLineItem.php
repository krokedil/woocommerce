<?php
/**
 * Class to generate a order line item from the WooCommerce order item product.
 */

namespace Krokedil\WooCommerce\Order;

/**
 * Class to generate a order line item from the WooCommerce order item product.
 */
class OrderLineItem extends OrderLine {
	/**
	 * Filter prefix.
	 *
	 * @var mixed
	 */
	public $filter_prefix = 'order_line_item';

    /**
     * Product.
     *
     * @var \WC_Product $product
     */
    public $product;

    /**
     * Constructor.
     *
     * @param \WC_Order_Item_Product $order_line_item The order line item.
     * @param array $config Configuration array.
     */
	public function __construct( $order_line_item, $config = array() ) {
		$this->product = $order_line_item->get_product();

		parent::__construct( $order_line_item, $config );
	}

	/**
	 * Abstract function to set product sku
	 * @return void
	 */
	public function set_sku() {
        $this->sku = apply_filters( $this->get_filter_name( 'sku' ), $this->order_line_item->get_product()->get_sku(), $this->order_line_item );
	}

	/**
	 * Abstract function to set product total discount amount
	 * @return void
	 */
	public function set_total_discount_amount() {
        $this->total_discount_amount = apply_filters( $this->get_filter_name( 'total_discount_amount' ), $this->format_price( $this->order_line_item->get_total() - $this->order_line_item->get_subtotal() ), $this->order_line_item );
	}

	/**
	 * Abstract function to set product type
	 * @return void
	 */
	public function set_type() {
        $this->type = apply_filters( $this->get_filter_name( 'type' ), $this->product->get_type(), $this->order_line_item );
	}

	/**
	 * Abstract function to set product url
	 * @return void
	 */
	public function set_product_url() {
        $this->product_url = apply_filters( $this->get_filter_name( 'product_url' ), $this->product->get_permalink(), $this->order_line_item );
	}

	/**
	 * Abstract function to set product image url
	 * @return void
	 */
	public function set_image_url() {
		$image_url = null;
		if ( $this->product ) {
			$image_url = wp_get_attachment_image_url( $this->product->get_image_id(), 'woocommerce_thumbnail' );
		}

		$this->image_url = apply_filters( $this->get_filter_name( 'image_url' ), $image_url ? $image_url : null, $this->order_line_item );
	}
}
