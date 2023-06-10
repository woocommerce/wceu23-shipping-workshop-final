/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	RichText,
	InspectorControls,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl, Disabled } from '@wordpress/components';
import { getSetting } from '@woocommerce/settings';

/**
 * Internal dependencies
 */
import './style.scss';
import { options } from './options';

const { defaultShippingText } = getSetting( 'shipping-workshop_data', '' );

export const Edit = ( { attributes, setAttributes } ) => {
	const { text } = attributes;
	const blockProps = useBlockProps();
	return (
		<div { ...blockProps } style={ { display: 'block' } }>
			<InspectorControls>
				<PanelBody title={ __( 'Block options', 'shipping-workshop' ) }>
					Options for the block go here.
				</PanelBody>
			</InspectorControls>
			<div>
				<RichText
					value={
						text ||
						defaultShippingText ||
						__(
							'If I am not at home, please…',
							'shipping-workshop'
						)
					}
					onChange={ ( value ) => setAttributes( { text: value } ) }
				/>
			</div>
			<div>
				<Disabled>
					<SelectControl options={ options } />
				</Disabled>
			</div>
		</div>
	);
};

export const Save = ( { attributes } ) => {
	const { text } = attributes;
	return (
		<div { ...useBlockProps.save() }>
			<RichText.Content value={ text } />
		</div>
	);
};
