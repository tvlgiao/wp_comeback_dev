<?php
/**
 * Plugin Name:  WPDanceClaraTheme Toolkit
 * Plugin URI:   https://wordpress.org/plugins/wpdanceclaratheme-toolkit/
 * Description:  Required plugin contains library, shortcodes to support the WordPress theme WPDanceClaraTheme
 * Author:       tvlgiao
 * Author URI:   http://wpdance.com
 * Contributors: tvlgiao
 * Version:      1.0.0
 * Text Domain:  wpdanceclaratheme-toolkit
 * Domain Path:  /languages
 * License:      Commercial License
 * License URI:  license.txt
 */

namespace wpdanceclaratheme\toolkit;

if (!defined('WPDANCECLARATHEME_TOOLKIT'))
	define('WPDANCECLARATHEME_TOOLKIT', 1);

require_once dirname(__FILE__).'/shortcodes.php';



// add_action('init', __NAMESPACE__.'\\init');
add_action('plugins_loaded', __NAMESPACE__.'\\plugins_loaded');



if (!function_exists(__NAMESPACE__.'\\plugins_loaded')):
function plugins_loaded() {
	load_plugin_textdomain( 'wpdanceclaratheme-toolkit', false, dirname(__FILE__).'/languages/' );
}
endif;


class Config {
	/**
	 * Allow html tags in translation functions
	 */
	public static $allowed_html = array(
		'a' => array(
			'href' => array(),
			'class' => array(),
			'title' => array(),
		),
		'br' => array(
			'class' => array(),
		),
		'em' => array(
			'class' => array(),
		),
		'strong' => array(
			'class' => array(),
		),
		'p' => array(
			'class' => array(),
		),
		'code' => array(
			'class' => array(),
		),
		'blockquote' => array(
			'class' => array(),
		),
		'span' => array(
			'class' => array(),
		),
		'div' => array(
			'class' => array(),
		),
		'ul' => array(
			'class' => array(),
		),
		'ol' => array(
			'class' => array(),
		),
		'li' => array(
			'class' => array(),
		),
		'dl' => array(
			'class' => array(),
		),
		'dd' => array(
			'class' => array(),
		),
		'dt' => array(
			'class' => array(),
		),
		'time' => array(
			'class' => array(),
			'datetime' => array(),
		),
	);
}
