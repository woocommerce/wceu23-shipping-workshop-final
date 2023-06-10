```jsx
{ validationError?.hidden === false && (
    <div
        className="wc-block-components-validation-error"
        role="alert"
    >
        <p>{ validationError?.message }</p>
    </div>
) }
```