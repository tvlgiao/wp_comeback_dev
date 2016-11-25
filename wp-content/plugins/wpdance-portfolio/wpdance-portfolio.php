<?php
/**
 * Plugin Name:  WPDance Portfolio
 * Plugin URI:   https://wordpress.org/plugins/wpdance-portfolio/
 * Description:  Add Portfolio post type support, portfolio shortcode and visual composer short code. Developed by wpdance.com.
 * Author:       tvlgiao
 * Author URI:   http://wpdance.com
 * Contributors: tvlgiao
 * Version:      1.0.0
 * Text Domain:  wpdance-portfolio
 * Domain Path:  /languages
 * License:      Commercial License
 * License URI:  license.txt
 */

namespace WPDance\Plugins\Portfolio;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!defined('WPDANCE_PORTFOLIO_PLUGIN'))
	define('WPDANCE_PORTFOLIO_PLUGIN', true);

require_once dirname(__FILE__).'/inc/cmb2_post_search_field/cmb2_post_search_field.php';


add_action('init', __NAMESPACE__.'\\init');
add_action('plugins_loaded', __NAMESPACE__.'\\plugins_loaded');
add_action('cmb2_admin_init', __NAMESPACE__.'\\cmb2_admin_init');
add_filter('vc_check_post_type_validation', __NAMESPACE__.'\\vc_check_post_type_validation', 10, 2);


if (!function_exists(__NAMESPACE__.'\\plugins_loaded')):
function plugins_loaded() {
	load_plugin_textdomain( 'wpdance-portfolio', false, dirname(__FILE__).'/languages/' );
}
endif;


if (!function_exists(__NAMESPACE__.'\\init')):
function init() {

	register_post_type('wpdance_portfolio', array(
		'labels'        => array(
			'name'               => __( 'Portfolios', 'wpdance-portfolio' ),
			'singular_name'      => __( 'Portfolio', 'wpdance-portfolio' ),
			'add_new'            => __( 'Add New', 'wpdance-portfolio' ),
			'add_new_item'       => __( 'Add New Portfolio', 'wpdance-portfolio' ),
			'edit_item'          => __( 'Edit Portfolio', 'wpdance-portfolio' ),
			'new_item'           => __( 'New Portfolio', 'wpdance-portfolio' ),
			'view_item'          => __( 'View Portfolio', 'wpdance-portfolio' ),
			'search_items'       => __( 'Search Portfolios', 'wpdance-portfolio' ),
			'not_found'          => __( 'No Portfolio(s) found', 'wpdance-portfolio' ),
			'not_found_in_trash' => __( 'No Portfolio(s) found in the Trash', 'wpdance-portfolio' ), 
			'parent_item_colon'  => '',
			'menu_name'          => __("Portfolios", 'wpdance-portfolio'),
		),
		'menu_position' => 7,
		'supports'      => array('title', 'editor', 'thumbnail'),
		'public'        => true,
		'has_archive'   => true,
	));

	register_taxonomy('wpdance_portfolio_category', array('wpdance_portfolio'), array(
		'public'       => true,
		'hierarchical' => true,
		'labels'       => array(
			'name'                       => __("Portfolio Category", 'wpdance-portfolio'),
			'singular_name'              => __("Category", 'wpdance-portfolio'),
			'search_items'               => __("Search Categories", 'wpdance-portfolio'),
			'popular_items'              => __("Popular Categories", 'wpdance-portfolio'),
			'all_items'                  => __("All Categories", 'wpdance-portfolio'),
			'parent_item'                => __("Parent Category", 'wpdance-portfolio'),
			'parent_item_colon'          => __("Parent Category:", 'wpdance-portfolio'),
			'edit_item'                  => __("Edit Category", 'wpdance-portfolio'),
			'view_item'                  => __("View Category", 'wpdance-portfolio'),
			'update_item'                => __("Update Category", 'wpdance-portfolio'),
			'add_new_item'               => __("Add New Category", 'wpdance-portfolio'),
			'new_item_name'              => __("New Category Name", 'wpdance-portfolio'),
			'separate_items_with_commas' => __("Separate categories with commas", 'wpdance-portfolio'),
			'add_or_remove_items'        => __("Add or remove categories", 'wpdance-portfolio'),
			'choose_from_most_used'      => __("Choose from the most used categories", 'wpdance-portfolio'),
			'not_found'                  => __("No categories found.", 'wpdance-portfolio'),
			'no_terms'                   => __("No categories", 'wpdance-portfolio'),
			'items_list_navigation'      => __("Categories list navigation", 'wpdance-portfolio'),
			'items_list'                 => __("Categories list", 'wpdance-portfolio'),
			'menu_name'                  => __("Categories", 'wpdance-portfolio')
		),
	));

	register_taxonomy('wpdance_portfolio_client', array('wpdance_portfolio'), array(
		'public' => true,
		'labels' => array(
			'name'                       => __("Portfolio Client", 'wpdance-portfolio'),
			'singular_name'              => __("Client", 'wpdance-portfolio'),
			'search_items'               => __("Search Clients", 'wpdance-portfolio'),
			'popular_items'              => __("Popular Clients", 'wpdance-portfolio'),
			'all_items'                  => __("All Clients", 'wpdance-portfolio'),
			'parent_item'                => __("Parent Client", 'wpdance-portfolio'),
			'parent_item_colon'          => __("Parent Client:", 'wpdance-portfolio'),
			'edit_item'                  => __("Edit Client", 'wpdance-portfolio'),
			'view_item'                  => __("View Client", 'wpdance-portfolio'),
			'update_item'                => __("Update Client", 'wpdance-portfolio'),
			'add_new_item'               => __("Add New Client", 'wpdance-portfolio'),
			'new_item_name'              => __("New Client Name", 'wpdance-portfolio'),
			'separate_items_with_commas' => __("Separate clients with commas", 'wpdance-portfolio'),
			'add_or_remove_items'        => __("Add or remove clients", 'wpdance-portfolio'),
			'choose_from_most_used'      => __("Choose from the most used clients", 'wpdance-portfolio'),
			'not_found'                  => __("No clients found.", 'wpdance-portfolio'),
			'no_terms'                   => __("No clients", 'wpdance-portfolio'),
			'items_list_navigation'      => __("Clients list navigation", 'wpdance-portfolio'),
			'items_list'                 => __("Clients list", 'wpdance-portfolio'),
			'menu_name'                  => __("Clients", 'wpdance-portfolio')
		),
	));

	register_taxonomy('wpdance_portfolio_skill', array('wpdance_portfolio'), array(
		'public' => true,
		'labels' => array(
			'name'                       => __("Portfolio Skill", 'wpdance-portfolio'),
			'singular_name'              => __("Skill", 'wpdance-portfolio'),
			'search_items'               => __("Search Skills", 'wpdance-portfolio'),
			'popular_items'              => __("Popular Skills", 'wpdance-portfolio'),
			'all_items'                  => __("All Skills", 'wpdance-portfolio'),
			'parent_item'                => __("Parent Skill", 'wpdance-portfolio'),
			'parent_item_colon'          => __("Parent Skill:", 'wpdance-portfolio'),
			'edit_item'                  => __("Edit Skill", 'wpdance-portfolio'),
			'view_item'                  => __("View Skill", 'wpdance-portfolio'),
			'update_item'                => __("Update Skill", 'wpdance-portfolio'),
			'add_new_item'               => __("Add New Skill", 'wpdance-portfolio'),
			'new_item_name'              => __("New Skill Name", 'wpdance-portfolio'),
			'separate_items_with_commas' => __("Separate skills with commas", 'wpdance-portfolio'),
			'add_or_remove_items'        => __("Add or remove skills", 'wpdance-portfolio'),
			'choose_from_most_used'      => __("Choose from the most used skills", 'wpdance-portfolio'),
			'not_found'                  => __("No skills found.", 'wpdance-portfolio'),
			'no_terms'                   => __("No skills", 'wpdance-portfolio'),
			'items_list_navigation'      => __("Skills list navigation", 'wpdance-portfolio'),
			'items_list'                 => __("Skills list", 'wpdance-portfolio'),
			'menu_name'                  => __("Skills", 'wpdance-portfolio')
		),
	));

	add_image_size('wpdance-portfolio-thumbnail', 220, 265, array('center', 'center'));
	add_image_size('wpdance-portfolio-thumbnail-landscape', 450, 265, array('center', 'center'));
	add_image_size('wpdance-portfolio', 550, 0, false);
}
endif;




