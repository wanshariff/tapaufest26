/**
 * Editor side of the TAPAUfest blocks.
 *
 * Every block is rendered on the server, so the editor shows a live preview
 * through ServerSideRender. Settings in the sidebar are built from each
 * block's attributes in block.json, so no build step is needed.
 */
( function ( wp ) {
	if ( ! wp || ! wp.blocks ) {
		return;
	}
	var el = wp.element.createElement;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var components = wp.components;
	var ServerSideRender = wp.serverSideRender;
	var __ = wp.i18n.__;

	var labels = {
		limit: __( 'Number of items (0 shows all)', 'tapaufest' ),
		filters: __( 'Show country and day filters', 'tapaufest' ),
		moreLink: __( 'Link to the full lineup', 'tapaufest' ),
		compare: __( 'Show the comparison table', 'tapaufest' ),
		type: __( 'Zone', 'tapaufest' ),
		group: __( 'Only this FAQ group (slug, blank for all)', 'tapaufest' ),
		screen: __( 'Which page', 'tapaufest' )
	};

	function controlFor( name, def, props ) {
		var value = props.attributes[ name ];
		var set = function ( v ) {
			var next = {};
			next[ name ] = v;
			props.setAttributes( next );
		};
		if ( def.type === 'boolean' ) {
			return el( components.ToggleControl, { key: name, label: labels[ name ] || name, checked: !! value, onChange: set, __nextHasNoMarginBottom: true } );
		}
		if ( def.type === 'number' ) {
			return el( components.RangeControl, { key: name, label: labels[ name ] || name, value: value || 0, min: 0, max: 24, onChange: set, __nextHasNoMarginBottom: true } );
		}
		if ( def.enum ) {
			return el( components.SelectControl, {
				key: name,
				label: labels[ name ] || name,
				value: value,
				options: def.enum.map( function ( o ) {
					return { label: o.toUpperCase(), value: o };
				} ),
				onChange: set,
				__nextHasNoMarginBottom: true
			} );
		}
		return el( components.TextControl, { key: name, label: labels[ name ] || name, value: value || '', onChange: set, __nextHasNoMarginBottom: true } );
	}

	var names = [ 'alert-bar', 'header-cta', 'hero', 'lineup', 'artist-details', 'timetable', 'now-next', 'tickets', 'vendors', 'faq', 'sticky-cta', 'screen' ];
	names.forEach( function ( slug ) {
		var name = 'tapaufest/' + slug;
		wp.blocks.registerBlockType( name, {
			edit: function ( props ) {
				var type = wp.blocks.getBlockType( name );
				var attrs = ( type && type.attributes ) || {};
				var controls = Object.keys( attrs )
					.filter( function ( key ) {
						return key !== 'lock' && key !== 'metadata' && key !== 'align' && key !== 'className';
					} )
					.map( function ( key ) {
						return controlFor( key, attrs[ key ], props );
					} );
				return el(
					'div',
					useBlockProps(),
					controls.length ? el( InspectorControls, null, el( components.PanelBody, { title: __( 'Settings', 'tapaufest' ) }, controls ) ) : null,
					el( components.Disabled, null, el( ServerSideRender, { block: name, attributes: props.attributes } ) )
				);
			},
			save: function () {
				return null;
			}
		} );
	} );
} )( window.wp );
