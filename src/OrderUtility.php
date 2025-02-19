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
	 * @param string|null $plugin_name The name of the plugin that created the order.
	 * @param string|null $plugin_version The version of the plugin that created the order, or empty to skip.
	 * @param string|null $checkout_flow The checkout flow that was used to create the order, or empty to skip.
	 * @param bool        $save Whether to save the order after updating the meta data. Default is true.
	 *
	 * @return WC_Order The updated order.
	 */
	public static function add_environment_info( $order, $plugin_name = null, $plugin_version = null, $checkout_flow = null, $save = true ) {
		$environment_details = array(
			'php_version'   => phpversion(),
			'wp_version'    => get_bloginfo( 'version' ),
			'platform_data' => array(
				'platform_name'    => 'woocommerce',
				'platform_version' => WC()->version,
			),
		);

		if ( ! empty( $plugin_name ) ) {
			$environment_details['platform_data']['platform_plugin_name'] = $plugin_name;
		}

		if ( ! empty( $plugin_version ) ) {
			$environment_details['platform_data']['platform_plugin_version'] = $plugin_version;
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
}
