/**
 * External dependencies
 */
import { registerPlugin } from '@wordpress/plugins';

const render = () => {};

registerPlugin( 'shipping-workshop', {
	render,
	scope: 'woocommerce-checkout',
} );
