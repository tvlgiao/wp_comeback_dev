<?php
/**
 * Plugin Name:  WPDance Testimonial
 * Plugin URI:   https://wordpress.org/plugins/wpdance-testimonial/
 * Description:  Add Testimonial post type support, testimonial shortcode and visual composer short code. Developed by wpdance.com.
 * Author:       tvlgiao
 * Author URI:   http://wpdance.com
 * Contributors: tvlgiao
 * Version:      1.0.0
 * Text Domain:  wpdance-testimonial
 * Domain Path:  /languages
 * License:      Commercial License
 * License URI:  license.txt
 */

namespace WPDance\Plugins\Testimonial;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// require_once dirname(__FILE__).'/visual-composer-support.php';

add_action('init', __NAMESPACE__.'\\init');
add_action('plugins_loaded', __NAMESPACE__.'\\plugins_loaded');
add_action('cmb2_admin_init', __NAMESPACE__.'\\cmb2_admin_init');



if (!function_exists(__NAMESPACE__.'\\plugins_loaded')):
function plugins_loaded() {
	load_plugin_textdomain( 'wpdance-testimonial', false, dirname(__FILE__).'/languages/' );
}
endif;


if (!function_exists(__NAMESPACE__.'\\init')):
function init() {

	register_post_type('wpdance_testimonial', array(
		'labels' => array(
			'name'                  	=> __( 'Testimonials', 'wpdance-testimonial' ),
			'singular_name'         	=> __( 'Testimonial', 'wpdance-testimonial' ),
			'add_new'               	=> __( 'Add New', 'wpdance-testimonial' ),
			'add_new_item'          	=> __( 'Add New Testimonial', 'wpdance-testimonial' ),
			'edit_item'             	=> __( 'Edit Testimonial', 'wpdance-testimonial' ),
			'new_item'              	=> __( 'New Testimonial', 'wpdance-testimonial' ),
			'view_item'             	=> __( 'View Testimonial', 'wpdance-testimonial' ),
			'search_items'          	=> __( 'Search Testimonials', 'wpdance-testimonial' ),
			'not_found'             	=> __( 'No Testimonial(s) found', 'wpdance-testimonial' ),
			'not_found_in_trash'    	=> __( 'No Testimonial(s) found in the Trash', 'wpdance-testimonial' ), 
			'parent_item_colon'     	=> '',
			'menu_name'             	=> __("Testimonials", 'wpdance-testimonial'),
		),
		'description'           => __("Customer Testimonials", 'wpdance-testimonial'),
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 7,
		'supports'              => array('title', 'editor', 'thumbnail'),
		// 'public' => true,
	));

	register_taxonomy('wpdance_testimonial_category', array('wpdance_testimonial'), array(
		'public'                => false,
		'hierarchical'          => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_admin_column'     => true,
	));
}
endif;




if (!function_exists(__NAMESPACE__.'\\cmb2_admin_init')):
function cmb2_admin_init() {

	$cmb = new_cmb2_box(array(
		'id'                 => 'wpdance_testimonial_additional',
		'title'              => __("Additional Information", 'wpdance-testimonial'),
		'object_types'       => array('wpdance_testimonial'),
		'cmb_styles'         => true,
		'closed'             => false,
	));

	$cmb->add_field(array(
		'name'       => __("Author Name", 'wpdance-testimonial'),
		'desc'       => '',
		'id'         => 'wpdance_testimonial_additional_author',
		'type'       => 'text_medium',
	));

	$cmb->add_field(array(
		'name'       => __("Job Title", 'wpdance-testimonial'),
		'desc'       => '',
		'id'         => 'wpdance_testimonial_additional_jobtitle',
		'type'       => 'text_medium',
	));

	$cmb->add_field(array(
		'name'       => __("Website URL", 'wpdance-testimonial'),
		'desc'       => '',
		'id'         => 'wpdance_testimonial_additional_website',
		'type'       => 'text_url',
	));

}
endif;
