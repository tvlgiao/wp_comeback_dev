<?php
namespace wpdanceclaratheme\Customizer;

# Stop if not viewing customizer
if (!function_exists('is_customize_preview') || !is_customize_preview()) return;

require_once ABSPATH . WPINC . '/class-wp-customize-control.php';

require_once get_template_directory().'/inc/wpdanceclaratheme/customizer/macrocustomizer.class.php';
require_once get_template_directory().'/inc/wpdanceclaratheme/customizer/font_control.class.php';
require_once get_template_directory().'/inc/wpdanceclaratheme/customizer/group_control.class.php';
require_once get_template_directory().'/inc/wpdanceclaratheme/customizer/group_control.class.php';
require_once get_template_directory().'/inc/wpdanceclaratheme/customizer/multiple_select_control.class.php';
require_once get_template_directory().'/inc/wpdanceclaratheme/customizer/preset_control.class.php';
require_once get_template_directory().'/inc/wpdanceclaratheme/customizer/import_control.class.php';
require_once get_template_directory().'/inc/wpdanceclaratheme/customizer/export_control.class.php';
require_once get_template_directory().'/inc/wpdanceclaratheme/customizer/reset_control.class.php';
require_once get_template_directory().'/inc/wpdanceclaratheme/customizer/htmlblock_control.class.php';



###########################################################################
### DEFINE ALL WP HOOKS IN THIS NAMESPACE HERE
###########################################################################

add_action('customize_register', __NAMESPACE__.'\\customize_register');
add_action('customize_controls_enqueue_scripts', __NAMESPACE__.'\\customize_controls_enqueue_scripts');
add_action('customize_save', __NAMESPACE__.'\\customize_save');





###########################################################################
### DEFINE HOOK FUNCTIONS HERE
###########################################################################


if (!function_exists(__NAMESPACE__.'\\customize_register')):
/**
 * Function hooks action 'customize_register'
 *
 * @param WP_Customize_Manager $wpc
 */
function customize_register($wpc) {

	wp_register_script('wpdanceclaratheme-customizer', get_template_directory_uri().'/js/customizer.js', array('customize-controls', 'iris', 'underscore', 'wp-util'), \wpdanceclaratheme\Config::VERSION, true);
	wp_register_style('wpdanceclaratheme-customizer', get_template_directory_uri().'/css/customizer.css', array(), \wpdanceclaratheme\Config::VERSION);

	wp_register_script('select2', get_template_directory_uri().'/js/select2/js/select2.js', array(), '4.0.3', true);
	wp_register_style('select2', get_template_directory_uri().'/js/select2/css/select2.css', array(), '4.0.3');

	#
	# Register my control types in order to make content_template() work
	#
	$wpc->register_control_type(__NAMESPACE__.'\\Font_Control');


	#
	# Build our theme settings in Customizer
	#
	panel_typo_color($wpc);
	panel_header($wpc);
	panel_footer($wpc);
	panel_layout($wpc);
	panel_advance($wpc);

}
endif;




if (!function_exists(__NAMESPACE__.'\\customize_controls_enqueue_scripts')):
/**
 * Function hooks action 'customize_controls_enqueue_scripts' 
 * to enqueue our customizer's scripts & styles
 */
function customize_controls_enqueue_scripts() {
	wp_enqueue_script('wpdanceclaratheme-customizer');
	wp_enqueue_style('wpdanceclaratheme-customizer');

	wp_enqueue_script('select2');
	wp_enqueue_style('select2');
}
endif;



if (!function_exists(__NAMESPACE__.'\\customize_save')):
/**
 * Function hooks action 'customize_save'
 *
 * @param WP_Customize_Manager $wpc
 */
function customize_save($wpc) {

	$clear_preset = false;

	#
	# Check if setting value is same default value, then unset value in theme mods
	#
	$settings = $wpc->settings();
	foreach ($settings as $setting) {

		# Only check our theme settings
		if (preg_match('/^wpdanceclaratheme/', $setting->id)) {

			# Check post value is same default value
			$post_value = $setting->post_value();

			if (isset($post_value)) {

				if (is_array($post_value))
					$same = empty($setting->default) 
							? empty($post_value) 
							: empty(array_diff($post_value, (array)$setting->default));
				else
					$same = strcasecmp($post_value, $setting->default) == 0 
							|| is_color($post_value) && same_color($post_value, $setting->default);


				if ($same) {
					# Remove setting value from theme mod
					remove_theme_mod($setting->id);
					// file_put_contents('remove_theme_mod', "{$setting->id}\n", FILE_APPEND);

					# Remove value from POST so that it does not save
					$wpc->set_post_value($setting->id, NULL);
				}

				# if save values contain typo & color values
				# we will delete all preset css files
				elseif (preg_match('/^wpdanceclaratheme_style_/', $setting->id))
					$clear_preset = true;
			}
		}
	}


	# Delete all css/preset-*.css & map files
	# if save value contain typo & colors value
	if ($clear_preset) {
		\wpdanceclaratheme\Scss\Preset\delete_all_presets_css();

		# delete google fonts URL cached
		remove_theme_mod('wpdanceclaratheme_google_fonts_url');
	}
}
endif;


