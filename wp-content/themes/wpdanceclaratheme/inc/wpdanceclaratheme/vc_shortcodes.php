<?php
namespace wpdanceclaratheme\VC_Shortcodes;

# Stop if Visual Composer is activated
if (!function_exists('visual_composer')) return;



# Hook action 'vc_before_init' visual composer init 
add_action('vc_before_init', __NAMESPACE__.'\\vc_before_init');


if (!function_exists(__NAMESPACE__.'\\vc_before_init')):
/**
 * Callback function for action hook 'vc_before_init'
 *
 * Function is called when before visual composer init
 */
function vc_before_init() {

	$path = get_template_directory().'/inc/wpdanceclaratheme/vc_shortcodes';


	##########################################################################
	# LEAN MAP ALL OUR CUSTOM VC SHORTCODES HERE
	##########################################################################
	
	vc_lean_map('wpdanceclaratheme_single_image_hover', null, $path.'/wpdanceclaratheme_single_image_hover.config.php');
	vc_lean_map('wpdanceclaratheme_gitem_post_meta_advanced', null, $path.'/wpdanceclaratheme_gitem_post_meta_advanced.config.php');
	vc_lean_map('wpdanceclaratheme_gitem_post_date_advanced', null, $path.'/wpdanceclaratheme_gitem_post_date_advanced.config.php');
	vc_lean_map('wpdanceclaratheme_gitem_post_comment_count', null, $path.'/wpdanceclaratheme_gitem_post_comment_count.config.php');
	vc_lean_map('wpdanceclaratheme_countdown_vc', null, $path.'/wpdanceclaratheme_countdown_vc.config.php');


	##########################################################################
	# REQUIRE ALL OUR CUSTOM VC SHORTCODES CLASSES HERE
	##########################################################################

	require_once $path.'/wpdanceclaratheme_single_image_hover.class.php';
	require_once $path.'/wpdanceclaratheme_gitem_post_meta_advanced.class.php';
	require_once $path.'/wpdanceclaratheme_gitem_post_date_advanced.class.php';
	require_once $path.'/wpdanceclaratheme_gitem_post_comment_count.class.php';
	require_once $path.'/wpdanceclaratheme_countdown_vc.class.php';


	# create link if option 'meta_key' in param 'link' is selected 
	# in VC shortcode 'wpdanceclaratheme_gitem_post_meta_advanced'
	# of VC Grid Builder
	add_filter('vc_gitem_post_data_get_link_link', __NAMESPACE__.'\\vc_gitem_post_data_get_link_link', 10, 3);


	# Show grid item Advanced Post Date's value with date formatted
	add_filter( 'vc_gitem_template_attribute_wpdanceclaratheme_post_date_advanced', __NAMESPACE__.'\\vc_gitem_template_attribute_wpdanceclaratheme_post_date_advanced', 10, 2 );


	# Show grid item comment count's value
	add_filter( 'vc_gitem_template_attribute_wpdanceclaratheme_post_comment_count', __NAMESPACE__.'\\vc_gitem_template_attribute_wpdanceclaratheme_post_comment_count', 10, 2 );
}
endif;



if (!function_exists(__NAMESPACE__.'\\vc_gitem_post_data_get_link_link')):
/**
 * Callback for filter 'vc_gitem_post_data_get_link_link'
 *
 * Check if 'link' field is 'meta_key' - our custom select option, 
 * load link from specified meta field value.
 * 
 * @param string $link
 * @param array $atts
 * @param string $css_class
 *
 * @return string new link
 */
function vc_gitem_post_data_get_link_link($link, $atts, $css_class) {
	if (isset($atts['link']) && $atts['link'] == 'meta_key' && isset($atts['key_url']) && $atts['key_url'] != '') {
		$link = 'a href="{{ post_meta_value:' . esc_attr($atts['key_url']). ' }}" class="' . esc_attr($css_class) .'"';
	}

	return $link;
}
endif;



if (!function_exists(__NAMESPACE__.'\\vc_gitem_template_attribute_wpdanceclaratheme_post_date_advanced')):
/**
 * Get post date
 *
 * @param $data
 *
 * @return bool|int|string
 */
function vc_gitem_template_attribute_wpdanceclaratheme_post_date_advanced( $value, $data ) {

	/**
	 * @var null|Wp_Post $post ;
	 */
	extract( array_merge( array(
		'post' => null,
	), $data ) );
	
	$atts = array();
	parse_str( $data, $atts );

	return get_the_date( $atts['date_format'], $post->ID );
}
endif;



if (!function_exists(__NAMESPACE__.'\\vc_gitem_template_attribute_wpdanceclaratheme_post_comment_count')):
/**
 * Get post date
 *
 * @param $data
 *
 * @return bool|int|string
 */
function vc_gitem_template_attribute_wpdanceclaratheme_post_comment_count( $value, $data ) {
	return get_comments_number_text();
}
endif;