/**
 * Editor registration for the WHA Tour Poster lead form block.
 *
 * No build step: the block is rendered on the server and previewed with
 * ServerSideRender, so plain ES5 is enough.
 *
 * @package WHA_Tours_Core
 */
( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.blocks || ! wp.element ) {
		return;
	}

	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var ServerSideRender = wp.serverSideRender;
	var useBlockProps = wp.blockEditor && wp.blockEditor.useBlockProps;

	wp.blocks.registerBlockType( 'wha-tours-core/lead-form', {
		edit: function ( props ) {
			var blockProps = useBlockProps ? useBlockProps() : {};
			var preview;

			if ( ServerSideRender ) {
				preview = el( ServerSideRender, {
					block: 'wha-tours-core/lead-form',
					attributes: props.attributes
				} );
			} else {
				preview = el( 'p', null, __( 'Lead form', 'wha-tours-core' ) );
			}

			return el( 'div', blockProps, preview );
		},
		save: function () {
			return null;
		}
	} );
} )( window.wp );
