<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @since WPDanceClaraTheme 1.0
 */

get_header(); ?>

	<div id="primary" class="<?php echo esc_attr(wpdanceclaratheme\Helper\get_primary_div_class()); ?> content-area">
		<?php get_sidebar('before-content') ?>
		<div id="content" class="site-content" role="main">

			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Not Found', 'wpdanceclaratheme' ); ?></h1>
			</header>

			<div class="page-wrapper">
				<div class="page-content">
					<h2 class="text-uppercase text-center h1 wpdanceclaratheme_style_general_color2"><?php echo esc_html_x("404", '404 page', 'wpdanceclaratheme'); ?></h2>
					<p class="text-uppercase text-center h2"><?php echo esc_html_x("Oops! Page not found.", '404 page', 'wpdanceclaratheme'); ?></p>
					<p class="text-uppercase text-center wpdanceclaratheme_style_general_color5"><?php echo esc_html_x("Sorry, but the page you are looking for is not found. please, make sure you have typed the current URL.", '404 page', 'wpdanceclaratheme'); ?></p>

					<div class="wpdanceclaratheme-search-form-xl text-center">
						<?php get_search_form(); ?>
					</div>

				</div><!-- .page-content -->
			</div><!-- .page-wrapper -->

		</div><!-- #content -->
		<?php get_sidebar('after-content'); ?>
	</div><!-- #primary -->

<?php if (wpdanceclaratheme\Helper\should_show_main_sidebar()) get_sidebar(); ?>
<?php get_footer(); ?>
