<?php
/**
 * Return config of shortcode wpdanceclaratheme_countdown_vc use for visual composer clean map.
 */

if (!defined('ABSPATH')) die('-1');


return array(
	'name'                    => esc_html__("Countdown", 'wpdanceclaratheme'),
	'base'                    => 'wpdanceclaratheme_countdown_vc',
	'description'             => esc_html__("Show countdown box using jQuery.", 'wpdanceclaratheme'),
	'category'                => esc_html__("WPDanceClaraTheme", 'wpdanceclaratheme'),
	'show_settings_on_create' => true,
	'params'                  => array(
		array(
			'type'        => 'textfield',
			'heading'     => esc_html_x("Countdown To", 'countdown shortcode', 'wpdanceclaratheme' ),
			'param_name'  => 'value',
			'group'       => esc_html__( 'Settings', 'wpdanceclaratheme'),
			'std'         => date('Y-m-d H:i:s', time() + 356*24*60*60),
			'description' => esc_html__("Countdown until reach this value. Format: YYYY-MM-DD HH:II:SS", 'wpdanceclaratheme')
        ),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html_x("Hide Weeks", 'countdown shortcode', 'wpdanceclaratheme' ),
			'param_name'  => 'hide_weeks',
			'group'       => esc_html__( 'Settings', 'wpdanceclaratheme'),
			'description' => esc_html__("If check, don't show weeks box", 'wpdanceclaratheme')
        ),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html_x("Hide Days", 'countdown shortcode', 'wpdanceclaratheme' ),
			'param_name'  => 'hide_days',
			'group'       => esc_html__( 'Settings', 'wpdanceclaratheme'),
			'description' => esc_html__("If check, don't show days box", 'wpdanceclaratheme')
        ),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html_x("Hide Hours", 'countdown shortcode', 'wpdanceclaratheme' ),
			'param_name'  => 'hide_hr',
			'group'       => esc_html__( 'Settings', 'wpdanceclaratheme'),
			'description' => esc_html__("If check, don't show hours box", 'wpdanceclaratheme')
        ),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html_x("Hide Minutes", 'countdown shortcode', 'wpdanceclaratheme' ),
			'param_name'  => 'hide_min',
			'group'       => esc_html__( 'Settings', 'wpdanceclaratheme'),
			'description' => esc_html__("If check, don't show minutes box", 'wpdanceclaratheme')
        ),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html_x("Hide Seconds", 'countdown shortcode', 'wpdanceclaratheme' ),
			'param_name'  => 'hide_sec',
			'group'       => esc_html__( 'Settings', 'wpdanceclaratheme'),
			'description' => esc_html__("If check, don't show seconds box", 'wpdanceclaratheme')
        ),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__("CSS Class", 'wpdanceclaratheme' ),
			'param_name'  => 'class',
			'group'       => esc_html__( 'Settings', 'wpdanceclaratheme'),
			'description' => esc_html__("Extra CSS classes add to the element", 'wpdanceclaratheme')
        ),

        array(
			'type'       => 'css_editor',
			'heading'    => esc_html__("CSS box", 'wpdanceclaratheme'),
			'param_name' => 'css',
			'group'      => esc_html__("Design Options", 'wpdanceclaratheme')
		)
	),
);
