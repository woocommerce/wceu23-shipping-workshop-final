# Build a "Not at Home" Shipping Extension in the WooCommerce Checkout Block

Welcome to WordCamp Europe! Join us in an engaging workshop where we'll guide you through building a practical "Not at Home" shipping extension for the WooCommerce Checkout Block.

## Prerequisites: What You'll Need

Before we begin, make sure you have the following prerequisites ready:

- A development environment with a blank WP installation
- WooCommerce plugin installed and activated
- Checkout page with the Checkout block integrated
- Free shipping method added in WooCommerce
- "Cash on Delivery" payment option enabled in WooCommerce settings
- Basic knowledge of JavaScript (React)
- IDE/Code editor (e.g., Visual Studio Code)
- Node or NVM (Node Version Manager) installed

## Before We Start: Preparation Steps

Before diving into the workshop, let's make sure everything is set up properly. Take the following steps:

1. Install Redux Dev Tools in your browser. [Chrome](https://chrome.google.com/webstore/detail/redux-devtools/lmhkpmbekcpmknklioeibfkpmmfibljd) | [Firefox](https://addons.mozilla.org/en-US/firefox/addon/reduxdevtools/)
2. Install the [sample product data](https://raw.githubusercontent.com/woocommerce/woocommerce/trunk/plugins/woocommerce/sample-data/sample_products.xml) into your WooCommerce site.
3. Ensure a free shipping method is enabled.
4. Activate the "Cash on Delivery" payment method.
5. Ensure the Checkout page contains the Checkout block, and that the `[woocommerce_checkout]` shortcode has been removed.

To get your development environment ready, follow these straightforward instructions:

```bash
npm i -D prettier@npm:wp-prettier@2.6.2
composer install
composer run phpcbf
npm start
```

Note: `composer run phpcbf` will show an error, this is OK!

Get ready to enhance your shipping capabilities with our workshop: Build a "Not at Home" Shipping extension in the WooCommerce Checkout block. Let's elevate your WooCommerce store and create a more convenient experience for your customers!
