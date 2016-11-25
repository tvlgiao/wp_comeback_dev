<?php 
namespace wpdanceclaratheme\Portfolio;

if (!defined('WPDANCE_PORTFOLIO_PLUGIN')) return;


add_action('pre_get_posts', __NAMESPACE__.'\\set_posts_per_page');
add_filter('post_class', __NAMESPACE__.'\\add_post_class_landscape', 10, 3);
add_filter('wpdanceclaratheme_is_page_masonry', __NAMESPACE__.'\\is_page_mansory');




if (!function_exists(__NAMESPACE__.'\\set_posts_per_page')):
/**
 * Set proper posts per page for portfolio archive pages
 * 
 * @param WP_Query $query [description]
 */
function set_posts_per_page(\WP_Query $query) {
	if (!is_admin() && $query->is_main_query() 
	&& (is_post_type_archive('wpdance_portfolio') || is_tax('wpdance_portfolio_category') || is_tax('wpdance_portfolio_client') || is_tax('wpdance_portfolio_skill'))
	&& ($n = get_theme_mod('wpdanceclaratheme_layout_portfolio_posts_per_page'))) {
		$query->set('posts_per_page', $n);
	}
}
endif;


if (!function_exists(__NAMESPACE__.'\\is_page_mansory')):
/**
 * Filter hook for 'wpdanceclaratheme_is_page_masonry' to add class content-masonry if current page is portfolio archive pages
 * 
 * @param  string  $value
 * @return string
 */
function is_page_mansory($value) {

	if (is_archive()
	&& (is_post_type_archive('wpdance_portfolio') || is_tax('wpdance_portfolio_category') || is_tax('wpdance_portfolio_client') || is_tax('wpdance_portfolio_skill')))
		return 'yes';

	return $value;
}
endif;




if (!function_exists(__NAMESPACE__.'\\add_post_class_landscape')):
/**
 * Add class post-thumbnail-XXX to post article element
 * 
 * @param  array   $classes   Input css classes
 * @param  string  $class     Classes in string
 * @param  int     $post_id   Post ID
 * @return array              New classes array
 */
function add_post_class_landscape($classes, $class, $post_id) {

	# Try getting value from the post meta first
	if (get_post_type($post_id) == 'wpdance_portfolio') {
		if (get_post_meta($post_id, 'wpdanceclaratheme_image_style', true) == 'landscape')
			$classes[] = 'wpdance-portfolio-landscape';
		else
			$classes[] = 'wpdance-portfolio-portrait';
	}

	return $classes;
}
endif;