if (!function_exists(__NAMESPACE__.'\\is_color')):
/**
 * Check is value is color #xxxxxx or #xxx
 *
 * @param string $value
 * @return boolean
 */
function is_color($value) {
	$len = strlen($value);
	return ($len == 4 || $len == 7) && preg_match('/^#[0-9a-f]+$/i', $value);
}
endif;


if (!function_exists(__NAMESPACE__.'\\same_color')):
/**
 * Check if 2 value are color and equal
 *
 * @param string $a
 * @param string $b
 * @return boolean
 */
function same_color($a, $b) {
	if (strlen($a) == 4) $a = $a[0].$a[1].$a[1].$a[2].$a[2].$a[3].$a[3];
	if (strlen($b) == 4) $b = $b[0].$b[1].$b[1].$b[2].$b[2].$b[3].$b[3];

	return (strcasecmp($a, $b) == 0);
}
endif;






if (!function_exists(__NAMESPACE__.'\\panel_typo_color')):
/**
 * Add panel Theme - Typography & Color to Customizer
 *
 * @param WP_Customize_Manager $wpc
 */

function panel_typo_color($wpc) {


	/**
	 * Retrieve Preset from fallback:
	 * 1. Customizer's POST
	 * 2. theme mod
	 * 3. 'default'
	 * 
	 * Then set preset's variables as default value of the settings
	 *
	 * @var string $preset_name 
	 */

	$post_values = $wpc->unsanitized_post_values();

	$k = 'wpdanceclaratheme_style_presets_preset';

	if (array_key_exists($k, $post_values) && !empty($post_values[$k]))
		$preset_name = $post_values[$k];
	else
		$preset_name = get_theme_mod($k, 'default');

	$preset_vars = \wpdanceclaratheme\Scss\Preset\get_preset_vars($preset_name);

	#
	# Set up customizer controls
	#
	$mc = MacroCustomizer::instance()
		->set_wp_customize_manager($wpc)
		->set_vars(\wpdanceclaratheme\Scss\Preset\get_preset_vars($preset_name))


		# Theme - Typo & Colors
		->add_panel('style', "Typography & Color")

		# -----------------------------------------------------------------

		# Section: Preset
		->add_section('presets', "Presets")
		->add_control_preset('preset', "Preset")

		# Section: General
		->add_section('general', "General")

		# Schema
		->add_group("Color Schema")
		->add_control_color('color1', "Color 1")
		->add_control_color('color2', "Color 2")
		->add_control_color('color3', "Color 3")
		->add_control_color('color4', "Color 4")
		->add_control_color('color5', "Color 5")
		->add_control_color('bgcolor1', "Background Color 1")
		->add_control_color('bgcolor2', "Background Color 2")
		->add_control_color('bgcolor3', "Background Color 3")
		->add_control_color('bgcolor4', "Background Color 4")
		->add_control_color('bgcolor5', "Background Color 5")
		->add_control_color('bordercolor1', "Border Color 1")
		->add_control_color('bordercolor2', "Border Color 2")
		->add_control_color('bordercolor3', "Border Color 3")
		->add_control_color('bordercolor4', "Border Color 4")
		->add_control_color('bordercolor5', "Border Color 5")

		# Body
		->add_group("Body")
		->add_control_typo('body', "Body")
		->add_control_color('body_color', "Body Color");

		# H1..H6
		for ($i = 1; $i <= 6; $i++) {
			$mc ->add_group("H".$i)
				->add_control_typo('h'.$i, "H".$i)
				->add_control_color('h'.$i.'_color', "H".$i." Color");
		}

		# Link & Link Hover
	$mc ->add_group("Link")
		->add_control_typo('link', "Link")
		->add_control_color('link_color', "Link Color")
		->add_control_typo('link_hover', "Link Hover")
		->add_control_color('link_hover_color', "Link Hover Color")

		# Button - Hover
		->add_group("Button")
		->add_control_typo('button', "")
		->add_control_color('button_color', "Color")
		->add_control_color('button_hover_color', "Hover Color")
		->add_control_color('button_bgcolor', "Background Color")
		->add_control_color('button_hover_bgcolor', "Hover Background Color")
		->add_control_color('button_bordercolor', "Border Color")
		->add_control_color('button_hover_bordercolor', "Hover Border Color")
		->add_control_text('button_borderradius', "Border Radius", "Border radius example: 2px 2px 2px 2px.")

		# Other
		->add_group("Other")
		->add_control_google_font_subsets('google_font_subsets', "Google Font Subsets")

		# -----------------------------------------------------------------

		->add_section('header', "Header")

		# Colors
		->add_group("Colors")
		->add_control_color('color1', "Color 1")
		->add_control_color('color2', "Color 2")
		->add_control_color('color3', "Color 3")
		->add_control_color('color4', "Color 4")
		->add_control_color('color5', "Color 5")
		->add_control_color('bgcolor1', "Background Color 1")
		->add_control_color('bgcolor2', "Background Color 2")
		->add_control_color('bgcolor3', "Background Color 3")
		->add_control_color('bgcolor4', "Background Color 4")
		->add_control_color('bgcolor5', "Background Color 5")

		# -----------------------------------------------------------------

		->add_section('footer', "Footer")

		# Colors
		->add_group("Colors")
		->add_control_color('color1', "Color 1")
		->add_control_color('color2', "Color 2")
		->add_control_color('color3', "Color 3")
		->add_control_color('color4', "Color 4")
		->add_control_color('color5', "Color 5")
		->add_control_color('bgcolor1', "Background Color 1")
		->add_control_color('bgcolor2', "Background Color 2")
		->add_control_color('bgcolor3', "Background Color 3")
		->add_control_color('bgcolor4', "Background Color 4")
		->add_control_color('bgcolor5', "Background Color 5")


		# -----------------------------------------------------------------

		# Section: Main Heading
		->add_section('heading', "Page Heading")

		# Container
		->add_group("Container")
		->add_control_color('bgcolor', "Background Color")
		->add_control_image('bgimage', "Background Image")

		# Title
		->add_group("Title")
		->add_control_typo('title', "Title")
		->add_control_color('title_color', "Title Color")

		# Content
		->add_group("Content")
		->add_control_typo('content', "Content")
		->add_control_color('content_color', "Content Color")


		# -----------------------------------------------------------------

		# Section: Loop Posts
		->add_section('loop', "Loop Posts")

		# Title
		->add_group("Article Title")
		->add_control_typo('title', "Title")
		->add_control_color('title_color', "Title Color")

		# Content
		->add_group("Article Content")
		->add_control_typo('content', "Content")
		->add_control_color('content_color', "Content Color")

		# Meta
		->add_group("Article Meta")
		->add_control_typo('meta', "Meta")
		->add_control_color('meta_color', "Meta Color")


		# -----------------------------------------------------------------

		# Section: Single Post
		->add_section('single', "Single Post")

		# Title
		->add_group("Article Title")
		->add_control_typo('title', "Title")
		->add_control_color('title_color', "Title Color")

		# Content
		->add_group("Article Content")
		->add_control_typo('content', "Content")
		->add_control_color('content_color', "Content Color")

		# Meta
		->add_group("Article Meta")
		->add_control_typo('meta', "Meta")
		->add_control_color('meta_color', "Meta Color")


		# -----------------------------------------------------------------

		# Section: Widgets
		->add_section('widget', "Widgets")

		# Title
		->add_group("Title")
		->add_control_typo('title', "Title")
		->add_control_color('title_color', "Color")
		->add_control_color('title_bgcolor', "Background Color")

		# Content
		->add_group("Content")
		->add_control_typo('content', "Content")
		->add_control_color('content_color', "Color")
		->add_control_color('content_bgcolor', "Background Color")
		->add_control_color('content_bordercolor', "Border Color")

		# -----------------------------------------------------------------

		# Section: Breadcrumbs
		->add_section('breadcrumb', "Breadcrumbs")

		# Parent Items
		->add_group("Parent Items")
		->add_control_typo('parent', "Parent Items")
		->add_control_color('parent_color', "Parent Items Color")

		# Current Item
		->add_group("Current Item")
		->add_control_typo('current', "Current Item")
		->add_control_color('current_color', "Current Item Color")

		# -----------------------------------------------------------------

		# Section: Main Navigation
		->add_section('menu', 'Main Navigation')

		->add_group("Root Level Menu")
		->add_control_typo('root', "Root Level Menu")
		->add_control_color('root_color1', "Normal Color")
		->add_control_color('root_color2', "Active Color")
		->add_control_color('root_bgcolor1', "Normal Background Color")
		->add_control_color('root_bgcolor2', "Active Background Color")
		->add_control_color('root_bordercolor', "Border Color")

		->add_group("Sub Level Menu")
		->add_control_typo('sub', "Sub Level Menu")
		->add_control_color('sub_color1', "Normal Color")
		->add_control_color('sub_color2', "Active Color")
		->add_control_color('sub_bgcolor1', "Normal Background Color")
		->add_control_color('sub_bgcolor2', "Active Background Color")
		->add_control_color('sub_bordercolor', "Border Color");
}
endif; /* function_exists() */







