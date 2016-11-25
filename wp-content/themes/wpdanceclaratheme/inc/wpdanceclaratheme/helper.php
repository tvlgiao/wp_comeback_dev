<?php
namespace wpdanceclaratheme\Helper;

use wpdanceclaratheme\Config;

/**
 * return wp_filesystem object
 *
 * @return WP_Filesystem_Base
 */
function wp_filesystem() {

	global $wp_filesystem;

	if ( empty( $wp_filesystem ) ) {
		require_once( ABSPATH . '/wp-admin/includes/file.php' );
		\WP_Filesystem();
	}


	/*
	if (!isset($wp_filesystem)) {
		require_once( ABSPATH . '/wp-admin/includes/file.php' );

		$url = \wp_nonce_url('customize.php');
		if (false === ($creds = \request_filesystem_credentials($url, '', false, false, null) ) ) {
			return; // stop processing here
		}
	
		if ( ! \WP_Filesystem($creds) ) {
			\request_filesystem_credentials($url, '', true, false, null);
			return;
		}
	}*/

	return $wp_filesystem;
}


/**
 * Retrieve custom header or footer HTMLBlock post object from current page
 * 
 * @param string $type value is 'header' or 'footer'
 * @return WP_Post|NULL
 */
function get_headerfooter_post($type = 'header') {
	if (!defined('WPDANCE_HTMLBLOCK')) return;

	if ($type == 'header') {
		$key = 'wpdanceclaratheme_custom_header';
		$theme_mod_key = 'wpdanceclaratheme_header_htmlblock';
	}

	elseif ($type = 'footer') {
		$key = 'wpdanceclaratheme_custom_footer';
		$theme_mod_key = 'wpdanceclaratheme_footer_htmlblock';	
	}

	else {
		throw new Exception("Unknown type");
	}

	$page_id = null;

	if (is_single() || is_page())
		$page_id = get_the_ID();



	# Hook for other plugin-support code handle
	# $page_id can be modified by the hook actions
	$arg = new \stdclass;
	$arg->page_id = &$page_id;
	do_action('wpdanceclaratheme_get_headerfooter_post_before', $arg);

	# Retrieve HTMLBlock ID (or Slug name) from post meta
	if (isset($page_id) && !empty($page_id))
		$htmlblock_id = @get_post_meta($page_id, $key, true);

	
	# Retrieve HTMLBlock ID (or Slug name) from theme mod
	if (!isset($htmlblock_id) || empty($htmlblock_id))
		$htmlblock_id = @get_theme_mod($theme_mod_key);

	if (!empty($htmlblock_id) && !is_numeric($htmlblock_id))
		$htmlblock_id = \WPDance\Plugins\htmlblock\get_post_id_by_slug($htmlblock_id);

	# Retrieve HTMLBlock post object
	if (!empty($htmlblock_id))
		return get_post($htmlblock_id);
	else
		return null;

}



if (!function_exists(__NAMESPACE__.'\\get_header_post')):
/**
 * Return HTML Block post assigned to the header
 *
 * @return WP_Post/null
 */
function get_header_post() {
	return get_headerfooter_post('header');
}
endif;




if (!function_exists(__NAMESPACE__.'\\get_header')):
/**
 * Return the content of HTML Block assigned to the Header
 *
 * @return string
 */
function get_header() {
	global $post;

	$pre_post = $post;

	if (!($cur_post = get_header_post()))
		return $cur_post;

	$post = $cur_post;
	$content = \apply_filters('the_content', $cur_post->post_content);
	$post = $pre_post;

	return $content;
}
endif;




if (!function_exists(__NAMESPACE__.'\\get_footer_post')):
/**
 * Return HTML Block post assigned to the footer
 *
 * @return WP_Post/null
 */
function get_footer_post() {
	return get_headerfooter_post('footer');
}
endif;





if (!function_exists(__NAMESPACE__.'\\get_footer')):
/**
 * Return the content of HTML Block assigned to the Footer
 *
 * @return string
 */
function get_footer() {
	global $post;

	$pre_post = $post;

	if (!($cur_post = get_footer_post()))
		return $cur_post;

	$post = $cur_post;
	$content = \apply_filters('the_content', $cur_post->post_content);
	$post = $pre_post;

	return $content;
}
endif;




if (!function_exists(__NAMESPACE__.'\\get_google_fonts')):
/**
 * Retrieve Google Fonts list
 *
 * @var int $count total fonts returned
 * @return array fonts list (@see cache/google-fonts.txt)
 */
function get_google_fonts($count = 100) {

	$fs = wp_filesystem();

	$api_key = Config::$google_api_key;
	$cache_time = 86400 * 7 * 1000000;
	$dir = get_template_directory();

	$fn = $dir.'/google-fonts.txt';

	if (!$fs->is_file($fn) || $fs->mtime($fn) < time() - $cache_time) {

	
		$result = wp_remote_get("https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&key={$api_key}", array('sslverify' => false));
		$content = $result['body'];

		$fs->put_contents($fn, $content);
	}
	else {
		$content = $fs->get_contents($fn);
	}

	$data = json_decode($content);
	if ($count <= 0) {
		return $data->items;
	}
	else {
		return array_slice($data->items, 0, $count);
	}
}
endif;



