import { registerBlockType } from '@wordpress/blocks'
import { useBlockProps, PanelColorSettings, InspectorControls } from '@wordpress/block-editor'
import { __ } from '@wordpress/i18n'
import block from './block.json'
import icons from '../../icons'
import './main.css'

registerBlockType(block.name, {
	icon: icons.primary,
	edit({ attributes, setAttributes }) {
		const { bgColor, textColor } = attributes
		const blockProps = useBlockProps({
			style: {
				'background-color': bgColor,
				color: textColor
			}
		})

		return (
			<>
				<InspectorControls>
					<PanelColorSettings
						title={__('Colors', 'cc-vortex')}
						colorSettings={[
							{
								label: __('Background Color', 'cc-vortex'),
								value: bgColor,
								onChange: newVal => setAttributes({bgColor: newVal})
							},
							{
								label: __('Text Color', 'cc-vortex'),
								value: textColor,
								onChange: newVal => setAttributes({textColor: newVal})
							}
						]}

					/>
				</InspectorControls>
				<div {...blockProps}>
					<h1>Search: Your social search term here</h1>
					<form>
						<input type="text" placeholder="Search" />
						<div className="btn-wrapper">
							<button type="submit" style={{
								'background-color': bgColor,
								color: textColor
							}}>Search</button>
						</div>
					</form>
				</div>
			</>
		)
	},

})
