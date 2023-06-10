```php
/**
 * Adds the address in the order page in WordPress admin.
 */
private function show_shipping_instructions_in_order() {
    add_action(
        'woocommerce_admin_order_data_after_shipping_address',
        function( $order ) {
            $alternate_shipping_instruction = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction' );
            $other_text                     = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction_other_text' );

            echo '<div>';
            printf( '<strong>%s</strong>', __( 'Shipping Instructions', 'shipping-workshop' ) );
            printf( '<p>%s</p>', $alternate_shipping_instruction );
            if ( $alternate_shipping_instruction === 'other' ) {
                printf('<p>(%s</p>)', $other_text );
            }
            echo '</div>';
        }
    );
}
```
