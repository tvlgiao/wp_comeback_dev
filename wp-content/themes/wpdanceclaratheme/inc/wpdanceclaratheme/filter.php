<?php
namespace wpdanceclaratheme\filter;



###########################################################################
# DEFINE ALL FILTERS BELONG TO THIS NAMESPACE
###########################################################################

add_filter('wp_title', __NAMESPACE__.'\\wp_title', 10, 2);
add_filter('body_class', __NAMESPACE__.'\\body_class', 10, 1);
add_filter('post_class', __NAMESPACE__.'\\post_class', 10, 3);
add_filter('404_template', __NAMESPACE__.'\\use_page_404');


# Remove frameborder from iframe tag youtube video to valid HTML5
add_filter('embed_handler_html', __NAMESPACE__.'\\remove_frameborder');
add_filter('embed_oembed_html', __NAMESPACE__.'\\remove_frameborder');

add_filter('post_thumbnail_size', __NAMESPACE__.'\\post_thumbnail_size');



if (!is_admin())
	add_filter('excerpt_more', __NAMESPACE__.'\\excerpt_more');



###########################################################################
# DEFINE ALL FILTER FUNCTIONS
###########################################################################

if (!function_exists(__NAMESPACE__.'\\wp_title')):
/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @param string $title Default title text for current view.
 * @param string $sep   Optional separator.
 * @return string The filtered title.
 */
function wp_title($title, $sep) {

	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
		$title = "$title $sep " . sprintf( esc_html__( 'Page %s', 'wpdanceclaratheme' ), max( $paged, $page ) );

	return $title;

}
endif;



if (!function_exists(__NAMESPACE__.'\\body_class')):
/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Active widgets in the sidebar to change the layout and spacing.
 * 3. When avatars are disabled in discussion settings.
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function body_class( $classes ) {
	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	if ( is_active_sidebar( 'sidebar-2' ) && ! is_attachment() && ! is_404() )
		$classes[] = 'sidebar';

	if ( ! get_option( 'show_avatars' ) )
		$classes[] = 'no-avatars';

	$pos = get_theme_mod('wpdanceclaratheme_layout_general_page_title_position', 'top');

	$classes[] = $pos == 'top-center' ? 'page-title-top page-title-top-center' : 'page-title-'.$pos;

	$classes[] = 'layout-'.\wpdanceclaratheme\Helper\get_layout();
	
	if (\wpdanceclaratheme\Helper\is_page_masonry()) $classes[] = 'content-masonry';

	return $classes;
}
endif;


if (!function_exists(__NAMESPACE__.'\\post_class')):
/**
 * Add class post-thumbnail-XXX to post article element
 * 
 * @param  array   $classes   Input css classes
 * @param  string  $class     Classes in string
 * @param  int     $post_id   Post ID
 * @return array              New classes array
 */
function post_class($classes, $class, $post_id) {

	if (is_singular()) {
		# Try getting value from the post meta first
		$value = get_post_meta($post_id, 'wpdanceclaratheme_image_position', true);
	}

	# Getting value from current page configs
	if (empty($value) && in_array(get_post_type($post_id), array('post', 'page')))
		$value = \wpdanceclaratheme\Helper\image_position();

	if (!empty($value))
		$classes[] = 'post-thumbnail-'.sanitize_key($value);

	return $classes;
}
endif;




if (!function_exists(__NAMESPACE__.'\\excerpt_more')):
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ...
 * and a Continue reading link.
 *
 * @since WPDanceClaraTheme 1.4
 *
 * @param string $more Default Read More excerpt link.
 * @return string Filtered Read More excerpt link.
 */
function excerpt_more( $more ) {
	$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
			/* translators: %s: Name of current post */
			sprintf( wp_kses(__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'wpdanceclaratheme' ), \wpdanceclaratheme\Config::$allowed_html), '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>' )
		);
	return ' &hellip; ' . $link;
}
endif;



if (!function_exists(__NAMESPACE__.'\\use_page_404')):
/**
 * Filter '404_template' to override 404 page by a static page
 * 
 * @param  string $template original template
 * @return string           new template
 */
function use_page_404($template) {
	global $wp_query, $post;

	if (is_404() && $page_id = \wpdanceclaratheme\Helper\page_id_404()) {

		# get 404 page and assign to the global $post so that get_post() can work
		$post = get_post($page_id);

		# Fill global $wp_query posts with our 404 page
		$wp_query->posts = array($post);

		# set query object to support custom page templates
		$wp_query->queried_object_id = $page_id;
		$wp_query->queried_object = $post;

		# Set post count to avoid loop error
		$wp_query->post_count = 1;
		$wp_query->found_posts = 1;
		$wp_query->max_num_pages = 0;

		# return page.php template instead of 404.php
		return get_page_template();

	}

	return $template;
}
endif;



if (!function_exists(__NAMESPACE__.'\\remove_frameborder')):
/**
 * Remove frameborder from iframe tag of youtube video to valid HTML5
 * 
 * @param  string $code
 * @return string
 */
function remove_frameborder($code) {
	if(strpos($code, 'youtu.be') !== false || strpos($code, 'youtube.com') !== false){
		$return = preg_replace('@ frameborder="0"@', '', $code);
		return $return;
	}
	return $code;
}
endif;


if (!function_exists(__NAMESPACE__.'\\post_thumbnail_size')):
/**
 * Filter change post-thumbnail to post-thumbnail-fullwidth if current layout is full width
 * 
 * @param  string $size current post thumbnail size
 * @return string new size
 */
function post_thumbnail_size($size) {
	if (is_string($size) && $size == 'post-thumbnail' && \wpdanceclaratheme\Helper\get_main_column_size() == 12)
		$size = 'post-thumbnail-fullwidth';

	return $size;
}
endif;