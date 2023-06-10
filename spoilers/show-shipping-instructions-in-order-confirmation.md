```php
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
```
