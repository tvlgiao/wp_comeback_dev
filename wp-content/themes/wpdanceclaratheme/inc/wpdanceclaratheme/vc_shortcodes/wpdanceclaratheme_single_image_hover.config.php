<?php
/**
 * Return config of shortcode wpdanceclaratheme_single_image_hover use for visual composer clean map.
 * Almost parameters inherited from the shortcode vs_single_image.
 */

if (!defined('ABSPATH')) die('-1');

$config = include(vc_path_dir('CONFIG_DIR') . '/content/shortcode-vc-single-image.php');

$config = array_merge($config, array(
	'name'         => esc_html__("Banner Image", 'wpdanceclaratheme'),
	'base'         => 'wpdanceclaratheme_single_image_hover',
	'category'     => esc_html__("WPDanceClaraTheme", 'wpdanceclaratheme'),
	'description'  => esc_html__("Show banner image with button, text and hover effects", 'wpdanceclaratheme'),
));


$config['params'] = array_merge($config['params'], array(
	array(
		'type'              => 'textarea_html',
		'heading'           => esc_html__("Text", 'wpdanceclaratheme'),
		'param_name'        => 'content',
		'holder'            => 'div',
		'group'             => esc_html__("Extras", 'wpdanceclaratheme'),
		'description'       => esc_html__("Enter content text to show on the banner.", 'wpdanceclaratheme'),
	),
	array(
		'type'              => 'textfield',
		'heading'           => esc_html__("Button Text", 'wpdanceclaratheme'),
		'param_name'        => 'button_text',
		'group'             => esc_html__("Extras", 'wpdanceclaratheme'),
		'description'       => esc_html__("Enter button text to show button on banner. Leave empty to hide button.", 'wpdanceclaratheme'),
	),
	array(
		'type'              => 'href',
		'heading'           => esc_html__("Button Link", 'wpdanceclaratheme'),
		'param_name'        => 'button_link',
		'group'             => esc_html__("Extras", 'wpdanceclaratheme'),
		'description'       => esc_html__("Enter URL link to page when click on the button.", 'wpdanceclaratheme'),
	),
	array(
		'type'              => 'textfield',
		'heading'           => esc_html__("Button CSS Class", 'wpdanceclaratheme'),
		'param_name'        => 'button_class',
		'group'             => esc_html__("Extras", 'wpdanceclaratheme'),
		'description'       => esc_html__("Extra CSS class for button, appending to the default class 'btn'. Example: btn-primary", 'wpdanceclaratheme'),
		'std'               => 'btn-primary',
	),
	array(
		'type'              => 'dropdown',
		'heading'           => esc_html__("Banner Style", 'wpdanceclaratheme'),
		'param_name'        => 'banner_style',
		'group'             => esc_html__("Extras", 'wpdanceclaratheme'),
		'description'       => esc_html__("Choose predefined style for the banner.", 'wpdanceclaratheme'),
		'value'             => 	\wpdanceclaratheme\Helper\translate_array_keys(\wpdanceclaratheme\Config::$banner_styles),
	),
	array(
		'type'              => 'dropdown',
		'heading'           => esc_html__("Hover Effect", 'wpdanceclaratheme'),
		'param_name'        => 'hover_effect',
		'group'             => esc_html__("Extras", 'wpdanceclaratheme'),
		'description'       => esc_html__("Choose animation effect when mouse hover the image.", 'wpdanceclaratheme'),
		'value'             => 	\wpdanceclaratheme\Helper\translate_array_keys(\wpdanceclaratheme\Config::$banner_hover_effects),
	),
));
return $config;