if (!function_exists(__NAMESPACE__.'\\panel_header')):
/**
 * Add panel Theme - Header to Customizer
 *
 * @param WP_Customize_Manager $wpc
 */
function panel_header($wpc) {
	
	# Add new section Header Settings in theme customizer
	$wpc->add_section('wpdanceclaratheme_header', array(
		'title' => esc_html_x("Header", 'customizer', 'wpdanceclaratheme'),
		// 'priority' => 200
	));
	
	
	# Add setting header_logo to store the logo image display on page header
	$wpc->add_setting('wpdanceclaratheme_header_logo');
	$wpc->add_control(new \WP_Customize_Image_Control($wpc, 'wpdanceclaratheme_header_logo', array(
		'label'         => esc_html__("Header Logo", 'wpdanceclaratheme'),
		'description'   => esc_html__("Upload a logo image to display on header", "wpdanceclaratheme"),
		'section'       => 'wpdanceclaratheme_header',
		'settings'      => 'wpdanceclaratheme_header_logo'
	)));
	
	# Add setting to allow hide site title in header
	$wpc->add_setting('wpdanceclaratheme_hide_site_title');
	$wpc->add_control('wpdanceclaratheme_hide_site_title', array(
		'type'          => 'checkbox',
		'label'         => esc_html__("Hide Site Title", 'wpdanceclaratheme'),
		'description'   => esc_html__("If check, hide site title on the header", 'wpdanceclaratheme'),
		'section'       => 'wpdanceclaratheme_header',
		'settings'      => 'wpdanceclaratheme_hide_site_title'
	));

	# Add setting to allow hide site description in header
	$wpc->add_setting('wpdanceclaratheme_hide_site_desc');
	$wpc->add_control('wpdanceclaratheme_hide_site_desc', array(
		'type'          => 'checkbox',
		'label'         => esc_html__("Hide Site Description", 'wpdanceclaratheme'),
		'description'   => esc_html__("If check, hide site description on the header", 'wpdanceclaratheme'),
		'section'       => 'wpdanceclaratheme_header',
		'settings'      => 'wpdanceclaratheme_hide_site_desc'
	));


	if (defined('WPDANCE_HTMLBLOCK')) {

		# Add new setting header_htmlblock to display proper HTML Block
		$wpc->add_setting('wpdanceclaratheme_header_htmlblock');
		$wpc->add_control(new HTMLBlock_Control($wpc, 'wpdanceclaratheme_header_htmlblock', array(
			'label'            => esc_html__("Header HTML Block", 'wpdanceclaratheme'),
			'description'      => esc_html__("Select HTML Block that shows on the header", 'wpdanceclaratheme'),
			'section'          => 'wpdanceclaratheme_header',
			'settings'         => 'wpdanceclaratheme_header_htmlblock',
			'htmlblock_type'   => 'header',
		)));
	}
	
}
endif; /* function_exists() */