if (!function_exists(__NAMESPACE__.'\\get_google_fonts_url')):
/**
 * Return google fonts URL to be included in HTML style element
 *
 * This function will loop all theme mods' variables, find variable names
 * match with 'font-family' and read variable 'style_general_font_subsets'
 * to generate the URL.
 *
 * @return $string
 */
function get_google_fonts_url() {
	
	#
	# Try to use the preset specified in $_REQUEST if possible
	#
	if (isset($_REQUEST['preset']) && !empty($_REQUEST['preset']) 
		&& ($s = sanitize_key(strtolower($_REQUEST['preset']))) 
		&& \wpdanceclaratheme\Scss\Preset\preset_exists($s))
		$preset_name = $s;
	else
		$preset_name = get_theme_mod('wpdanceclaratheme_preset', 'default');
	



	$urls = (array)get_theme_mod('wpdanceclaratheme_google_fonts_url', array());

	if (Config::CSS_DEV_MODE || !isset($urls[$preset_name]) || empty($urls[$preset_name])) {

		/**
		 * Preset variables defined in Preset Scss files
		 *
		 * @var array $preset_vars
		 */
		$preset_vars = \wpdanceclaratheme\Scss\Preset\get_preset_vars($preset_name);


		/**
		 * Retrieve google fonts array has keys are font family
		 *
		 * @var array(string => array) $fonts
		 */
		# 
		$fonts = array();
		$fonts_tmp = get_google_fonts();
		foreach ($fonts_tmp as $item) {
			$fonts[$item->family] = $item;
		}

		/**
		 * Fonts specified in Customizer
		 *
		 * @var array(string => string) $fonts_used
		 */

		$fonts_used = Config::$include_google_fonts;
		if (!is_array($fonts_used))
			$fonts_used = array($fonts_used);


		# Loop each preset variable & check whether it is font-family
		foreach ($preset_vars as $name => $var) {
			if (preg_match('/font_family/', $name)) {

				$var = get_theme_mod($name, $var);
				$var = str_replace(array('"', "'"), '', $var);

				# font is google font
				if (array_key_exists($var, $fonts) && !array_key_exists($var, $fonts_used))
					$fonts_used[$var] = $var . ':' . implode(',', $fonts[$var]->variants);
			}
		}


		# loop each theme mod & check whether it is font-family
		$vars = get_theme_mods();
		if (is_array($vars)) {
			foreach ($vars as $name => $var) {
				if (preg_match('/font_family/', $name, $m)) {

					# font is google font
					if (array_key_exists($var, $fonts) && !array_key_exists($var, $fonts_used))
						$fonts_used[$var] = $var . ':' . implode(',', $fonts[$var]->variants);
				}
			}
		}


		if (empty($fonts_used))
			return '';


		/**
		 * Font Subsets
		 *
		 * @var string $subsets
		 */
		$subsets = get_theme_mod('wpdanceclaratheme_style_general_font_subsets', 'latin,latin-ext');
		if (is_array($subsets))
			$subsets = implode(',', $subsets);



		$query_args = array(
			'family' => urlencode(implode( '|', $fonts_used)),
			'subset' => urlencode($subsets),
		);
		$urls[$preset_name] = add_query_arg($query_args, 'https://fonts.googleapis.com/css');

		set_theme_mod('wpdanceclaratheme_google_fonts_url', $urls);
	}

	return $urls[$preset_name];
}
endif;



if (!function_exists(__NAMESPACE__.'\\paging_nav')):
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function paging_nav() {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 )
		return;
		
	$paging_type = get_theme_mod('wpdanceclaratheme_layout_general_posts_pagination', 'default');
	
	if ($paging_type == 'number'):
		the_posts_pagination(array(
			'prev_text' => esc_html__("Previous", 'wpdanceclaratheme'),
			'next_text' => esc_html__("Next", 'wpdanceclaratheme'),
			'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__("Page", 'wpdanceclaratheme') . ' </span>',
		));
	else:
	?>
	<nav class="navigation paging-navigation">
		<h3 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'wpdanceclaratheme' ); ?></h3>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( wp_kses(__( '<span class="meta-nav">&larr;</span> Older posts', 'wpdanceclaratheme' ), Config::$allowed_html) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( wp_kses(__( 'Newer posts <span class="meta-nav">&rarr;</span>', 'wpdanceclaratheme' ), Config::$allowed_html) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
	endif;
}
endif;


if (!function_exists(__NAMESPACE__.'\\post_nav')):
/**
 * Display navigation to next/previous post when applicable.
 */
function post_nav() {
	global $post;

	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous )
		return;
	?>
	<nav class="navigation post-navigation">
		<h3 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'wpdanceclaratheme' ); ?></h3>
		<div class="nav-links">

			<?php previous_post_link( '%link', wp_kses(_x( '<span class="meta-nav">&larr; Previous Post</span><span class="meta-nav-title">%title</span>', 'Previous post link', 'wpdanceclaratheme' ), Config::$allowed_html) ); ?>
			<?php next_post_link( '%link', wp_kses(_x( '<span class="meta-nav-title">%title</span><span class="meta-nav">Next Post &rarr;</span>', 'Next post link', 'wpdanceclaratheme' ), Config::$allowed_html) ); ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

