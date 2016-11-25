<?php
namespace wpdanceclaratheme\TGMPA;


/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory().'/inc/class-tgm-plugin-activation.php';


add_action('tgmpa_register', __NAMESPACE__.'\\tgmpa_register');


if (!function_exists(__NAMESPACE__.'\\tgmpa_register')):
/**
 * Register the required plugins for this theme.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function tgmpa_register() {


	

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
    $config = array(
 		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
    );

    tgmpa( get_plugins(), $config );

}
endif;


if (!function_exists(__NAMESPACE__.'\\get_plugins')):
function get_plugins() {
	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		// Advanced Excerpt
		array(
			'name' => esc_html__("Advanced Excerpt", 'wpdanceclaratheme'),
			'slug' => 'advanced-excerpt',
		),

		// Better Font Awesome
		array(
			'name' => esc_html__("Better Font Awesome", 'wpdanceclaratheme'),
			'slug' => 'better-font-awesome',
		),

		// Breadcrumb NavXT
		array(
			'name' => esc_html__("Breadcrumb NavXT", 'wpdanceclaratheme'),
			'slug' => 'breadcrumb-navxt',
		),

		// CM Registration (or CM Invitation Codes)
		array(
			'name' => esc_html__("CM Registration", 'wpdanceclaratheme'),
			'slug' => 'cm-invitation-codes',
		),

		// CMB2
		array(
			'name' => esc_html__("CMB2", 'wpdanceclaratheme'),
			'slug' => 'cmb2',
		),

		// CMB2 Taxonomy
		array(
			'name' => esc_html__("CMB2 Taxonomy", 'wpdanceclaratheme'),
			'slug' => 'cmb2-taxonomy',
		),
		
		// Comments Widget Plus
		array(
			'name' => esc_html__("Comments Widget Plus", 'wpdanceclaratheme'),
			'slug' => 'comments-widget-plus',
		),

		// Contact Form 7
		array(
			'name' => esc_html__("Contact Form 7", 'wpdanceclaratheme'),
			'slug' => 'contact-form-7',
		),
		
		// jQuery Colorbox
		array(
			'name' => esc_html__("jQuery Colorbox", 'wpdanceclaratheme'),
			'slug' => 'jquery-colorbox',
		),		

		// Recent Posts Widget With Thumbnails
		array(
			'name' => esc_html__("Recent Posts Widget With Thumbnails", 'wpdanceclaratheme'),
			'slug' => 'recent-posts-widget-with-thumbnails',
		),

		// Slider Revolution
		array(
			'name'    => esc_html__("Slider Revolution", 'wpdanceclaratheme'),
			'slug'    => 'revslider',
			'source'  => get_template_directory() . '/plugins/revslider.zip',
			'version' => '5.2.6',
		),


		// Widget CSS Classes
		array(
			'name' => esc_html__("Widget CSS Classes", 'wpdanceclaratheme'),
			'slug' => 'widget-css-classes',
		),

		// WooCommerce
		array(
			'name' => esc_html__("WooCommerce", 'wpdanceclaratheme'),
			'slug' => 'woocommerce',
		),

		// WooCommerce Dropdown Cart
		array(
			'name' => esc_html__("WooCommerce Dropdown Cart", 'wpdanceclaratheme'),
			'slug' => 'woocommerce-dropdown-cart',
		),

		// WooCommerce Social Media Share Buttons
		array(
			'name' => esc_html__("WooCommerce Social Media Share Buttons", 'wpdanceclaratheme'),
			'slug' => 'woocommerce-social-media-share-buttons',
		),

		// WooSidebars
		array(
			'name' => esc_html__("WooSidebars", 'wpdanceclaratheme'),
			'slug' => 'woosidebars',
		),

		// WPBakery Visual Composer
		array(
			'name'    => esc_html__("WPBakery Visual Composer", 'wpdanceclaratheme'),
			'slug'    => 'js_composer',
			'source'  => get_template_directory() . '/plugins/js_composer.zip',
			'version' => '4.12.1',
		),

		// WPDance HTMLBlock
		array(
			'name'   => esc_html__("WPDance HTMLBlock", 'wpdanceclaratheme'),
			'slug'   => 'wpdance-htmlblock',
			'source' => get_template_directory() . '/plugins/wpdance-htmlblock.zip',
		),

		// WPDance Portfolio
		array(
			'name'   => esc_html__("WPDance Portfolio", 'wpdanceclaratheme'),
			'slug'   => 'wpdance-portfolio',
			'source' => get_template_directory() . '/plugins/wpdance-portfolio.zip',
		),

		// WPDance Testimonial
		array(
			'name'   => esc_html__("WPDance Testimonial", 'wpdanceclaratheme'),
			'slug'   => 'wpdance-testimonial',
			'source' => get_template_directory() . '/plugins/wpdance-testimonial.zip',
		),

		// WPDanceClaraTheme Toolkit
		array(
			'name'   => esc_html__("WPDanceClaraTheme Toolkit", 'wpdanceclaratheme'),
			'slug'   => 'wpdanceclaratheme-toolkit',
			'source' => get_template_directory() . '/plugins/wpdanceclaratheme-toolkit.zip',
		),

		// YITH WooCommerce Quick View
		array(
			'name' => esc_html__("YITH WooCommerce Quick View", 'wpdanceclaratheme'),
			'slug' => 'yith-woocommerce-quick-view',
		),

		// YITH WooCommerce Wishlist
		array(
			'name' => esc_html__("YITH WooCommerce Wishlist", 'wpdanceclaratheme'),
			'slug' => 'yith-woocommerce-wishlist',
		),
	);

	return $plugins;
}
endif;


