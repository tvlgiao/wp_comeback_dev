<?php
namespace wpdanceclaratheme;


/**
 * Class Config
 */
class Config {
	const VERSION = '1.0.1';

	const CSS_DEV_MODE = false;
	// const CSS_DEV_MODE = true;

	const PHP_DEV_MODE = false;
	// const PHP_DEV_MODE = true;

	const TRANSLATION_DOMAIN = 'wpdanceclaratheme';

	const DEFAULT_PRODUCTS_PER_PAGE = 12;

	const DEFAULT_PRODUCTS_PER_ROW = 4;

	const DEFAULT_PORTFOLIOS_PER_ROW = 5;

	/**
	 * Specify Google Fonts which always import in the theme 
	 * regardless configure in Customizer
	 * @var array
	 */
	// public static $include_google_fonts = '';
	public static $include_google_fonts = array('Lato' => 'Lato:400,100,300,100italic,300italic,400italic,700,700italic,900,900italic');


	public static $google_api_key = "AIzaSyCJbMR0tu0nJbbo-pCFWWf1lhTTYmBCASU";

	/**
	 * Specific available presets supported by the theme
	 *
	 * Configure here for faster look up instead of checking file exists
	 * 
	 * @var array
	 */
	public static $presets = array(
		'default', 
		'nunito', 
		'garden', 
		'interior', 
		'healthcare',
		'comeback_furniture',
		'comeback_jewelry',
		'comeback_perfume',
		'comeback_mediacenter',
		'comeback_furnish',
	);

	public static $menu_locations = array(
		'primary' => "Main Menu",
		// 'secondary' => "Secondary Menu",
	);
	public static $default_menu_location = 'primary';


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

	/**
	 * Define all Styles supported for VC Shortcode Dropdown Cart
	 * 
	 * @var array
	 */
	public static $dropdown_cart_styles = array(
		"Text Only"    => '',
		"Icon and Qty" => 'icon-qty',
		"Icon Left"    => 'icon-left',
	);

	/**
	 * Define all banner styles supported for VC Single Image Hover shortcode
	 * 
	 * @var array
	 */
	public static $banner_styles = array(
		"No Style"   => '',
		"Comeback 1" => 'comeback_1',
	);

	/**
	 * Define all banner hover effects list supported for VC Single Image Hover shortcode
	 * 
	 * @var array
	 */
	public static $banner_hover_effects = array(
		"No Effect"              => '',
		"Zoom In"                => 'zoom_in',
		"Zoom In - Rotate 15deg" => 'zoom_in_rot15',
		"Blur"                   => 'blur',
		"Darker"                 => 'darker',
		"Brighter"               => 'brighter',
		"Sepia"                  => 'sepia',
		"Grayscale"              => 'grayscale',
	);

	/**
	 * Specify UberMenu menus to be import when the theme is activate
	 *
	 * Theme will look into directory import-data/ubermenu/ find file matches
	 * with menu name to import settings.
	 * 
	 * @var array
	 */
	public static $ubermenu_import = array(
		'wpdanceclaratheme-horizontal-menu',
		'wpdanceclaratheme-vertical-menu',
	);


	/**
	 * Specify Revolution sliders slug name to be check in the theme Walkthrough page
	 *
	 * @var array
	 */
	public static $revslider_check = array(
		'home-01',
		'home-02',
		'home-03',
		'home-04',
	);

	public static $userguide_url = 'https://tvlgiao.github.io/wpdance-clara-docs/';

	public static $support_url = 'http://codespotsupport.com/others/open.php';

	// public static $rating_url = 'https://themeforest.net/purchases/-/ratings?rating=5';
	public static $rating_url = 'https://themeforest.net/downloads?ref=tvlgiao';
}
