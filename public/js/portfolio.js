/* SE Portfolio — Front-End JavaScript
 * Vanilla JS only. No jQuery dependency.
 * ====================================== */

( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		initTopNav();
		initStickyNav();
		initProjectFilters();
		initSmoothScroll();
		initTypingAnimation();
	} );

	/* ------------------------------------------------------------------
	 * Top Navigation — Hamburger Toggle + Active Link Highlighting
	 * ------------------------------------------------------------------ */
	function initTopNav() {
		var toggle = document.querySelector( '.sep-topnav-toggle' );
		var links  = document.querySelector( '.sep-topnav-links' );

		if ( toggle && links ) {
			toggle.addEventListener( 'click', function () {
				var open = links.classList.toggle( 'is-open' );
				toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			} );

			// Close menu when a nav link is clicked.
			links.querySelectorAll( 'a' ).forEach( function ( a ) {
				a.addEventListener( 'click', function () {
					links.classList.remove( 'is-open' );
					toggle.setAttribute( 'aria-expanded', 'false' );
				} );
			} );
		}

		// Highlight active nav link as user scrolls.
		var sections = document.querySelectorAll( '[data-sep-section]' );
		var navLinks = document.querySelectorAll( '.sep-topnav-links a' );
		if ( ! sections.length || ! navLinks.length || ! ( 'IntersectionObserver' in window ) ) {
			return;
		}

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						var id = entry.target.getAttribute( 'data-sep-section' );
						navLinks.forEach( function ( link ) {
							link.classList.toggle(
								'is-active',
								link.getAttribute( 'href' ) === '#sep-' + id
							);
						} );
					}
				} );
			},
			{ rootMargin: '-50% 0px -40% 0px', threshold: 0 }
		);

		sections.forEach( function ( s ) { observer.observe( s ); } );
	}

	/* ------------------------------------------------------------------
	 * Sticky Nav — Highlight Active Section
	 * ------------------------------------------------------------------ */
	function initStickyNav() {
		var sections = document.querySelectorAll( '[data-sep-section]' );
		var navDots  = document.querySelectorAll( '.sep-nav-dot' );

		if ( ! sections.length || ! navDots.length ) {
			return;
		}
		if ( ! ( 'IntersectionObserver' in window ) ) {
			return;
		}

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						var id = entry.target.getAttribute( 'data-sep-section' );
						navDots.forEach( function ( dot ) {
							dot.classList.toggle(
								'is-active',
								dot.dataset.section === id
							);
						} );
					}
				} );
			},
			{ rootMargin: '-40% 0px -50% 0px', threshold: 0 }
		);

		sections.forEach( function ( section ) {
			observer.observe( section );
		} );

		// Click nav dots to scroll to section.
		navDots.forEach( function ( dot ) {
			dot.addEventListener( 'click', function () {
				var id      = dot.dataset.section;
				var target  = document.querySelector( '[data-sep-section="' + id + '"]' );
				if ( target ) {
					target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
				}
			} );
		} );
	}

	/* ------------------------------------------------------------------
	 * Project Filter Tabs — No Page Reload
	 * ------------------------------------------------------------------ */
	function initProjectFilters() {
		var tabs  = document.querySelectorAll( '.sep-filter-tab' );
		var cards = document.querySelectorAll( '.sep-project-card' );

		if ( ! tabs.length ) {
			return;
		}

		tabs.forEach( function ( tab ) {
			tab.addEventListener( 'click', function () {
				var filter = tab.dataset.filter;

				// Update active tab.
				tabs.forEach( function ( t ) {
					t.classList.toggle( 'is-active', t === tab );
				} );

				// Show / hide cards.
				cards.forEach( function ( card ) {
					if ( 'all' === filter || card.dataset.status === filter ) {
						card.style.display = '';
					} else {
						card.style.display = 'none';
					}
				} );
			} );
		} );
	}

	/* ------------------------------------------------------------------
	 * Typing Animation — Hero Job Title Letter-by-Letter Reveal
	 * ------------------------------------------------------------------ */
	function initTypingAnimation() {
		var el = document.querySelector( '.sep-hero-title' );
		if ( ! el ) {
			return;
		}
		var text = el.dataset.text || el.textContent.trim();
		el.dataset.text = text;
		el.textContent  = '';
		var i = 0;
		function type() {
			if ( i < text.length ) {
				el.textContent += text.charAt( i++ );
				setTimeout( type, 40 + Math.random() * 30 );
			}
		}
		setTimeout( type, 400 );
	}

	/* ------------------------------------------------------------------
	 * Smooth Scroll — All Internal Anchor Links Within the Portfolio
	 * ------------------------------------------------------------------ */
	function initSmoothScroll() {
		var sel = '.sep-topnav a[href^="#"], .sep-footer a[href^="#"], .sep-nav-link[href^="#"]';
		document.querySelectorAll( sel ).forEach( function ( link ) {
			link.addEventListener( 'click', function ( e ) {
				var target = document.querySelector( link.getAttribute( 'href' ) );
				if ( target ) {
					e.preventDefault();
					var offset = 68; // topnav height + small buffer
					var top    = target.getBoundingClientRect().top + window.pageYOffset - offset;
					window.scrollTo( { top: top, behavior: 'smooth' } );
				}
			} );
		} );
	}

} )();
