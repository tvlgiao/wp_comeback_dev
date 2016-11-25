<?php
/**
 * Sidebar wpdanceclaratheme-after-content
 */


if ( is_active_sidebar( 'wpdanceclaratheme-after-content' ) ) : ?>
	<div id="sidebar_wpdanceclaratheme_after_content" class="sidebar-container" role="complementary">
		<div class="sidebar-inner">
			<div class="widget-area">
				<?php dynamic_sidebar( 'wpdanceclaratheme-after-content' ); ?>
			</div><!-- .widget-area -->
		</div><!-- .sidebar-inner -->
	</div><!-- #sidebar_wpdanceclaratheme_before_content -->
<?php endif; ?>
