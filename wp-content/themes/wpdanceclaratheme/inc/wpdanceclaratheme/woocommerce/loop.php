<?php
namespace wpdanceclaratheme\WooCommerce\Loop;

/** Stop if WooCommerce is not installed */
if (!defined('WOOCOMMERCE_VERSION')) return;


/**
 * woocommerce_before_shop_loop_item:
 *     - ADD       div_image_open - 5
 *     - DEFAULT   woocommerce_template_loop_product_link_open - 10
 *
 * woocommerce_before_shop_loop_item_title:
 *     - DEFAULT   woocommerce_show_product_loop_sale_flash - 10
 *     - DEFAULT   woocommerce_template_loop_product_thumbnail - 10
 *     - ADD       woocommerce_template_loop_product_link_close - 15
 *     - ADD       buttons_div_open - 20
 *     - ADD       woocommerce_template_loop_add_to_cart - 25
 *     - ADD       show_wishlist - 25
 *     - ADD       show_quickview - 25
 *     - ADD       buttons_div_close - 30
 *     - ADD       div_image_close - 35
 *     
 * woocommerce_shop_loop_item_title:
 *     - ADD       div_details_open - 5
 *     - DEFAULT   woocommerce_template_loop_product_title - 10
 *
 * woocommerce_after_shop_loop_item_title:
 *     - DEFAULT   woocommerce_template_loop_rating - 5
 *     - DEFAULT   woocommerce_template_loop_price - 10
 *
 * woocommerce_after_shop_loop_item:
 *     - REMOVE    woocommerce_template_loop_product_link_close - 5
 *     - REMOVE    woocommerce_template_loop_add_to_cart - 10
 *     - REMOVE    yith_add_quick_view_button - 15
 *     - ADD       div_details_close - 15
 */

add_action    ('woocommerce_before_shop_loop_item', __NAMESPACE__.'\\div_image_open', 5);

add_action    ('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 15);
add_action    ('woocommerce_before_shop_loop_item_title', __NAMESPACE__.'\\div_buttons_open', 20);
add_action    ('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 25);
add_action    ('woocommerce_before_shop_loop_item_title', __NAMESPACE__.'\\show_wishlist', 25);
add_action    ('woocommerce_before_shop_loop_item_title', __NAMESPACE__.'\\show_quickview', 25);
add_action    ('woocommerce_before_shop_loop_item_title', __NAMESPACE__.'\\div_buttons_close', 30);
add_action    ('woocommerce_before_shop_loop_item_title', __NAMESPACE__.'\\div_image_close', 35);

add_action    ('woocommerce_shop_loop_item_title', __NAMESPACE__.'\\div_details_open', 5);

remove_action ('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action ('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
add_action    ('woocommerce_after_shop_loop_item', __NAMESPACE__.'\\div_details_close', 15);

if (defined('YITH_WCQV') && class_exists('YITH_WCQV_Frontend'))
	remove_action('woocommerce_after_shop_loop_item', array(\YITH_WCQV_Frontend::get_instance(), 'yith_add_quick_view_button'), 15);




function div_image_open() {
	?>
	<div class="wpdanceclaratheme-product-image">
	<?php
}

function div_image_close() {
	?>
	</div><!-- .wpdanceclaratheme-product-image -->
	<?php
}

function div_buttons_open() {
	?>
	<div class="wpdanceclaratheme-product-buttons">
	<?php
}

function div_buttons_close() {
	?>
	</div><!-- .wpdanceclaratheme-product-buttons -->
	<?php
}

function div_details_open() {
	?>
	<div class="wpdanceclaratheme-product-details">
	<?php
}

function div_details_close() {
	?>
	</div><!-- .wpdanceclaratheme-product-details -->
	<?php
}

function show_wishlist() {
	if (defined('YITH_WCWL'))
		echo do_shortcode('[yith_wcwl_add_to_wishlist]');
}

function show_quickview() {
	if (defined('YITH_WCQV') && class_exists('YITH_WCQV_Frontend'))
		\YITH_WCQV_Frontend::get_instance()->yith_add_quick_view_button();
}

