```js
if (
    selectedAlternateShippingInstruction !== 'other' ||
    otherShippingValue !== ''
) {
    return;
}
setValidationErrors( {
    [ validationErrorId ]: {
        message: __( 'Please enter a value', 'shipping-workshop' ),
        hidden: false,
    },
} );
```
