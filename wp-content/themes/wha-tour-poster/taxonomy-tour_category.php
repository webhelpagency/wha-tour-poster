<?php
/**
 * The template for displaying a tour category archive
 *
 * Only ever loaded when the WHA Tours Core plugin registers the `tour_category`
 * taxonomy. It shares the catalogue layout with archive-tour.php.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WHA_Tour_Poster
 */

get_header();

get_template_part( 'template-parts/tour/archive' );

get_footer();
