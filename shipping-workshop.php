<?php
/**
 * Plugin Name:     Shipping Workshop
 * Version:         1.0
 * Author:          Thomas Roberts and Niels Lange
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     shipping-workshop
 *
 * @package         create-block
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function shipping_workshop_load_textdomain() {
	load_plugin_textdomain( 'shipping-workshop', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'shipping_workshop_load_textdomain' );

// Define SHIPPING_WORKSHOP_VERSION.
$plugin_data = get_file_data( __FILE__, array( 'version' => 'version' ) );
define( 'SHIPPING_WORKSHOP_VERSION', $plugin_data['version'] );

/**
 * Include the dependencies needed to instantiate the block.
 */
add_action(
	'woocommerce_blocks_loaded',
	function() {
		require_once __DIR__ . '/shipping-workshop-extend-store-endpoint.php';
		require_once __DIR__ . '/shipping-workshop-extend-woo-core.php';
		require_once __DIR__ . '/shipping-workshop-blocks-integration.php';

		// Initialize our store endpoint extension when WC Blocks is loaded.
		Shipping_Workshop_Extend_Store_Endpoint::init();

		// Add hooks relevant to extending the Woo core experience.
		$extend_core = new Shipping_Workshop_Extend_Woo_Core();
		$extend_core->init();

		add_action(
			'woocommerce_blocks_checkout_block_registration',
			function( $integration_registry ) {
				$integration_registry->register( new Shipping_Workshop_Blocks_Integration() );
			}
		);
	}
);

/**
 * Registers the slug as a block category with WordPress.
 *
 * @param array $categories Existing block categories.
 * @return array
 */
function register_Shipping_Workshop_block_category( $categories ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'shipping-workshop',
				'title' => __( 'Shipping_Workshop Blocks', 'shipping-workshop' ),
			),
		)
	);
}
add_action( 'block_categories_all', 'register_Shipping_Workshop_block_category', 10, 2 );
