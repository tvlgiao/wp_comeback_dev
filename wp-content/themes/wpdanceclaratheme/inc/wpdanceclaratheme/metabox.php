<?php
namespace wpdanceclaratheme\metabox;


###########################################################################
# DEFINE WP HOOKS
###########################################################################

add_action('admin_init', __NAMESPACE__.'\\metabox_css');
add_action('cmb2_admin_init', __NAMESPACE__.'\\show_metabox_on_post_edit');
add_filter('cmb2-taxonomy_meta_boxes', __NAMESPACE__.'\\show_metabox_on_taxonomy_edit', 10, 1);



###########################################################################
# DEFINE HOOK CALLBACKS
###########################################################################

function metabox_css() {
	wp_enqueue_style('wpdanceclaratheme-metabox-style', get_template_directory_uri().'/css/metabox.css', array(), \wpdanceclaratheme\Config::VERSION);
}


/**
 * Show metabox on Post, Page admin edit
 *
 * Allow configure:
 * - Layout
 * - Show/Hide Title
 * - Custom Header
 * - Custom Footer
 */
function show_metabox_on_post_edit() {
	$cmb = new_cmb2_box(array(
		'id'           => 'wpdanceclaratheme_metabox1',
		'title'        => esc_html_x("Options", 'metabox', 'wpdanceclaratheme'),
		'object_types' => apply_filters('wpdanceclaratheme_metabox1_object_types', array('page', 'post')),
		'context'      => 'side',
		'priority'     => 'default',
		'show_names'   => true,
		'cmb_styles'   => true,
		'closed'       => false,
	));

	$cmb->add_field(array(
		'name'    => esc_html__("Page Layout", 'wpdanceclaratheme'),
		'desc'    => wp_kses(__("Layout of the single page. If not set (Default), it inherits values specified from <strong>Customizer &gt; Theme - Layout</strong>.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html),
		'id'      => 'wpdanceclaratheme_layout',
		'type'    => 'select',
		'options' => array(
			''              => esc_html__("Default", 'wpdanceclaratheme'),
			'sidebar-left'  => esc_html__("Left Sidebar", 'wpdanceclaratheme'),
			'sidebar-right' => esc_html__("Right Sidebar", 'wpdanceclaratheme'),
			'fullwidth'     => esc_html__("Full Width", 'wpdanceclaratheme'),
		)
	));

	$cmb->add_field(array(
		'name'    => esc_html__("Image Position", 'wpdanceclaratheme'),
		'desc'    => wp_kses(__("Position of the featured image show on single post and archive pages. If not set (Default), it inherits values specified from <strong>Customizer &gt; Theme - Layout</strong>.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html),
		'id'      => 'wpdanceclaratheme_image_position',
		'type'    => 'select',
		'options' => array(
			''       => esc_html__("Default", 'wpdanceclaratheme'),
			'left'   => esc_html__("Left", 'wpdanceclaratheme'),
			'right'  => esc_html__("Right", 'wpdanceclaratheme'),
			'center' => esc_html__("Center", 'wpdanceclaratheme'),
		)
	));

	$cmb->add_field(array(
		'name'    => esc_html__("Hide Title", 'wpdanceclaratheme'),
		'desc'    => esc_html__("Show or Hide post title on the single page", 'wpdanceclaratheme'),
		'id'      => 'wpdanceclaratheme_hide_title',
		'type'    => 'checkbox',
	));

	$cmb->add_field(array(
		'name'    => esc_html__("Custom Header", 'wpdanceclaratheme'),
		'desc'    => esc_html__("Specify a custom header for the single page", 'wpdanceclaratheme'),
		'id'      => 'wpdanceclaratheme_custom_header',
		'type'    => 'select',
		'options' => \wpdanceclaratheme\Helper\get_header_choices()
	));

	$cmb->add_field(array(
		'name'    => esc_html__("Custom Footer", 'wpdanceclaratheme'),
		'desc'    => esc_html__("Specify a custom footer for the single page", 'wpdanceclaratheme'),
		'id'      => 'wpdanceclaratheme_custom_footer',
		'type'    => 'select',
		'options' => \wpdanceclaratheme\Helper\get_footer_choices()
	));



	if (defined('WPDANCE_PORTFOLIO_PLUGIN')) {
		$cmb = new_cmb2_box(array(
			'id'           => 'wpdanceclaratheme_metabox_portfolio',
			'title'        => esc_html_x("Options", 'metabox', 'wpdanceclaratheme'),
			'object_types' => apply_filters('wpdanceclaratheme_metabox_portfolio_object_types', array('wpdance_portfolio')),
			'context'      => 'side',
			'priority'     => 'default',
			'show_names'   => true,
			'cmb_styles'   => true,
			'closed'       => false,
		));

		$cmb->add_field(array(
			'name'    => esc_html__("Image Style", 'wpdanceclaratheme'),
			'desc'    => esc_html__("Is featured image size portrail or landscape? If landscape, it takes 2 columns on archive pages. Default is portrait.", 'wpdanceclaratheme'),
			'id'      => 'wpdanceclaratheme_image_style',
			'type'    => 'select',
			'default' => 'portrait',
			'options' => array(
				'landscape' => esc_html__("Landscape", 'wpdanceclaratheme'),
				'portrait'  => esc_html__("Portrait", 'wpdanceclaratheme'),
			)
		));

		$cmb->add_field(array(
			'name'    => esc_html__("Page Layout", 'wpdanceclaratheme'),
			'desc'    => wp_kses(__("Layout of the single portfolio page. If not set (Default), it uses layout configured in <strong>Customizer &gt; Theme - Layout &gt; Portfolio</strong>.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html),
			'id'      => 'wpdanceclaratheme_layout',
			'type'    => 'select',
			'options' => array(
				''              => esc_html__("Default", 'wpdanceclaratheme'),
				'sidebar-left'  => esc_html__("Left Sidebar", 'wpdanceclaratheme'),
				'sidebar-right' => esc_html__("Right Sidebar", 'wpdanceclaratheme'),
				'fullwidth'     => esc_html__("Full Width", 'wpdanceclaratheme'),
			)
		));

		$cmb->add_field(array(
			'name'    => esc_html__("Hide Title", 'wpdanceclaratheme'),
			'desc'    => esc_html__("Show or Hide portfolio title on single portfolio page", 'wpdanceclaratheme'),
			'id'      => 'wpdanceclaratheme_hide_title',
			'type'    => 'checkbox',
		));

		$cmb->add_field(array(
			'name'    => esc_html__("Custom Header", 'wpdanceclaratheme'),
			'desc'    => esc_html__("Specify a custom header for the portfolio single page", 'wpdanceclaratheme'),
			'id'      => 'wpdanceclaratheme_custom_header',
			'type'    => 'select',
			'options' => \wpdanceclaratheme\Helper\get_header_choices()
		));

		$cmb->add_field(array(
			'name'    => esc_html__("Custom Footer", 'wpdanceclaratheme'),
			'desc'    => esc_html__("Specify a custom footer for the portfolio single page", 'wpdanceclaratheme'),
			'id'      => 'wpdanceclaratheme_custom_footer',
			'type'    => 'select',
			'options' => \wpdanceclaratheme\Helper\get_footer_choices()
		));
		
	}
}





if (!function_exists(__NAMESPACE__.'\\show_metaboxes_on_taxonomy_edit')):
/**
 * Show metabox (custom fields) on taxonomy admin edit page
 *
 * Show select box custom Page Layout (fullwidth, left, right...)
 * 
 * @param  array $metaboxes
 * @return array
 */
function show_metabox_on_taxonomy_edit(array $metaboxes) {
	$metaboxes['wpdanceclaratheme_metabox1_taxonomy'] = array(
		'id'           => 'wpdanceclaratheme_metabox1_taxonomy',
		'title'        => esc_html_x("Options", 'metabox', 'wpdanceclaratheme'),
		'object_types' => array('category', 'post_tag', 'wpdance_portfolio_category', 'wpdance_portfolio_skill', 'wpdance_portfolio_client'),
		'show_names'   => true,
		'cmb_styles'   => true,
		'fields'       => array(
			array(
				'name'    => esc_html__("Layout", 'wpdanceclaratheme'),
				'desc'    => '',
				'id'      => 'wpdanceclaratheme_layout',
				'type'    => 'select',
				'options' => array(
					''              => esc_html__("Default", 'wpdanceclaratheme'),
					'sidebar-left'  => esc_html__("Left Sidebar", 'wpdanceclaratheme'),
					'sidebar-right' => esc_html__("Right Sidebar", 'wpdanceclaratheme'),
					'fullwidth'     => esc_html__("Full Width", 'wpdanceclaratheme'),
				)
			),
		)
	);


	$metaboxes['wpdanceclaratheme_metabox2_taxonomy'] = array(
		'id'           => 'wpdanceclaratheme_metabox2_taxonomy',
		'title'        => esc_html_x("Options", 'metabox', 'wpdanceclaratheme'),
		'object_types' => array('category', 'post_tag', 'wpdance_portfolio_category', 'wpdance_portfolio_skill', 'wpdance_portfolio_client'),
		'show_names'   => true,
		'cmb_styles'   => true,
		'fields'       => array(
			array(
				'name'       => esc_html__("Columns", 'wpdanceclaratheme'),
				'desc'       => esc_html__("Number of columns (1 to 12). Please empty for default value configured in Customizer.", 'wpdanceclaratheme'),
				'id'         => 'wpdanceclaratheme_col',
				'default'    => '',
				'type'       => 'text_small',
				'attributes' => array(
					'type' => 'number',
					'min'  => 1,
					'max'  => 12,
					'step' => 1,
				),
			),
			array(
				'name'    => esc_html__("Image Position", 'wpdanceclaratheme'),
				'desc'    => '',
				'id'      => 'wpdanceclaratheme_image_position',
				'type'    => 'select',
				'options' => array(
					''          => esc_html__("Default", 'wpdanceclaratheme'),
					'left'      => esc_html__("Left", 'wpdanceclaratheme'),
					'right'     => esc_html__("Right", 'wpdanceclaratheme'),
					'center'    => esc_html__("Center", 'wpdanceclaratheme'),
					'leftright' => esc_html__("Odd Left & Even Right", 'wpdanceclaratheme'),
					'rightleft' => esc_html__("Odd Right & Even Left", 'wpdanceclaratheme'),
				)
			),
			array(
				'name'    => esc_html__("Masonry", 'wpdanceclaratheme'),
				'desc'    => '',
				'id'      => 'wpdanceclaratheme_masonry',
				'type'    => 'select',
				'options' => array(
					''    => esc_html__("Default", 'wpdanceclaratheme'),
					'yes' => esc_html__("Yes", 'wpdanceclaratheme'),
					'no'  => esc_html__("No", 'wpdanceclaratheme'))
			),
			array(
				'name'    => esc_html__("Excerpt?", 'wpdanceclaratheme'),
				'desc'    => esc_html__("Show posts exerpt instead of content on archive pages.", 'wpdanceclaratheme'),
				'id'      => 'wpdanceclaratheme_excerpt',
				'type'    => 'select',
				'options' => array(
					''    => esc_html__("Default", 'wpdanceclaratheme'),
					'yes' => esc_html__("Yes", 'wpdanceclaratheme'),
					'no'  => esc_html__("No", 'wpdanceclaratheme'))
			),
		)
	);


	return $metaboxes;
}
endif;

