<?php
use Automattic\WooCommerce\Blocks\Package;
use Automattic\WooCommerce\Blocks\StoreApi\Schemas\CartSchema;
use Automattic\WooCommerce\Blocks\StoreApi\Schemas\CheckoutSchema;

/**
 * Shipping Workshop Extend Store API.
 */
class Shipping_Workshop_Extend_Store_Endpoint {
	/**
	 * Stores Rest Extending instance.
	 *
	 * @var ExtendRestApi
	 */
	private static $extend;

	/**
	 * Plugin Identifier, unique to each plugin.
	 *
	 * @var string
	 */
	const IDENTIFIER = 'shipping-workshop';

	/**
	 * Bootstraps the class and hooks required data.
	 */
	public static function init() {
		self::$extend = Automattic\WooCommerce\StoreApi\StoreApi::container()->get( Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema::class );
		self::extend_store();
		self::save_shipping_instructions();
		self::show_shipping_instructions_in_order();
		self::show_shipping_instructions_in_order_confirmation();
		self::show_shipping_instructions_in_order_email();
	}

	/**
	 * Registers the actual data into each endpoint.
	 */
	public static function extend_store() {

		if ( is_callable( [ self::$extend, 'register_endpoint_data' ] ) ) {
			self::$extend->register_endpoint_data(
				[
					'endpoint'        => CheckoutSchema::IDENTIFIER,
					'namespace'       => self::IDENTIFIER,
					'schema_callback' => [ 'Shipping_Workshop_Extend_Store_Endpoint', 'extend_checkout_schema' ],
					'schema_type'     => ARRAY_A,
				]
			);
		}
	}


	/**
	 * Register shipping workshop schema into the Checkout endpoint.
	 *
	 * @return array Registered schema.
	 */
	public static function extend_checkout_schema() {

		return [
			'otherShippingValue'           => [
				'description' => 'Other text for shipping instructions',
				'type'        => 'string',
				'context'     => [ 'view', 'edit' ],
				'readonly'    => true,
				'optional'    => true,
				'arg_options' => [
					'validate_callback' => function( $value ) {
						return is_string( $value );
					},
				],
			],
			'alternateShippingInstruction' => [
				'description' => 'Alternative shipping instructions for the courier',
				'type'        => 'string',
				'context'     => [ 'view', 'edit' ],
				'readonly'    => true,
				'arg_options' => [
					'validate_callback' => function( $value ) {
						return is_string( $value );
					},
				],
			],
		];
	}

	/**
	 * Saves the shipping instructions to the order's metadata.
	 *
	 * @return void
	 */
	private static function save_shipping_instructions() {
		/**
		 * 📝 Write a hook, using the `woocommerce_store_api_checkout_update_order_from_request` action
		 * that will update the order metadata with the shipping-workshop alternate shipping instruction.
		 *
		 * The documentation for this hook is at: https://github.com/woocommerce/woocommerce-blocks/blob/b73fbcacb68cabfafd7c3e7557cf962483451dc1/docs/third-party-developers/extensibility/hooks/actions.md#woocommerce_store_api_checkout_update_order_from_request
		 */
		add_action(
			'woocommerce_store_api_checkout_update_order_from_request',
			function( \WC_Order $order, \WP_REST_Request $request ) {
				$shipping_workshop_request_data = $request['extensions'][ self::IDENTIFIER ];
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
				if ( 'other' === $alternate_shipping_instruction ) {
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
	private static function show_shipping_instructions_in_order() {
		add_action(
			'woocommerce_admin_order_data_after_shipping_address',
			function( \WC_Order $order ) {
				$alternate_shipping_instruction            = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction' );
				$alternate_shipping_instruction_other_text = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction_other_text' );

				echo '<div>';
				echo '<strong>' . esc_html__( 'Shipping Instructions', 'shipping-workshop' ) . '</strong>';
				/** 📝 Output the alternate shipping instructions here! */
				printf( '<p>%s</p>', esc_html( $alternate_shipping_instruction ) );
				if ( 'other' === $alternate_shipping_instruction ) {
					printf( '<p>%s</p>', esc_html( $alternate_shipping_instruction_other_text ) );
				}
				echo '</div>';
			}
		);
	}

	/**
	 * Adds the address on the order confirmation page.
	 */
	private static function show_shipping_instructions_in_order_confirmation() {
		add_action(
			'woocommerce_thankyou',
			function( int $order_id ) {
				$order = wc_get_order( $order_id );
				$shipping_workshop_alternate_shipping_instruction            = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction' );
				$shipping_workshop_alternate_shipping_instruction_other_text = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction_other_text' );

				if ( '' !== $shipping_workshop_alternate_shipping_instruction ) {
					echo '<h2>' . esc_html__( 'Shipping Instructions', 'shipping-workshop' ) . '</h2>';
					echo '<p>' . esc_html( $shipping_workshop_alternate_shipping_instruction ) . '</p>';

					if ( '' !== $shipping_workshop_alternate_shipping_instruction_other_text ) {
						echo '<p>' . esc_html( $shipping_workshop_alternate_shipping_instruction_other_text ) . '</p>';
					}
				}
			}
		);
	}

	/**
	 * Adds the address on the order confirmation email.
	 */
	private static function show_shipping_instructions_in_order_email() {
		add_action(
			'woocommerce_email_after_order_table',
			function( $order, $sent_to_admin, $plain_text, $email ) {
				$shipping_workshop_alternate_shipping_instruction            = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction' );
				$shipping_workshop_alternate_shipping_instruction_other_text = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction_other_text' );

				if ( '' !== $shipping_workshop_alternate_shipping_instruction ) {
					echo '<h2>' . esc_html__( 'Shipping Instructions', 'shipping-workshop' ) . '</h2>';
					echo '<p>' . esc_html( $shipping_workshop_alternate_shipping_instruction ) . '</p>';

					if ( '' !== $shipping_workshop_alternate_shipping_instruction_other_text ) {
						echo '<p>' . esc_html( $shipping_workshop_alternate_shipping_instruction_other_text ) . '</p>';
					}
				}
			},
			10,
			4
		);
	}
}
