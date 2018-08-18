// Highly inspired by https://github.com/inpsyde/gutenberg-example/blob/ceac1c6fa0f1484b955d2ba5b7414cc5672617b1/assets/js/src/EditorPicks/index.js
const { CheckboxControl } = wp.components
const { PluginPostStatusInfo } = wp.editPost
const { 
	compose,
	withInstanceId
 } = wp.compose
const { withSelect, withDispatch } = wp.data
const { registerPlugin } = wp.plugins

const Render = ({ isChecked = false, updateCheck, instanceId }) => {
	const callback = () => updateCheck(!isChecked)
	const id = instanceId + '-editors-pick'
	return (
		<PluginPostStatusInfo className='dewp-planet'>
			<div>
				<CheckboxControl
						className={ isChecked ? '-is-checked' : '' }
						label={ 'Im DEWP-Planet-Feed anzeigen' }
						checked={ isChecked }
						onChange={ callback }
				/>
				<a href="https://github.com/deworg/dewp-planet-feed/blob/master/ABOUT.md" className="dewp-planet__help-link dashicons-before dashicons-editor-help hide-if-no-js" target="_blank">
					<span className="screen-reader-text">Was ist das?</span>
				</a>
				<span className="dewp-planet__label-notice">
					<span className="dashicons dashicons-warning" aria-hidden="true"></span><strong>Erscheint in allen deutschsprachigen WordPress-Dashboards!</strong>
				</span>
			</div>
		</PluginPostStatusInfo>
	)
}

const DewpPlanetGutenberg = compose(
	[
		withSelect((select) => {
			return {
				isChecked: select('core/editor').getEditedPostAttribute('meta').wpf_show_in_dewp_planet_feed
			}
		}),
		withDispatch((dispatch) => {
			return {
				updateCheck (wpf_show_in_dewp_planet_feed) {
					dispatch('core/editor').editPost({ meta: { wpf_show_in_dewp_planet_feed} })
				}
			}
		}),
		withInstanceId
	]
)(Render)

registerPlugin('dewp-planet-gutenberg', {
  render: DewpPlanetGutenberg
})