<?php
namespace wpdanceclaratheme\Setup;

add_action('after_switch_theme', __NAMESPACE__.'\\after_switch_theme');



if (!function_exists(__NAMESPACE__.'\\after_switch_theme')):
/**
 * Callback function for hook 'after_switch_theme'
 */

function after_switch_theme() {
	\wpdanceclaratheme\AdminPage\raise_walkthrough_notice_after_activated();
}
endif;



if (!function_exists(__NAMESPACE__.'\\import_all')):
/**
 * Import all data come with the theme
 */
function import_all() {

	# import data of wpdance_htmlblock post type come with the theme
	import_htmlblocks_data();

	# import data of vc_grid_item Type post type come with the theme
	import_gridbuilder_data();

	# import Ubermenu configuration for this theme
	import_ubermenu_settings();

	# import sample menus
	// import_nav_menu(get_template_directory().'/import-data/menus.xml');

	
}
endif;





if (!function_exists(__NAMESPACE__.'\\import_htmlblocks_data')):
/**
 * Import HTML Blocks (wpdance_htmlblock) post type data, ignore if post name already exists
 */
function import_htmlblocks_data() {
	_import_xml_data(get_template_directory().'/import-data/htmlblocks.xml', esc_html__("HTML Block", 'wpdanceclaratheme'));
}
endif;






if (!function_exists(__NAMESPACE__.'\\import_gridbuilder_data')):
/**
 * Import Grid Builder (vc_grid_item) post type data of Visual Composer.
 * Ignore if post name already exists
 */
function import_gridbuilder_data() {
	_import_xml_data(get_template_directory().'/import-data/gridbuilder.xml', esc_html__("VC Grid Item", 'wpdanceclaratheme'));
}
endif;




if (!function_exists(__NAMESPACE__.'\\import_ubermenu_settings')):
/**
 * Import Ubermenu Configurations used in this theme
 */
function import_ubermenu_settings() {

	$imported = array();
	$exists = array();

	# skip if no ubermenu specified in the Config file
	if (empty(\wpdanceclaratheme\Config::$ubermenu_import)) return;


	#
	# Add our menus in WP option 'ubermenu_menus'
	# 

	$menus = get_option('ubermenu_menus');
	if (!is_array($menus))
		$menus = array();

	foreach (\wpdanceclaratheme\Config::$ubermenu_import as $menu) {
		if (!in_array($menu, $menus)) 
			$menus[] = $menu;
	}

	add_option('ubermenu_menus', $menus);


	#
	# Add WP option 'ubermenu_[ubermenu configuration name]'
	#

	foreach (\wpdanceclaratheme\Config::$ubermenu_import as $menu) {
		$option_name = 'ubermenu_'.$menu;

		# Ignore creating ubermenu configuration if it alreay exists
		if (get_option($option_name)) {
			$exists[] = $menu;
			continue;
		}

		$wp_filesystem = \wpdanceclaratheme\Helper\wp_filesystem();
		
		$option_content = $wp_filesystem->get_contents(get_template_directory()."/import-data/ubermenu/$menu.json");
		$option_content = json_decode($option_content, true);

		add_option($option_name, $option_content);
		$imported[] = $menu;
	}


	#
	# Show notice messages
	#

	if (!empty($exists))
		\wpdanceclaratheme\AdminNotice::nag(sprintf(esc_html__("Ubermenu configuration %s already exists. Ignore importing.", 'wpdanceclaratheme'), '<code>'.implode(', ', $exists).'</code>'));

	if (!empty($imported))
		\wpdanceclaratheme\AdminNotice::updated(sprintf(esc_html__("Ubermenu configuration %s created.", 'wpdanceclaratheme'), '<code>'.implode(', ', $imported).'</code>'));
}
endif;






if (!function_exists(__NAMESPACE__.'\\_import_xml_data')):
/**
 * Function to help importing xml data into WP posts
 *
 * @access private
 * @param string $xml_file Path to xml file to be imported
 * @param string $post_type_title Title of post type to show on notice messages
 */
