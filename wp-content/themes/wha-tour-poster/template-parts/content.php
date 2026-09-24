<?php
/**
 * Template part for displaying posts in a list
 *
 * Uses the `.card` component of the reference layout. A single post is rendered
 * by template-parts/content-single.php instead.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WHA_Tour_Poster
 */

$wha_tour_poster_term = wha_tour_poster_get_post_term( get_the_ID() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'card card--post' ); ?>>
	<?php wha_tour_poster_post_thumbnail( 'wha-tour-poster-wide', 'card__media' ); ?>

	<div class="card__body">
		<?php if ( $wha_tour_poster_term instanceof WP_Term ) : ?>
			<span class="badge badge--cat badge--cat-<?php echo esc_attr( wha_tour_poster_term_color( $wha_tour_poster_term ) ); ?> card__cat"><?php echo esc_html( $wha_tour_poster_term->name ); ?></span>
		<?php endif; ?>

		<?php the_title( '<h2 class="card__title entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

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

	<footer class="entry-footer card__foot">
		<?php wha_tour_poster_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
