<?php

# Stop if Visual Composer is activated
if (!function_exists('visual_composer')) return;

# Stop if wpdanceclaratheme-toolkit plugin is not activate
if (!defined('WPDANCECLARATHEME_TOOLKIT')) return;

# Hook action 'vc_before_init' visual composer init 
add_action('vc_before_init', 'wpdanceclaratheme_vc_map_shortcodes');



if (!function_exists('wpdanceclaratheme_vc_map_shortcodes')):
/**
 * Add theme's custom shortcodes to Visual Composer
 */
function wpdanceclaratheme_vc_map_shortcodes() {

	

	# Add shortcode Site Header
	vc_map(array(
		'name' => esc_html__("Site Header", 'wpdanceclaratheme'),
		'base' => 'wpdanceclaratheme_site_header',
		'description' => esc_html__("Display site info (title, tagline, logo...) on the header", 'wpdanceclaratheme'),
		'class' => '',
		'show_settings_on_create' => false,
		'category' => esc_html__("WPDanceClaraTheme", 'wpdanceclaratheme'),
		'params' => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__("Custom Logo URL", 'wpdanceclaratheme'),
				'description' => esc_html__("Specify a custom logo URL to replace default logo configured in the customizer.", 'wpdanceclaratheme'),
				'admin_label' => true,
				'param_name'  => 'logo',
			),

			array(
				'type'        => 'textfield',
				'heading'     => esc_html__("Extra class name", 'wpdanceclaratheme'),
				'description' => esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wpdanceclaratheme'),
				'admin_label' => true,
				'param_name'  => 'class',
			),
		)
	));
	
	# Add shortcode User Links
	vc_map(array(
		'name' => esc_html__("User Links", 'wpdanceclaratheme'),
		'base' => 'wpdanceclaratheme_user_links',
		'description' => esc_html__("Display user's links (login, logout, register...)", 'wpdanceclaratheme'),
		'class' => '',
		'show_settings_on_create' => false,
		'category' => esc_html__("WPDanceClaraTheme", 'wpdanceclaratheme'),
		'params' => array(
			array(
				'type' => 'textfield',
				'class' => '',
				'heading' => esc_html__("Extra class name", 'wpdanceclaratheme'),
				'description' => esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wpdanceclaratheme'),
				'admin_label' => true,
				'param_name' => 'class',
				'value' => ''
			),
			array(
				'type' => 'textfield',
				'class' => '',
				'heading' => esc_html__("Login icon CSS class", 'wpdanceclaratheme'),
				'description' => wp_kses(__("CSS classes for Login icon. You can find icon class at <a href='http://fontawesome.io/icons/'>fontawesome.io</a>.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html),
				'admin_label' => true,
				'param_name' => 'login_icon_class',
				'value' => ''
			),
			array(
				'type' => 'textfield',
				'class' => '',
				'heading' => esc_html__("Logout icon CSS class", 'wpdanceclaratheme'),
				'description' => wp_kses(__("CSS classes for Logout icon. You can find icon class at <a href='http://fontawesome.io/icons/'>fontawesome.io</a>.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html),
				'admin_label' => true,
				'param_name' => 'logout_icon_class',
				'value' => ''
			),
			array(
				'type' => 'textfield',
				'class' => '',
				'heading' => esc_html__("Register icon CSS class", 'wpdanceclaratheme'),
				'description' => wp_kses(__("CSS classes for Register icon. You can find icon class at <a href='http://fontawesome.io/icons/'>fontawesome.io</a>.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html),
				'admin_label' => true,
				'param_name' => 'register_icon_class',
				'value' => ''
			)
		)
	));



	

	#
	# Add shortcode Nav Menu
	#

	$choices = array();
	foreach (\wpdanceclaratheme\Config::$menu_locations as $name => $title) {
		$choices[] = array(
			'value' => $name,
			'label' => $title,
		);
	}

	vc_map(array(
		'name' => esc_html__("Navigation Menu", 'wpdanceclaratheme'),
		'base' => 'wpdanceclaratheme_nav_menu',
		'description' => esc_html__("Display navigation menu", 'wpdanceclaratheme'),
		'class' => '',
		'show_settings_on_create' => true,
		'category' => esc_html__("WPDanceClaraTheme", 'wpdanceclaratheme'),
		'params' => array(
			array(
				'type' => 'dropdown',
				'class' => '',
				'heading' => esc_html__("Theme Location", 'wpdanceclaratheme'),
				'description' => esc_html__("Chooice theme location contains menu to show up", 'wpdanceclaratheme'),
				'admin_label' => true,
				'param_name' => 'theme_location',
				'value' => $choices,
			),
			array(
				'type' => 'textfield',
				'class' => '',
				'heading' => esc_html__("Extra class name", 'wpdanceclaratheme'),
				'description' => esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wpdanceclaratheme'),
				'admin_label' => true,
				'param_name' => 'class',
				'value' => ''
			)
		)
	));




	
	if (class_exists('WooCommerce_Widget_DropdownCart')) {
		# Add shortcode dropdown cart
		vc_map(array(
			'name' => esc_html__("WooCommerce Dropdown Cart", 'wpdanceclaratheme'),
			'base' => 'wpdanceclaratheme_dropdowncart',
			'description' => esc_html__("Display the user's Cart in the sidebar.", 'wpdanceclaratheme'),
			'class' => '',
			'category' => esc_html__("WPDanceClaraTheme", 'wpdanceclaratheme'),
			'params' => array(
				array(
					'type' => 'textfield',
					'class' => '',
					'heading' => esc_html__("Title:", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name' => 'title',
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__("Hide if cart is empty", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name' => 'hide_if_empty'
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__("Show this widget on cart/checkout pages", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name' => 'show_on_checkout' 
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__("Popup align", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name' => 'popup_align',
					'value' => array(
						esc_html__("Left", 'wpdanceclaratheme') => 'left',
						esc_html__("Right", 'wpdanceclaratheme') => 'right',
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__("Style", 'wpdanceclaratheme'),
					'description' => esc_html__("Choose dropdown button style.", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name' => 'style',
					'value' => \wpdanceclaratheme\Helper\translate_array_keys(\wpdanceclaratheme\Config::$dropdown_cart_styles),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__("Extra class name", 'wpdanceclaratheme'),
					'description' => esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name' => 'class',
				)
			)
		));
	}



	
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
        class WPBakeryShortCode_wpdanceclaratheme_owlcarousel extends WPBakeryShortCodesContainer { 
		}
	}


	$defaults = wpdanceclaratheme_shortcode_owlcarousel_defaults();
	
	vc_map(array(
		'name' => esc_html__("Owl Carousel", 'wpdanceclaratheme'),
		'base' => 'wpdanceclaratheme_owlcarousel',
		'as_parent' => array('only' => 'product_category,vc_basic_grid'),
		'category' => esc_html__("WPDanceClaraTheme", 'wpdanceclaratheme'),
		'content_element' => true,
		'show_settings_on_create' => true,
		'is_container' => true,
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__("Number of Items", 'wpdanceclaratheme' ),
				'param_name' => 'items',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'std' => $defaults['items'],
				'value' => array(1, 2, 3, 4, 5, 6, 7, 8, 9),
				'description' => esc_html__("This variable allows you to set the maximum amount of items displayed at a time with the widest browser width. Default: 5", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("On Desktop", 'wpdanceclaratheme' ),
				'param_name' => 'itemsdesktop',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['itemsdesktop'],
				'description' => __("This allows you to preset the number of slides visible with a particular browser width. The format is [x,y] whereby x=browser width and y=number of slides displayed. For example [1199,4] means that if(window<=1199){ show 4 slides per page} Alternatively use itemsDesktop: false to override these settings. Default: 1199,4", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("On Small Desktop", 'wpdanceclaratheme' ),
				'param_name' => 'itemsdesktopsmall',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['itemsdesktopsmall'],
				'description' => esc_html__("As above.. Default: 979,3", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("On Tablet", 'wpdanceclaratheme' ),
				'param_name' => 'itemstablet',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['itemstablet'],
				'description' => esc_html__("As above.. Default: 768,2", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("On Small Tablet", 'wpdanceclaratheme' ),
				'param_name' => 'itemstabletsmall',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['itemstabletsmall'],
				'description' => esc_html__("As above.. Default: false", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("On Mobile", 'wpdanceclaratheme' ),
				'param_name' => 'itemsmobile',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['itemsmobile'],
				'description' => esc_html__("As above.. Default: 479,1", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("On Mobile", 'wpdanceclaratheme' ),
				'param_name' => 'itemscustom',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['itemscustom'],
				'description' => __("This allow you to add custom variations of items depending from the width If this option is set, itemsDeskop, itemsDesktopSmall, itemsTablet, itemsMobile etc. are disabled For better preview, order the arrays by screen size, but it's not mandatory Don't forget to include the lowest available screen size, otherwise it will take the default one for screens lower than lowest available. 
								Example:
								[[0, 2], [400, 4], [700, 6], [1000, 8], [1200, 10], [1600, 16]]
								", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__("Single Item", 'wpdanceclaratheme' ),
				'param_name' => 'singleitem',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'std' => $defaults['singleitem'],
				'description' => esc_html__("Display only one item. Default: false", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__("Item Scale Up", 'wpdanceclaratheme' ),
				'param_name' => 'itemsscaleup',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'std' => $defaults['itemsscaleup'],
				'description' => esc_html__("Option to not stretch items when it is less than the supplied items. Default: false", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Slide Speed", 'wpdanceclaratheme' ),
				'param_name' => 'slidespeed',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['slidespeed'],
				'description' => esc_html__("Slide speed in milliseconds. Default: 200", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Pagination Speed", 'wpdanceclaratheme' ),
				'param_name' => 'paginationspeed',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['paginationspeed'],
				'description' => esc_html__("Pagination speed in milliseconds. Default: 800", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Rewind Speed", 'wpdanceclaratheme' ),
				'param_name' => 'rewindspeed',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['rewindspeed'],
				'description' => esc_html__("Rewind speed in milliseconds. Default: 1000", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__("Stop On Hover", 'wpdanceclaratheme' ),
				'param_name' => 'stoponhover',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'std' => $defaults['stoponhover'],
				'description' => esc_html__("Stop autoplay on mouse hover. Default: false", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Show Navigation", 'wpdanceclaratheme' ),
				'param_name' => 'navigation',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['navigation'],
				'description' => esc_html__("Display 'next' and 'prev' buttons. Default: false", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Navigation Text", 'wpdanceclaratheme' ),
				'param_name' => 'navigationtext',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['navigationtext'],
				'description' => esc_html__("You can cusomize your own text for navigation. To get empty buttons use navigationText : false. Also HTML can be used here. Default: prev,next", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__("Rewind Nav", 'wpdanceclaratheme' ),
				'param_name' => 'rewindnav',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'std' => $defaults['rewindnav'],
				'description' => esc_html__("Slide to first item. Use rewindSpeed to change animation speed. Default: true", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Scroll Per Page", 'wpdanceclaratheme' ),
				'param_name' => 'scrollperpage',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['scrollperpage'],
				'description' => esc_html__("Scroll per page not per item. This affect next/prev buttons and mouse/touch dragging. Default: false", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__("Pagination", 'wpdanceclaratheme' ),
				'param_name' => 'pagination',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'std' => $defaults['pagination'],
				'description' => esc_html__("Show pagination. Default: true", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__("Pagination Numbers", 'wpdanceclaratheme' ),
				'param_name' => 'paginationnumbers',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'std' => $defaults['paginationnumbers'],
				'description' => esc_html__("Show numbers inside pagination buttons. Default: false", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__("Responsive", 'wpdanceclaratheme' ),
				'param_name' => 'responsive',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'std' => $defaults['responsive'],
				'description' => esc_html__("You can use Owl Carousel on desktop-only websites too! Just change that to 'false' to disable responsive capabilities. Default: true", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Responsive Refresh Rate", 'wpdanceclaratheme' ),
				'param_name' => 'responsiverefreshrate',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['responsiverefreshrate'],
				'description' => esc_html__("Check window width changes every 200ms for responsive actions. Default: 200", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Responsive Base Width", 'wpdanceclaratheme' ),
				'param_name' => 'responsivebasewidth',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['responsivebasewidth'],
				'description' => esc_html__("Owl Carousel check window for browser width changes. You can use any other jQuery element to check width changes for example '.owl-demo'. Owl will change only if '.owl-demo' get new width. Default: window", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Base Class", 'wpdanceclaratheme' ),
				'param_name' => 'baseclass',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['baseclass'],
				'description' => esc_html__("Automaticly added class for base CSS styles. Best not to change it if you don't need to. Default: owl-carousel", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Theme", 'wpdanceclaratheme' ),
				'param_name' => 'theme',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['theme'],
				'description' => esc_html__("Default Owl CSS styles for navigation and buttons. Change it to match your own theme. Default: owl-theme", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__("Lazy Load", 'wpdanceclaratheme' ),
				'param_name' => 'lazyload',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'std' => $defaults['lazyload'],
				'description' => esc_html__("Delays loading of images. Images outside of viewport won't be loaded before user scrolls to them. Great for mobile devices to speed up page loadings. IMG need special markup class='lazyOwl' and data-src='your img path'. Default: false", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Lazy Follow", 'wpdanceclaratheme' ),
				'param_name' => 'lazyfollow',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['lazyfollow'],
				'description' => esc_html__("When pagination used, it skips loading the images from pages that got skipped. It only loads the images that get displayed in viewport. If set to false, all images get loaded when pagination used. It is a sub setting of the lazy load function. Default: true", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Lazy Effect", 'wpdanceclaratheme' ),
				'param_name' => 'lazyeffect',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['lazyeffect'],
				'description' => esc_html__("Default is fadeIn on 400ms speed. Use false to remove that effect. Default: false", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__("Auto Height", 'wpdanceclaratheme' ),
				'param_name' => 'autoheight',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'std' => $defaults['autoheight'],
				'description' => esc_html__("Add height to owl-wrapper-outer so you can use diffrent heights on slides. Use it only for one item per page setting. Default: false", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Json Path", 'wpdanceclaratheme' ),
				'param_name' => 'jsonpath',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['jsonpath'],
				'description' => esc_html__("Allows you to load directly from a jSon file. The JSON structure you use needs to match the owl JSON structure used here. To use custom JSON structure see jsonSuccess option. Default: false", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Json Success", 'wpdanceclaratheme' ),
				'param_name' => 'jsonsuccess',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['jsonsuccess'],
				'description' => esc_html__("Success callback for $.getJSON build in into carousel. See demo with custom JSON structure. Default: false", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__("Drag Before Anim Finish", 'wpdanceclaratheme' ),
				'param_name' => 'dragbeforeanimfinish',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'std' => $defaults['dragbeforeanimfinish'],
				'description' => esc_html__("Ignore whether a transition is done or not (only dragging). Default: true", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__("Mouse Drag", 'wpdanceclaratheme' ),
				'param_name' => 'mousedrag',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'std' => $defaults['mousedrag'],
				'description' => esc_html__("Turn off/on mouse events. Default: true", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__("Touch Drag", 'wpdanceclaratheme' ),
				'param_name' => 'touchdrag',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'std' => $defaults['touchdrag'],
				'description' => esc_html__("Turn off/on touch events. Default: true", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Add Class Active", 'wpdanceclaratheme' ),
				'param_name' => 'addclassactive',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['addclassactive'],
				'description' => esc_html__("Add 'active' classes on visible items. Works with any numbers of items on screen. Default: false", 'wpdanceclaratheme')
            ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__("Transition Style", 'wpdanceclaratheme' ),
				'param_name' => 'transitionstyle',
				'group' => esc_html__( 'Settings', 'wpdanceclaratheme'),
				'value' => $defaults['transitionstyle'],
				'description' => esc_html__("Add CSS3 transition style. Works only with one item on screen. Default: false", 'wpdanceclaratheme')
            ),
   			array(
				'type' => 'textfield',
				'heading' => esc_html__("Extra class name", 'wpdanceclaratheme'),
				'description' => esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wpdanceclaratheme'),
				'admin_label' => true,
				'param_name' => 'class',
			),
			
			
			
			
			array(
				'type' => 'css_editor',
				'heading' => esc_html__("CSS box", 'wpdanceclaratheme'),
				'param_name' => 'css',
				'group' => esc_html__("Design Options", 'wpdanceclaratheme')
			)
		),
		'js_view' => 'VcColumnView'
	));


	vc_map(array(
		'name'                    => esc_html__("TagTray - Instagram Photos Stream", 'wpdanceclaratheme'),
		'base'                    => 'wpdanceclaratheme_tagtray',
		'description'             => esc_html__("Show Instagram photos stream through TagTray services.", 'wpdanceclaratheme'),
		'category'                => esc_html__("WPDanceClaraTheme", 'wpdanceclaratheme'),
		'show_settings_on_create' => true,
		'params'                  => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_html_x("Gallery Code", 'tagtray shortcode', 'wpdanceclaratheme'),
				'param_name'  => 'gallery_code',
				'group'       => esc_html__( 'Configure', 'wpdanceclaratheme'),
				'description' => esc_html_x("(Required) Enter gallery code provided by TagTray", 'tagtray shortcode', 'wpdanceclaratheme')
			),

			array(
				'type'        => 'textfield',
				'heading'     => esc_html_x("Gallery Type", 'tagtray shortcode', 'wpdanceclaratheme'),
				'param_name'  => 'gallery_type',
				'group'       => esc_html__( 'Configure', 'wpdanceclaratheme'),
				'value'       => 'passive',
				'description' => esc_html_x("Gallery type 'active', 'passive' or 'carousel'.", 'tagtray shortcode', 'wpdanceclaratheme')
			),

			array(
				'type'        => 'textfield',
				'heading'     => esc_html_x("Overlay Type", 'tagtray shortcode', 'wpdanceclaratheme'),
				'param_name'  => 'overlay_type',
				'group'       => esc_html__( 'Configure', 'wpdanceclaratheme'),
				'value'       => 'none',
				'description' => esc_html_x("Overlay type 'message', 'likes' or 'none'.", 'tagtray shortcode', 'wpdanceclaratheme')
			),

			array(
				'type'        => 'textfield',
				'heading'     => esc_html_x("Carousel Slide Witdh", 'tagtray shortcode', 'wpdanceclaratheme'),
				'param_name'  => 'carousel_slide_width',
				'group'       => esc_html__( 'Configure', 'wpdanceclaratheme'),
				'description' => _x("Width of each slide in a carousel layout (default = 200)
", 'tagtray shortcode', 'wpdanceclaratheme')
			),

			array(
				'type'        => 'textfield',
				'heading'     => esc_html_x("Carousel Slide Max Height", 'tagtray shortcode', 'wpdanceclaratheme'),
				'param_name'  => 'carousel_slide_max_height',
				'group'       => esc_html__( 'Configure', 'wpdanceclaratheme'),
				'description' => _x("Maximum height of each slide in a carousel layout (default = 200)
", 'tagtray shortcode', 'wpdanceclaratheme')
			),

			array(
				'type'        => 'textfield',
				'heading'     => esc_html_x("Page Size", 'tagtray shortcode', 'wpdanceclaratheme'),
				'param_name'  => 'page_size',
				'group'       => esc_html__( 'Configure', 'wpdanceclaratheme'),
				'description' => _x("Number of images on each page of the gallery, up to a maximum of 30 (default = 24)
", 'tagtray shortcode', 'wpdanceclaratheme')
			),

			array(
				'type'        => 'checkbox',
				'heading'     => esc_html_x("Hide Source Icon", 'tagtray shortcode', 'wpdanceclaratheme'),
				'param_name'  => 'hide_source_icon',
				'group'       => esc_html__( 'Configure', 'wpdanceclaratheme'),
				'description' => _x("We'll display an icon over your gallery photo indicating the source of the photo (e.g. Instagram or Facebook) unless you select this option to hide that icon (default = false)
", 'tagtray shortcode', 'wpdanceclaratheme')
			),

			array(
				'type'        => 'checkbox',
				'heading'     => esc_html_x("Masonry Layout?", 'tagtray shortcode', 'wpdanceclaratheme'),
				'param_name'  => 'masonry',
				'group'       => esc_html__( 'Style', 'wpdanceclaratheme'),
				'description' => esc_html_x("Check if you want to layout with masonry js.", 'tagtray shortcode', 'wpdanceclaratheme')
			),

			array(
				'type'        => 'textfield',
				'heading'     => esc_html_x("CSS Class", 'tagtray shortcode', 'wpdanceclaratheme'),
				'param_name'  => 'el_class',
				'group'       => esc_html__( 'Style', 'wpdanceclaratheme'),
				'description' => esc_html_x("Add custom CSS classes to the element'.", 'tagtray shortcode', 'wpdanceclaratheme')
			),


		)
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
        class WPBakeryShortCode_wpdanceclaratheme_tagtray extends WPBakeryShortCode { 
		}
	}






	if (class_exists('F2_Tag_Cloud_Widget')) {

		# Code get taxonomy copied from vc_wp_tagcloud.php
		$tag_taxonomies = array();
		if ( 'vc_edit_form' === vc_post_param( 'action' ) && vc_verify_admin_nonce() ) {
			$taxonomies = get_taxonomies();
			if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {
				foreach ( $taxonomies as $taxonomy ) {
					$tax = get_taxonomy( $taxonomy );
					if ( ( is_object( $tax ) && ( ! $tax->show_tagcloud || empty( $tax->labels->name ) ) ) || ! is_object( $tax ) ) {
						continue;
					}
					$tag_taxonomies[ $tax->labels->name ] = esc_attr( $taxonomy );
				}
			}
		}

		# Add shortcode F2 Tag Cloud Widget
		vc_map(array(
			'name'        => esc_html__("F2 Tag Cloud Widget", 'wpdanceclaratheme'),
			'base'        => 'wpdanceclaratheme_f2tagcloud',
			'description' => esc_html__("Show tag cloud using widget F2 Tag Cloud for extra settings.", 'wpdanceclaratheme'),
			'class'       => '',
			'category'    => esc_html__("WPDanceClaraTheme", 'wpdanceclaratheme'),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'class'       => '',
					'heading'     => esc_html__("Title:", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name'  => 'title',
					'value'       => esc_html__("Tag", 'wpdanceclaratheme'),
				),
				array(
					'type'        => 'textfield',
					'class'       => '',
					'heading'     => esc_html__("Minimum tag size:", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name'  => 'smallest',
					'value'       => '8',
				),
				array(
					'type'        => 'textfield',
					'class'       => '',
					'heading'     => esc_html__("Maximum tag size:", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name'  => 'largest',
					'value'       => '22',
				),
				array(
					'type'        => 'textfield',
					'class'       => '',
					'heading'     => esc_html__("Maximum tag count (0 for no limit):", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name'  => 'number',
					'value'       => '0',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__("Tag cloud format:", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name'  => 'format',
					'value'       => array(
						esc_html__("Flat", 'wpdanceclaratheme') => 'flat',
						esc_html__("List", 'wpdanceclaratheme') => 'list',
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__("Order tags by:", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name'  => 'orderby',
					'value'       => array(
						esc_html__("Name", 'wpdanceclaratheme')  => 'name',
						esc_html__("Count", 'wpdanceclaratheme') => 'count',
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__("Tag order direction:", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name'  => 'order',
					'value'       => array(
						esc_html__("Ascending", 'wpdanceclaratheme')  => 'ASC',
						esc_html__("Descending", 'wpdanceclaratheme') => 'DESC',
						esc_html__("Random", 'wpdanceclaratheme')     => 'RAND',
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__("Tag cloud alignment:", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name'  => 'alignment',
					'value'       => array(
						esc_html__("Theme Default", 'wpdanceclaratheme') => 'default',
						esc_html__("Left", 'wpdanceclaratheme')          => 'left',
						esc_html__("Center", 'wpdanceclaratheme')        => 'center',
						esc_html__("Right", 'wpdanceclaratheme')         => 'right',
					),
				),
				array(
					'type'        => 'textfield',
					'class'       => '',
					'heading'     => esc_html__("Padding between tags:", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name'  => 'padding',
					'value'         => '0',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__("Taxonomy:", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name'  => 'taxonomy',
					'value'       => $tag_taxonomies,
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__("Extra class name", 'wpdanceclaratheme'),
					'description' => esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wpdanceclaratheme'),
					'admin_label' => true,
					'param_name' => 'class',
				)
			)
		));
	}

	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
        class WPBakeryShortCode_wpdanceclaratheme_f2tagcloud extends WPBakeryShortCode { 
		}
	}



}
endif;
