/**
 * Functionality specific to WPDanceClaraTheme.
 *
 * Provides helper functions to enhance the theme experience.
 */

;( function( $ ) {
	"use strict";
	
	var body    = $( 'body' ),
	    _window = $( window );


	/**
	 * Enables menu toggle for small screens.
	 */
	$('nav.navigation').each(function() {

		var nav = $(this);
		var button = nav.find('.menu-toggle');
		var menu = nav.find('.nav-menu');

		if (!nav || !button) {
			return;
		}

		// Hide button if menu is missing or empty.
		if (!menu || !menu.children().length) {
			button.hide();
			return;
		}

		button.on( 'click', function() {
			nav.toggleClass( 'toggled-on' );
			if ( nav.hasClass( 'toggled-on' ) ) {
				$( this ).attr( 'aria-expanded', 'true' );
				menu.attr( 'aria-expanded', 'true' );
			} else {
				$( this ).attr( 'aria-expanded', 'false' );
				menu.attr( 'aria-expanded', 'false' );
			}
		} );

		// Fix sub-menus for touch devices.
		if ( 'ontouchstart' in window ) {
			menu.find( '.menu-item-has-children > a, .page_item_has_children > a' ).on( 'touchstart', function( e ) {
				var el = $( this ).parent( 'li' );

				if ( ! el.hasClass( 'focus' ) ) {
					e.preventDefault();
					el.toggleClass( 'focus' );
					el.siblings( '.focus' ).removeClass( 'focus' );
				}
			} );
		}

		// Better focus for hidden submenu items for accessibility.
		menu.find('a')
			.on('focus', function() {
				$( this ).parents( '.menu-item, .page_item' ).addClass( 'focus' );
			})
			.on('blur', function() {
				$( this ).parents( '.menu-item, .page_item' ).removeClass( 'focus' );
			});

		/**
		 * @summary Add or remove ARIA attributes.
		 * Uses jQuery's width() function to determine the size of the window and add
		 * the default ARIA attributes for the menu toggle if it's visible.
		 * @since WPDanceClaraTheme 1.5
		 */
		function onResizeARIA() {
			if ( 643 > _window.width() ) {
				button.attr( 'aria-expanded', 'false' );
				menu.attr( 'aria-expanded', 'false' );
				button.attr( 'aria-controls', 'primary-menu' );
			} else {
				button.removeAttr( 'aria-expanded' );
				menu.removeAttr( 'aria-expanded' );
				button.removeAttr( 'aria-controls' );
				menu.find('a').trigger('blur');
			}
		}

		_window
			.on( 'load', onResizeARIA )
			.on( 'resize', function() {
				onResizeARIA();
		} );

	});


	

	

	/**
	 * Makes "skip to content" link work correctly in IE9 and Chrome for better
	 * accessibility.
	 *
	 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
	 */
	_window.on( 'hashchange', function() {
		var element = document.getElementById( location.hash.substring( 1 ) );

		if ( element ) {
			if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) {
				element.tabIndex = -1;
			}

			element.focus();
		}
	} );


	/**
	 * Fix Parallax Row effect of Visual Composer does not work correctly.
	 */
	$(window).on('vc_js', function() {
		if (typeof skrollr != 'undefined')
			setTimeout(function() {
				skrollr.get() && skrollr.get().refresh();
			}, 200);
	});


	/**
	 * Call when DOM Ready
	 *
	 * NOTE: DOM modifications should be process in order of appearance first process first.
	 */
	$(function() {
		var $body = $('body');
		var $content = $('#content');
		var $sidebar_before_content = $('#sidebar_wpdanceclaratheme_before_content');
		var $comments = $('#comments');
		var is_woocommerce_page = $body.hasClass('woocommerce-page');
		var is_single_product_page = $body.hasClass('single-product');
		var is_archive_page = $body.hasClass('archive');
		var is_page_title_top = $body.hasClass('page-title-top');

		/** 
		 * PAGE CONFIGURED WITH MAIN TITLE APPEAR AT HEADER AREA
		 * --------------------------------------------------------------------
		 */
		if (is_page_title_top) {

			/**
			 * Move header of Archive pages to the site header section
			 */
			if (is_archive_page)
				$('#content .archive-header').first().appendTo($('#masthead > .container'));

			/**
			 * Move header of Single Page or Post to the site header section
			 */
			if ($body.hasClass('page') || $body.hasClass('single')) {
				var $elm = $('#content .hentry .entry-header').first().find('.entry-title, .breadcrumbs');
				if ($elm.length > 0) {
					var $header = $('#masthead > .container').append('<div class="single-header"></div>').children('.single-header');
					$elm.appendTo($header);
				}
			}


			/**
			 * Move Page Heading section on top header
			 */
			// $('#primary #content > header:eq(0)').appendTo($('#masthead > .container'));

			/** Is current page WooCommerce page? */
			if (is_woocommerce_page) {
				var $page_title = $('#content .page-title'),
					$breadrumb  = $('#content .woocommerce-breadcrumb');

				if ($page_title.length > 0 || $breadrumb.length > 0) {
					/**
					 * Move woocommerce page title and breadrumb to header section
					 */
					$('<div class="single-header"></div>')
						.appendTo('#masthead > .container')
						.append($page_title)
						.append($breadrumb);
				}
			}
		}

		
		/**
		 * ARCHIVE PAGES
		 * --------------------------------------------------------------------
		 */
		if (is_archive_page) {

			/**
			 * Add class odd/even to each post entry in the main loop
			 */
			$content.find('article:odd').addClass('odd');
			$content.find('article:even').addClass('even');

			/**
			 * Check whether current page is masonry layout
			 * @type {Boolean}
			 */
			var is_masonry = $.isFunction($.fn.masonry) && $body.hasClass('content-masonry');


			/**
			 * Add class col-i to each .hentry to help CSS styling margin
			 */
			for (var i = 2; i <= 12; i++) {
				$('.grid-' + i + ' > .hentry', $content).each(function(idx) {
					$(this).addClass('col-' + (idx % i + 1))
				});
			}

			/**
			 * Update masonry layout if configured
			 */
			if (is_masonry) {
				var cw = $('.hentry.wpdance-portfolio-portrait', $content).length > 0 ? '.hentry.wpdance-portfolio-portrait' : '.hentry';

				for (var i = 2; i <= 12; i++) {
					var $grid = $('.grid-' + i, $content);
					$grid.imagesLoaded({grid: $grid}, function() {
						this.options.grid.masonry({
							itemSelector    : '.hentry',
							columnWidth     : cw,
							percentPosition : true,
							isRTL           : body.is('.rtl')
						});
					});
				}
			}
		}


		/**
		 * SINGLE PRODUCT PAGE
		 * --------------------------------------------------------------------
		 */
		if (is_woocommerce_page && is_single_product_page) {
			var $product_el = $content.find('.wpdanceclaratheme-single-product-layout-1');

			/**
			 * BUTTON ADD-TO-CART
			 * Make button AddToCart in the mini box works like the default AddToCart button
			 */
			$product_el.find('.btn-addtocart').on('click', function() {
				$product_el.find('form.cart:first').submit();
			});
		}
		

		

		/**
		 * Apply Bootstrap class 'form-control' to all input box, selectbox 
		 */
		$('select, input[type=text] input[type=search], input[type=email], input[type=number], input[type=tel], textarea').addClass('form-control');



		/**
		 * EQUAL ALL WIDGET'S HEIGHT IN SIDEBAR "BEFORE CONTENT"
		 * --------------------------------------------------------------------
		 */			
		if ($sidebar_before_content.length > 0) {
			function update_sidebar_before_content_height() {
				var max_height = 0;

				$('.widget-inner', $sidebar_before_content)
					.height('auto')
					.height(function(i, height) {
						max_height = Math.max(max_height, height);
					})
					.height(max_height);
			}
			update_sidebar_before_content_height();

			$body.bind('price_slider_updated', update_sidebar_before_content_height);
			$(window).on('load', update_sidebar_before_content_height);
		}



		/**
		 * WRAP SOME HEADINGS HAVING STRIKE LINE OVER
		 * ---------------------------------------------------------------------
		 */
		
		function wrap_heading($selector) {
			$selector
				.addClass('wpdanceclaratheme-strikethrough-left')
				.wrapInner('<span class="wpdanceclaratheme-sep-holder-center"></span>')
				.prepend('<span class="wpdanceclaratheme-sep-holder-left"></span>')
				.append('<span class="wpdanceclaratheme-sep-holder-right"></span>');
		}

		if (is_woocommerce_page) {
			wrap_heading($content.find('.woocommerce-tabs h2, .related.products h2, .upsells.products h2, .comment-reply-title'));
		}

		if ($comments.length > 0)
			wrap_heading($comments.find('.comments-title, #reply-title'));



		/**
		 * AUTO COLLAPSE SIDEBAR'S WIDGETS
		 * 
		 * Check if sidebar has widget .sidebar-toggle then attach event
		 * when click on this toggle it show/hide all widgets in this sidebar
		 * 
		 * --------------------------------------------------------------------
		 */
		var $sidebar_toggle = $('.widget.sidebar-toggle:visible');
		if ($sidebar_toggle.length > 0) {

			function toggle_sidebar($toggle) {
				var $widgets = $toggle.parent().children().not($toggle);
				$toggle.hasClass('sidebar-toggled-on') ? $widgets.show() : $widgets.hide();
			}

			$sidebar_toggle.on('click', function(e) {
				$(this).toggleClass('sidebar-toggled-on');
				toggle_sidebar($(this));
			});

			$sidebar_toggle.each(function() {
				toggle_sidebar($(this));
			});
		}



		if ($.isFunction($.fn.masonry))
			$('.tagtray-gallery.masonry .tagtray-gallery-items').masonry({
				itemSelector: '.tagtray-gallery-item',
				columnWidth: '.tagtray-gallery-item',
				percentPosition: true,
				isRTL: body.is('.rtl')
			});



		

		/**
		 * ACCESSIBILITY
		 * --------------------------------------------------------------------
		 */
		
		/**
		 * Make Accessibility for Products grid
		 */
		$('.products .product a').on('focusin', function(e) {
			$(this).closest('.product').addClass('focus');
		}).on('focusout', function(e) {
			$(this).closest('.product').removeClass('focus');
		});

		/**
		 * Make Accessibility for Menu
		 */
		$('.nav-menu a').on('focusin', function(e) {
			$(this).parentsUntil('.nav-menu', '.menu-item').addClass('focus');
		}).on('focusout', function(e) {
			$(this).parentsUntil('.nav-menu', '.menu-item').removeClass('focus');
		});


	});

} )( jQuery );
