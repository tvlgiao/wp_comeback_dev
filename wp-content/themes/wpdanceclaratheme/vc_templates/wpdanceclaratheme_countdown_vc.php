<?php
$css = '';
extract(shortcode_atts(array(
	'class' => '',
	'css' => '',
), $atts));
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
?>
<div class="<?php echo esc_attr( $css_class . ' ' . $class); ?>">
    <?php echo wpdanceclaratheme_shortcode_countdown($atts); ?>
</div><?php echo $this->endBlockComment('wpdanceclaratheme_countdown'); ?>
