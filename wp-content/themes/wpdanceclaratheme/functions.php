<?php
/**
 * WPDanceClaraTheme functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme (see https://codex.wordpress.org/Theme_Development
 * and https://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link https://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @since WPDanceClaraTheme 1.0
 */


/**
 * Include the entire framework
 */
require_once get_template_directory().'/inc/wpdanceclaratheme.php';




/*
 * Set up the content width value based on the theme's design.
 *
 * @see wpdanceclaratheme\action\template_redirect() for template-specific adjustments.
 */
if ( ! isset( $content_width ) )
	$content_width = 750;



