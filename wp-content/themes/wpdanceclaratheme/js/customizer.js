(function($, api) {
	"use strict";

	// --------------------------------------------------------------------------------------------
	// FONT CONTROL 
	// --------------------------------------------------------------------------------------------


	/**
	 * Class wp.customize.WpdanceclarathemeFontControl
	 *
	 * Javascript handle for php class wpdanceclaratheme\Customizer\Font_Control
	 * @class
	 */
	api.WpdanceclarathemeFontControl = api.Control.extend({
		ready: function() {
			var control = this,
				select_el = this.container.find('select');

			// set current value
			select_el.val(this.setting.get()).change();

			// update setting on change
			select_el.on('change', function() {
				control.setting.set($(this).val());
			});

			// update control when setting is change
			this.setting.bind(function(value) {
				select_el.val(value);
			});
		}
	});

	// register Font control
	api.controlConstructor.wpdanceclaratheme_font = api.WpdanceclarathemeFontControl;



	// --------------------------------------------------------------------------------------------
	// HTMLBLOCK CONTROL
	// --------------------------------------------------------------------------------------------


	api.WpdanceclarathemeHtmlblockControl = api.Control.extend({
		ready: function() {
			var $select = this.container.find('select').hide(),

				$img = this.container.find('.wpdanceclaratheme-htmlblock-preview img')
						.wrap('<a class="option-handler" href="#"></a>')
						.filter('[data-post-id=' + $select.val() + ']').parent('a').addClass('select'),

				$a = this.container.find('a.option-handler')
					.on('click', function(e) {
						e.preventDefault();
						
						var id = $(this).children('img').data('post-id');
						$select.val(id).change();


						$a.removeClass('select');
						$(this).addClass('select');
					});

		}
	});

	api.controlConstructor.wpdance_htmlblock = api.WpdanceclarathemeHtmlblockControl;



	// --------------------------------------------------------------------------------------------
	// MULTIPLE SELECT CONTROL
	// --------------------------------------------------------------------------------------------


	api.WpdanceclarathemeMultipleSelectControl = api.Control.extend({
		ready: function() {
			var control = this,
				$select = this.container.find('select');

			$select.on('change', function() {
				if ($(this).val() === null)
					control.setting.set([]);
			});

		}
	});

	api.controlConstructor.wpdanceclaratheme_multiselect = api.WpdanceclarathemeMultipleSelectControl;




	// --------------------------------------------------------------------------------------------
	// PRESET CONTROL 
	// --------------------------------------------------------------------------------------------


	/**
	 * Class wp.customize.WpdanceclarathemePresetControl
	 *
	 * Javascript handle for php class wpdanceclaratheme\Customizer\Preset_Control
	 * @class
	 */
	api.WpdanceclarathemePresetControl = api.Control.extend({
		ready: function() {
			var control = this,
				select_el = this.container.find('select');

				// update all typography & color controls which associate
				// with scss preset variables
				select_el.on('change', function() {
					var v = this.value;
					var presets = control.params.presets_vars;

					if (typeof presets == 'object' && typeof presets[v] == 'object') {
						var preset = presets[v];

						for (var name in preset) {
							var ctl = api.control('wpdanceclaratheme_' + name);
							if (typeof ctl == 'object') {
								ctl.setting('default').set(preset[name]);
							}
						}
					}
				});
		}
	});

	// register Preset Control
	api.controlConstructor.wpdanceclaratheme_preset = api.WpdanceclarathemePresetControl;






	// --------------------------------------------------------------------------------------------
	// GROUP CONTROL
	// --------------------------------------------------------------------------------------------

	//
	// Bind event after panels are reflowed
	// 
	api.bind('pane-contents-reflowed', function() {
		
		$('#customize-theme-controls .customize-control-wpdanceclaratheme_group').each(function() {

			// Append controls into groups
			$(this).children('.customize-control-content').append(
				$(this).nextUntil('.customize-control-wpdanceclaratheme_group, :not(.customize-control)', '.customize-control')
			);

			// Collapse/Expand group when its title is clicked
			$(this).children('.customize-control-title')
				.off('click', group_control_click)
				.on('click', group_control_click);
		});

	});


	function group_control_click() {
		$(this).parent().toggleClass('open');
	}



	// --------------------------------------------------------------------------------------------
	// IMPORT CONTROL 
	// --------------------------------------------------------------------------------------------


	// dom ready
	$(function() {
		//
		// Handle scripts for each customize-control-wpdanceclaratheme_import
		//
		$('.customize-control-wpdanceclaratheme_import').each(function() {

			var $file    = $('input[type=file]', this),
				$form    = $('<form method="POST" enctype="multipart/form-data" style="display:none"></form>'),
				$fields  = $('.form-fields', this),
				$msg     = $('.uploading-msg', this).hide();

			$('.submit-button', this).on('click', function(e) {
				e.preventDefault();
				
				if ($file.val() == '')
					alert("Please select a file to upload."); // TODO localize text

				else {
					$(window).off('beforeunload');
					$('body').append($form);
					$form.append($fields);
					$msg.show();
					$form.submit();
				}

			});
		});
	});



	// --------------------------------------------------------------------------------------------
	// OTHERS
	// --------------------------------------------------------------------------------------------

	$(function() {
		setTimeout(function() {
			
			//
			// Apply select2 js for all styling controls, color attributes, image attributes controls
			// 

			api.panel('wpdanceclaratheme_style').container.find('select').select2();

			$.each(['wpdanceclaratheme_layout_wc_color_attributes', 'wpdanceclaratheme_layout_wc_image_attributes'], function(i, setting) {
				if (api.control(setting))
					api.control(setting).container.find('select').select2({
						tags: true, 
						allowClear: true,
						placeholder: ''	,
						createTag: function(params) {
							var term = $.trim(params.term);
							if (term === '') return null;
							return { id: term, text: term };
						}
					});
			});

		}, 5000);

	});

})(jQuery, wp.customize);
