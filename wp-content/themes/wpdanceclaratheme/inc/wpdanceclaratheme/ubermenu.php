<?php
namespace wpdanceclaratheme\UberMenu;

# Stop if plugin UberMenu is not activated
if (!defined('UBERMENU_VERSION')) return;




###########################################################################
# DEFINE WP HOOKS
###########################################################################

add_action('init', __NAMESPACE__.'\\init_callback');
add_action('admin_init', __NAMESPACE__.'\\enqueue_scripts');

add_action('after_setup_theme', __NAMESPACE__.'\\custom_icons', 20);

# NOTICE: Disable UberMenu showing on Customize 
# because it takes much time to generate thousands of settings!!!
remove_action( 'customize_register', 'ubermenu_register_theme_customizers' );


###########################################################################
# DEFINE HOOK CALLBACK FUNCTIONS
###########################################################################

if (!function_exists(__NAMESPACE__.'\\init_callback')):
/**
 * Callback function for WP Hook 'init'
 */
function init_callback() {
	
	/**
	 * If UberMenu plugin is installed, add our custom menu styles css
	 */
	if (function_exists('ubermenu_register_skin')) {
		$main = get_template_directory_uri() . '/ubermenu/assets/css/skins/';
		ubermenu_register_skin('wpdanceclaratheme-style01' ,"Wpdanceclaratheme Style01" , $main.'wpdanceclaratheme-style01.css');
	}
}
endif;




if (!function_exists(__NAMESPACE__.'\\enqueue_scripts')):
/**
 * Enqueue scripts & styles to support UberMenu plugin
 * 
 * @return [type] [description]
 */
function enqueue_scripts() {
	wp_enqueue_style('wpdanceclaratheme-icons', get_template_directory_uri().'/css/icons.css', array(), \wpdanceclaratheme\Config::VERSION);
}
endif;



if (!function_exists(__NAMESPACE__.'\\custom_icons')):
/**
 * Hook to add more icons for UberMenu plugin
 * 
 * @return [type] [description]
 */
function custom_icons() {
	if (!function_exists('ubermenu_register_icons')) return;

	ubermenu_register_icons('wpdanceclaratheme', array(
		'title'        => "WPDanceClaraTheme", 
		'class_prefix' => 'wpdanceclaratheme-icon-',
		'iconmap'      => array(
			'infinity' => array(
				'title' => "Infinity",
			),
			'filter' => array(
				'title' => "Filter",
			),
			'heart-o' => array(
				'title' => "Heart O",
			),
			'heart' => array(
				'title' => "Heart",
			),
			'palette' => array(
				'title' => "Palette",
			),
			'teaser' => array(
				'title' => "Teaser",
			),
			'about' => array(
				'title' => "About",
			),
			'contact' => array(
				'title' => "Contact",
			),
			'visual-composer' => array(
				'title' => "Visual Composer",
			),
			'woocommerce' => array(
				'title' => "Woocommerce",
			),
			'preset' => array(
				'title' => "Preset",
			),
			'sass' => array(
				'title' => "Sass",
			),
			'bootstrap' => array(
				'title' => "Bootstrap",
			),
			'faq' => array(
				'title' => "Faq",
			),
			'team-member' => array(
				'title' => "Team Member",
			),
			'price-table' => array(
				'title' => "Price Table",
			),
			'custom-header' => array(
				'title' => "Custom Header",
			),
			'fullwidth' => array(
				'title' => "Fullwidth",
			),
			'leftsidebar' => array(
				'title' => "Leftsidebar",
			),
			'rightsidebar' => array(
				'title' => "Rightsidebar",
			),
			'apps' => array(
				'title' => "Apps",
			),
			'photolib' => array(
				'title' => "Photolib",
			),
			'error' => array(
				'title' => "Error",
			),
			'font' => array(
				'title' => "Font",
			),
			'color' => array(
				'title' => "Color",
			),
			'font2' => array(
				'title' => "Font2",
			),
			'help' => array(
				'title' => "Help",
			),
			'help2' => array(
				'title' => "Help2",
			),
			'menu' => array(
				'title' => "Menu",
			),
			'store' => array(
				'title' => "Store",
			),
			'subtitles' => array(
				'title' => "Subtitles",
			),
			'tune' => array(
				'title' => "Tune",
			),
			'vibration' => array(
				'title' => "Vibration",
			),
			'view-agenda' => array(
				'title' => "View Agenda",
			),
			'view-array' => array(
				'title' => "View Array",
			),
			'view-carousel' => array(
				'title' => "View Carousel",
			),
			'view-column' => array(
				'title' => "View Column",
			),
			'view-comfy' => array(
				'title' => "View Comfy",
			),
			'view-compact' => array(
				'title' => "View Compact",
			),
			'view-day' => array(
				'title' => "View Day",
			),
			'view-headline' => array(
				'title' => "View Headline",
			),
			'view-list' => array(
				'title' => "View List",
			),
			'view-module' => array(
				'title' => "View Module",
			),
			'view-quilt' => array(
				'title' => "View Quilt",
			),
			'view-stream' => array(
				'title' => "View Stream",
			),
			'view-week' => array(
				'title' => "View Week",
			),
			'wallpaper' => array(
				'title' => "Wallpaper",
			),
			'web' => array(
				'title' => "Web",
			),
			'testimonial' => array(
				'title' => "Testimonial",
			),
			'eye' => array(
				'title' => "Eye",
			),
			'settings' => array(
				'title' => "Settings",
			),
			'infinity2' => array(
				'title' => "Infinity2",
			),


		)
	));
}
endif;



