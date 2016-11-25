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
				

				<article id="portfolio-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-container row">

						<div class="col-sm-6">
							<?php if (has_post_thumbnail() && !post_password_required() && !is_attachment()) : ?>
							<div class="entry-thumbnail">
								<?php the_post_thumbnail(); ?>
							</div>
							<?php endif; ?>

							<?php if ($files = get_post_meta(get_the_ID(), 'wpdance_portfolio_more_images', 1)): ?>
							<ul class="wpdance-portfolio-more-images">
								<?php foreach ((array)$files as $attachment_id => $attachment_url): ?>
								<li><?php echo wp_get_attachment_image($attachment_id, 'large'); ?></li>
								<?php endforeach; ?>
							</ul>
							<?php endif; ?>
						</div>

						<div class="col-sm-6">
							<header class="entry-header">
								<h1 class="entry-title"><?php the_title(); ?></h1>
								<?php wpdanceclaratheme\Helper\breadcrumb() ?>
							</header><!-- .entry-header -->

							<div class="entry-content">
								<?php
									/* translators: %s: Name of current post */
									the_content( sprintf(
										wp_kses(__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'wpdanceclaratheme' ), \wpdanceclaratheme\Config::$allowed_html),
										the_title( '<span class="screen-reader-text">', '</span>', false )
									) );

									wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'wpdanceclaratheme' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
								?>
							</div><!-- .entry-content -->

							<footer class="entry-meta">
								<?php if($s = get_the_term_list(0, 'wpdance_portfolio_client', '', ', ', '')): ?>
								<dl class="wpdance-portfolio-client">
									<dt><?php esc_html_e("Client", 'wpdanceclaratheme'); ?></dt>
									<dd><?php echo $s; ?></dd>
								</dl>
								<?php endif; ?>

								<dl class="wpdance-portfolio-date">
									<dt><?php esc_html_e("Date", 'wpdanceclaratheme'); ?></dt>
									<dd><?php echo sprintf(wp_kses(__('<time class="entry-date" datetime="%s">%s</time>', 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html), get_the_date('c'), get_the_date()); ?></dd>
								</dl>

								<?php if($s = get_the_term_list(0, 'wpdance_portfolio_skill', '', ', ', '')): ?>
								<dl class="wpdance-portfolio-skill">
									<dt><?php esc_html_e("Skills", 'wpdanceclaratheme'); ?></dt>
									<dd><?php echo $s; ?></dd>
								</dl>
								<?php endif; ?>

								<?php if($s = get_the_term_list(0, 'wpdance_portfolio_category', '', ', ', '')): ?>
								<dl class="wpdance-portfolio-category">
									<dt><?php esc_html_e("Categories", 'wpdanceclaratheme'); ?></dt>
									<dd><?php echo $s; ?></dd>
								</dl>
								<?php endif; ?>

								<?php edit_post_link( esc_html__( 'Edit', 'wpdanceclaratheme' ), '<span class="edit-link">', '</span>' ); ?>

								<?php if (get_the_author_meta( 'description' ) && is_multi_author()) : ?>
									<?php get_template_part( 'author-bio' ); ?>
								<?php endif; ?>
							</footer><!-- .entry-meta -->

							<?php if (shortcode_exists('woocommerce_social_media_share_buttons')) 
								echo do_shortcode('[woocommerce_social_media_share_buttons]'); ?>

							<?php wpdanceclaratheme\Helper\portfolio_nav(); ?>

						</div>
					</div>
				</article><!-- #post -->


				<?php comments_template(); ?>

			<?php endwhile; ?>

		</div><!-- #content -->
		<?php get_sidebar('after-content'); ?>
	</div><!-- #primary -->

<?php if (wpdanceclaratheme\Helper\should_show_main_sidebar()) get_sidebar(); ?>
<?php get_footer(); ?>
