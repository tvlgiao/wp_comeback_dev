<?php
namespace WPDance\Plugins\Testmonial\VC;


add_action('vc_before_init', __NAMESPACE__.'\\vc_before_init');

if (!function_exists('vc_before_init')):
function vc_before_init() {

	# Stop if Visual Composer is not activated
	if (!function_exists('visual_composer')) return;


	$path = dirname(__FILE__).'/vc_shortcodes';

	#
	# Lean map all shortcodes
	#

	vc_lean_map('wpdance_gitem_testimonial_author', null, $path.'/wpdance_gitem_testimonial_author.config.php');
	// vc_lean_map('wpdance_gitem_testimonial_jobtitle', null, $path.'/wpdance_gitem_testimonial_jobtitle.config.php');
	// vc_lean_map('wpdance_gitem_testimonial_website', null, $path.'/wpdance_gitem_testimonial_website.config.php');


	#
	# Require all VC shortcode classes
	#

	require_once $path.'/wpdance_gitem_testimonial_author.class.php';
	// require_once $path.'/wpdance_gitem_testimonial_jobtitle.class.php';
	// require_once $path.'/wpdance_gitem_testimonial_website.class.php';
}
endif;