function portfolio_nav() {
	$previous = get_previous_post();
	$next     = get_next_post();

	if (!$previous && !$next)
		return;

	?>
	<nav class="navigation portfolio-navigation">
		<h3 class="screen-reader-text"><?php esc_html_e( 'Portfolio navigation', 'wpdanceclaratheme' ); ?></h3>
		<div class="nav-links">

			<?php previous_post_link( '%link', wp_kses(__( '<span class="meta-nav">Previous Project</span><span class="meta-nav-title"> %title</span>', 'wpdanceclaratheme' ), Config::$allowed_html) ); ?>
			<?php next_post_link( '%link', wp_kses(__( '<span class="meta-nav">Next Project</span><span class="meta-nav-title"> %title</span>', 'wpdanceclaratheme' ), Config::$allowed_html) ); ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}


if (!function_exists(__NAMESPACE__.'\\entry_meta')):
/**
 * Print HTML with meta information for current post: categories, tags, permalink, author, and date.
 */
function entry_meta() {
	if ( is_sticky() && is_home() && ! is_paged() )
		echo '<span class="featured-post">' . esc_html__( 'Sticky', 'wpdanceclaratheme' ) . '</span>';

	if ( ! has_post_format( 'link' ) && 'post' == get_post_type() )
		namespace\entry_date();

	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( esc_html__( ', ', 'wpdanceclaratheme' ) );
	if ( $categories_list ) {
		echo '<span class="categories-links">' . $categories_list . '</span>';
	}

	// Do not show tags on single post of stantard post
	if (!is_single() || get_post_format()) {
		// Translators: used between list items, there is a space after the comma.
		$tag_list = get_the_tag_list( '', esc_html__( ', ', 'wpdanceclaratheme' ) );
		if ( $tag_list ) {
			echo '<span class="tags-links">' . $tag_list . '</span>';
		}
	}

	// Post author
	if ( 'post' == get_post_type() ) {
		printf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'wpdanceclaratheme' ), get_the_author() ) ),
			get_the_author()
		);
	}
}
endif;



if (!function_exists(__NAMESPACE__.'\\post_tag_list')):
/**
 * Print tag list belongs to the current post
 */
function post_tag_list() {
	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', esc_html__( ', ', 'wpdanceclaratheme' ) );
	if ( $tag_list ) {
		echo '<span class="tags-links">' . $tag_list . '</span>';
	}
}
endif;



if (!function_exists(__NAMESPACE__.'\\entry_date')):
/**
 * Print HTML with date information for current post.
 *
 * @param boolean $echo (optional) Whether to echo the date. Default true.
 * @return string The HTML-formatted post date.
 */
function entry_date( $echo = true ) {
	if ( has_post_format( array( 'chat', 'status' ) ) )
		$format_prefix = esc_html_x( '%1$s on %2$s', '1: post format name. 2: date', 'wpdanceclaratheme' );
	else 
		$format_prefix = '%2$s';
	
	$the_date = sprintf('<span class="month">%s</span> <span class="day">%s</span>', get_the_date('F'), get_the_date('j'));
	
	$date = sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
		esc_url( get_permalink() ),
		esc_attr( sprintf( __( 'Permalink to %s', 'wpdanceclaratheme' ), the_title_attribute( 'echo=0' ) ) ),
		esc_attr( get_the_date( 'c' ) ),
		sprintf( $format_prefix, get_post_format_string( get_post_format() ), $the_date )
	);

	if ( $echo )
		echo $date;

	return $date;
}
endif;





if (!function_exists(__NAMESPACE__.'\\the_attached_image')):
/**
 * Print the attached image with a link to the next attached image.
 */
function the_attached_image() {
	/**
	 * Filter the image attachment size to use.
	 *
	 * @since Twenty thirteen 1.0
	 *
	 * @param array $size {
	 *     @type int The attachment height in pixels.
	 *     @type int The attachment width in pixels.
	 * }
	 */
	$attachment_size     = apply_filters( 'wpdanceclaratheme_attachment_size', array( 724, 724 ) );
	$next_attachment_url = wp_get_attachment_url();
	$post                = get_post();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID',
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id )
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		else
			$next_attachment_url = get_attachment_link( reset( $attachment_ids ) );
	}

	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),
		the_title_attribute( array( 'echo' => false ) ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;




if (!function_exists(__NAMESPACE__.'\\get_link_url')):
/**
 * Return the post URL.
 *
 * @uses get_url_in_content() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @return string The Link format URL.
 */
function get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content( $content );

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}
endif;



