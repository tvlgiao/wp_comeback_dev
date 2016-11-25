<?php
/**
 * Plugin advanced-excerpt integration
 *
 * @author tvlgiao
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @license Commercial License
 * @copyright wpdance.com
 */

namespace wpdanceclaratheme\AdvancedExcerpt;

# Stop if plugin advanced-exerpt is not activated
if (!class_exists('Advanced_Excerpt')) return;

add_filter('advanced_excerpt_skip_excerpt_filtering', __NAMESPACE__.'\\exclude_excerpt');


if (!function_exists(__NAMESPACE__.'\\exclude_excerpt')):
/**
 * Ignore excerpt content for post has post type = 'wpdance_htmlblock'
 * 
 * @param  [type] $default [description]
 * @return [type]          [description]
 */
function exclude_excerpt($default) {
	global $post, $advanced_excerpt;

	if ($post instanceof \WP_Post && $post->post_type == 'wpdance_htmlblock')
		return true;

	if (get_theme_mod('wpdanceclaratheme_layout_general_excerpt_exclude_content', true) && $advanced_excerpt->filter_type == 'content')
		return true;

	return $default;
}
endif;


