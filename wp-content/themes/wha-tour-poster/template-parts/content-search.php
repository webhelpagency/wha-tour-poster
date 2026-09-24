<?php
/**
 * Template part for displaying results in search pages
 *
 * Uses the same `.card` component as the blog list. Tours are rendered as
 * poster cards by search.php instead.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WHA_Tour_Poster
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'card card--post' ); ?>>
	<?php wha_tour_poster_post_thumbnail( 'wha-tour-poster-wide', 'card__media' ); ?>

	<div class="card__body">
		<?php the_title( sprintf( '<h2 class="card__title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta card__meta">
				<?php
				wha_tour_poster_posted_on();
				wha_tour_poster_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>

		<div class="entry-summary card__excerpt">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	</div><!-- .card__body -->
</article><!-- #post-<?php the_ID(); ?> -->
