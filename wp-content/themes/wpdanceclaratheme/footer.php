<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @since WPDanceClaraTheme 1.0
 */

$footer = wpdanceclaratheme\Helper\get_footer();
?>

			</div><!-- #main -->
		</div><!-- .container -->
		
		
		<footer id="colophon" class="container site-footer">
			<?php echo $footer ?>

			<?php if (!wpdanceclaratheme\Helper\hide_credit_link()): ?>
			<div class="site-info">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'wpdanceclaratheme' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'wpdanceclaratheme' ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'wpdanceclaratheme' ), 'WordPress' ); ?></a>
				-
				<a href="<?php esc_html_e("http://www.wpdance.com/", 'wpdanceclaratheme'); ?>"><?php esc_html_e("WPDanceClaraTheme WordPress Theme", 'wpdanceclaratheme'); ?></a>
			</div><!-- .site-info -->
			<?php endif; ?>
		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>
