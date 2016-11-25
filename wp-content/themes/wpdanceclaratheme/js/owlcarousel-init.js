(function($) {
	"use strict";
	
	$(function() {
		$('.wpdanceclaratheme-owlcarousel').each(function() {
			var id = $(this).attr('id');
			
			var settings = window[id];
			
			var k;
			for (k in settings) {
			    if (settings.hasOwnProperty(k)) {
			        if (settings[k] === 'false' || settings[k] === '') {
			            settings[k] = false;
			        }
			        if (settings[k] === 'true') {
			            settings[k] = true;
			        }
			        if (typeof settings[k] === 'string') {
			            if (settings[k].indexOf(',') !== -1) {
			                settings[k] = settings[k].split(',');
			            }
			        }
			    }
			}
			
			if (typeof settings['responsiveBaseWidth'] != 'undefined' && settings['responsiveBaseWidth'] === 'window')
				settings['responsiveBaseWidth'] = window;
			
			
			/**
			 * apply owl carousel to:
			 * - woocommerce category products shortcode
			 * //- images gallery (grid type) shortcode
			 */
			$('.woocommerce > .products', this).owlCarousel(settings);

		});
	});
})(jQuery);
