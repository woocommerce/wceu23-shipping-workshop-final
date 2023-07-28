```php
return [
    'otherShippingValue'   => [
        'description' => 'Other text for shipping instructions',
        'type'        => 'string',
        'context'     => [ 'view', 'edit' ],
        'readonly'    => true,
        'optional'    => true,
        'arg_options' => [
            'validate_callback' => function( $value ) {
                return is_string( $value );
            },
        ]
    ],
    'alternateShippingInstruction'   => [
        'description' => 'Alternative shipping instructions for the courier',
        'type'        => 'string',
        'context'     => [ 'view', 'edit' ],
        'readonly'    => true,
        'arg_options' => [
            'validate_callback' => function( $value ) {
                return is_string( $value );
            },
        ]
    ],
];
```