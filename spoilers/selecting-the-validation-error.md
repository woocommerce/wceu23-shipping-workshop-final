```js
const validationError = useSelect( ( select ) => {
	const store = select( 'wc/store/validation' );
	return store.getValidationError( 'shipping-workshop-other-value' );
} );
```
