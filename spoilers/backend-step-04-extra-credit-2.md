```php
if ( $alternate_shipping_instruction === 'other' ) {
    $order->update_meta_data( 'shipping_workshop_alternate_shipping_instruction_other_text', $other_shipping_value );
}
```