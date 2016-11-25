<?php
/**
 * WooCommerce Social Media Share Buttons plugin integration script
 */

namespace wpdanceclaratheme\WooCommerceSMSB;

# Stop if the plugin is not activated
if (!function_exists('toastie_wc_smsb_social_footer')) return;

remove_action('wp_footer', 'toastie_wc_smsb_social_footer');
add_action('wp_head', __NAMESPACE__.'\\style');

/**
 * Hook print the plugin's CSS
 *
 * Replace all invalidated W3C
 * 
 * @return string
 */
function style() {
	ob_start();
	toastie_wc_smsb_social_footer();
	$s = ob_get_clean();
	$s = str_replace('line-height:auto', 'line-height:none', $s);

	return $s;
}