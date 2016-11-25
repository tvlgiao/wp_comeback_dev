<?php
/**
 * Print out shortcode wpdanceclaratheme_single_image_hover
 *
 * This script inherits almost code from vc_single_imge shortcode
 *
 * @see wp-content/plugins/js_composer/include/templates/shortcodes/vc_single_image.php
 *
 * Shortcode attributes:
 * @var $atts
 * @var $title
 * @var $source
 * @var $image
 * @var $custom_src
 * @var $onclick
 * @var $img_size
 * @var $external_img_size
 * @var $caption
 * @var $img_link_large
 * @var $link
 * @var $img_link_target
 * @var $alignment
 * @var $el_class
 * @var $css_animation
 * @var $style
 * @var $external_style
 * @var $border_color
 * @var $css
 * @var $content
 * @var $button_text
 * @var $button_link
 * @var $hover_effect
 * @var $banner_style
 *
 * @var $this WPBakeryShortCode_wpdanceclaratheme_single_image_hover
 */


if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$button_text = $button_link = $button_class = $hover_effect = $banner_style = '';

#
# Inherit all code from shortcode 'vc_single_image' but disable output
#

ob_start();
include vc_manager()->getDefaultShortcodesTemplatesDir().'/vc_single_image.php';
ob_end_clean();



#
# Add custom css class to indicate our shortcode
# Add css class for hover effect
#

$css_class = 'wpdanceclaratheme_single_image_hover '.$css_class;
if ($hover_effect != '')
	$css_class .= ' hover_effect_'.$hover_effect;

if ($banner_style != '')
	$css_class .= ' banner_style_'.$banner_style;

if (isset($content) && !empty($content) && $button_text)
	$html .= '<div class="content-wrapper">';

if (isset($content) && !empty($content))
	$html .= '<div class="content">'.wpb_js_remove_wpautop(apply_filters('the_content', $content), true).'</div>';

if ($button_text && $button_link)
	$html .= '<p class="btn-container"><a class="'.trim(esc_attr('btn '.$button_class)).'" href="'.esc_url($button_link).'">'.esc_html($button_text).'</a></p>';

if (isset($content) && !empty($content) && $button_text)
	$html .= '</div>'; // .content-wrapper


#
# Now output shortcode content
#

$output = '
	<div class="' . esc_attr( trim( $css_class ) ) . '">
		' . wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_singleimage_heading' ) ) . '
		<figure class="wpb_wrapper vc_figure">
			' . $html . '
		</figure>
	</div>
';

echo $output;
