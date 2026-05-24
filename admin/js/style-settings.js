/* SE Portfolio — Style Settings Admin JS
 * Handles: wp-color-picker init, preset switching, accordion, reset-to-defaults.
 * ============================================================ */
/* global sepStyleData, jQuery */

( function ( $ ) {
	'use strict';

	// Map field name → input selector (color pickers).
	var colorFieldMap = {
		bg:       '[name="sep_style[bg]"]',
		surface:  '[name="sep_style[surface]"]',
		surface2: '[name="sep_style[surface2]"]',
		border:   '[name="sep_style[border]"]',
		accent:   '[name="sep_style[accent]"]',
		green:    '[name="sep_style[green]"]',
		text:     '[name="sep_style[text]"]',
		muted:    '[name="sep_style[muted]"]',
		prompt:   '[name="sep_style[prompt]"]',
		warning:  '[name="sep_style[warning]"]',
	};

	// ---- Initialize color pickers ----------------------------------------

	$( '.sep-color-picker' ).wpColorPicker();

	// ---- Preset selector --------------------------------------------------

	$( '#sep-style-preset' ).on( 'change', function () {
		var key    = $( this ).val();
		var preset = ( sepStyleData.presets && sepStyleData.presets[ key ] ) ? sepStyleData.presets[ key ] : null;

		if ( ! preset ) {
			return;
		}

		// Update global color pickers.
		$.each( colorFieldMap, function ( field, selector ) {
			if ( preset[ field ] ) {
				var $input = $( selector );
				if ( $input.length ) {
					$input.val( preset[ field ] ).wpColorPicker( 'color', preset[ field ] );
				}
			}
		} );

		// Update font and radius fields.
		if ( preset.font_mono ) { $( '[name="sep_style[font_mono]"]' ).val( preset.font_mono ); }
		if ( preset.font_body ) { $( '[name="sep_style[font_body]"]' ).val( preset.font_body ); }
		if ( preset.radius )    { $( '[name="sep_style[radius]"]' ).val( preset.radius ); }

		// Update typography size fields.
		if ( preset.base_size )       { $( '[name="sep_style[base_size]"]' ).val( preset.base_size ); }
		if ( preset.hero_name_size )  { $( '[name="sep_style[hero_name_size]"]' ).val( preset.hero_name_size ); }

		// Update spacing / transition fields.
		if ( preset.section_py )    { $( '[name="sep_style[section_py]"]' ).val( preset.section_py ); }
		if ( preset.card_pad )      { $( '[name="sep_style[card_pad]"]' ).val( preset.card_pad ); }
		if ( preset.hero_pt )       { $( '[name="sep_style[hero_pt]"]' ).val( preset.hero_pt ); }
		if ( preset.container_max ) { $( '[name="sep_style[container_max]"]' ).val( preset.container_max ); }
		if ( preset.transition )    { $( '[name="sep_style[transition]"]' ).val( preset.transition ); }

		// Update effect toggle checkboxes.
		var toggles = [ 'show_glows', 'show_scanlines', 'show_animations', 'show_blink' ];
		$.each( toggles, function ( i, field ) {
			if ( typeof preset[ field ] !== 'undefined' ) {
				$( '[name="sep_style[' + field + ']"]' ).prop( 'checked', !! preset[ field ] );
			}
		} );

		// Update card style radio.
		if ( preset.card_style ) {
			$( '[name="sep_style[card_style]"][value="' + preset.card_style + '"]' ).prop( 'checked', true );
		}
	} );

	// ---- Component accordion ----------------------------------------------

	$( document ).on( 'click', '.sep-component-toggle', function () {
		var $btn    = $( this );
		var $fields = $btn.next( '.sep-component-fields' );

		$btn.toggleClass( 'is-open' );
		$fields.slideToggle( 150 );
	} );

	// ---- Reset to defaults ------------------------------------------------

	$( '#sep-reset-defaults' ).on( 'click', function ( e ) {
		e.preventDefault();

		if ( ! window.confirm( sepStyleData.i18n.resetConfirm ) ) {
			return;
		}

		var $btn    = $( this );
		var $notice = $( '#sep-reset-notice' );

		$btn.prop( 'disabled', true ).text( '…' );

		$.post(
			sepStyleData.ajaxurl,
			{
				action: 'sep_reset_style',
				nonce:  sepStyleData.nonce,
			},
			function ( response ) {
				$btn.prop( 'disabled', false ).text( $btn.data( 'label' ) );

				if ( ! response.success ) {
					$notice.removeClass( '' ).addClass( 'is-error' ).text( sepStyleData.i18n.resetError ).show();
					return;
				}

				var defaults = response.data.defaults;

				// Update global color pickers.
				$.each( colorFieldMap, function ( field, selector ) {
					if ( defaults[ field ] ) {
						var $input = $( selector );
						if ( $input.length ) {
							$input.val( defaults[ field ] ).wpColorPicker( 'color', defaults[ field ] );
						}
					}
				} );

				// Update font, radius, and other text fields.
				var textFields = [
					'font_mono', 'font_body', 'radius',
					'base_size', 'hero_name_size',
					'section_py', 'card_pad', 'hero_pt', 'container_max', 'transition'
				];
				$.each( textFields, function ( i, field ) {
					if ( defaults[ field ] ) {
						$( '[name="sep_style[' + field + ']"]' ).val( defaults[ field ] );
					}
				} );

				// Reset effect toggles.
				var toggles = [ 'show_glows', 'show_scanlines', 'show_animations', 'show_blink' ];
				$.each( toggles, function ( i, field ) {
					$( '[name="sep_style[' + field + ']"]' ).prop( 'checked', !! defaults[ field ] );
				} );

				// Reset card style radio.
				if ( defaults.card_style ) {
					$( '[name="sep_style[card_style]"][value="' + defaults.card_style + '"]' ).prop( 'checked', true );
				}

				// Clear all component override pickers.
				$( '.sep-component-color-picker' ).each( function () {
					$( this ).val( '' ).wpColorPicker( 'color', '' );
				} );

				$notice.removeClass( 'is-error' ).text( sepStyleData.i18n.resetSuccess ).show();
				setTimeout( function () { $notice.fadeOut( 400 ); }, 3000 );
			}
		).fail( function () {
			$btn.prop( 'disabled', false ).text( $btn.data( 'label' ) );
			$notice.addClass( 'is-error' ).text( sepStyleData.i18n.resetError ).show();
		} );
	} );

} )( jQuery );
