```php
echo '<div>';
echo '<strong>' . __( 'Shipping Instructions', 'shipping-workshop' ) . '</strong>';
printf( '<p>%s</p>', $alternate_shipping_instruction );
if ( $alternate_shipping_instruction === 'other' ) {
    printf( '<p>%s</p>', $alternate_shipping_instruction_other_text );
}
echo '</div>';
```