function _import_xml_data($xml_file, $post_type_title) {

	$posts_exist = array();
	$posts_imported = array();
	$posts_error = array();

	$xml = simplexml_load_file($xml_file);

	$ns = $xml->getNamespaces(true);

	foreach ($xml->channel->item as $item) {
		
		$item_wp = $item->children($ns['wp']);
		$item_content = $item->children($ns['content']);

		# find the post to check if exists
		$query = new \WP_Query(array(
			'post_type'  => (string)$item_wp->post_type,
			'name'       => (string)$item_wp->post_name,
		));

		# ignore importing if the post exists
		if ($query->have_posts()) {
			$posts_exist[] = (string)$item_wp->post_name;
			continue;
		}

		wp_reset_postdata();


		#
		# Prepare post data
		#

		$post = array(
			'post_title'     => (string)$item->title,
			'post_status'    => (string)$item_wp->status,
			'post_type'      => (string)$item_wp->post_type,
			'post_name'      => (string)$item_wp->post_name,
			'post_content'   => (string)$item_content->encoded,
			'meta_input'     => array(),
		);

		foreach ($item_wp->postmeta as $meta) {
			$post['meta_input'][(string)$meta->meta_key] = (string)$meta->meta_value;
		}

		#
		# Insert post to database
		# 

		if ($id = wp_insert_post($post)) {
			$posts_imported[] = $post['post_name'].' ('.$id.')';
		}
		else {
			$posts_error[] = $post['post_name'];
		}
	}
	

	#
	# Show notice messages on backend
	#

	if (!empty($posts_exist)) {
		\wpdanceclaratheme\AdminNotice::nag(sprintf(esc_html__("%s already exist. Ignored import.", 'wpdanceclaratheme'), $post_type_title.' <code>'.implode(', ', $posts_exist).'</code>'));
	}

	if (!empty($posts_imported)) {
		\wpdanceclaratheme\AdminNotice::updated(sprintf(esc_html__("%s are imported.", 'wpdanceclaratheme'), $post_type_title.' </code>'.implode(', ', $posts_imported).'</code>'));
	}

	if (!empty($posts_error)) {
		\wpdanceclaratheme\AdminNotice::error(sprintf(esc_html__("%s import failed. Please contact us for further help.", 'wpdanceclaratheme'), $post_type_title.' <code>'.implode(', ', $posts_error).'</code>'));
	}

}
endif;



if (!function_exists(__NAMESPACE__.'\\get_missing_data')):
/**
 * Retrieve content do not exist which need to import
 *
 * This is helper function, called in the Walkthrough page.
 * @return array 
 */
function get_missing_data() {

	$ret = array(
		'htmlblocks' => array(),
		'gridbuilder' => array(),
		'ubermenu' => array(),
	);



	#
	# HTML Blocks
	#

	$f = \wpdanceclaratheme\Helper\wp_filesystem();
	$s = $f->get_contents(get_template_directory().'/import-data/htmlblocks.xml');

	preg_match_all('#<wp:post_name>(.+)</wp:post_name>#uU', $s, $matches_name);
	preg_match_all('#<wp:post_type>(.+)</wp:post_type>#uU', $s, $matches_type);

	foreach ($matches_name[1] as $i => $name) {
		$name = rtrim(ltrim($name, '<![CDATA['), ']]>');

		$query = new \WP_Query(array(
			'name' => $name,
			'post_type' => rtrim(ltrim($matches_type[1][$i], '<![CDATA['), ']]>'),
		));
		
		if (!$query->have_posts()) 
			$ret['htmlblocks'][] = $name;

		wp_reset_postdata();

	}



	#
	# Grid Types
	#

	$f = \wpdanceclaratheme\Helper\wp_filesystem();
	$s = $f->get_contents(get_template_directory().'/import-data/gridbuilder.xml');

	preg_match_all('#<wp:post_name>(.+)</wp:post_name>#uU', $s, $matches_name);
	preg_match_all('#<wp:post_type>(.+)</wp:post_type>#uU', $s, $matches_type);

	foreach ($matches_name[1] as $i => $name) {
		$name = rtrim(ltrim($name, '<![CDATA['), ']]>');

		$query = new \WP_Query(array(
			'name' => $name,
			'post_type' => rtrim(ltrim($matches_type[1][$i], '<![CDATA['), ']]>'),
		));
		
		if (!$query->have_posts()) 
			$ret['gridbuilder'][] = $name;

		wp_reset_postdata();

	}


	#
	# UberMenu
	#

	$menus = get_option('ubermenu_menus');
	if (!is_array($menus))
		$menus = array();

	foreach (\wpdanceclaratheme\Config::$ubermenu_import as $s)
		if (!in_array($s, $menus))
			$ret['ubermenu'][] = $s;


	return $ret;
}
endif;




