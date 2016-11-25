<?php

$config = array(
	'name' => __('Testimonial Author', 'wpdance-testimonial'),
	'base' => 'wpdance_gitem_testimonial_author',
	'icon' => 'vc_icon-vc-gitem-post-author', // @todo change icon ?
	'category' => __('Post', 'js_composer'),
	'params' => array_merge(array(
		array(
			'type' => 'checkbox',
			'heading' => __('Add link', 'wpdance-testimonial'),
			'param_name' => 'link',
			'value' => '',
			'description' => __('Add link to author? Show link only if testimonial\'s website is provided', 'wpdance-testimonial'),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'wpdance-testimonial' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' ),
		),
	), $custom_fonts_params, array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		),
	) ),
	'post_type' => Vc_Grid_Item_Editor::postType(),
)
