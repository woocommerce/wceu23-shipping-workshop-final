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
		$this->save_shipping_instructions();
		$this->show_shipping_instructions_in_order();

		$this->show_shipping_instructions_in_order_confirmation();
		$this->show_shipping_instructions_in_order_email();
	}

    private function save_shipping_instructions() {
        /**
         * 📝 Write a hook, using the `woocommerce_store_api_checkout_update_order_from_request` action
         * that will update the order metadata with the shipping-workshop alternate shipping instruction.
         *
         * The documentation for this hook is at: https://github.com/woocommerce/woocommerce-blocks/blob/b73fbcacb68cabfafd7c3e7557cf962483451dc1/docs/third-party-developers/extensibility/hooks/actions.md#woocommerce_store_api_checkout_update_order_from_request
         */
        add_action(
            'woocommerce_store_api_checkout_update_order_from_request',
            function( \WC_Order $order, \WP_REST_Request $request ) {
                $shipping_workshop_request_data = $request['extensions'][$this->get_name()];
                /**
                 * 📝From the `$shipping_workshop_request_data` array, get the `alternateShippingInstruction` and
                 * `otherShippingValue` entries. Store them in their own variables, $alternate_shipping_instruction and .
                 */
				$alternate_shipping_instruction = $shipping_workshop_request_data['alternateShippingInstruction'];
				$other_shipping_value           = $shipping_workshop_request_data['otherShippingValue'];

                /**
                 * 📝Using `$order->update_meta_data` update the order metadata.
                 * Set the value of the `shipping_workshop_alternate_shipping_instruction` key to `$alternate_shipping_instruction`.
                 * Set the value of the `shipping_workshop_alternate_shipping_instruction_other_text` key to `$other_shipping_value`.
                 */
				$order->update_meta_data( 'shipping_workshop_alternate_shipping_instruction', $alternate_shipping_instruction );

                /**
                 * 💰 Extra credit: Avoid setting `shipping_workshop_alternate_shipping_instruction_other_text` if
                 * `$alternate_shipping_instruction_other_text` is not a string, or if it is empty.
                 */
				if ( $alternate_shipping_instruction === 'other' ) {
					$order->update_meta_data( 'shipping_workshop_alternate_shipping_instruction_other_text', $other_shipping_value );
				}

                /**
                 * 💡Don't forget to save the order using `$order->save()`.
                 */
				$order->save();
            },
            10,
            2
        );
    }

    /**
     * Adds the address in the order page in WordPress admin.
     */
    private function show_shipping_instructions_in_order() {
        add_action(
            'woocommerce_admin_order_data_after_shipping_address',
            function( \WC_Order $order ) {
				$alternate_shipping_instruction            = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction' );
				$alternate_shipping_instruction_other_text = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction_other_text' );

                echo '<div>';
                echo '<strong>' . __( 'Shipping Instructions', 'shipping-workshop' ) . '</strong>';
                /** 📝 Output the alternate shipping instructions here! */
				printf( '<p>%s</p>', $alternate_shipping_instruction );
				if ( $alternate_shipping_instruction === 'other' ) {
					printf('<p>%s</p>', $alternate_shipping_instruction_other_text);
				}
                echo '</div>';
            }
        );
    }

	/**
     * Adds the address on the order confirmation page.
     */
	private function show_shipping_instructions_in_order_confirmation() {
		add_action(
			'woocommerce_order_details_after_customer_details',
			function( \WC_Order $order ) {
				$shipping_workshop_alternate_shipping_instruction = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction' );
				$shipping_workshop_alternate_shipping_instruction_other_text = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction_other_text' );

				if ( $shipping_workshop_alternate_shipping_instruction !== '' ) {
					echo '<h2>' . __( 'Shipping Instructions', 'shipping-workshop' ) . '</h2>';
					echo '<p>' . $shipping_workshop_alternate_shipping_instruction . '</p>';

					if ( $shipping_workshop_alternate_shipping_instruction_other_text !== '' ) {
						echo '<p>' . $shipping_workshop_alternate_shipping_instruction_other_text . '</p>';
					}
				}
			}
		);
	}

	/**
	 * Adds the address on the order confirmation email.
	 */
	public function show_shipping_instructions_in_order_email() {
		add_action( 'woocommerce_email_after_order_table', function( $order, $sent_to_admin, $plain_text, $email ) {
			$shipping_workshop_alternate_shipping_instruction = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction' );
			$shipping_workshop_alternate_shipping_instruction_other_text = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction_other_text' );

			if ( $shipping_workshop_alternate_shipping_instruction !== '' ) {
				echo '<h2>' . __( 'Shipping Instructions', 'shipping-workshop' ) . '</h2>';
				echo '<p>' . $shipping_workshop_alternate_shipping_instruction . '</p>';

				if ( $shipping_workshop_alternate_shipping_instruction_other_text !== '' ) {
					echo '<p>' . $shipping_workshop_alternate_shipping_instruction_other_text . '</p>';
				}
			}
		}, 10, 4 );
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
