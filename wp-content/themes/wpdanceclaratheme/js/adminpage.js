(function($) {
	"use strict";
	
	// call when dom ready
	$(function() {

		/**
		 * Toggle class 'skip' to hide/show incomplete sections
		 * when click on its title (h3)
		 */
		//$('#wpdanceclaratheme_walkthrough_page .card.skip:not(.complete) h3').on('click', function(e) {



		/**
		 * Toggle class 'hide' to show/hide content of element .inside.hide
		 */
		$('#wpdanceclaratheme_walkthrough_page .card:has(.inside.hide) h3')
			.addClass('hndler')
			.on('click', function(e) {
				e.preventDefault();
				$(this).offsetParent('.card').find('.inside').toggleClass('hide');
				$(this).toggleClass('open');
			});



		var $cards_hide = $('#wpdanceclaratheme_walkthrough_page .card.hide-done');

		/**
		 * Toggle class 'hide-done' to hide/show sections 
		 * when click on button #wpdanceclaratheme_walkthrough_show_check_list
		 */ 
		$('#wpdanceclaratheme_walkthrough_show_check_list').on('click', function(e) {
			e.preventDefault();
			$cards_hide.toggleClass('hide-done');
			
		});

	});

})(jQuery);
