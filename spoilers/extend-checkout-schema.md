```php
public static function extend_checkout_schema() {
    return [
        'otherShippingValue'   => [
            'description' => __( 'User-provided instructions for courier', 'shipping-workshop' ),
            'type'        => 'string',
            'context'     => array( 'view', 'edit' ),
            'readonly'    => true,
            'optional'	  => true,
            'arg_options' => array(
                'validate_callback' => function( $value ) {
                    return is_string( $value );
                },
            )
        ],
        'alternateShippingInstruction'   => [
            'description' => __( 'Alternative shipping options for an order', 'shipping-workshop' ),
            'type'        => 'string',
            'context'     => array( 'view', 'edit' ),
            'readonly'    => true,
            'arg_options' => array(
                'validate_callback' => function( $value ) {
                    return is_string( $value );
                },
            )
        ],
    ];
}
```
