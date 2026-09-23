<?php
/**
 * The template for displaying the programme catalogue
 *
 * Only ever loaded when the Elita Tour Core plugin registers the `tour` post
 * type. The layout itself lives in template-parts/tour/archive.php, which the
 * taxonomy-tour_*.php templates reuse.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Elita_Tour
 */

get_header();

get_template_part( 'template-parts/tour/archive' );

get_footer();
