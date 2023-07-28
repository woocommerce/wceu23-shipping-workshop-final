```php
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
```