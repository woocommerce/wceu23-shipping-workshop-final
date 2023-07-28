```jsx
if (
    selectedAlternateShippingInstruction !== 'other' ||
    otherShippingValue !== ''
) {
    if ( validationError ) {
        clearValidationError( validationErrorId );
    }
    return;
}
```