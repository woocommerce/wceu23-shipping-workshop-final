```js
/* Handle changing the select's value */
useEffect( () => {
  setExtensionData(
    'shipping-workshop',
    'alternateShippingInstruction',
    selectedAlternateShippingInstruction
  );

  // If shopper does not select other then clear the other value, clear the validation error, and return.
  if ( selectedAlternateShippingInstruction !== 'other' ) {
    clearValidationError( 'shipping-workshop-other-value' );

    setExtensionData(
      'shipping-workshop',
      'alternateShippingInstructionOtherText',
      null
    );
    return;
  }

  // If they select other, add a hidden validation error to the other field.
  setValidationErrors( {
    'shipping-workshop-other-value': {
      message: 'Please enter a value',
      hidden: true,
    },
  } );
}, [
  clearValidationError,
  setValidationErrors,
  setExtensionData,
  selectedAlternateShippingInstruction,
] );
```