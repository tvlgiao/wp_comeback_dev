<?php
/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @since WPDanceClaraTheme 1.0
 */

get_header(); ?>

	<div id="primary" class="col-md-<?php echo wpdanceclaratheme\Helper\get_main_column_size() ?> content-area">
		<?php get_sidebar('before-content') ?>
		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'wpdanceclaratheme' ), get_search_query() ); ?></h1>
			</header>

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

			<?php wpdanceclaratheme\Helper\paging_nav(); ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
		<?php get_sidebar('after-content'); ?>
	</div><!-- #primary -->

<?php if (wpdanceclaratheme\Helper\should_show_main_sidebar()) get_sidebar(); ?>
<?php get_footer(); ?>
