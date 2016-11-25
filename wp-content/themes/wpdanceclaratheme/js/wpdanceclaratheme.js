;(function($) {
	"use strict";

	window.wpdanceclaratheme = {
		/**
		 * Declare a namespace
		 * 
		 * @param {String} ns namespace to declare. e.i wpdanceclaratheme.my.name.space
		 * @return {object}
		 */
		namespace: function (ns) {
			var a = ns.split('.');
			
			var parent = window;

			for (var i = 0; i < a.length; i++) {
				if (typeof parent[a[i]] == 'undefined')
					parent[a[i]] = {};
				parent = parent[a[i]];
			}

			return parent;
		}
	};
	wpdanceclaratheme.ns = wpdanceclaratheme.namespace;



	var imagepicker = wpdanceclaratheme.ns('wpdanceclaratheme.woocommerce.imagepicker');

	$.extend(imagepicker, {

		/**
		 * Default image URL used for 'choose an option' image option
		 * @type {String}
		 */
		BLANK_IMG_URL: '',

		/**
		 * Products list with ID and attributes
		 * @type {Object}
		 */
		products: {},


		/**
		 * Array contains name of attributes which are image options
		 * 
		 * @type {Array}
		 */
		image_attributes: [],

		/**
		 * Array contains name of attributes which are color options
		 * 
		 * @type {Array}
		 */
		color_attributes: [],

		/**
		 * URLs of attribute's image options
		 * 
		 * {
		 *     [attribute-name] : { 
		 *         [option-slug] : [image-url],
		 *         ...
		 *     },
		 *     ...
		 * }
		 * 
		 * @type {Object}
		 */
		image_option_urls_by_slug: {},


		/**
		 * Associated array of select element's selector to apply image options picker.
		 * 
		 * { 
		 *     [attribute-name] : [element selector string],
		 *     ...
		 * }
		 *     
		 * Example: { 'pa_color' : '#woocommerce_layered_nav-3' }
		 * 
		 * @type {Object}
		 */
		widget_selects: {},

	


		init_variation_image_options_attributes: function() {

			// Stop if no atribute configured as image options
			if (imagepicker.image_attributes.length == 0
			&& imagepicker.color_attributes.length == 0) return;

			// Fire when variations has changed
			$('.variations_form').on('woocommerce_variation_has_changed', function() {
				var $form = $(this);
				var product_id = $form.data('product_id');
				var restores = [];
				
				$form.find('.variations select').each(function(index, select) {
					var $select = $(select);
					var attribute_name = $(select).data('attribute_name') || $(select).attr('name');

					// Update select <option>s according to available variations
					$form.trigger('check_variations', [attribute_name, true]);

					attribute_name = attribute_name.replace(/^attribute_/, '');

					var is_img = imagepicker.image_attributes.indexOf(attribute_name) > -1;
					var is_color = imagepicker.color_attributes.indexOf(attribute_name) > -1;

					// Stop if attribute is not image/color options
					if (!is_img && !is_color 
					|| typeof imagepicker.products[product_id] == 'undefined' 
					|| typeof imagepicker.products[product_id]['attributes'][attribute_name] == 'undefined')
						return;

					// Add data-img-src / data-color to each <option> element used for image/color options picker
					$select.find('option').each(function(index, el) {
						var $option = $(el);
						var option = $option.val();
						var attr = imagepicker.products[product_id]['attributes'][attribute_name];
						var src = imagepicker.BLANK_IMG_URL;

						// Add data-color if attribute is color option
						if (is_color) {
							if (typeof attr[option] != 'undefined' && typeof attr[option]['color'] != 'undefined') {
								var val = attr[option]['color'];
								if (val.match(/^[a-f0-9]{3,6}$/i)) val = '#' + val;

								$option.data('color', val);
							}
							else
								$option.data('color', 'transparent');
						}

						// Add data-img-src if attribute is image option
						else if (is_img) {
							if (typeof attr[option] != 'undefined' && typeof attr[option]['img-src'] != 'undefined' && attr[option]['img-src'] != '')
								src = attr[option]['img-src'];

							$option.data('img-src', src);
						}
					});

					// Convert <select> element to image options picker
					$select.nextAll('.select2OptionPicker').remove();
					$select.select2OptionPicker();
					$select.nextAll('.select2OptionPicker')
						.addClass('wpdanceclaratheme-product-attribute-swatches')
						.addClass(is_color ? 'color-picker' : 'image-picker');

					restores.push($select);
				});

				// Restore options of all selects image options picker
				for (var i in restores) {
					var $select = restores[i];
					$select.find('option:gt(0)').remove();
					$select.append( $select.data( 'attribute_options' ) );
				}

			});

		},

		init_widgets_image_options: function() {
			$.each(imagepicker.widget_selects, function(attribute, selector) {

				var is_color = imagepicker.color_attributes.indexOf(attribute) > -1;
				var is_image = imagepicker.image_attributes.indexOf(attribute) > -1;
				var $select = $(selector).find('select');

				$select.find('option').each(function() {
					var $option = $(this);

					if (is_color) {
						var val = $option.val();
						if (!val)
							val = 'transparent';
						else if (val.match(/^[a-f0-9]{3,6}$/i))
							val = '#' + val;

						$option.data('color', val);
					}

					if (is_image) {
						var val = $option.val();

						if (typeof imagepicker.image_option_urls_by_slug[attribute] != 'undefined' 
						&& typeof imagepicker.image_option_urls_by_slug[attribute][val] != 'undefined')
							$(this).data('img-src', imagepicker.image_option_urls_by_slug[attribute][val]);

						else
							$(this).data('img-src', imagepicker.BLANK_IMG_URL);
					}
				})

				$select.select2OptionPicker().nextAll('.select2OptionPicker')
					.addClass('wpdanceclaratheme-product-attribute-swatches')
					.addClass( is_color ? 'color-picker' : '')
					.addClass( is_image ? 'image-picker' : '')
			});
		}
	});


	// Call when DOM ready
	$(function() {
		imagepicker.init_variation_image_options_attributes();
		imagepicker.init_widgets_image_options();
	});

})(jQuery);