if (!function_exists(__NAMESPACE__.'\\cmb2_admin_init')):
function cmb2_admin_init() {

	$cmb = new_cmb2_box(array(
		'id'           => 'wpdance_portfolio_more_images_box',
		'title'        => __("More Images", 'wpdance-portfolio'),
		'object_types' => array('wpdance_portfolio'),
		'context'      => 'side',
		'priority'     => 'low',
		'cmb_styles'   => false,
		'closed'       => false,
		'show_names'   => false,
	));

	$cmb->add_field(array(
		'name' => __("More Images", 'wpdance-portfolio'),
		'desc' => '',
		'id'   => 'wpdance_portfolio_more_images',
		'type' => 'file_list',
	));

	$cmb = new_cmb2_box(array(
		'id'           => 'wpdance_portfolio_related_box',
		'title'        => __("Related Posts", 'wpdance-portfolio'),
		'object_types' => array('wpdance_portfolio'),
		'context'      => 'side',
		'priority'     => 'low',
		'cmb_styles'   => false,
		'closed'       => false,
		'show_names'   => false,
	));

	$cmb->add_field(array(
		'name'            => __("Related Portfolios", 'wpdance-portfolio'),
		'desc'            => __("Choose related portolio to show on single portfolio post.", 'wpdance-portfolio'),
		'id'              => 'wpdance_portfolio_related_posts',
		'type'            => 'post_search_text',
		'post_type'       => 'wpdance_portfolio',
		'select_type'     => 'checkbox', // 'checkbox' or 'radio'
		'select_behavior' => 'add', // 'add' or 'replace'
	));
}
endif;




if (!function_exists(__NAMESPACE__.'\\vc_check_post_type_validation')):
/**
 * Callback for filter vc_check_post_type_validation
 *
 * Always enable VC Editor for post type 'wpdance_portfolio'
 *
 * @param null/boolean $value
 * @param string $type Post Type
 *
 * @return true/null return true if post type is 'wpdance_portfolio'
 */
function vc_check_post_type_validation($value, $type) {
	if ($type == 'wpdance_portfolio')
		return true;

	return $value;
}
endif;


