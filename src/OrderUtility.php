<?php
namespace Krokedil\WooCommerce;

defined( 'ABSPATH' ) || exit;

/**
 * Utility functions for orders.
 *
 * @package Krokedil/WooCommerce
 */

class OrderUtility {

	/**
	 * Add the environment information for the order from the plugin and WooCommerce environment.
	 *
	 * @param WC_Order    $order
	 * @param string|null $plugin_version The version of the plugin that created the order, or empty to skip.
	 * @param string|null $checkout_flow The checkout flow that was used to create the order, or empty to skip.
	 * @param bool        $save Whether to save the order after updating the meta data. Default is true.
	 *
	 * @return WC_Order The updated order.
	 */
	public static function add_environment_info( $order, $plugin_version = null, $checkout_flow = null, $save = true ) {
		$environment_details = array(
			'php_version' => phpversion(),
			'wc_version'  => WC()->version,
			'wp_version'  => get_bloginfo( 'version' ),
		);

		if ( ! empty( $plugin_version ) ) {
			$environment_details['plugin_version'] = $plugin_version;
		}

		if ( ! empty( $checkout_flow ) ) {
			$environment_details['checkout_flow'] = $checkout_flow;
		}

		$order->update_meta_data( '_krokedil_environment_info', $environment_details );

		if ( $save ) {
			$order->save();
		}

		return $order;
	}

	/**
	 * Check if a cart item is a subscription item.
	 *
	 * @param array $cart_item The cart item to check.
	 *
	 * @return bool True if the cart item is a subscription item, false otherwise.
	 */
	public static function is_subscription_item( $cart_item ) {
		if ( class_exists( 'WC_Subscriptions_Product' ) && \WC_Subscriptions_Product::is_subscription( $cart_item['data'] ) ) {
			return true;
		}

		if ( method_exists( 'WCS_ATT_Cart', 'get_subscription_scheme' ) && false !== \WCS_ATT_Cart::get_subscription_scheme( $cart_item ) ) {
			return true;
		}

		return false;
	}
}
