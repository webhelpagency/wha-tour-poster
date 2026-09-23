<?php
/**
 * Template part for displaying results in search pages
 *
 * Uses the same `.card` component as the blog list. Tours are rendered as
 * poster cards by search.php instead.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Elita_Tour
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'card card--post' ); ?>>
	<?php elita_tour_post_thumbnail( 'elita-tour-wide', 'card__media' ); ?>

	<div class="card__body">
		<?php the_title( sprintf( '<h2 class="card__title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta card__meta">
				<?php
				elita_tour_posted_on();
				elita_tour_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>

		<div class="entry-summary card__excerpt">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	</div><!-- .card__body -->
</article><!-- #post-<?php the_ID(); ?> -->
