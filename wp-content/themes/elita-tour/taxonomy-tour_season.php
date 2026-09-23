<?php
/**
 * The template for displaying a season archive
 *
 * Only ever loaded when the Elita Tour Core plugin registers the `tour_season`
 * taxonomy. It shares the catalogue layout with archive-tour.php.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Elita_Tour
 */

get_header();

get_template_part( 'template-parts/tour/archive' );

get_footer();