if (!function_exists(__NAMESPACE__.'\\import_nav_menu')):
/**
 * Import Navigation Menus from XML file
 * 
 * @param string $xml_file path to xml file to import
 */
function import_nav_menu($xml_file) {

	# Skip if import file is not exist
	$fs = \wpdanceclaratheme\Helper\wp_filesystem();
	if (!$fs->exists($xml_file))
		return;


	$menus_exist = array();

	/**
	 * Array of Menu Name imported successfully
	 * @var array
	 */
	$menus_imported = array();


	/**
	 * Array of Menu Name failed to import
	 * @var array
	 */
	$menus_error = array();


	/**
	 * Menus to ignore importing
	 * @var array
	 */
	$skip_menus = array();

	$xml = simplexml_load_file($xml_file);

	$ns = $xml->getNamespaces(true);

	# Import Menus (terms)
	foreach ($xml->channel->children($ns['wp'])->term as $term) {

		# Ignore if term is not nav_menu
		if ((string)$term->term_taxonomy != 'nav_menu') continue;

		# Skip import menu items if menu alreayd exists
		if (term_exists((string)$term->term_slug, (string)$term->term_taxonomy))
			$skip_menus[] = (string)$term->term_slug;

		# Import Menu
		else {
			$id = wp_insert_term((string)$term->term_name, (string)$term->term_taxonomy, array(
				'description' => '',
				'name'        => (string)$term->term_name,
				'parent'      => 0,
				'slug'        => (string)$term->term_slug,
			));

			if (!is_wp_error($id))
				$menus_imported[] = (string)$term->term_slug;
			else
				$menus_error[] = (string)$term->term_slug;
		}
	}


	/**
	 * Array maps child old menu item ID -> parent old menu item ID
	 * @var array()
	 */
	$parent_old_ids = array();

	/**
	 * Array maps old menu item ID -> new menu item ID
	 * @var array
	 */
	$new_ids = array();

	# Import Menu Items
	foreach ($xml->channel->item as $item) {
		
		$item_wp = $item->children($ns['wp']);
		$item_content = $item->children($ns['content']);
		$item_excerpt = $item->children($ns['excerpt']);

		# Ignore if item is not menu item
		if ((string)$item_wp->post_type != 'nav_menu_item') continue;

		# ignore if not published post
		if ((string)$item_wp->status != 'publish') continue;

		# Skip if item belongs to a Menu not imported
		$menu = '';
		foreach ($item->category as $category) {
			if ((string)$category['domain'] == 'nav_menu' && in_array((string)$category['nicename'], $menus_imported)) {
				$menu = (string)$category['nicename'];
				break;
			}
		}
		if (!$menu) continue;

		# find the menu item to check if exists
		$query = new \WP_Query(array(
			'post_type'  => (string)$item_wp->post_type,
			'name'       => (string)$item_wp->post_name,
		));

		# ignore importing if the post exists
		if ($query->have_posts()) {
			$posts_exist[] = (string)$item_wp->post_name;
			wp_reset_postdata();
			continue;
		}

		wp_reset_postdata();


		#
		# Prepare menu item data
		#
		
		// $args = array(
		// 	'menu-item-object-id' => $_menu_item_object_id,
		// 	'menu-item-object' => $_menu_item_object,
		// 	'menu-item-parent-id' => $_menu_item_menu_item_parent,
		// 	'menu-item-position' => intval( $item['menu_order'] ),
		// 	'menu-item-type' => $_menu_item_type,
		// 	'menu-item-title' => $item['post_title'],
		// 	'menu-item-url' => $_menu_item_url,
		// 	'menu-item-description' => $item['post_content'],
		// 	'menu-item-attr-title' => $item['post_excerpt'],
		// 	'menu-item-target' => $_menu_item_target,
		// 	'menu-item-classes' => $_menu_item_classes,
		// 	'menu-item-xfn' => $_menu_item_xfn,
		// 	'menu-item-status' => $item['status']
		// );

		$post = array(
			'post_title'     => (string)$item->title,
			'post_status'    => (string)$item_wp->status,
			'post_type'      => (string)$item_wp->post_type,
			'post_name'      => (string)$item_wp->post_name,
			'menu_order'     => (string)$item_wp->menu_order,
			'post_content'   => (string)$item_content->encoded,
			'post_excerpt'   => (string)$item_excerpt->encoded,
			'meta_input'     => array(),
			'tax_input'      => array(
				'nav_menu' => $menu
			)
		);

		foreach ($item_wp->postmeta as $meta) {
			# Try unserialize first
			$value = @unserialize((string)$meta->meta_value);
			$post['meta_input'][(string)$meta->meta_key] = $value !== false ? $value : (string)$meta->meta_value;
		}


		# Mark parent item ID to update later
		if (isset($post['meta_input']['_menu_item_menu_item_parent']) && $post['meta_input']['_menu_item_menu_item_parent'] != 0) {
			$parent_old_ids[(int)$item_wp->post_id] = (int)$post['meta_input']['_menu_item_menu_item_parent'];
			$post['meta_input']['_menu_item_menu_item_parent'] = 0;
		}

		#
		# Insert menu item to database
		# 

		$id = wp_insert_post($post);

		# map old ID -> new ID
		$new_ids[(int)$item_wp->post_id] = $id;
	}


	# Update menu item parent ID
	foreach ($parent_old_ids as $child_old_id => $parent_old_id) {
		if (array_key_exists($child_old_id, $new_ids) && array_key_exists($parent_old_id, $new_ids)) {
			update_post_meta($new_ids[$child_old_id], '_menu_item_menu_item_parent', $new_ids[$parent_old_id]);
		}
	}
	

	#
	# Show notice messages on backend
	#

	if (!empty($menus_exist)) {
		\wpdanceclaratheme\AdminNotice::nag(sprintf(esc_html__("%s already exist. Ignored import.", 'wpdanceclaratheme'),'Menu: <code>'.implode(', ', $menus_exist).'</code>'));
	}

	if (!empty($menus_imported)) {
		\wpdanceclaratheme\AdminNotice::updated(sprintf(esc_html__("%s are imported successfully.", 'wpdanceclaratheme'),'Menu: <code>'.implode(', ', $menus_imported).'</code>'));
	}

	if (!empty($menus_error)) {
		\wpdanceclaratheme\AdminNotice::error(sprintf(esc_html__("%s import failed. Please contact us for further help.", 'wpdanceclaratheme'),'Menu: <code>'.implode(', ', $menus_error).'</code>'));
	}

}
endif;



