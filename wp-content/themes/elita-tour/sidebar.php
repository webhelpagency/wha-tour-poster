<?php
/**
 * The sidebar containing the main widget area
 *
 * Included by index.php, archive.php, search.php and single.php as the aside
 * column of the `.layout--sidebar` grid, and only when the widget area holds
 * something.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Elita_Tour
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