if (!function_exists(__NAMESPACE__.'\\panel_footer')):
/**
 * Add panel Theme - Footer to Customizer
 *
 * @param WP_Customize_Manager $wpc
 */
function panel_footer($wpc) {
	
	# Add new section Footer Settings in theme customizer
	$wpc->add_section('wpdanceclaratheme_footer', array(
		'title' => esc_html_x("Footer", 'customizer', 'wpdanceclaratheme'),
		// 'priority' => 210
	));
	
	if (defined('WPDANCE_HTMLBLOCK')) {

		# Add new setting header_htmlblock to display proper HTML Block
		$wpc->add_setting('wpdanceclaratheme_footer_htmlblock');
		$wpc->add_control(new HTMLBlock_Control($wpc, 'wpdanceclaratheme_footer_htmlblock', array(
			'label'            => esc_html__("Footer HTML Block", 'wpdanceclaratheme'),
			'description'      => esc_html__("Select HTML Block that shows on the footer", 'wpdanceclaratheme'),
			'section'          => 'wpdanceclaratheme_footer',
			'settings'         => 'wpdanceclaratheme_footer_htmlblock',
			'htmlblock_type'   => 'footer',
		)));
	}

	# Checkbox Control Hide Credit Links
	$wpc->add_setting('wpdanceclaratheme_footer_hide_credit_link');
	$wpc->add_control('wpdanceclaratheme_footer_hide_credit_link', array(
		'label'         => esc_html__("Hide Credit Links", 'wpdanceclaratheme'),
		'description'   => esc_html__("Check to hide WordPress Power By credit link and the theme credit link", 'wpdanceclaratheme'),
		'section'       => 'wpdanceclaratheme_footer',
		'settings'      => 'wpdanceclaratheme_footer_hide_credit_link',
		'type'          => 'checkbox',
	));
}
endif; /* function_exists() */







if (!function_exists(__NAMESPACE__.'\\panel_layout')):
/**
 * Add panel Theme - Layout to Customizer
 *
 * @param WP_Customize_Manager $wpc
 */