if (!function_exists(__NAMESPACE__.'\\get_header_choices')):
/**
 * Return HTML Headers array used for WP_Customize_Control select
 *
 * @return array
 */
function get_header_choices() {

	$choices = array('' => '');

	$posts = new \WP_Query(array(
		'post_type'  => 'wpdance_htmlblock',
		'post_status'=> 'publish',
		'orderby'    => 'post_title',
		'order'      => 'ASC',
		'nopaging'   => true
	));

	while ($posts->have_posts()) {
		$posts->the_post();
		$post = get_post();
		if (preg_match('/^(wpdanceclaratheme-)?header/i', $post->post_name))
			$choices[$post->post_name] = get_the_title('', '', false);
	}

	wp_reset_postdata();

	return $choices;
}
endif;



if (!function_exists(__NAMESPACE__.'\\get_footer_choices')):
/**
 * Return HTML Footers array used for WP_Customize_Control select
 *
 * @return array
 */
function get_footer_choices() {

	$choices = array('' => '');

	$posts = new \WP_Query(array(
		'post_type'  => 'wpdance_htmlblock',
		'post_status'=> 'publish',
		'orderby'    => 'post_title',
		'order'      => 'ASC',
		'nopaging'   => true
	));

	while ($posts->have_posts()) {
		$posts->the_post();
		$post = get_post();
		if (preg_match('/^(wpdanceclaratheme-)?footer/i', $post->post_name))
			$choices[$post->post_name] = get_the_title('', '', false);
	}

	wp_reset_postdata();

	return $choices;
}
endif;



if (!function_exists(__NAMESPACE__.'\\get_menu_location_choices')):
/**
 * Return array of Menu Location used for control select or select options elements
 *
 * @return array
 */
function get_menu_location_choices() {
	$choices = array();
	foreach (Config::$menu_locations as $name => $title) {
		$choices[$name] = call_user_func('_x', $title, 'menu location', 'wpdanceclaratheme');
	}
	return $choices;
}
endif;



if (!function_exists(__NAMESPACE__.'\\get_layout')):
/**
 * Return the current page's layout (fullwidth, sidebar-left, sidebar-right)
 * 
 * @return string 'fullwidth', 'sidebar-left', 'sidebar-right'
 */
function get_layout() {

	$layout = null;

	# Current page is Home Page or Blog
	if (is_front_page() || is_home()) {

		# Read layout from theme_mod Home Page layout
		$layout = get_theme_mod('wpdanceclaratheme_layout_home');
	}

	# Current page is Post Category
	if (!$layout && is_category()) {

		# Read layout from category's meta data
		if (function_exists('get_term_meta'))
			$layout = get_term_meta(get_query_var('cat'), 'wpdanceclaratheme_layout', true);

		# Read layout from theme_mod of blog layout
		if (!$layout)
			$layout = get_theme_mod('wpdanceclaratheme_layout_blog');
	}

	# Current page is Tag page
	if (!$layout && is_tag()) {

		# Read layout from tag's meta data
		if (function_exists('get_term_meta'))
			$layout = get_term_meta(get_query_var('tag_id'), 'wpdanceclaratheme_layout', true);

		# Read layout from theme_mod of archive layout
		if (!$layout)
			$layout = get_theme_mod('wpdanceclaratheme_layout_archive');
	}

	# Current page is Author page
	if (!$layout && is_author()) {

		# Read layout from theme_mod Author page layout
		$layout = get_theme_mod('wpdanceclaratheme_layout_author');
	}

	# Portfolio plugin's taxonomy pages
	if (!$layout && (is_tax('wpdance_portfolio_category') || is_tax('wpdance_portfolio_client') || is_tax('wpdance_portfolio_skill'))) {

		# Read layout from term's meta data
		if (function_exists('get_term_meta'))
			$layout = get_term_meta(get_queried_object()->term_id, 'wpdanceclaratheme_layout', true);

		# Read layout from theme_mod Portfolio page layout
		if (!$layout)
			$layout = get_theme_mod('wpdanceclaratheme_layout_portfolio', 'fullwidth');
	}

	# Portfolio plugin's archive pages
	if (!$layout && is_post_type_archive('wpdance_portfolio')) {

		# Read layout from theme_mod Portfolio page layout
		$layout = get_theme_mod('wpdanceclaratheme_layout_portfolio', 'fullwidth');	
	}

	# Current page is Archive page
	if (!$layout && is_archive()) {

		# Read layout from theme_mod Archive page layout
		$layout = get_theme_mod('wpdanceclaratheme_layout_archive');
	}

	# Current page is static page
	if (is_page()) {

		# Read layout from meta data
		$layout = get_post_meta(get_the_ID(), 'wpdanceclaratheme_layout', true);

		# Read layout from meta data page template
		if (!$layout && preg_match('/page-(.+).php/', get_page_template_slug(), $m)) {
			switch ($m[1]) {
				case 'fullwidth':
					$layout = 'fullwidth';
					break;
				
				case 'left':
					$layout = 'sidebar-left';
					break;

				case 'right':
					$layout = 'sidebar-right';
					break;
			}
		}
	}

	# Current page is single (post)
	if (is_single()) {

		# Read layout from meta data
		$layout = get_post_meta(get_the_ID(), 'wpdanceclaratheme_layout', true);

		# Read layout from custom post template if supported (by the plugin custom-post-template)
		if (!$layout && function_exists('is_post_template')) {

			if (is_post_template('single-fullwidth.php'))
				$layout = 'fullwidth';

			elseif (is_post_template('single-left.php'))
				$layout = 'sidebar-left';

			elseif (is_post_template('single-right.php'))
				$layout = 'sidebar-right';
		}

		# Read layout from theme_mod portfolio layout
		if (!$layout && get_post_type() == 'wpdance_portfolio') {
			$layout = get_theme_mod('wpdanceclaratheme_layout_portfolio', 'fullwidth');
		}

		# Read layout from theme_mod single layout
		if (!isset($layout) || empty($layout))
			$layout = get_theme_mod('wpdanceclaratheme_layout_single');
	}

	# Current page is 404 and a static page is set for show on 404 errors
	if (!$layout &&  is_404() && $page_id = page_id_404()) {

		# Read layout from meta data
		$layout = get_post_meta($page_id, 'wpdanceclaratheme_layout', true);
	}

	# Fall back to default layout
	if (!isset($layout) || empty($layout))
		$layout = 'sidebar-right';

	return apply_filters('wpdanceclaratheme_get_layout', $layout);
}
endif;


