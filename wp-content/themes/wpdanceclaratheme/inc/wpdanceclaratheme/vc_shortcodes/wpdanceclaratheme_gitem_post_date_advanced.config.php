<?php

$shortcode_post_date = (array)WPBMap::getShortCode('vc_gitem_post_date');
$config = array_merge($shortcode_post_date, array(
	'name' => esc_html__('Advanced Post Date', 'wpdanceclaratheme'),
	'base' => 'wpdanceclaratheme_gitem_post_date_advanced',
	'category' => array(
		esc_html__( 'WPDanceClaraTheme', 'wpdanceclaratheme' )
	),
	'description' => esc_html__( 'Advanced post date for showing custom date format.', 'wpdanceclaratheme' ),
	'post_type' => Vc_Grid_Item_Editor::postType(),
));

$config['params'][] = array(
	'type' => 'textfield',
	'heading' => esc_html__( 'Date format', 'wpdanceclaratheme' ),
	'param_name' => 'date_format',
	'description' => wp_kses(__('Example: <code>M j</code> showing <code>Oct 2</code>, See php function date_format() for reference.', 'wpdanceclaratheme'), wpdanceclaratheme\Config::$allowed_html),
);

return $config;