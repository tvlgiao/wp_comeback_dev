<?php
namespace wpdanceclaratheme\WooCommerce;

/** Stop if WooCommerce is not installed */
if (!defined('WOOCOMMERCE_VERSION')) return;

require_once get_template_directory().'/inc/wpdanceclaratheme/woocommerce/loop.php';
require_once get_template_directory().'/inc/wpdanceclaratheme/woocommerce/imagepicker.php';



# Let theme support WooCommerce plugin
add_action('after_setup_theme', __NAMESPACE__.'\\hook_add_theme_support_woocommerce');

# Remove WooCommerce Sidebar if current page is full width
add_action('woocommerce_after_main_content', __NAMESPACE__.'\\hook_remove_wc_sidebar_if_fullwidth');

# Update page ID if current page is woocommerce page
add_action('wpdanceclaratheme_get_headerfooter_post_before', __NAMESPACE__.'\\action_wc_page_id', 10, 1);


# Show right column on product page, containing buttons, social sharing, rating like design
# Priority: 5 - to show before woocommerce_output_product_data_tabs (10)
add_action ('woocommerce_after_single_product_summary', __NAMESPACE__.'\\show_right_column_on_product_page', 5);

# Show number of products on each category
add_filter('woocommerce_subcategory_count_html', __NAMESPACE__.'\\show_products_count', 10, 2);

# Update layout of current page if current page is a WooCommerce page
add_filter('wpdanceclaratheme_get_layout', __NAMESPACE__.'\\get_layout_of_wc_page', 10, 1);

# Let plugin custom-post-templates support WooCommerce Product post type as well
add_filter('cpt_post_types', __NAMESPACE__.'\\support_post_template_for_product', 10, 1);

# Show metaboxes to select layout on taxonomy 'product_cat' & 'product_tag' admin edit
add_filter('cmb2-taxonomy_meta_boxes', __NAMESPACE__.'\\show_metaboxes_on_product_cat', 11, 1); // after callback in metabox.php

# Show metaboxes to select layout on product admin edit page
add_filter('wpdanceclaratheme_metabox1_object_types', __NAMESPACE__.'\\show_metaboxes_on_product', 10, 1);

# Add class to identify page is product and has custom layout 1
add_filter('post_class', __NAMESPACE__.'\\post_class_identify_product_layout_1', 10, 3);

# Update YITH Wishlist to not show wishlist on single product page summary section
# It will show on the right minibox like theme design
add_filter('yith_wcwl_positions', __NAMESPACE__.'\\dont_show_wishlist_on_single_product_summary', 10, 1);

# Remove product rating on summary section of single product page
# It will show on the right minibox like theme design
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating');

# Remove social-media-sharing-icons on summary section of single product page
# It will show on the right minibox like theme design
remove_action('woocommerce_single_product_summary', 'toastie_wc_smsb_form_code', 31);

# Set number of products per page as specified in theme mods
add_filter('loop_shop_per_page', __NAMESPACE__.'\\products_per_page', 20);

# Set number of products per row as specified in theme mods
add_filter('loop_shop_columns', __NAMESPACE__.'\\products_per_row', 20);

# Add class to 'body' element to specify number of products per row on WooCommerce pages
add_filter('body_class', __NAMESPACE__.'\\add_body_class_shop_columns', 10, 1);


###################################################################################################
# HOOK & FILTER FUNCTIONS
###################################################################################################

if (!function_exists(__NAMESPACE__.'\\hook_add_theme_support_woocommerce')):
function hook_add_theme_support_woocommerce() {
	/**
	 * Let WooCommerce plugin knows that our theme support the plugin
	 */
	add_theme_support( 'woocommerce' );
}
endif;


if (!function_exists(__NAMESPACE__.'\\hook_remove_wc_sidebar_if_fullwidth')):
/**
 * Remove sidebar on woocommerce pages if the current page template is full width
 */
function hook_remove_wc_sidebar_if_fullwidth() {
	if (\wpdanceclaratheme\Helper\get_main_column_size() == 12)
		remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar');
}
endif;


if (!function_exists(__NAMESPACE__.'\\action_wc_page_id')):
/**
 * Hook change $page_id to woocommece page ID if current page is woocommerce page
 * @param stdclass $arg contains variable:
 * - page_id
 */
