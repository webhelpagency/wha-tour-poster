/* global wp */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience: they make the
 * preview reflect the site title and tagline without reloading the page.
 *
 * Based on the Underscores (_s) customizer script, rewritten without jQuery.
 */
( function () {
	'use strict';

	function setText( selector, text ) {
		Array.prototype.forEach.call( document.querySelectorAll( selector ), function ( el ) {
			el.textContent = text;
		} );
	}

	wp.customize( 'blogname', function ( value ) {
		value.bind( function ( to ) {
			setText( '.site-title', to );
		} );
	} );

	wp.customize( 'blogdescription', function ( value ) {
		value.bind( function ( to ) {
			setText( '.site-description', to );
		} );
	} );
}() );
