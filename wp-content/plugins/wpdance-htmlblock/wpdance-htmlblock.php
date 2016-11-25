<?php
/**
 * Plugin Name:  WPDance HTMLBlock
 * Plugin URI:   https://wordpress.org/plugins/wpdance-htmlblock/
 * Description:  Add HTMLBlock post type, allow to edit content using Visual Composer to embed into any page. Developed by wpdance.com.
 * Author:       tvlgiao
 * Author URI:   http://wpdance.com
 * Contributors: tvlgiao
 * Version:      1.0.0
 * Text Domain:  wpdance-htmlblock
 * Domain Path:  /languages
 * License:      Commercial License
 * License URI:  license.txt
 */

namespace WPDance\Plugins\htmlblock;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!defined('WPDANCE_HTMLBLOCK'))
	define('WPDANCE_HTMLBLOCK', 1);

###########################################################################
# DEFINE WP HOOKS
###########################################################################

add_action('init', __NAMESPACE__.'\\init');
add_action('plugins_loaded', __NAMESPACE__.'\\plugins_loaded');
add_action('cmb2_admin_init', __NAMESPACE__.'\\cmb2_admin_init');


###########################################################################
# DEFINE WP FILTERS
###########################################################################

add_filter('vc_check_post_type_validation', __NAMESPACE__.'\\vc_check_post_type_validation', 10, 2);



###########################################################################
# DEFINE HOOK CALLBACK FUNCTIONS
###########################################################################

if (!function_exists(__NAMESPACE__.'\\init')):
/**
 * Callback function for WP Hook 'init'
 */
function init() {

	# Register post type: wpdance_htmlblock
	register_post_type('wpdance_htmlblock', array(
		'labels'      => array(
			'name'          => __("HTML Blocks", 'wpdance-htmlblock'),
			'singular_name' => __("HTML Block", 'wpdance-htmlblock')
		),
		'public'      => true,
		'has_archive' => false,
		'supports'    => array('title', 'editor'),
	));
}
endif;



if (!function_exists(__NAMESPACE__.'\\plugins_loaded')):
/**
 * Callback when plugin is loaded
 */
function plugins_loaded() {
	load_plugin_textdomain( 'wpdance-htmlblock', false, dirname(__FILE__).'/languages/' );
}
endif;



if (!function_exists(__NAMESPACE__.'\\cmb2_admin_init')):
/**
 * Callback function for hook 'cmb2_admin_init'
 *
 * Add more additional fields on add/edit post type htmlblock
 *
 * @param array $meta_boxes
 */
function cmb2_admin_init() {
	$cmb = new_cmb2_box(array(
		'id'                 => 'wpdance_htmlblock_addtional',
		'title'              => __("Additional Information", 'wpdance-htmlblock'),
		'object_types'       => array('wpdance_htmlblock'),
		'cmb_styles'         => true,
		'closed'             => false,
	));

	$cmb->add_field(array(
		'name'       => __("Note", 'wpdance-htmlblock'),
		'desc'       => __("Add note to show on Customizer when this post is selected.", 'wpdance-htmlblock'),
		'id'         => 'wpdance_htmlblock_addtional_note',
		'type'       => 'textarea_small',
	));

	$cmb->add_field(array(
		'name'       => __("Preview Image", 'wpdance-htmlblock'),
		'desc'       => __("Preview image display on Customizer (Optional). If not specified it will show image at <code>[current-theme]/images/preview/[slug].jpg</code>.", 'wpdance-htmlblock'),
		'id'         => 'wpdance_htmlblock_addtional_preview',
		'type'       => 'file',
	));
}
endif;


###########################################################################
# DEFINE FILTER CALLBACK FUNCTIONS
###########################################################################




if (!function_exists(__NAMESPACE__.'\\vc_check_post_type_validation')):
/**
 * Callback for filter vc_check_post_type_validation
 *
 * Always enable VC Editor for post type 'wpdance_htmlblock'
 *
 * @param null/boolean $value
 * @param string $type Post Type
 *
 * @return true/null return true if post type is 'wpdance_htmlblock'
 */
function vc_check_post_type_validation($value, $type) {
	if ($type == 'wpdance_htmlblock')
		return true;

	return $value;
}
endif;

###########################################################################
# DEFINE OTHER FUNCTIONS
###########################################################################


if (!function_exists(__NAMESPACE__.'\\get_css')):
/**
 * Function get custom CSS of HTML Block in the head element
 *
 * @param integer $post_id Post ID
 * @return string CSS to add to the head tag
 */
function get_css($post_id) {
	$ret = '';
	
	/** code copied from Vc_Base::addPageCustomCss() */
	$post_custom_css = \get_post_meta( $post_id, '_wpb_post_custom_css', true );
	if ( ! empty( $post_custom_css ) )
		$ret .= '<style type="text/css" data-type="vc_custom-css">'.$post_custom_css.'</style>';
	
	/** code copied from Vc_Base::addShortcodesCustomCss() */
	$shortcodes_custom_css = \get_post_meta( $post_id, '_wpb_shortcodes_custom_css', true );
	if ( ! empty( $shortcodes_custom_css ) ) {
		$ret .= '<style type="text/css" data-type="vc_shortcodes-custom-css">'.$shortcodes_custom_css.'</style>';
	}
	
	return $ret;
}
endif;



/**
 * Retrieve array of 'wpdance_htmlblock' posts used in HTMLBlock_Control
 *
 * Return array of:
 * array(
 *   [id] => array(
 *      'title' => (string)
 *      'note'  => (string)
 *      'preview' => (string)
 *   )
 * )
 *
 * @see class HTMLBlock_Control
 * @param string $filter preg_match filter string
 * @return array
 */
function get_list($filter = '') {
	$list = array();

	$posts = new \WP_Query(array(
		'post_type' => 'wpdance_htmlblock',
		'post_status' => 'publish',
		'orderby' => 'post_title',
		'order' => 'ASC',
		'nopaging' => true
	));

	while ($posts->have_posts()) {
		$posts->the_post();
		$post = get_post();
		if ($filter == '' || preg_match($filter, $post->post_name)) {

			$preview = get_post_meta(get_the_ID(), 'wpdance_htmlblock_addtional_preview', true);
			if (empty($preview)) {
				if (file_exists(get_stylesheet_directory().'/images/preview/'.$post->post_name.'.jpg'))
					$preview = get_stylesheet_directory_uri().'/images/preview/'.$post->post_name.'.jpg';
				elseif (file_exists(get_template_directory().'/images/preview/'.$post->post_name.'.jpg'))
					$preview = get_template_directory_uri().'/images/preview/'.$post->post_name.'.jpg';
				else
					$preview = get_template_directory_uri().'/images/preview/noimg.jpg';
			}

			$list[$post->post_name] = array(
				'title'     => get_the_title(),
				'note'      => get_post_meta(get_the_ID(), 'wpdance_htmlblock_addtional_note', true),
				'preview'   => $preview,
				'edit_link' => add_query_arg('post', get_the_ID(), network_admin_url('post.php?action=edit')),
			);
		}

	}

	wp_reset_postdata();

	return $list;
}


/**
 * Return HTMLBlock ID by slug name
 * 
 * @param  string $slug slug name or post name
 * @return string       HTMLBlock ID
 */
function get_post_id_by_slug($slug) {
	$posts = get_posts(array(
		'name'        => $slug,
		'post_type'   => 'wpdance_htmlblock',
		'post_status' => 'publish',
		'numberposts' => 1
	));
	if (!empty($posts))
		return $posts[0]->ID;

	return null;
}
