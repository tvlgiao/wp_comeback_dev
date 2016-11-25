<?php
namespace wpdanceclaratheme\action;



###########################################################################
# DEFINE WP HOOKS
###########################################################################

add_action('after_setup_theme', __NAMESPACE__.'\\after_setup_theme');
add_action('wp_enqueue_scripts', __NAMESPACE__.'\\wp_enqueue_scripts');
add_action('widgets_init', __NAMESPACE__.'\\widgets_init');
add_action('template_redirect', __NAMESPACE__.'\\template_redirect');
add_action('export_filters', __NAMESPACE__.'\\export_filters');
add_action('rss2_head', __NAMESPACE__.'\\rss2_head');




###########################################################################
# DEFINE HOOK CALLBACK FUNCTIONS
###########################################################################


if (!(function_exists(__NAMESPACE__.'\\after_setup_theme'))):
/**
 * Callback function for hook 'after_setup_theme'
 */
function after_setup_theme() {
	/*
	 * Makes WPDanceClaraTheme available for translation in directory /languages
	 */
	load_theme_textdomain('wpdanceclaratheme', get_template_directory() . '/languages');

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style(array('css/editor-style.css', 'genericons/genericons.css', \wpdanceclaratheme\Helper\get_google_fonts_url()));

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support('automatic-feed-links');

	/*
	 * Switches default core markup for search form, comment form,
	 * and comments to output valid HTML5.
	 */
	add_theme_support('html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	));

	/*
	 * This theme supports all available post formats by default.
	 * See https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support('post-formats', array(
		'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'
	));

	// This theme uses wp_nav_menu() in one location.
	foreach (\wpdanceclaratheme\Config::$menu_locations as $name => $title) {
		register_nav_menu($name, call_user_func('_x', $title, 'menu location', 'wpdanceclaratheme'));
	}

	/**
	 * Theme support Title Tag
	 */
	add_theme_support('title-tag');
	

	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size( 750, 400, true );

	add_image_size('post-thumbnail-fullwidth', 1140, 0, false);
	add_image_size('thumbnail', 150, 150, true);
	add_image_size('medium', 300, 250, true);
	add_image_size('large', 750, 400, true);
	add_image_size('full', 1600, 1200, false);

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
endif;





if (!function_exists(__NAMESPACE__.'\\wp_enqueue_scripts')):
/**
 * Callback function for hook 'wp_enqueue_scripts'
 */
function wp_enqueue_scripts() {
	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	//if (\wpdanceclaratheme\Helper\is_page_masonry()) 
	wp_enqueue_script('jquery-masonry');
	wp_enqueue_script('imagesloaded');

	// Load Bootstrap JS
	wp_enqueue_script('bootstrap-script', get_template_directory_uri().'/bootstrap/js/bootstrap.js', array('jquery'), '3.3.7', true);
	
	// Load Bootstrap CSS
	// wp_enqueue_style('bootstrap-style', get_template_directory_uri().'/bootstrap/css/bootstrap.min.css', array(), '3.3.7');
	// wp_enqueue_style('bootstrap-theme-style', get_template_directory_uri().'/bootstrap/css/bootstrap-theme.min.css', array(), '3.3.7');

	// Hover CSS
	// wp_enqueue_style( 'hover-css', get_template_directory_uri() . '/css/hover/hover.css', array(), '2.0.2' );

	wp_register_style('owl.carousel-css', get_template_directory_uri().'/js/owl-carousel/owl.carousel.css', array(), '1.3.3');
	wp_register_style('owl.theme-css', get_template_directory_uri().'/js/owl-carousel/owl.theme.css', array(), '1.3.3');
	wp_register_style('owl.transitions-css', get_template_directory_uri().'/js/owl-carousel/owl.transitions.css', array(), '1.3.3');
	wp_register_script('owl.carousel', get_template_directory_uri().'/js/owl-carousel/owl.carousel.min.js', array('jquery'), '1.3.3', true);
	wp_register_script('wpdanceclaratheme-owlcarousel-init', get_template_directory_uri().'/js/owlcarousel-init.js', array('jquery', 'owl.carousel'), '20151106', true);

	wp_register_script('jquery-countdown', get_template_directory_uri().'/js/jquery.countdown.js', array('jquery'), '2.2.0');
	
	wp_enqueue_script('html5shiv', get_template_directory_uri() . '/js/html5.js');
	wp_script_add_data('html5shiv', 'conditional', 'lt IE 9');

	// Loads JavaScript theme helpers
	wp_enqueue_script( 'wpdanceclaratheme-lib-script', get_template_directory_uri() . '/js/wpdanceclaratheme.js', array( 'jquery' ), \wpdanceclaratheme\Config::VERSION, true);

	// Loads JavaScript file with functionality specific to WPDanceClaraTheme.
	wp_enqueue_script( 'wpdanceclaratheme-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20150330', true );

	// Add Google fonts
	if (($url = \wpdanceclaratheme\Helper\get_google_fonts_url()) != '')
		wp_enqueue_style( 'wpdanceclaratheme-google-fonts', $url, array(), null );


	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.03' );
	
	// Add dashicons font, used in the main stylesheet.
	wp_enqueue_style( 'dashicons');

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'wpdanceclaratheme-ie', get_template_directory_uri() . '/css/ie.css', array( 'wpdanceclaratheme-style' ), '2013-07-18' );
	wp_style_add_data( 'wpdanceclaratheme-ie', 'conditional', 'lt IE 9' );


}
endif;




if (!function_exists(__NAMESPACE__.'\\widgets_init')):
/**
 * Register two widget areas.
 */
function widgets_init() {

	register_sidebar( array(
		'name'          => esc_html__( 'Main Sidebar', 'wpdanceclaratheme' ),
		'id'            => 'sidebar-2',
		'description'   => esc_html__( 'Appears on posts and pages in the sidebar.', 'wpdanceclaratheme' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );

	register_sidebar(array(
		'name' => esc_html__("Before Main Content", 'wpdanceclaratheme'),
		'id' => 'wpdanceclaratheme-before-content',
		'description' => esc_html__("Appears before output main content", 'wpdanceclaratheme'),
		'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	));

	register_sidebar(array(
		'name' => esc_html__("After Main Content", 'wpdanceclaratheme'),
		'id' => 'wpdanceclaratheme-after-content',
		'description' => esc_html__("Appears after output main content", 'wpdanceclaratheme'),
		'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	));
}
endif;




if (!function_exists(__NAMESPACE__.'\\template_redirect')):
/**
 * Callback function for hook 'template_redirect'
 */
function template_redirect() {
	global $content_width;

	/*
	 * Adjust content_width value for video post formats and attachment templates.
	 */

	if ( is_attachment() )
		$content_width = 724;
	elseif ( has_post_format( 'audio' ) )
		$content_width = 484;
}
endif;





if (!function_exists(__NAMESPACE__.'\\export_filters')):
/**
 * Allow WP Export navigation menu items 
 */
function export_filters() {
	?>
	<p><label><input type="radio" name="content" value="nav_menu_item" /> <?php echo esc_html( "Navigation Menus", 'wpdanceclaratheme' ); ?></label></p>
	<?php
}
endif;


if (!function_exists(__NAMESPACE__.'\\rss2_head')):
function rss2_head() {
	if (isset($_GET['content']) && $_GET['content'] == 'nav_menu_item') {
		wxr_nav_menu_terms();
	}
}
endif;



