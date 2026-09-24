<?php
/**
 * Template part for displaying a single post
 *
 * The list view of the same post lives in template-parts/content.php.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WHA_Tour_Poster
 */

$wha_tour_poster_term = wha_tour_poster_get_post_term( get_the_ID() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<header class="entry-header">
		<?php
		if ( $wha_tour_poster_term instanceof WP_Term ) :
			$wha_tour_poster_term_link = get_term_link( $wha_tour_poster_term );

			if ( ! is_wp_error( $wha_tour_poster_term_link ) ) :
				?>
				<a class="eyebrow" href="<?php echo esc_url( $wha_tour_poster_term_link ); ?>"><?php echo esc_html( $wha_tour_poster_term->name ); ?></a>
				<?php
			endif;
		endif;

		the_title( '<h1 class="entry-title">', '</h1>' );

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				wha_tour_poster_posted_on();
				wha_tour_poster_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php wha_tour_poster_post_thumbnail( 'wha-tour-poster-wide' ); ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wha-tour-poster' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php wha_tour_poster_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
