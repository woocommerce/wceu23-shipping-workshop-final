```php
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
```