function action_wc_page_id($arg) {

	if ($woocommerce_page_id = current_wc_page_id())
		$arg->page_id = $woocommerce_page_id;

}
endif;



if (!function_exists(__NAMESPACE__.'\\show_products_count')):
function show_products_count($html, $category) {
	/** 
	 * Display number of products in sub category: n product(s)
	 */
	return ' <mark class="count">'.sprintf(_n("%s product", "%s products", $category->count, 'wpdanceclaratheme'), $category->count).'</mark>';
}
endif;



if (!function_exists(__NAMESPACE__.'\\show_right_column_on_product_page')):
/**
 * Show a box on right of product summary to hold buttons, 
 * social icons, reviews like the design.
 */
function show_right_column_on_product_page() {
	global $product;

	/**
	 * @var WC_Product $product
	 */	


	$email_link = "mailto:?subject=".esc_attr($product->get_title)."&body=".esc_attr(sprintf(__("I saw this and thought of you! %s", 'wpdanceclaratheme'), $product->get_permalink));
	?>
	<div class="wpdanceclaratheme-product-minibox">
		<div class="wpdanceclaratheme-product-minibox-inner">

			<?php if ($product->is_purchasable() && $product->is_in_stock()): ?>
			<button class="btn btn-primary btn-addtocart"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
			<?php endif; ?>

			<?php if (defined('YITH_WCWL')) 
				echo do_shortcode("[yith_wcwl_add_to_wishlist]"); ?>

			<a class="btn btn-default btn-email" href="<?php echo $email_link; ?>"><?php esc_html_e("Send Email", 'wpdanceclaratheme'); ?></a>

			<?php if (function_exists('woocommerce_template_single_rating')) 
				woocommerce_template_single_rating(); ?>

			<?php if (function_exists('toastie_wc_smsb_form_code')) 
				toastie_wc_smsb_form_code(); ?>

			<?php
			/**
			 * wpdanceclaratheme_product_minibox hook 
			 */
			do_action('wpdanceclaratheme_product_minibox');
			?>
		</div>
	</div>
	<?php
	# Javascript may be added to show inner content
}
endif;



if (!function_exists(__NAMESPACE__.'\\get_layout_of_wc_page')):
/**
 * Filter function returns page layout (full width, left, right sidebar...) of WooCommerce pages
 * 
 * @param  string $layout
 * @return string new layout
 */
function get_layout_of_wc_page($layout) {
	$new_layout = NULL;

	# If current page is Product Category / Tag page
	if (is_product_category() || is_product_tag()) {

		# Read layout from product_cat's meta
		if (function_exists('get_term_meta')) {

			// $wp_query->get_queried_object()->term_id
			$new_layout = get_term_meta(get_queried_object_id(), 'wpdanceclaratheme_layout', true);
		}
	}

	# If current page is WooCommerce Product page
	elseif (is_product()) {

		# Read layout from meta data
		$new_layout = get_post_meta(get_the_ID(), 'wpdanceclaratheme_layout', true);

		# Read layout from custom post template if supported (by the plugin custom-post-template)
		if (!$new_layout && function_exists('is_post_template')) {

			if (is_post_template('single-fullwidth.php'))
				$new_layout = 'fullwidth';

			elseif (is_post_template('single-left.php'))
				$new_layout = 'sidebar-left';

			elseif (is_post_template('single-right.php'))
				$new_layout = 'sidebar-right';
		}
	}

	# If current page is WooCommerce page which is assigned from a static page
	# Or any other woocommerce page will be considered as 'shop' page
	if (!$new_layout && $page_id = current_wc_page_id()) {

		# Read layout from meta data
		$new_layout = get_post_meta($page_id, 'wpdanceclaratheme_layout', true);

		# Read layout from meta data page template
		if (!$new_layout && preg_match('/page-(.+).php/', get_page_template_slug($page_id), $m)) {
			switch ($m[1]) {
				case 'fullwidth':
					$new_layout = 'fullwidth';
					break;
				
				case 'left':
					$new_layout = 'sidebar-left';
					break;

				case 'right':
					$new_layout = 'sidebar-right';
					break;
			}
		}
	}

	return $new_layout ? $new_layout : $layout;
}
endif;


