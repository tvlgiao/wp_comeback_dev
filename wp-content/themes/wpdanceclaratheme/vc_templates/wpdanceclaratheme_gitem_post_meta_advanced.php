<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * @var $this WPBakeryShortCode_wpdanceclaratheme_gitem_post_meta_advanced
 */



/**
 * Shortcode attributes (copied from vc_gitem_post_meta.php)
 * @var $atts
 * @var $key
 * @var $el_class
 * @var $align
 * @var $label
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Gitem_Post_Meta
 */
$key = $el_class = $align = $label = '';
$label_html = '';

# (copied from vc_gitem_post_data.php) {{{
$output = $text = $google_fonts = $font_container = $el_class = $css = $google_fonts_data = $font_container_data = $link_html = '';
extract( $this->getAttributes( $atts ) );
extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );
// }}}


# specific parameters for this shortcode
$link = $key_url = '';


$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_class .= ' vc_gitem-post-meta-field-' . $key
	. ( strlen( $el_class ) ? ' ' . $el_class : '' )
	. ( strlen( $align ) ? ' vc_gitem-align-' . $align : '' );
if ( strlen( $label ) ) {
	$label_html = '<span class="vc_gitem-post-meta-label">' . esc_html( $label ) . '</span>';
}






/**
 * Shortcode attributes (copied from vc_gitem_post_data.php)
 * @var $atts
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Gitem_Post_Data
 */



if ( isset( $atts['link'] ) && '' !== $atts['link'] && 'none' !== $atts['link'] ) {
	$link_html = vc_gitem_create_link( $atts );
}
$use_custom_fonts = isset( $atts['use_custom_fonts'] ) && 'yes' === $atts['use_custom_fonts'];
$settings = get_option( 'wpb_js_google_fonts_subsets' );
$subsets = '';
if ( is_array( $settings ) && ! empty( $settings ) ) {
	$subsets = '&subset=' . implode( ',', $settings );
}

$content = '{{ post_meta_value:' . esc_attr($key) . ' }}';
if ( ! empty( $link_html ) ) {
	$content = '<' . $link_html . '>' . $content . '</a>';
}

if ( $use_custom_fonts && ! empty( $google_fonts_data ) && isset( $google_fonts_data['values']['font_family'] ) ) {
	wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
}
$output .= '<div class="' . esc_attr( $css_class ) . '" >';
$style = '';
if ( ! empty( $styles ) ) {
	$style = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
}
$output .= '<' . $font_container_data['values']['tag'] . ' ' . $style . ' >';
$output .= $label_html.' ';
$output .= $content;
$output .= '</' . $font_container_data['values']['tag'] . '>';
$output .= '</div>';

if (strlen($key))
	echo $output;