if (!function_exists(__NAMESPACE__.'\\get_main_column_size')):
/**
 * Determine main column size from active sidebar
 * 
 * @return integer return column size.
 */
function get_main_column_size() {

	$layout = get_layout();

	if (!is_active_sidebar('sidebar-2') || $layout == 'fullwidth')
		return 12;
	else
		return 8;

	
}
endif;


if (!function_exists(__NAMESPACE__.'\\get_main_push_size')):
/**
 * Get number of column for md-col-push-XXX of Bootstrap CSS
 * 
 * @param  string default layout (previous_layout, sidebar-right, sidebar-left, fullwidth)
 * @return int push columm number
 */
function get_main_push_size($default_layout = '') {

	static $previous_layout = 'sidebar-right';

	if ($default_layout == 'previous_layout')
		$layout = $previous_layout;

	else
		$previous_layout = $layout = get_layout();

	if (is_active_sidebar('sidebar-2') && $layout == 'sidebar-left')
		return 4;

	else
		return 0;
}
endif;



if (!function_exists(__NAMESPACE__.'\\get_primary_div_class')):
/**
 * Return CSS class for div#primary element of current page
 *
 * @return string
 */
function get_primary_div_class() {
	
	# main column size
	$class = 'col-md-'.get_main_column_size();

	# push for showing sidebar left
	if ($push = get_main_push_size())
		$class .= ' col-md-push-'.$push;

	return $class;
}
endif; 

/**
 * Return CSS class for the main sidebar (left/right)
 * 
 * @return string
 */
function get_main_sidebar_class() {
	$push = get_main_push_size();

	$class = 'col-md-4';
	if ($push = get_main_push_size())
		$class .= ' col-md-pull-'.(12-$push);

	return $class;
}


if (!function_exists(__NAMESPACE__.'\\should_show_main_sidebar')):
/**
 * Check whether show the main sidebar (left/right) nor not, depend on current page layout
 * 
 * - fullwidth: Don't show sidebar
 * - left, right, default: Show sidebar
 * 
 * @return boolean
 */
function should_show_main_sidebar() {
	return get_main_column_size() != 12;
}
endif;


if (!function_exists(__NAMESPACE__.'\\hide_title')):
/**
 * Whether to show title of the current page or not
 *
 * @return boolean
 */
function hide_title() {
	$val = get_post_meta(get_the_ID(), 'wpdanceclaratheme_hide_title', true);
	return $val == 'yes' || $val == 'on';
}
endif;






if (!function_exists(__NAMESPACE__.'\\breadcrumb')):
/**
 * Print breadcrumb html content
 */
function breadcrumb() {
	if (function_exists('bcn_display')) {
		?>
		<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">
		        <?php bcn_display(); ?>
		</div>
		<?php
	}
}
endif;





if (!function_exists(__NAMESPACE__.'\\image_position')):
/**
 * Return image position configured for current page
 * 
 * @return boolean
 */