function panel_layout($wpc) {

	$wpc->add_panel($cur_panel = 'wpdanceclaratheme_layout', array(
		'title' => esc_html_x("Layout", 'customizer', 'wpdanceclaratheme'),
		// 'priority' => 240
	));



	# General Section
	$wpc->add_section($cur_section = 'wpdanceclaratheme_layout_general_section', array(
		'title' => esc_html__("General", 'wpdanceclaratheme'),
		'panel' => $cur_panel,
	));
	
	# Heading Position
	$wpc->add_setting('wpdanceclaratheme_layout_general_page_title_position', array(
		'default' => 'top'
	));
	$wpc->add_control('wpdanceclaratheme_layout_general_page_title_position', array(
		'label'         => esc_html__("Page Title Position", 'wpdanceclaratheme'),
		'section'       => $cur_section,
		'settings'      => 'wpdanceclaratheme_layout_general_page_title_position',
		'type'          => 'select',
		'choices'       => array(
			'standard'   => esc_html__("Standard", 'wpdanceclaratheme'),
			'top'        => esc_html__("Top", 'wpdanceclaratheme'),
			'top-center' => esc_html__("Top-center", 'wpdanceclaratheme'))
	));
	
	# Posts Pagination
	$wpc->add_setting('wpdanceclaratheme_layout_general_posts_pagination', array(
		'default' => 'default'
	));
	$wpc->add_control('wpdanceclaratheme_layout_general_posts_pagination', array(
		'label'         => esc_html__("Posts Pagination Type", 'wpdanceclaratheme'),
		'section'       => $cur_section,
		'settings'      => 'wpdanceclaratheme_layout_general_posts_pagination',
		'type'          => 'select',
		'choices'       => array(
								'default' => esc_html__("Next / Previous", 'wpdanceclaratheme'),
								'number' => esc_html__("Numbers", 'wpdanceclaratheme'))
	));

	_add_control_layout($wpc, 'wpdanceclaratheme_layout_general', $cur_section, false);
	_add_control_col($wpc, 'wpdanceclaratheme_layout_general_col', $cur_section, 1);
	_add_control_image_position($wpc, 'wpdanceclaratheme_layout_general_image_position', $cur_section, false);
	_add_control_masonry($wpc, 'wpdanceclaratheme_layout_general_masonry', $cur_section, 'checkbox');
	_add_control_excerpt($wpc, 'wpdanceclaratheme_layout_general_excerpt', $cur_section, 'checkbox');



	if (!class_exists('Advanced_Excerpt')) {
		# Advance Excerpt: Exclude excerpt the_content()
		$wpc->add_setting('wpdanceclaratheme_layout_general_excerpt_exclude_content', array(
			'default' => true
		));
		$wpc->add_control('wpdanceclaratheme_layout_general_excerpt_exclude_content', array(
			'label'         => esc_html__("Don't excerpt for the_content()", 'wpdanceclaratheme'),
			'description'   => wp_kses(__("Plugin <strong>advanced-excerpt</strong> occurs error when preview page in WordPress Customizer. A workaround is excluding excerpt filter for the_content().", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html),
			'section'       => $cur_section,
			'settings'      => 'wpdanceclaratheme_layout_general_excerpt_exclude_content',
			'type'          => 'checkbox',
		));
	}


	# -------------------------------------------------
	

	# Home Page Section
	$wpc->add_section($cur_section = 'wpdanceclaratheme_layout_home_section', array(
		'title' => esc_html__("Homepage", 'wpdanceclaratheme'),
		'panel' => $cur_panel,
	));

	_add_control_layout($wpc, 'wpdanceclaratheme_layout_home', $cur_section);
	_add_control_col($wpc, 'wpdanceclaratheme_layout_home_col', $cur_section);
	_add_control_image_position($wpc, 'wpdanceclaratheme_layout_home_image_position', $cur_section);
	_add_control_masonry($wpc, 'wpdanceclaratheme_layout_home_masonry', $cur_section);
	_add_control_excerpt($wpc, 'wpdanceclaratheme_layout_home_excerpt', $cur_section);

	

	
	# -------------------------------------------------
	
	
	# Blog Section
	$wpc->add_section($cur_section = 'wpdanceclaratheme_layout_blog_section', array(
		'title' => esc_html__("Post Categories", 'wpdanceclaratheme'),
		'panel' => $cur_panel,
	));
	
	_add_control_layout($wpc, 'wpdanceclaratheme_layout_blog', $cur_section);
	_add_control_col($wpc, 'wpdanceclaratheme_layout_blog_col', $cur_section);
	_add_control_image_position($wpc, 'wpdanceclaratheme_layout_blog_image_position', $cur_section);
	_add_control_masonry($wpc, 'wpdanceclaratheme_layout_blog_masonry', $cur_section);
	_add_control_excerpt($wpc, 'wpdanceclaratheme_layout_blog_excerpt', $cur_section);
	


	# -------------------------------------------------
	
	
	# Archive Section
	$wpc->add_section($cur_section = 'wpdanceclaratheme_layout_archive_section', array(
		'title' => esc_html__("Archive Pages", 'wpdanceclaratheme'),
		'panel' => $cur_panel,
	));

	_add_control_layout($wpc, 'wpdanceclaratheme_layout_archive', $cur_section);
	_add_control_col($wpc, 'wpdanceclaratheme_layout_archive_col', $cur_section);
	_add_control_image_position($wpc, 'wpdanceclaratheme_layout_archive_image_position', $cur_section);
	_add_control_masonry($wpc, 'wpdanceclaratheme_layout_archive_masonry', $cur_section);
	_add_control_excerpt($wpc, 'wpdanceclaratheme_layout_archive_excerpt', $cur_section);
	

	# -------------------------------------------------
	
	
	# Tag Section
	$wpc->add_section($cur_section = 'wpdanceclaratheme_layout_tag_section', array(
		'title' => esc_html__("Tag Page", 'wpdanceclaratheme'),
		'panel' => $cur_panel,
	));

	_add_control_layout($wpc, 'wpdanceclaratheme_layout_tag', $cur_section);
	_add_control_col($wpc, 'wpdanceclaratheme_layout_tag_col', $cur_section);
	_add_control_image_position($wpc, 'wpdanceclaratheme_layout_tag_image_position', $cur_section);
	_add_control_masonry($wpc, 'wpdanceclaratheme_layout_tag_masonry', $cur_section);
	_add_control_excerpt($wpc, 'wpdanceclaratheme_layout_tag_excerpt', $cur_section);


	# -------------------------------------------------
	
	
	# Author Section
	$wpc->add_section($cur_section = 'wpdanceclaratheme_layout_author_section', array(
		'title' => esc_html__("Author Page", 'wpdanceclaratheme'),
		'panel' => $cur_panel,
	));
	
	_add_control_layout($wpc, 'wpdanceclaratheme_layout_author', $cur_section);
	_add_control_col($wpc, 'wpdanceclaratheme_layout_author_col', $cur_section);
	_add_control_image_position($wpc, 'wpdanceclaratheme_layout_author_image_position', $cur_section);
	_add_control_masonry($wpc, 'wpdanceclaratheme_layout_author_masonry', $cur_section);
	_add_control_excerpt($wpc, 'wpdanceclaratheme_layout_author_excerpt', $cur_section);
	
	# -------------------------------------------------
	
	
	# Singe Post Section
	$wpc->add_section($cur_section = 'wpdanceclaratheme_layout_single_section', array(
		'title' => esc_html__("Single Post", 'wpdanceclaratheme'),
		'panel' => $cur_panel,
	));

	_add_control_image_position($wpc, 'wpdanceclaratheme_layout_single_image_position', $cur_section);
	_add_control_layout($wpc, 'wpdanceclaratheme_layout_single', $cur_section);


	# -------------------------------------------------
	# THEME LAYOUT > WOOCOMMERCE
	# 
	
	section_layout_woocommerce($wpc, $cur_panel);


	# -------------------------------------------------
	# THEME LAYOUT > PORTFOLIO
	# 
	
	section_layout_portfolio($wpc, $cur_panel);


	# -------------------------------------------------
	# THEME LAYOUT > 404 Not Found
	# 
	
	section_layout_notfound($wpc, $cur_panel);



}
endif; /* function_exists() */





if (!function_exists(__NAMESPACE__.'\\section_layout_woocommerce')):
/**
 * Show section WooCommerce on panel Theme - Layout
 * 
 * @param  WP_Customize_Manager $wpc
 * @param  string $cur_panel Current panel name
 */
function section_layout_woocommerce($wpc, $cur_panel) {
	if (!defined('WOOCOMMERCE_VERSION')) return;

	$wpc->add_section($cur_section = 'wpdanceclaratheme_layout_wc', array(
		'title' => esc_html__("WooCommece", 'wpdanceclaratheme'),
		'panel' => $cur_panel,
	));

	# PRODUCTS PER PAGE
	$wpc->add_setting($cur_setting = 'wpdanceclaratheme_layout_wc_products_per_page', array(
		'default' => \wpdanceclaratheme\Config::DEFAULT_PRODUCTS_PER_PAGE
	));
	$wpc->add_control($cur_setting, array(
		'label'       => esc_html__("Products Per Page", 'wpdanceclaratheme'),
		'description' => esc_html__("Number of products per page show on WooCommerce shop page and category pages.", 'wpdanceclaratheme'),
		'section'     => $cur_section,
		'settings'    => $cur_setting,
		'type'        => 'text'
	));

	# PRODUCTS PER ROW
	$wpc->add_setting($cur_setting = 'wpdanceclaratheme_layout_wc_products_per_row', array(
		'default' => \wpdanceclaratheme\Config::DEFAULT_PRODUCTS_PER_ROW
	));
	$wpc->add_control($cur_setting, array(
		'label'       => esc_html__("Products Column", 'wpdanceclaratheme'),
		'description' => esc_html__("Number of products per row show on WooCommerce shop page and category pages.", 'wpdanceclaratheme'),
		'section'     => $cur_section,
		'settings'    => $cur_setting,
		'type'        => 'text'
	));



	$choices = array(null => '');
	$taxonomies = wc_get_attribute_taxonomies();
	foreach ($taxonomies as $tax)
		$choices[wc_attribute_taxonomy_name($tax->attribute_name)] = $tax->attribute_label;

	$attrs = (array)get_theme_mod('wpdanceclaratheme_layout_wc_color_attributes');
	$choices = array_merge(array_combine($attrs, $attrs), $choices);

	$attrs = (array)get_theme_mod('wpdanceclaratheme_layout_wc_image_attributes');
	$choices = array_merge(array_combine($attrs, $attrs), $choices);


	# COLOR-OPTION ATTRIBUTES
	$wpc->add_setting($cur_setting = 'wpdanceclaratheme_layout_wc_color_attributes', array(
		'transport'  => 'postMessage',
		'default'    => array(),
	));
	$wpc->add_control(new \wpdanceclaratheme\Customizer\Multiple_Select_Control($wpc, $cur_setting, array(
		'label'       => esc_html__("Color Attributes", 'wpdanceclaratheme'),
		'description' => wp_kses(__("Specify product attributes convert to color options picker. You can type a string and press <code>Enter</code> to add more items.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html),
		'section'     => $cur_section,
		'settings'    => $cur_setting,
		'choices'     => $choices,
		'size'        => 4,
	)));

	# IMAGE-OPTION ATTRIBUTES
	$wpc->add_setting($cur_setting = 'wpdanceclaratheme_layout_wc_image_attributes', array(
		'transport'   => 'postMessage',
		'default'    => array(),
	));
	$wpc->add_control(new \wpdanceclaratheme\Customizer\Multiple_Select_Control($wpc, $cur_setting, array(
		'label'       => esc_html__("Image Attributes", 'wpdanceclaratheme'),
		'description' => wp_kses(__("Specify product attributes convert to image options picker. You can type a string and press <code>Enter</code> to add more items.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html),
		'section'     => $cur_section,
		'settings'    => $cur_setting,
		'choices'     => $choices,
		'size'        => 4,
	)));
}
endif; /* function_exists() */



if (!function_exists(__NAMESPACE__.'\\section_layout_portfolio')):
/**
 * Show section Portfolio on panel Theme - Layout
 * 
 * @param  WP_Customize_Manager $wpc
 * @param  string $cur_panel Current panel name
 */
function section_layout_portfolio($wpc, $cur_panel) {
	if (!defined('WPDANCE_PORTFOLIO_PLUGIN')) return;

	$wpc->add_section($cur_section = 'wpdanceclaratheme_layout_portfolio', array(
		'title' => esc_html__("Portfolio", 'wpdanceclaratheme'),
		'panel' => $cur_panel,
	));

	# ITEMS PER PAGE
	$wpc->add_setting($cur_setting = 'wpdanceclaratheme_layout_portfolio_posts_per_page');
	$wpc->add_control($cur_setting, array(
		'label'       => esc_html__("Portfolio Items Per Page", 'wpdanceclaratheme'),
		'description' => wp_kses(__("Number of portfolio items per page show on category page and archive pages. If empty, use value from <strong>Blog pages show at most</strong> specified in <strong>Settings</strong> &gt; <strong>Reading</strong>", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html),
		'section'     => $cur_section,
		'settings'    => $cur_setting,
		'type'        => 'number',
		'input_attrs' => array(
			'step' => 1,
			'min'  => 1,
		)
	));

	_add_control_layout($wpc, 'wpdanceclaratheme_layout_portfolio', $cur_section, false, 'fullwidth');
	_add_control_col($wpc, 'wpdanceclaratheme_layout_portfolio_col', $cur_section, \wpdanceclaratheme\Config::DEFAULT_PORTFOLIOS_PER_ROW);
}
endif;



if (!function_exists(__NAMESPACE__.'\\section_layout_notfound')):
/**
 * Show section Page 404 Not Found on panel Theme - Layout
 * 
 * @param  WP_Customize_Manager $wpc
 * @param  string $cur_panel Current panel name
 */
function section_layout_notfound($wpc, $cur_panel) {
	if (!defined('WPDANCE_PORTFOLIO_PLUGIN')) return;

	$wpc->add_section($cur_section = 'wpdanceclaratheme_layout_pagenotfound', array(
		'title' => esc_html__("404 Not Found", 'wpdanceclaratheme'),
		'panel' => $cur_panel,
	));

	# ITEMS PER PAGE
	$wpc->add_setting($cur_setting = 'wpdanceclaratheme_layout_pagenotfound_page');
	$wpc->add_control($cur_setting, array(
		'label'       => esc_html__("Static Page for 404 Not Found", 'wpdanceclaratheme'),
		'description' => esc_html__("Select a page to show when user access to broken links.", 'wpdanceclaratheme'),
		'section'     => $cur_section,
		'settings'    => $cur_setting,
		'type'        => 'dropdown-pages',
	));
}
endif;


if (!function_exists(__NAMESPACE__.'\\panel_advance')):
/**
 * Add panel Theme - Advance to Customizer
 *
 * @param WP_Customize_Manager $wpc
 */
function panel_advance($wpc) {


	$wpc->add_panel('wpdanceclaratheme_advance', array(
		'title' => esc_html_x("Advance", 'customizer', 'wpdanceclaratheme'),
	));

	$wpc->add_section('wpdanceclaratheme_advance_importexport', array(
		'title' => esc_html__("Import / Export Settings", 'wpdanceclaratheme'),
		'panel' => 'wpdanceclaratheme_advance',
	));


	$wpc->add_setting('wpdanceclaratheme_advance_importexport_import', array(
		'default' => 1,
		'type'    => 'none', // Let WP do not store this setting
	));

	$wpc->add_control(new Import_Control($wpc, 'wpdanceclaratheme_advance_importexport_import', array(
		'section' => 'wpdanceclaratheme_advance_importexport',
	)));

	$wpc->add_setting('wpdanceclaratheme_advance_importexport_export', array(
		'type' => 'none', // Let WP do not store this setting
	));

	$wpc->add_control(new Export_Control($wpc, 'wpdanceclaratheme_advance_importexport_export', array(
		'section' => 'wpdanceclaratheme_advance_importexport',
	)));


	$wpc->add_section('wpdanceclaratheme_advance_reset', array(
		'title' => esc_html__("Reset All Settings", 'wpdanceclaratheme'),
		'panel' => 'wpdanceclaratheme_advance',
	));

	$wpc->add_setting('wpdanceclaratheme_advance_reset_reset', array(
		'type' => 'none', // Let WP do not store this setting
	));

	$wpc->add_control(new Reset_Control($wpc, 'wpdanceclaratheme_advance_reset_reset', array(
		'section' => 'wpdanceclaratheme_advance_reset',
	)));

}
endif; /** function_exists() */



if (!function_exists(__NAMESPACE__.'\\_add_control_layout')):
/**
 * Add setting & control for Layout configuration
 * 
 * @param [type]  $wpc           [description]
 * @param [type]  $control       [description]
 * @param [type]  $section       [description]
 * @param boolean $empty_option [description]
 */
function _add_control_layout($wpc, $control, $section, $empty_option = true, $default = '') {

	if ($empty_option) {
		$choices[''] = esc_html__("Default", 'wpdanceclaratheme');
	}

	$choices['sidebar-left']  = esc_html__("Left Sidebar", 'wpdanceclaratheme');
	$choices['sidebar-right'] = esc_html__("Right Sidebar", 'wpdanceclaratheme');
	$choices['fullwidth']     = esc_html__("Full Width", 'wpdanceclaratheme');

	$wpc->add_setting($control, array(
		'default' => $default,
	));
	$wpc->add_control($control, array(
		'label'         => esc_html__("Layout", 'wpdanceclaratheme'),
		'section'       => $section,
		'settings'      => $control,
		'type'          => 'select',
		'choices'       => $choices
	));		
}
endif;



if (!function_exists(__NAMESPACE__.'\\_add_control_masonry')):
/**
 * Add setting & control for setting Masonry layout
 * 
 * @param [type] $wpc     [description]
 * @param [type] $control [description]
 * @param [type] $section [description]
 * @param string $type    [description]
 */
function _add_control_masonry($wpc, $control, $section, $type = 'select') {

	$args = $type == 'checkbox' ? array('default' => 'yes') : array();

	$wpc->add_setting($control, $args);
	$wpc->add_control($control, array(
		'label'         => esc_html__("Masonry", 'wpdanceclaratheme'),
		'description'   => esc_html__("Show masonry layout on archive pages?", 'wpdanceclaratheme'),
		'section'       => $section,
		'settings'      => $control,
		'type'          => $type,
		'choices'       => array(
			''    => esc_html__("Default", 'wpdanceclaratheme'),
			'yes' => esc_html__("Yes", 'wpdanceclaratheme'),
			'no'  => esc_html__("No", 'wpdanceclaratheme'))
	));
}
endif;



if (!function_exists(__NAMESPACE__.'\\_add_control_excerpt')):
/**
 * Add setting & control for on/off excerpt content in loops
 * 
 * @param [type] $wpc     [description]
 * @param [type] $control [description]
 * @param [type] $section [description]
 * @param string $type    [description]
 */
function _add_control_excerpt($wpc, $control, $section, $type = 'select') {

	$args = $type == 'checkbox' ? array('default' => 'yes') : array();

	$wpc->add_setting($control, $args);
	$wpc->add_control($control, array(
		'label'         => esc_html__("Excerpt", 'wpdanceclaratheme'),
		'description'   => esc_html__("Show excerpt instead of content on archive pages?", 'wpdanceclaratheme'),
		'section'       => $section,
		'settings'      => $control,
		'type'          => $type,
		'choices'       => array(
			''    => esc_html__("Default", 'wpdanceclaratheme'),
			'yes' => esc_html__("Yes", 'wpdanceclaratheme'),
			'no'  => esc_html__("No", 'wpdanceclaratheme'))
	));
}
endif;


if (!function_exists(__NAMESPACE__.'\\_add_control_col')):
/**
 * Add setting & control for setting number of columns in loops
 * 
 * @param [type]  $wpc          [description]
 * @param [type]  $control      [description]
 * @param [type]  $section      [description]
 * @param boolean $empty_option [description]
 */
function _add_control_col($wpc, $control, $section, $default = '') {

	$wpc->add_setting($control, array(
		'default' => $default
	));
	$wpc->add_control($control, array(
		'label'         => esc_html__("Column", 'wpdanceclaratheme'),
		'description'   => esc_html__("Number of columns in the main loop", 'wpdanceclaratheme'),
		'section'       => $section,
		'settings'      => $control,
		'type'          => 'number',
		'input_attrs'   => array(
			'step' => 1,
			'min'  => 1,
			'max'  => 12,
		)
	));
}
endif;



if (!function_exists(__NAMESPACE__.'\\_add_control_image_position')):
/**
 * Add setting & control for positioning thumbnail image in loops
 * 
 * @param [type]  $wpc          [description]
 * @param [type]  $control      [description]
 * @param [type]  $section      [description]
 * @param boolean $empty_option [description]
 */
function _add_control_image_position($wpc, $control, $section, $empty_option = true) {

	if ($empty_option) {
		$choices[''] = esc_html__("Default", 'wpdanceclaratheme');
	}

	$choices['left'] = esc_html__("Left", 'wpdanceclaratheme');
	$choices['right'] = esc_html__("Right", 'wpdanceclaratheme');
	$choices['center'] = esc_html__("Top", 'wpdanceclaratheme');
	$choices['leftright'] = esc_html__("Odd Left & Event Right", 'wpdanceclaratheme');
	$choices['rightleft'] = esc_html__("Odd Right & Event Left", 'wpdanceclaratheme');

	$wpc->add_setting($control, array(
		'default' => $empty_option ? '' : 'right'
	));
	$wpc->add_control($control, array(
		'label'         => esc_html__("Image Position", 'wpdanceclaratheme'),
		'description'   => esc_html__("Position of post image in the main loop", 'wpdanceclaratheme'),
		'section'       => $section,
		'settings'      => $control,
		'type'          => 'select',
		'choices'       => $choices
	));
}
endif;


