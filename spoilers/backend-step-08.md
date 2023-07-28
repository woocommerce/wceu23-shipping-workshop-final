```php
echo '<div>';
echo '<strong>' . __( 'Shipping Instructions', 'shipping-workshop' ) . '</strong>';
printf( '<p>%s</p>', $alternate_shipping_instruction );
printf( '<p>%s</p>', $alternate_shipping_instruction_other_text );
echo '</div>';
```