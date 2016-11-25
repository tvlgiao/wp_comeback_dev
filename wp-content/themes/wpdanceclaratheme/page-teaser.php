<?php
/**
 * Template Name: Teaser
 *
 * Display full screen page without header and footer.
 *
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @since WPDanceClaraTheme 1.0
 */

?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
		<div class="container-fluid">
			<div id="main" class="row site-main">
				<div id="primary" class="content-area">
					<?php get_sidebar('before-content') ?>
					<div id="content" class="site-content" role="main">

						<?php /* The loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<?php if (!wpdanceclaratheme\Helper\hide_title()): ?>
								<header class="entry-header">
									<h1 class="entry-title"><?php the_title(); ?></h1>
									<?php wpdanceclaratheme\Helper\breadcrumb() ?>
								</header><!-- .entry-header -->
								<?php endif ?>

								<div class="entry-content">
									<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
									<div class="entry-thumbnail">
										<?php the_post_thumbnail(); ?>
									</div>
									<?php endif; ?>
									
									<?php the_content(); ?>
									<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'wpdanceclaratheme' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
								</div><!-- .entry-content -->

								<footer class="entry-meta">
									<?php edit_post_link( esc_html__( 'Edit', 'wpdanceclaratheme' ), '<span class="edit-link">', '</span>' ); ?>
								</footer><!-- .entry-meta -->
							</article><!-- #post -->

							<?php comments_template(); ?>
						<?php endwhile; ?>

					</div><!-- #content -->
					<?php get_sidebar('after-content'); ?>
				</div><!-- #primary -->
			</div><!-- #main -->
		</div><!-- .container-fluid -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>
