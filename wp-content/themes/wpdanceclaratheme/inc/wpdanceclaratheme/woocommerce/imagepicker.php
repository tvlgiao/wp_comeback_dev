<?php 
namespace wpdanceclaratheme\WooCommerce\ImagePicker;

/** Stop if WooCommerce is not installed */
if (!defined('WOOCOMMERCE_VERSION')) return;


# Add script convert attribute options select to image/color options picker
# On single product page for variation type product
add_filter('woocommerce_dropdown_variation_attribute_options_html', __NAMESPACE__.'\\variation_attribute_image_options_html', 10, 2);

# Add script to specify attributes are image/color options
# On single product page
add_action('wp_head', __NAMESPACE__.'\\image_options_scripts');

# Enqueue jQuery-select2OptionPicker on all pages
add_action('wp_enqueue_scripts', __NAMESPACE__.'\\enqueue_select2OptionPicker_script');

# Apply image options picker to widget WC Layered Nav
add_filter('widget_display_callback', __NAMESPACE__.'\\apply_image_options_widget_layered_nav', 10, 3);
add_action('wp_footer', __NAMESPACE__.'\\widget_layered_nav_image_options_scripts');



###################################################################################################
# HOOK & FILTER FUNCTIONS
###################################################################################################





if (!function_exists(__NAMESPACE__.'\\image_options_scripts')):
/**
 * Hook to 'wp_head' to show scripts data for product attribute image options picker
 */
function image_options_scripts() {
	// if (!is_product()) return;

	$fs = \wpdanceclaratheme\Helper\wp_filesystem();

	if ($fs->exists(get_stylesheet_directory() . '/images/product_attributes/blank.png'))
		$blank_img = get_stylesheet_directory_uri() . '/images/product_attributes/blank.png';
	if ($fs->exists(get_template_directory() . '/images/product_attributes/blank.png'))
		$blank_img = get_template_directory_uri() . '/images/product_attributes/blank.png';
	else
		$blank_img = '';

	$color_attributes = (array)get_theme_mod('wpdanceclaratheme_layout_wc_color_attributes');
	$image_attributes = (array)get_theme_mod('wpdanceclaratheme_layout_wc_image_attributes');

	$urls = array();
	foreach ($image_attributes as $tax) {
		if (!taxonomy_exists($tax)) continue;

		$terms = get_terms($tax);
		foreach ($terms as $term)
			$urls[$tax][$term->slug] = attribute_option_image_url($tax, $term->slug);
	}

	?>
	<script type="text/javascript">
	// <![[CDATA
	jQuery(function($) {
		'use strict';
		$.extend(wpdanceclaratheme.woocommerce.imagepicker, {
			BLANK_IMG_URL             : '<?php echo esc_js($blank_img); ?>',
			image_attributes          : <?php echo json_encode($image_attributes); ?>,
			color_attributes          : <?php echo json_encode($color_attributes); ?>,
			image_option_urls_by_slug : <?php echo json_encode($urls); ?>
		});
	});
	// ]]>
	</script>
	<?php
}
endif;





if (!function_exists(__NAMESPACE__.'\\variation_attribute_image_options_html')):
/**
 * Filter hook for showing product attribute image/color options on single product page.
 *
 * Append script to convert normal select to image/color options picker.
 * 
 * @param  string $html Content to apply filter
 * @param  array $args 
 * 
 * (string) attribute
 * (array) options
 *     (string)
 *     ...
 * (WC_Product_Variable) product
 *     (int) id
 *     (WP_Post) post
 *         (string) post_name
 *         ...
 *     (string) product_type
 *     ...
 *     
 * 
 * @return string New content
 */
function variation_attribute_image_options_html($html, $args) {

	$is_color_attr = is_color_attr($args['attribute']);
	$is_image_attr = is_image_attr($args['attribute']);

	# Stop if attribute is not color and not image attribute
	if (!$is_color_attr && !$is_image_attr) 
		return $html;

	$products = array();
	$id       = (int)$args['product']->id;

	foreach ($args['options'] as $option) {
		
		if ($is_image_attr)
			$products[$id]['attributes'][$args['attribute']][$option]['img-src'] = attribute_option_image_url($args['attribute'], $option, $id);

		if ($is_color_attr)
			$products[$id]['attributes'][$args['attribute']][$option]['color'] = $option;
	}

	ob_start();
	?>

	<script type="text/javascript">
	// <![[CDATA
	jQuery(function($) {
		'use strict';
		$.extend(true, wpdanceclaratheme.woocommerce.imagepicker.products, <?php echo json_encode($products); ?>);
	});
	// ]]>
	</script>

	<?php
	$html .= ob_get_contents();
	ob_end_clean();
	return $html;
}
endif;


