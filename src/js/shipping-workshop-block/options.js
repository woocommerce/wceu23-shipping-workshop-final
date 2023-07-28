/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';

export const options = [
	{
		label: __( 'Try again another day', 'shipping-workshop' ),
		value: 'try-again',
	},
	/**
	 * [frontend-step-01]
	 * 📝 Add more options using the same format as above. Ensure one option has the key "other".
	 */
	{
		label: __( 'Throw over fence', 'shipping-workshop' ),
		value: 'throw-over-fence',
	},
	{
		label: __( 'Leave with neighbour', 'shipping-workshop' ),
		value: 'leave-with-neighbour',
	},
	{
		label: __( 'Other', 'shipping-workshop' ),
		value: 'other',
	},
];
