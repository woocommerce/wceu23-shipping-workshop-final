```php
$i18n = [
    'leave-with-neighbour' 	=> __('Leave with neighbour', 'textdomain'),
    'leave-in-shed' 		=> __('Leave in shed', 'textdomain'),
    'other' 				=> __('Other', 'textdomain'),
];
if ( isset( $i18n[ $alternate_shipping_instruction ] ) ) {
    $order->update_meta_data( 'shipping_workshop_alternate_shipping_instruction', $i18n[ $alternate_shipping_instruction ] );
}
$order->update_meta_data( 'shipping_workshop_alternate_shipping_instruction_other_text', $other_shipping_value );
```