function image_position() {
 
	# Current page is Home Page or Blog
	if (is_front_page() || is_home()) {

		# Read value from theme_mod Home Page layout
		$value = get_theme_mod('wpdanceclaratheme_layout_home_image_position');
	}

	# Current page is Post Category
	elseif (is_category()) {

		# Read value from category's meta data
		if (function_exists('get_term_meta'))
			$value = get_term_meta(get_query_var('cat'), 'wpdanceclaratheme_image_position', true);

		# Read value from theme_mod of blog layout
		if (!$value)
			$value = get_theme_mod('wpdanceclaratheme_layout_blog_image_position');
	}

	# Current page is Tag page
	elseif (is_tag()) {

		# Read value from tag's meta data
		if (function_exists('get_term_meta'))
			$value = get_term_meta(get_query_var('tag_id'), 'wpdanceclaratheme_image_position', true);

		# Read value from theme_mod of archive layout
		if (!$value)
			$value = get_theme_mod('wpdanceclaratheme_layout_archive_image_position');
	}

	# Current page is Author page
	elseif (is_author()) {

		# Read value from theme_mod Author page layout
		$value = get_theme_mod('wpdanceclaratheme_layout_author_image_position');
	}

	# Current page is Archive page
	elseif (is_archive()) {

		# Read value from theme_mod Archive page layout
		$value = get_theme_mod('wpdanceclaratheme_layout_archive_image_position');
	}

	# Current page is static page
	elseif (is_page()) {

		# Read value from meta data
		$value = get_post_meta(get_the_ID(), 'wpdanceclaratheme_image_position', true);
	}

	# Current page is single (post)
	elseif (is_single()) {

		# Read value from meta data
		$value = get_post_meta(get_the_ID(), 'wpdanceclaratheme_image_position', true);

		# Read value from theme_mod single layout
		if (!isset($value) || empty($value))
			$value = get_theme_mod('wpdanceclaratheme_layout_single_image_position');
	}

	# Current page is 404 and a static page is set for show on 404 errors
	elseif (is_404() && $page_id = page_id_404()) {

		# Read value from meta data
		$value = get_post_meta($page_id, 'wpdanceclaratheme_image_position', true);
	}

	# Fall back to default value
	if (!isset($value) || empty($value))
		$value = get_theme_mod('wpdanceclaratheme_layout_general_image_position', 'right');

	return apply_filters('wpdanceclaratheme_image_position', $value);
}
endif;





if (!function_exists(__NAMESPACE__.'\\is_page_masonry')):
/**
 * Check current page should use masonry layout or not
 * 
 * @return boolean
 */
function is_page_masonry() {

	# Current page is Home Page or Blog
	if (is_front_page() || is_home()) {

		# Read value from theme_mod Home Page layout
		$value = get_theme_mod('wpdanceclaratheme_layout_home_masonry');
	}

	# Current page is Post Category
	elseif (is_category()) {

		# Read value from category's meta data
		if (function_exists('get_term_meta'))
			$value = get_term_meta(get_query_var('cat'), 'wpdanceclaratheme_masonry', true);

		# Read value from theme_mod of blog layout
		if (!isset($value) || empty($value))
			$value = get_theme_mod('wpdanceclaratheme_layout_blog_masonry');
	}

	# Current page is Tag page
	elseif (is_tag()) {

		# Read value from tag's meta data
		if (function_exists('get_term_meta'))
			$value = get_term_meta(get_query_var('tag_id'), 'wpdanceclaratheme_masonry', true);

		# Read value from theme_mod of archive layout
		if (!isset($value) || empty($value))
			$value = get_theme_mod('wpdanceclaratheme_layout_tag_masonry');
	}

	# Current page is Author page
	elseif (is_author()) {

		# Read value from theme_mod Author page layout
		$value = get_theme_mod('wpdanceclaratheme_layout_author_masonry');
	}

	# Current page is Archive page
	elseif (is_archive()) {

		# Read value from theme_mod Archive page layout
		$value = get_theme_mod('wpdanceclaratheme_layout_archive_masonry');
	}

	# Fall back to default value
	if (!isset($value) || empty($value))
		$value = get_theme_mod('wpdanceclaratheme_layout_general_masonry', 'yes');

	$value = apply_filters('wpdanceclaratheme_is_page_masonry', $value);

	return $value == 'yes' || $value == 'on';
}
endif;





if (!function_exists(__NAMESPACE__.'\\show_excerpt')):
/**
 * Specify current page should show excerpt or content
 * 
 * @return boolean
 */