if (!function_exists(__NAMESPACE__.'\\enqueue_select2OptionPicker_script')):
/**
 * Enqueue jquery script select2OptionPicker
 */
function enqueue_select2OptionPicker_script() {
	wp_enqueue_script('select2OptionPicker', get_template_directory_uri().'/js/jQuery.select2OptionPicker.js', array('jquery'), '1.0.0', true);
}
endif;





/**
 * Hook action 'widget_display_callback' to apply image options picker on widget WC Layered Nav
 * 
 * @param array $instance
 * @param WP_Widget $widget
 * @param array $args
 * @return array $instance
 */
function apply_image_options_widget_layered_nav($instance, $widget, $args) {

	if (!is_a($widget, 'WC_Widget_Layered_Nav')
	|| !isset($instance['attribute']) || empty($instance['attribute'])
	|| !isset($instance['display_type']) || $instance['display_type'] != 'dropdown')
		return $instance;


	ob_start();

	$taxonomy = wc_attribute_taxonomy_name($instance['attribute']);

	if (is_color_attr($taxonomy) || is_image_attr($taxonomy)) {
		$selectors = array($taxonomy => '#'.$widget->id);

		?>
		<script> type="text/javascript">
		// <![[CDATA
		jQuery(function($) {
			'use strict';
			$.extend(wpdanceclaratheme.woocommerce.imagepicker.widget_selects, <?php echo json_encode($selectors); ?>);
		});
		// ]]>
		</script>
		<?php
	}

	$content = ob_get_contents();
	ob_end_clean();

	if (!empty($content))
		ImageOption::$append[$widget->id] = $content;

	return $instance;
	
}

/**
 * Hook 'wp_footer' to add script showing image options for widgets
 * 
 */
function widget_layered_nav_image_options_scripts() {
	foreach (ImageOption::$append as $id => $content)
		echo $content;
}





###################################################################################################
# HELPER FUNCTIONS
###################################################################################################





function attribute_option_image_url($attribute, $option, $product_id = null) {

	$fs   = \wpdanceclaratheme\Helper\wp_filesystem();
	$attr = esc_attr(strtolower($attribute));
	$opt  = esc_attr(strtolower($option));
	$src  = '';

	if ($product_id) {
		# check child theme directory images/product_attributes/[ID]/[ATTR]/[VALUE].png
		if ($fs->exists(get_stylesheet_directory() . "/images/product_attributes/$product_id/$attr/$opt.png"))
			$src = get_stylesheet_directory_uri() . "/images/product_attributes/$product_id/$attr/$opt.png";

		# check theme directory images/product_attributes/[ID]/[ATTR]/[VALUE].png
		elseif ($fs->exists(get_template_directory() . "/images/product_attributes/$product_id/$attr/$opt.png"))
			$src = get_template_directory_uri() . "/images/product_attributes/$product_id/$attr/$opt.png";
	}

	if (!$src) {
		# check child theme directory images/product_attributes/[ATTR]/[VALUE].png
		if ($fs->exists(get_stylesheet_directory() . "/images/product_attributes/$attr/$opt.png"))
			$src = get_stylesheet_directory_uri() . "/images/product_attributes/$attr/$opt.png";

		# check theme directory images/product_attributes/[ATTR]/[VALUE].png
		//elseif ($fs->exists(get_template_directory() . "/images/product_attributes/$attr/$opt.png"))
			$src = get_template_directory_uri() . "/images/product_attributes/$attr/$opt.png";
	}

	return $src;

}


function is_color_attr($attr) {
	$color_attributes = (array)get_theme_mod('wpdanceclaratheme_layout_wc_color_attributes');
	return in_array($attr, $color_attributes);
}

function is_image_attr($attr) {
	$image_attributes = (array)get_theme_mod('wpdanceclaratheme_layout_wc_image_attributes');
	return in_array($attr, $image_attributes);
}




class ImageOption {
	/**
	 * Code to append after widget output
	 * @var array
	 * - widget_id => content to append
	 */
	static $append = array();
}


