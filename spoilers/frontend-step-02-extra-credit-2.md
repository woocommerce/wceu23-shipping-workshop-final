```js
const [ hasInteractedWithOtherInput, setHasInteractedWithOtherInput ] = useState( false );
```

Then in the `onBlur` and `onChange` handler for the "other" input, set the state to `true`:

```js
<TextareaControl
    className={
        'shipping-workshop-other-textarea' +
        ( validationError?.hidden === false
            ? ' has-error'
            : '' )
    }
    onChange={ ( e ) => {
        setOtherShippingValue( e );
        setHasInteractedWithOtherInput( true );
    } }
    onBlur={ () => setHasInteractedWithOtherInput( true ) }
    value={ otherShippingValue }
    required={ true }
    placeholder={ __(
        'Enter shipping instructions',
        'shipping-workshop'
    ) }
/>
```

Finally, in the `useEffect` where we set the validation errors, update the hidden property to be the opposite of the `hasInteractedWithOtherInput` state:

```js
setValidationErrors( {
    [ validationErrorId ]: {
        message: __( 'Please add some text', 'shipping-workshop' ),
        hidden: ! hasInteractedWithOtherInput,
    },
} );
```
