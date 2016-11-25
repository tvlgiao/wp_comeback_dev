<?php

$shortcode_post_date = (array)WPBMap::getShortCode('vc_gitem_post_date');
$config = array_merge($shortcode_post_date, array(
	'name' => esc_html__('Comment Count', 'wpdanceclaratheme'),
	'base' => 'wpdanceclaratheme_gitem_post_comment_count',
	'category' => array(
		esc_html__( 'WPDanceClaraTheme', 'wpdanceclaratheme' )
	),
	'description' => esc_html__( 'Show number of comments of the post.', 'wpdanceclaratheme' ),
	'post_type' => Vc_Grid_Item_Editor::postType(),
));

return $config;