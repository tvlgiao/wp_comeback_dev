<?php


global $vc_gitem_add_link_param;

$my_gitem_add_link_param = $vc_gitem_add_link_param;
$my_gitem_add_link_param['value'][esc_html__("Meta field", 'wpdanceclaratheme')] = 'meta_key';


$post_data_params = array(
	$my_gitem_add_link_param,
	array(
		'type' => 'vc_link',
		'heading' => esc_html__( 'URL (Link)', 'wpdanceclaratheme' ),
		'param_name' => 'url',
		'dependency' => array(
			'element' => 'link',
			'value' => array( 'custom' ),
		),
		'description' => esc_html__( 'Add custom link.', 'wpdanceclaratheme' ),
	),
	array(
		'type' => 'textfield',
		'heading' => esc_html__("Field key name for link", 'wpdanceclaratheme'),
		'param_name' => 'key_url',
		'dependency' => array(
			'element' => 'link',
			'value' => 'meta_key',
		),
		'description' => esc_html__( 'Enter custom field name to retrieve meta data value to make link.', 'wpdanceclaratheme' ),
	),
	array(
		'type' => 'css_editor',
		'heading' => esc_html__( 'CSS box', 'wpdanceclaratheme' ),
		'param_name' => 'css',
		'group' => esc_html__( 'Design Options', 'wpdanceclaratheme' ),
	),
);
$custom_fonts_params = array(
	array(
		'type' => 'font_container',
		'param_name' => 'font_container',
		'value' => '',
		'settings' => array(
			'fields' => array(
				'tag' => 'div', // default value h2
				'text_align',
				'tag_description' => esc_html__( 'Select element tag.', 'wpdanceclaratheme' ),
				'text_align_description' => esc_html__( 'Select text alignment.', 'wpdanceclaratheme' ),
				'font_size_description' => esc_html__( 'Enter font size.', 'wpdanceclaratheme' ),
				'line_height_description' => esc_html__( 'Enter line height.', 'wpdanceclaratheme' ),
				'color_description' => esc_html__( 'Select color for your element.', 'wpdanceclaratheme' ),
			),
		),
	),
	array(
		'type' => 'checkbox',
		'heading' => esc_html__( 'Use custom fonts?', 'wpdanceclaratheme' ),
		'param_name' => 'use_custom_fonts',
		'value' => array( esc_html__( 'Yes', 'wpdanceclaratheme' ) => 'yes' ),
		'description' => esc_html__( 'Enable Google fonts.', 'wpdanceclaratheme' ),
	),
	array(
		'type' => 'font_container',
		'param_name' => 'block_container',
		'value' => '',
		'settings' => array(
			'fields' => array(
				'font_size',
				'line_height',
				'color',
				'tag_description' => esc_html__( 'Select element tag.', 'wpdanceclaratheme' ),
				'text_align_description' => esc_html__( 'Select text alignment.', 'wpdanceclaratheme' ),
				'font_size_description' => esc_html__( 'Enter font size.', 'wpdanceclaratheme' ),
				'line_height_description' => esc_html__( 'Enter line height.', 'wpdanceclaratheme' ),
				'color_description' => esc_html__( 'Select color for your element.', 'wpdanceclaratheme' ),
			),
		),
		'group' => esc_html__( 'Custom fonts', 'wpdanceclaratheme' ),
		'dependency' => array(
			'element' => 'use_custom_fonts',
			'value' => array( 'yes' ),
		),
	),
	array(
		'type' => 'checkbox',
		'heading' => esc_html__( 'Yes theme default font family?', 'wpdanceclaratheme' ),
		'param_name' => 'use_theme_fonts',
		'value' => array( esc_html__( 'Yes', 'wpdanceclaratheme' ) => 'yes' ),
		'description' => esc_html__( 'Yes font family from the theme.', 'wpdanceclaratheme' ),
		'group' => esc_html__( 'Custom fonts', 'wpdanceclaratheme' ),
		'dependency' => array(
			'element' => 'use_custom_fonts',
			'value' => array( 'yes' ),
		),
	),
	array(
		'type' => 'google_fonts',
		'param_name' => 'google_fonts',
		'value' => '',
		// Not recommended, this will override 'settings'. 'font_family:'.rawurlencode('Exo:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic').'|font_style:'.rawurlencode('900 bold italic:900:italic'),
		'settings' => array(
			'fields' => array(
				// Default font style. Name:weight:style, example: "800 bold regular:800:normal"
				'font_family_description' => esc_html__( 'Select font family.', 'wpdanceclaratheme' ),
				'font_style_description' => esc_html__( 'Select font styling.', 'wpdanceclaratheme' ),
			),
		),
		'group' => esc_html__( 'Custom fonts', 'wpdanceclaratheme' ),
		'dependency' => array(
			'element' => 'use_theme_fonts',
			'value_not_equal_to' => 'yes',
		),
	),
);

$config = array(
	'name' => esc_html__('Advanced Custom Field', 'wpdanceclaratheme'),
	'base' => 'wpdanceclaratheme_gitem_post_meta_advanced',
	'icon' => 'vc_icon-vc-gitem-post-meta',
	'category' => array(
		esc_html__( 'WPDanceClaraTheme', 'wpdanceclaratheme' )
	),
	'description' => esc_html__( 'Advanced custom fields data from meta values of the post.', 'wpdanceclaratheme' ),
	'params' => array_merge(array(
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Field key name', 'wpdanceclaratheme' ),
			'param_name' => 'key',
			'description' => esc_html__( 'Enter custom field name to retrieve meta data value.', 'wpdanceclaratheme' ),
		),
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Label', 'wpdanceclaratheme' ),
			'param_name' => 'label',
			'description' => esc_html__( 'Enter label to display before key value.', 'wpdanceclaratheme' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Alignment', 'wpdanceclaratheme' ),
			'param_name' => 'align',
			'value' => array(
				esc_html__( 'Left', 'wpdanceclaratheme' ) => 'left',
				esc_html__( 'Right', 'wpdanceclaratheme' ) => 'right',
				esc_html__( 'Center', 'wpdanceclaratheme' ) => 'center',
				esc_html__( 'Justify', 'wpdanceclaratheme' ) => 'justify',
			),
			'description' => esc_html__( 'Select alignment.', 'wpdanceclaratheme' ),
		),
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Extra class name', 'wpdanceclaratheme' ),
			'param_name' => 'el_class',
			'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wpdanceclaratheme' ),
		),
	), $post_data_params, $custom_fonts_params),
	'post_type' => Vc_Grid_Item_Editor::postType(),
);

return $config;


