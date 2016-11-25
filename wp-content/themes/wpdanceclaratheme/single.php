<?php
/**
 * The template for displaying all single posts
 *
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @since WPDanceClaraTheme 1.0
 */

get_header(); ?>


	<div id="primary" class="<?php echo esc_attr(wpdanceclaratheme\Helper\get_primary_div_class()); ?> content-area">
		<?php get_sidebar('before-content') ?>
		<div id="content" class="site-content" role="main">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
				<?php wpdanceclaratheme\Helper\post_nav(); ?>
				<?php comments_template(); ?>

			<?php endwhile; ?>

		</div><!-- #content -->
		<?php get_sidebar('after-content'); ?>
	</div><!-- #primary -->

<?php if (wpdanceclaratheme\Helper\should_show_main_sidebar()) get_sidebar(); ?>
<?php get_footer(); ?>
