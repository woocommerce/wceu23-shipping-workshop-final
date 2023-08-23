```jsx
if (
  selectedAlternateShippingInstruction !== 'other' ||
  otherShippingValue !== ''
) {
  clearValidationError( validationErrorId );
  return;
}
```