if (!function_exists(__NAMESPACE__.'\\support_post_template_for_product')):
/**
 * Let plugin custom-post-templates support WooCommerce Product
 *
 * Show Custom Post Type select on editing product page in the backend
 * 
 * @param  array $types
 * @return array
 */
function support_post_template_for_product($types) {
	return array_merge($types, array('product'));
}
endif;


if (!function_exists(__NAMESPACE__.'\\show_metaboxes_on_product_cat')):
/**
 * Show metabox to select page layout (full width, left, right sidebar...) 
 * on admin edit page of taxonomy 'product_cat' & 'product_tag'.
 * 
 * @param  array $metaboxes
 * @return array
 */
function show_metaboxes_on_product_cat(array $metaboxes) {
	$metaboxes['wpdanceclaratheme_metabox1_taxonomy']['object_types'] = array_merge(
		$metaboxes['wpdanceclaratheme_metabox1_taxonomy']['object_types'], 
		array('product_cat', 'product_tag'));
	return $metaboxes;
}
endif;



if (!function_exists(__NAMESPACE__.'\\show_metaboxes_on_product')):
/**
 * Show metabox to select page layout (full width, left, right sidebar...)
 * on product admin edit page.
 * 
 * @param  array $types
 * @return array
 */
function show_metaboxes_on_product($types) {
	return array_merge($types, array('product'));
}
endif;


if (!function_exists(__NAMESPACE__.'\\post_class_identify_product_layout_1')):
/**
 * Add a class to post class in order to identify product has custom layout 1
 * 
 * @param  array   $classes
 * @param  string  $class
 * @param  int     $post_id
 * @return array
 */
function post_class_identify_product_layout_1($classes, $class, $post_id) {
	if (is_product())
		$classes[] = 'wpdanceclaratheme-single-product-layout-1';

	return $classes;
}
endif;


/**
 * Let YITH Wishlist do not show on single product page summary position.
 *
 * We already show wishlist on right mini box.
 * 
 * @param  array $positions
 * @return array
 */
function dont_show_wishlist_on_single_product_summary($positions) {
	$positions['add-to-cart']['hook'] = '';
	$positions['summary']['hook'] = '';

	return $positions;
}



if (!function_exists(__NAMESPACE__.'\\add_body_class_shop_columns')):
/**
 * Add class wpdanceclaratheme-wc-shop-columns-X to 'body' element
 * to specify number of product columns
 * 
 * @param array $classes
 * @return array
 */
function add_body_class_shop_columns($classes) {
	if (is_woocommerce() || is_checkout() || is_cart())
		$classes[] = 'wpdanceclaratheme-wc-shop-columns-'.\wpdanceclaratheme\Helper\products_per_row();

	return $classes;
}
endif;




###################################################################################################
# HELPER FUNCTIONS
###################################################################################################




if (!function_exists(__NAMESPACE__.'\\current_wc_page_id')):
/**
 * Retrieve current Page ID if current page WooCommerce page
 *
 * @return integer|NULL
 */
function current_wc_page_id() {

	$page_id = null;
	if (is_woocommerce()) {

		# Cart pages
		if (is_cart())
			$page_id = wc_get_page_id('cart');

		# Checkout pages
		elseif (is_checkout() || is_checkout_pay_page() || is_order_received_page())
			$page_id = wc_get_page_id('checkout');

		# My account pages
		elseif (is_account_page() || is_view_order_page() || is_edit_account_page() || is_add_payment_method_page() || is_lost_password_page())
			$page_id = wc_get_page_id('myaccount');

		# Fallback to page 'shop'
		else
			$page_id = wc_get_page_id('shop');

	}
	return $page_id;
}
endif;





if (!function_exists(__NAMESPACE__.'\\products_per_page')):
/**
 * Filter set products per page in WooCommerce pages
 * 
 * @return int
 */
function products_per_page() {
	return \wpdanceclaratheme\Helper\products_per_page();
}
endif;



if (!function_exists(__NAMESPACE__.'\\products_per_row')):
/**
 * Filter set products per row in WooCommerce pages
 * 
 * @return int
 */
function products_per_row() {
	return \wpdanceclaratheme\Helper\products_per_row();
}
endif;