function show_excerpt() {

	# Current page is a single page
	if (is_single()) {
		$excerpt = 'no';
	}

	# Current page is Home Page or Blog
	elseif (is_front_page() || is_home()) {

		# Read excerpt from theme_mod Home Page excerpt
		$excerpt = get_theme_mod('wpdanceclaratheme_layout_home_excerpt');
	}

	# Current page is Post Category
	elseif (is_category()) {

		# Read excerpt from category's meta data
		if (function_exists('get_term_meta'))
			$excerpt = get_term_meta(get_query_var('cat'), 'wpdanceclaratheme_excerpt', true);

		# Read excerpt from theme_mod of blog excerpt
		if (!isset($excerpt) || empty($excerpt))
			$excerpt = get_theme_mod('wpdanceclaratheme_layout_blog_excerpt');
	}

	# Current page is Tag page
	elseif (is_tag()) {

		# Read excerpt from tag's meta data
		if (function_exists('get_term_meta'))
			$excerpt = get_term_meta(get_query_var('tag_id'), 'wpdanceclaratheme_excerpt', true);

		# Read excerpt from theme_mod of archive excerpt
		if (!isset($excerpt) || empty($excerpt))
			$excerpt = get_theme_mod('wpdanceclaratheme_layout_tag_excerpt');
	}

	# Current page is Author page
	elseif (is_author()) {

		# Read excerpt from theme_mod Author page excerpt
		$excerpt = get_theme_mod('wpdanceclaratheme_layout_author_excerpt');
	}

	# Current page is Archive page
	elseif (is_archive()) {

		# Read excerpt from theme_mod Archive page excerpt
		$excerpt = get_theme_mod('wpdanceclaratheme_layout_archive_excerpt');
	}

	# Fall back to default excerpt
	if (!isset($excerpt) || empty($excerpt))
		$excerpt = get_theme_mod('wpdanceclaratheme_layout_general_excerpt', 'yes');

	$excerpt = apply_filters('wpdanceclaratheme_show_excerpt', $excerpt);

	return $excerpt == 'yes' || $excerpt == 'on';
}
endif;



if (!function_exists(__NAMESPACE__.'\\get_loop_col')):
/**
 * Return number of columns inside the main loop of current page
 * 
 * @return int
 */
function get_loop_col() {

	# Current page is Home Page or Blog
	if (is_front_page() || is_home()) {

		# Read value from theme_mod Home Page
		$value = get_theme_mod('wpdanceclaratheme_layout_home_col');
	}

	# Current page is Post Category
	elseif (is_category()) {

		# Read value from category's meta data
		if (function_exists('get_term_meta'))
			$value = get_term_meta(get_query_var('cat'), 'wpdanceclaratheme_col', true);

		# Read value from theme_mod of blog layout
		if (!isset($value) || empty($value))
			$value = get_theme_mod('wpdanceclaratheme_layout_blog_col');
	}

	# Current page is Tag page
	elseif (is_tag()) {

		# Read value from tag's meta data
		if (function_exists('get_term_meta'))
			$value = get_term_meta(get_query_var('tag_id'), 'wpdanceclaratheme_col', true);

		# Read value from theme_mod of archive layout
		if (!isset($value) || empty($value))
			$value = get_theme_mod('wpdanceclaratheme_layout_tag_col');
	}

	# Current page is Author page
	elseif (is_author()) {

		# Read value from theme_mod Author page layout
		$value = get_theme_mod('wpdanceclaratheme_layout_author_col');
	}

	# Current page is taxonomy of portfolio plugin
	elseif (is_tax('wpdance_portfolio_category') || is_tax('wpdance_portfolio_client') || is_tax('wpdance_portfolio_skill')) {

		# Read value from term's meta data
		if (function_exists('get_term_meta'))
			$value = get_term_meta(get_queried_object()->term_id, 'wpdanceclaratheme_col', true);

		# Read value from theme_mod Portfolio page layout
		if (!$value)
			$value = get_theme_mod('wpdanceclaratheme_layout_portfolio_col', Config::DEFAULT_PORTFOLIOS_PER_ROW);
	}


	# Portfolio plugin's archive pages
	elseif (is_post_type_archive('wpdance_portfolio')) {

		# Read value from theme_mod Portfolio page layout
		$value = get_theme_mod('wpdanceclaratheme_layout_portfolio_col', Config::DEFAULT_PORTFOLIOS_PER_ROW);
	}

	# Current page is Archive page
	elseif (is_archive()) {

		# Read value from theme_mod Archive page layout
		$value = get_theme_mod('wpdanceclaratheme_layout_archive_col');
	}

	# Fall back to default value
	if (!isset($value) || empty($value))
		$value = get_theme_mod('wpdanceclaratheme_layout_general_col', 1);

	return apply_filters('wpdanceclaratheme_get_loop_col', $value);
}
endif;



if (!function_exists(__NAMESPACE__.'\\products_per_page')):
/**
 * Return number of products per page on WooCommerce shop page and category pages
 * 
 * @return int Number of products per page
 */
function products_per_page() {
	return get_theme_mod('wpdanceclaratheme_layout_wc_products_per_page', Config::DEFAULT_PRODUCTS_PER_PAGE);
}
endif;