if (!function_exists(__NAMESPACE__.'\\get_missing_revsliders')):
/**
 * Retrive Revolution Sliders come with the theme but not exist in database
 *
 * This helper function is called from the theme Walkthrough page
 * @global $wpdb
 * @return array
 */
function get_missing_revsliders() {
	global $wpdb;

	if (!is_revslider_active()) 
		return array();

	$missing = array();

	$tableSliders = $wpdb->prefix . \RevSliderGlobals::TABLE_SLIDERS_NAME;

	$sliders = $wpdb->get_col("SELECT alias FROM $tableSliders");
	if (!is_array($sliders))
		$sliders = array();
	
	foreach (\wpdanceclaratheme\Config::$revslider_check as $check) {
		if (!in_array($check, $sliders))
			$missing[] = $check;
	}

	return $missing;
}
endif;


/**
 * Check revolution slider plugin is activated
 */
function is_revslider_active() {
	return class_exists('RevSliderGlobals');
}





function is_composium_active() {
	return defined('COMPOSIUM_EXTENSIONS');
}


function import_composium() {
	$fs      = \wpdancebootstrap\Helper\wp_filesystem();
	$content = $fs->get_contents(get_template_directory().'/import-data/composium.json');
	$data    = json_decode($content, true);
	
	foreach ($data['settings'] as $k => $v)
		update_option($k, $v);
}


