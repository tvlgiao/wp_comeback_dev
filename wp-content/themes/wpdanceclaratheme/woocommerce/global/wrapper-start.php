<?php
/**
 * Content wrappers
 *
 * @author tvlgiao
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div id="primary" class="<?php echo wpdanceclaratheme\Helper\get_primary_div_class(); ?> content-area">
	<?php get_sidebar('before-content') ?>
	<div id="content" class="site-content" role="main">

