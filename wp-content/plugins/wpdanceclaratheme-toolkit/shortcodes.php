<?php
/** 
 * Custom shortcodes for theme WPDanceClaraTheme
 *
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @author tvlgiao
 */


if (!function_exists('wpdanceclaratheme_shortcode_site_header')) {
	/**
	 * Shortcode: wpdanceclaratheme_site_header
	 */
	function wpdanceclaratheme_shortcode_site_header($atts) {
		extract(shortcode_atts(array(
			'class' => '',
			'logo' => '',
		), $atts));
		
		$header_logo = $logo ? $logo : get_theme_mod('wpdanceclaratheme_header_logo');
		$hide_site_title = get_theme_mod('wpdanceclaratheme_hide_site_title');
		$hide_site_desc = get_theme_mod('wpdanceclaratheme_hide_site_desc');
		
		ob_start();
		?>
		<div class="header-main <?php echo esc_attr($class) ?>">
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
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
}
add_shortcode('wpdanceclaratheme_site_header', 'wpdanceclaratheme_shortcode_site_header');




if (!function_exists('wpdanceclaratheme_shortcode_user_links')) {
	/**
	 * Shortcode: wpdanceclaratheme_user_links
	 */
	function wpdanceclaratheme_shortcode_user_links($atts) {
		extract(shortcode_atts(array(
			'class'               => '',
			'login_icon_class'    => '',
			'logout_icon_class'   => '',
			'register_icon_class' => '',
		), $atts));
		
		ob_start();
		?>
		<ul class="wpdanceclaratheme-user-links <?php echo esc_attr($class) ?>">
		
		<?php
		/** User is not logged in? */
		if (!is_user_logged_in()) {

			$login_icon = $login_icon_class ? '<i class="'.esc_attr($login_icon_class).'" aria-hidden="true"></i>' : '';
			
			/** Show 'login' link */
			echo apply_filters('wpdanceclaratheme_login_li', sprintf('<li><a href="%s">%s%s</a></li>', wp_login_url(), $login_icon, __("Login", 'wpdanceclaratheme-toolkit')));
			
			/** User can register? */
			if (get_option('users_can_register')) {

				$register_icon = $register_icon_class ? '<i class="'.esc_attr($register_icon_class).'" aria-hidden="true"></i>' : '';
				
				/** Show 'register' link */
				echo apply_filters('wpdanceclaratheme_register_li', sprintf('<li><a href="%s">%s%s</a></li>', wp_registration_url(), $register_icon, __("Register", 'wpdanceclaratheme-toolkit')));
			}
		}
		
		/** User is logged in */
		else {
			$logout_icon = $logout_icon_class ? '<i class="'.esc_attr($logout_icon_class).'" aria-hidden="true"></i>' : '';

			/** Show 'logout' link */
			echo apply_filters('wpdanceclaratheme_logout_li', sprintf('<li><a href="%s">%s%s</a></li>', wp_logout_url(), $logout_icon, __("Logout", 'wpdanceclaratheme-toolkit')));
		}
		?>
		
		</ul><!-- .wpdanceclaratheme-user-links -->
		<?php
		return ob_get_clean();
	}
}
add_shortcode('wpdanceclaratheme_user_links', 'wpdanceclaratheme_shortcode_user_links');




if (!function_exists('wpdanceclaratheme_shortcode_dropdowncart')) {
	/**
	 * Shortcode: wpdanceclaratheme_dropdowncart
	 */
	function wpdanceclaratheme_shortcode_dropdowncart($atts) {

		if (!class_exists('WooCommerce_Widget_DropdownCart'))
			return '<div class="alert alert-warning">'.__("Plugin <code>woocommerce-dropdown-cart</code> is not activated. Shortcode is disabled.", 'wpdanceclaratheme-toolkit').'</div>';
		
		$atts = shortcode_atts(array(
			'title' => '',
			'hide_if_empty' => 0,
			'show_on_checkout' => 0,
			'popup_align' => 'left',
			'class' => '',
			'style' => '',
		), $atts);

		if ($atts['style']) 
			$atts['class'] .= ' style-'.$atts['style'];
		else
			$atts['class'] .= ' style-default';
	
		ob_start();
		the_widget('WooCommerce_Widget_DropdownCart', $atts, array(
			'before_widget' => '<div class="widget ' . esc_attr($atts['class']) . ' %s">'
		));
		$s = ob_get_clean();

		if ($atts['style'] == 'icon-qty') {

			# Add a <span> wrap quantity to help css displaying icon & qty styling
			$s = preg_replace('#(<a\s+.*?class="\s*dropdown-total\s*"\s*.*?>\s*)([0-9]+)#msiu', '\1<span class="dropdown-cart-qty">\2</span>', $s);
		}

		return $s;
	}
}
add_shortcode('wpdanceclaratheme_dropdowncart', 'wpdanceclaratheme_shortcode_dropdowncart');





if (!function_exists('wpdanceclaratheme_shortcode_nav_menu')):
/**
 * Shorcode: wpdanceclaratheme_nav_menu display navigation menu
 *
 * Shortcode Parameters:
 * - theme_location		: menu location (primary)
 * - class 				: custom CSS class
 *
 * @param array $atts shortcode parameters
 * @return string
 */
function wpdanceclaratheme_shortcode_nav_menu($atts) {

	/**
	 * @var array(string => num) array indexed by theme_location, indicate number of menu created by this shortcode.
	 */
	static $menu_ids = array();

	$atts = shortcode_atts(array(
		'theme_location' => wpdanceclaratheme\Config::$default_menu_location,
		'class' => '',
	), $atts);

	$theme_location = sanitize_key($atts['theme_location']);
	$class = $atts['class'];

	if (!array_key_exists($theme_location, $menu_ids))
		$menu_ids[$theme_location] = '';

	$menu_id = $theme_location.'-menu'.$menu_ids[$theme_location];

	ob_start();
	?>
	<nav class="navigation navigation-<?php echo esc_attr($menu_id) ?>" role="navigation">
		<button class="menu-toggle"><?php _e( 'Menu', 'wpdanceclaratheme-toolkit' ); ?></button>
		<a class="screen-reader-text skip-link" href="#content" title="<?php esc_attr_e( 'Skip to content', 'wpdanceclaratheme-toolkit' ); ?>"><?php _e( 'Skip to content', 'wpdanceclaratheme-toolkit' ); ?></a>
		
		<?php
		wp_nav_menu(array(
			'theme_location'     => $theme_location,
			'menu_class'         => 'nav-menu '.$class, 
			'menu_id'            => $menu_id
		));
		?>
	</nav>

	<?php
	
	@$menu_ids[$theme_location]++;

	return ob_get_clean();
}
endif;
add_shortcode('wpdanceclaratheme_nav_menu', 'wpdanceclaratheme_shortcode_nav_menu');





if (!function_exists('wpdanceclaratheme_shortcode_owlcarousel_js_defaults')) {
	function wpdanceclaratheme_shortcode_owlcarousel_js_defaults() {
		return array(
			'items'                 => 5,
			'itemsDesktop'          => '1199,4',
			'itemsDesktopSmall'     => '979,3',
			'itemsTablet'           => '768,2',
			'itemsTabletSmall'      => 'false',
			'itemsMobile'           => '479,1',
			'itemsCustom'           => 'false',
			'singleItem'            => 'false',
			'itemsScaleUp'          => 'false',
			'slideSpeed'            => 200,
			'paginationSpeed'       => 800,
			'rewindSpeed'           => 1000,
			'autoPlay'              => 'false',
			'stopOnHover'           => 'false',
			'navigation'            => 'true',
			'navigationText'        => 'prev,next',
			'rewindNav'             => 'true',
			'scrollPerPage'         => 'false',
			'pagination'            => 'true',
			'paginationNumbers'     => 'false',
			'responsive'            => 'true',
			'responsiveRefreshRate' => 200,
			'responsiveBaseWidth'   => 'window',
			'baseClass'             => 'owl-carousel',
			'theme'                 => 'owl-theme',
			'lazyLoad'              => 'false',
			'lazyFollow'            => 'true',
			'lazyEffect'            => 'false',
			'autoHeight'            => 'false',
			'jsonPath'              => 'false',
			'jsonSuccess'           => 'false',
			'dragBeforeAnimFinish'  => 'true',
			'mouseDrag'             => 'true',
			'touchDrag'             => 'true',
			'addClassActive'        => 'false',
			'transitionStyle'       => 'false',
			'class'                 => '',
		);
	}
} 

if (!function_exists('wpdanceclaratheme_shortcode_owlcarousel_defaults')) {
	function wpdanceclaratheme_shortcode_owlcarousel_defaults() {
		$ret = array();

		$a = wpdanceclaratheme_shortcode_owlcarousel_js_defaults();
		foreach ($a as $k => $v)
			$ret[strtolower($k)] = $v;

		return $ret;
	}
}

if (!function_exists('wpdanceclaratheme_shortcode_owlcarousel_to_jsvars')) {
	function wpdanceclaratheme_shortcode_owlcarousel_to_jsvars($vars) {

		$names = array();
		$defs = wpdanceclaratheme_shortcode_owlcarousel_js_defaults();
		foreach ($defs as $k => $v)
			$names[strtolower($k)] = $k;

		$new_vars = array();
		foreach ($vars as $k => $v)
			if (array_key_exists($k, $names))
				$new_vars[ $names[$k] ] = $v;
			else
				$new_vars[$k] = $v;

		return $new_vars;
	}
}

if (!function_exists('wpdanceclaratheme_shortcode_owlcarousel')) {
	/**
	 * Shortcode: wpdanceclaratheme_owlcarousel
	 */
	function wpdanceclaratheme_shortcode_owlcarousel($atts, $content = null) {
		$defaults = wpdanceclaratheme_shortcode_owlcarousel_defaults();
		
		$atts = shortcode_atts($defaults, $atts);
		$atts = wpdanceclaratheme_shortcode_owlcarousel_to_jsvars($atts);
		
		$atts['id'] = 'wpdanceclaratheme_owlcarousel_'.uniqid();
		
		wp_enqueue_style('owl.carousel-css');
		wp_enqueue_style('owl.theme-css');
		wp_enqueue_style('owl.transitions-css');
		wp_enqueue_script('owl.carousel');
		wp_enqueue_script('wpdanceclaratheme-owlcarousel-init');
		wp_localize_script('wpdanceclaratheme-owlcarousel-init', $atts['id'], $atts);
		
		return '<div id="'.$atts['id'].'" class="wpdanceclaratheme-owlcarousel ' . esc_attr($atts['class']) . '">'.do_shortcode($content).'</div>';
			
	}
}
add_shortcode('wpdanceclaratheme_owlcarousel', 'wpdanceclaratheme_shortcode_owlcarousel');






if (!function_exists('wpdanceclaratheme_shortcode_countdown')):
/**
 * Short Code: wpdanceclaratheme_countdown
 * 
 * @param  [type] $atts [description]
 * @return [type]       [description]
 */
function wpdanceclaratheme_shortcode_countdown($atts) {
	static $count = 0;

	$atts = shortcode_atts(array(
		'el_class'      => '',
		'value'      => '',
		'hide_weeks' => 'no',
		'hide_days'  => 'no',
		'hide_hr'    => 'no',
		'hide_min'   => 'no',
		'hide_sec'   => 'no',
	), $atts);

	extract($atts);

	$uid = 'wpdanceclaratheme_countdown_'.$count++;

	wp_enqueue_script('jquery-countdown');

	ob_start();
	?>
	<span id="<?php echo esc_attr($uid); ?>" class="wpdanceclaratheme-countdown <?php echo esc_attr($el_class); ?>"></span>
	<script type="text/javascript">
	// <![CDATA[
	jQuery(function($) {
		$("#<?php echo esc_attr($uid); ?>").countdown("<?php echo esc_html($value); ?>", function(e) {
			$(this).html(e.strftime(''

				<?php if ($hide_weeks != 'yes' && $hide_weeks != 'true'): ?>
				+ '<span class="week"><span class="val">%w</span><span class="txt"><?php echo _x("weeks", 'countdown shortcode', 'wpdanceclaratheme-toolkit'); ?></span></span>'
				<?php endif; ?>

				<?php if ($hide_days != 'yes' && $hide_days != 'true'): ?>
				+ '<span class="day"><span class="val">%d</span><span class="txt"><?php echo _x("days", 'countdown shortcode', 'wpdanceclaratheme-toolkit'); ?></span></span>'
				<?php endif; ?>

				<?php if ($hide_hr != 'yes' && $hide_hr != 'true'): ?>
				+ '<span class="hour"><span class="val">%H</span><span class="txt"><?php echo _x("hours", 'countdown shortcode', 'wpdanceclaratheme-toolkit'); ?></span></span>'
				<?php endif; ?>

				<?php if ($hide_min != 'yes' && $hide_min != 'true'): ?>
				+ '<span class="min"><span class="val">%M</span><span class="txt"><?php echo _x("minutes", 'countdown shortcode', 'wpdanceclaratheme-toolkit'); ?></span></span>'
				<?php endif; ?>

				<?php if ($hide_sec != 'yes' && $hide_sec != 'true'): ?>
				+ '<span class="sec"><span class="val">%S</span><span class="txt"><?php echo _x("seconds", 'countdown shortcode', 'wpdanceclaratheme-toolkit'); ?></span></span>'
				<?php endif; ?>
			));
		});
	});
	// ]]>
	</script>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
endif;
add_shortcode('wpdanceclaratheme_countdown', 'wpdanceclaratheme_shortcode_countdown');






if (!function_exists('wpdanceclaratheme_shortcode_tagtray')):
/**
 * Short Code: wpdanceclaratheme_tagtray
 *
 * Shortcode to show instagram photos
 * 
 * @param  [type] $atts [description]
 * @return [type]       [description]
 */
function wpdanceclaratheme_shortcode_tagtray($atts) {
	$atts = shortcode_atts(array(
		'el_class'                  => '',
		'masonry'                   => '',
		'gallery_code'              => '',
		'gallery_type'              => 'passive', // carousel, passive
		'overlay_type'              => 'none',
		'carousel_slide_width'      => '',
		'carousel_slide_max_height' => '',
		'page_size'                 => '',
		'hide_source_icon'          => '',
	), $atts);

	extract($atts);

	$masonry_class = $masonry ? 'masonry' : '';

	wp_enqueue_script('tagtray', '//api.tagtray.com/v3/tagtray.js', array(), '3');

	if ($gallery_type == 'carousel')
		wp_enqueue_script('tagtray-carousel', '//api.tagtray.com/v3/tagtray-carousel.js', array('tagtray'), '3');


	$s = '<div class="wpdanceclaratheme-tagtray tagtray-gallery '.esc_attr($el_class.' '.$masonry_class).'" ';
	if ($gallery_code)              $s .= 'data-gallery-code="'.esc_attr($gallery_code).'" ';
	if ($gallery_type)              $s .= 'data-gallery-type="'.esc_attr($gallery_type).'" ';
	if ($overlay_type)              $s .= 'data-overlay-type="'.esc_attr($overlay_type).'" ';
	if ($carousel_slide_width)      $s .= 'data-carousel-slide-width="'.esc_attr($carousel_slide_width).'" ';
	if ($carousel_slide_max_height) $s .= 'data-carousel-slide-max-height="'.esc_attr($carousel_slide_max_height).'" ';
	if ($page_size)                 $s .= 'data-page-size="'.esc_attr($page_size).'" ';
	if ($hide_source_icon)          $s .= 'data-hide-source-icon="'.esc_attr($hide_source_icon).'" ';
	$s .= '></div>';


	return $s;
}
endif;
add_shortcode('wpdanceclaratheme_tagtray', 'wpdanceclaratheme_shortcode_tagtray');




if (!function_exists('wpdanceclaratheme_ubermenu_shortcode')):
/**
 * Short Code: ubermenu
 *
 * Register this shortcode to show standard menu if UberMenu plugin is not activated
 * 
 * @param  [type] $atts [description]
 * @return [type]       [description]
 */
function wpdanceclaratheme_ubermenu_shortcode($atts) {
	$atts = shortcode_atts(array(
		'menu' => '',
	), $atts);

	extract($atts);

	ob_start();
	?>
	<nav class="navigation" role="navigation">
		<button class="menu-toggle"><?php _e( 'Menu', 'wpdanceclaratheme-toolkit' ); ?></button>
		<a class="screen-reader-text skip-link" href="#content" title="<?php esc_attr_e( 'Skip to content', 'wpdanceclaratheme-toolkit' ); ?>"><?php _e( 'Skip to content', 'wpdanceclaratheme-toolkit' ); ?></a>
		
		<?php
		wp_nav_menu(array(
			'menu'       => $menu,
			'menu_class' => 'nav-menu', 
		));
		?>
	</nav>

	<?php

	return ob_get_clean();
}
endif;
if (!shortcode_exists('ubermenu'))
	add_shortcode('ubermenu', 'wpdanceclaratheme_ubermenu_shortcode');





if (!function_exists('wpdanceclaratheme_shortcode_f2tagcloud')) {
	/**
	 * Shortcode: wpdanceclaratheme_dropdowncart
	 */
	function wpdanceclaratheme_shortcode_f2tagcloud($atts) {

		if (!class_exists('F2_Tag_Cloud_Widget'))
			return '<div class="alert alert-warning">'.__("Plugin <code>f2-tagcloud</code> is not activated. Shortcode is disabled.", 'wpdanceclaratheme-toolkit').'</div>';
		
		$atts = shortcode_atts(array(
			'title'      => 'Tag',
			'smallest'   => '8',
			'largest'    => '22',
			'number'     => '0',
			'format'     => 'flat',
			'orderby'    => 'name',
			'order'      => 'ASC',
			'alignment'  => 'default',
			'padding'    => '0',
			'taxonomy'   => 'post_tag',
			'exclude'    => '',
			'include'    => '',
			'unit'       => 'px',
			'class'      => '',
		), $atts);

	
		ob_start();
		the_widget('F2_Tag_Cloud_Widget', $atts, array(
			'before_widget' => '<div class="widget ' . esc_attr($atts['class']) . ' %s">'
		));
		$s = ob_get_clean();

		return $s;
	}
}
add_shortcode('wpdanceclaratheme_f2tagcloud', 'wpdanceclaratheme_shortcode_f2tagcloud');

