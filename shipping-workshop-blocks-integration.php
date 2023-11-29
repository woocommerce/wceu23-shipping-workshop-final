<?php
use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

/**
 * Class for integrating with WooCommerce Blocks
 */
    class Shipping_Workshop_Blocks_Integration implements IntegrationInterface {

	/**
	 * The name of the integration.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'shipping-workshop';
	}

	/**
	 * When called invokes any initialization/setup for the integration.
	 */
	public function initialize() {
		require_once __DIR__ . '/shipping-workshop-extend-store-endpoint.php';
		$this->register_shipping_workshop_block_frontend_scripts();
		$this->register_shipping_workshop_block_editor_scripts();
		$this->register_shipping_workshop_block_editor_styles();
		$this->register_main_integration();
	}

	/**
	 * Registers the main JS file required to add filters and Slot/Fills.
	 */
	private function register_main_integration() {
		$script_path = '/build/index.js';
		$style_path  = '/build/style-index.css';

		$script_url = plugins_url( $script_path, __FILE__ );
		$style_url  = plugins_url( $style_path, __FILE__ );

		$script_asset_path = dirname( __FILE__ ) . '/build/index.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: [
				'dependencies' => [],
				'version'      => $this->get_file_version( $script_path ),
			];

		wp_enqueue_style(
			'shipping-workshop-blocks-integration',
			$style_url,
			[],
			$this->get_file_version( $style_path )
		);

		wp_register_script(
			'shipping-workshop-blocks-integration',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);
		wp_set_script_translations(
			'shipping-workshop-blocks-integration',
			'shipping-workshop',
			dirname( __FILE__ ) . '/languages'
		);
	}

	/**
	 * Returns an array of script handles to enqueue in the frontend context.
	 *
	 * @return string[]
	 */
	public function get_script_handles() {
		return [ 'shipping-workshop-blocks-integration', 'shipping-workshop-block-frontend' ];
	}

	/**
	 * Returns an array of script handles to enqueue in the editor context.
	 *
	 * @return string[]
	 */
	public function get_editor_script_handles() {
		return [ 'shipping-workshop-blocks-integration', 'shipping-workshop-block-editor' ];
	}

	/**
	 * An array of key, value pairs of data made available to the block on the client side.
	 *
	 * @return array
	 */
	public function get_script_data() {
		$data = [
			'shipping-workshop-active'    => true,

			/**
             * 💰Extra credit: Add a key/value pair to the data array that will be available to the block on the client side.
             * It should be called defaultLabelText and the value should be the default text to display above the label
             * in the editor.
             *
             * To test if this is working, go to the editor, remove and re-add the Checkout block and see the label text
             * above the alternative shipping options select box.
             */
			'defaultLabelText' => __( 'What should we do if you are not at home?', 'shipping-workshop' ),
		];

		return $data;

	}

	public function register_shipping_workshop_block_editor_styles() {
		$style_path = '/build/style-shipping-workshop-block.css';

		$style_url = plugins_url( $style_path, __FILE__ );
		wp_enqueue_style(
			'shipping-workshop-block',
			$style_url,
			[],
			$this->get_file_version( $style_path )
		);
	}

	public function register_shipping_workshop_block_editor_scripts() {
		$script_path       = '/build/shipping-workshop-block.js';
		$script_url        = plugins_url( $script_path, __FILE__ );
		$script_asset_path = dirname( __FILE__ ) . '/build/shipping-workshop-block.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: [
				'dependencies' => [],
				'version'      => $this->get_file_version( $script_asset_path ),
			];

		wp_register_script(
			'shipping-workshop-block-editor',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations(
			'shipping-workshop-block-editor',
			'shipping-workshop',
			dirname( __FILE__ ) . '/languages'
		);
	}

	public function register_shipping_workshop_block_frontend_scripts() {
		$script_path       = '/build/shipping-workshop-block-frontend.js';
		$script_url        = plugins_url( $script_path, __FILE__ );
		$script_asset_path = dirname( __FILE__ ) . '/build/shipping-workshop-block-frontend.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: [
				'dependencies' => [],
				'version'      => $this->get_file_version( $script_asset_path ),
			];

		wp_register_script(
			'shipping-workshop-block-frontend',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);
		wp_set_script_translations(
			'shipping-workshop-block-frontend',
			'shipping-workshop',
			dirname( __FILE__ ) . '/languages'
		);
	}

	/**
	 * Get the file modified time as a cache buster if we're in dev mode.
	 *
	 * @param string $file Local path to the file.
	 * @return string The cache buster value to use for the given file.
	 */
	protected function get_file_version( $file ) {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && file_exists( $file ) ) {
			return filemtime( $file );
		}
		return SHIPPING_WORKSHOP_VERSION;
	}
}