if (!function_exists(__NAMESPACE__.'\\products_per_row')):
/**
 * Return number of products per row
 * 
 * @return int
 */
function products_per_row() {
	return get_theme_mod('wpdanceclaratheme_layout_wc_products_per_row', Config::DEFAULT_PRODUCTS_PER_ROW);
}
endif;



if (!function_exists(__NAMESPACE__.'\\is_the_post_image_style_landscape')):
/**
 * Check if the post metadata image style is landscape. 
 *
 * It checks meta key 'wpdanceclaratheme_image_style'.
 * 
 * @return boolean return TRUE if post meta key 'wpdanceclaratheme_image_style' = 'landscape'
 */
function is_the_post_image_style_landscape() {
	return get_post_meta(get_the_ID(), 'wpdanceclaratheme_image_style', true) == 'landscape';
}
endif;


if (!function_exists(__NAMESPACE__.'\\page_id_404')):
/**
 * Return ID of 404 Not Found Page
 * 
 * @return [type] [description]
 */
function page_id_404() {
	return get_theme_mod('wpdanceclaratheme_layout_pagenotfound_page');
}
endif;


if (!function_exists(__NAMESPACE__.'\\user_links')):
/**
 * Print shortcode wpdanceclaratheme_user_links or alternative content if shortcode is not activated
 * 
 * @return [type] [description]
 */
function user_links() {
	if (shortcode_exists('wpdanceclaratheme_user_links'))
		echo do_shortcode('[wpdanceclaratheme_user_links]');
	else {
		?>

		<ul class="wpdanceclaratheme-user-links">
		
		<?php
		/** User is not logged in? */
		if (!is_user_logged_in()) {
			
			/** Show 'login' link */
			echo apply_filters('wpdanceclaratheme_login_li', sprintf('<li><a href="%s">%s</a></li>', wp_login_url(), esc_html__("Login", 'wpdanceclaratheme')));
			
			/** User can register? */
			if (get_option('users_can_register')) {
				
				/** Show 'register' link */
				echo apply_filters('wpdanceclaratheme_register_li', sprintf('<li><a href="%s">%s</a></li>', wp_registration_url(), esc_html__("Register", 'wpdanceclaratheme')));
			}
		}
		
		/** User is logged in */
		else {
			/** Show 'logout' link */
			echo apply_filters('wpdanceclaratheme_logout_li', sprintf('<li><a href="%s">%s</a></li>', wp_logout_url(), esc_html__("Logout", 'wpdanceclaratheme')));
		}
		?>
		
		</ul><!-- .wpdanceclaratheme-user-links -->

		<?php
	}
}
endif;




function site_header() {
	if (shortcode_exists('wpdanceclaratheme_site_header'))
		echo do_shortcode('[wpdanceclaratheme_user_links]');
	else {
		$header_logo = get_theme_mod('wpdanceclaratheme_header_logo');
		$hide_site_title = get_theme_mod('wpdanceclaratheme_hide_site_title');
		$hide_site_desc = get_theme_mod('wpdanceclaratheme_hide_site_desc');
		?>

		<div class="header-main">
			<a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<?php if ($header_logo): ?>
					<img id="site-logo" class="site-logo" src="<?php echo esc_attr($header_logo) ?>" alt="<?php echo esc_attr(bloginfo('name')) ?>" title="<?php echo esc_attr(bloginfo('name')) ?>" />
				<?php endif ?>
			
				<?php if (!$hide_site_title): ?>
					<?php if (is_front_page() && is_home()): ?>
						<h1 class="site-title" rel="home"><?php bloginfo( 'name' ); ?></h1>
					<?php else: ?>
						<p class="site-title" rel="home"><?php bloginfo( 'name' ); ?></p>
					<?php endif ?>
				<?php endif ?>
			
				<?php if (!$hide_site_desc): ?>
				<p class="site-description"><?php bloginfo( 'description' ); ?></p>
				<?php endif; ?>
			</a>
		</div>

		<?php
	}
}

if (!function_exists(__NAMESPACE__.'\\hide_credit_link')):
/**
 * Check if theme config Hide Credit Link is checked
 * 
 * @return boolean
 */
function hide_credit_link() {
	return (boolean)get_theme_mod('wpdanceclaratheme_footer_hide_credit_link', false);
}
endif;


if (!function_exists(__NAMESPACE__.'\\translate_array_keys')):
/**
 * Translate the keys of array
 *
 * Use for translating dropdown boxes of VC shortcode mapping.
 * 
 * @param  array $a        Array variable to translate
 * @param  string $domain  Translation domain
 * @return array           New array
 */
function translate_array_keys($a, $domain = null) {
	if (!$domain) $domain = Config::TRANSLATION_DOMAIN;
	$ret = array();
	foreach ($a as $k => $v)
		$ret[ esc_html__($k) ] = $v;
	return $ret;
}
endif;

