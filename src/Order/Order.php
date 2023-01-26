<?php
/**
 * Class to generate a cart object from a WooCommerce order.
 *
 * @package Krokedil/WooCommerce
 */

namespace Krokedil\WooCommerce\Order;

use Krokedil\WooCommerce\OrderData;

/**
 * Class to generate a cart object from a WooCommerce order.
 */
class Order extends OrderData {
	/**
	 * Filter prefix.
	 *
	 * @var mixed
	 */
	public $filter_prefix = 'order';

	/**
	 * The WooCommerce order.
	 *
	 * @var \WC_Order $cart
	 */
	public $order;

	/**
	 * Constructor.
	 *
	 * @param \WC_Order $order The WooCommerce order.
	 * @param array    $config Configuration array.
	 */
	public function __construct( $order, $config = array() ) {
		parent::__construct( $config );

		$this->order = $order;

		$this->set_line_items();
		$this->set_line_shipping();
		$this->set_line_fees();
		$this->set_line_coupons();
		$this->set_line_compatibility();
		$this->set_customer();
		$this->set_total();
		$this->set_total_tax();
		$this->set_subtotal();
		$this->set_subtotal_tax();
	}
	/**
	 * Sets the line items.
	 * @return void
	 */
	public function set_line_items() {
        foreach ( $this->order->get_items() as $order_item ) {
            $order_item          = new OrderLineItem( $order_item, $this->config );
            $this->line_items[] = apply_filters( $this->get_filter_name( 'line_items' ), $order_item, $this->order );
        }
	}

	/**
	 * Sets the shipping lines.
	 * @return void
	 */
	public function set_line_shipping() {
        foreach ( $this->order->get_items( 'shipping' ) as $shipping_item ) {
            $shipping_method          = new OrderLineShipping( $shipping_item, $this->config );
            $this->line_shipping[] = apply_filters( $this->get_filter_name( 'line_shipping' ), $shipping_method, $this->order );
        }
	}

	/**
	 * Sets the coupon lines.
	 * @return void
	 */
	public function set_line_coupons() {
        // TODO - Coupons
	}

	/**
	 * Sets the fee lines.
	 * @return void
	 */
	public function set_line_fees() {
        foreach ( $this->order->get_items( 'fee' ) as $fee_item ) {
            $fee_item          = new OrderLineFee( $fee_item, $this->config );
            $this->line_fees[] = apply_filters( $this->get_filter_name( 'line_fees' ), $fee_item, $this->order );
        }
	}

	/**
	 * Sets the compatibility lines.
	 * @return void
	 */
	public function set_line_compatibility() {
        // TODO - Compatibility
	}

	/**
	 * Sets the customer data.
	 * @return void
	 */
	public function set_customer() {
        $this->customer = apply_filters( $this->get_filter_name( 'customer' ), new OrderCustomer( $this->order, $this->config ), $this->order );
	}

	/**
	 * Sets the total ex tax.
	 * @return void
	 */
	public function set_total() {
        $this->total = apply_filters( $this->get_filter_name( 'total' ), $this->format_price( $this->order->get_total() ), $this->order );
	}

	/**
	 * Sets the total tax.
	 * @return void
	 */
	public function set_total_tax() {
        $this->total_tax = apply_filters( $this->get_filter_name( 'total_tax' ), $this->format_price( $this->order->get_total_tax() ), $this->order );
	}

	/**
	 * Sets the subtotal ex tax.
	 * @return void
	 */
	public function set_subtotal() {
        $this->subtotal = apply_filters( $this->get_filter_name( 'subtotal' ), $this->format_price( $this->order->get_subtotal() ), $this->order );
	}

	/**
	 * Sets the subtotal tax.
	 * @return void
	 */
	public function set_subtotal_tax() {
        // TODO - Subtotal tax
        //$this->subtotal_tax = $this->format_price( $this->order->get_subtotal_tax() );
	}
}
