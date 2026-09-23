<?php
/**
 * Template part for displaying a single post
 *
 * The list view of the same post lives in template-parts/content.php.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Elita_Tour
 */

$elita_tour_term = elita_tour_get_post_term( get_the_ID() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<header class="entry-header">
		<?php
		if ( $elita_tour_term instanceof WP_Term ) :
			$elita_tour_term_link = get_term_link( $elita_tour_term );

			if ( ! is_wp_error( $elita_tour_term_link ) ) :
				?>
				<a class="eyebrow" href="<?php echo esc_url( $elita_tour_term_link ); ?>"><?php echo esc_html( $elita_tour_term->name ); ?></a>
				<?php
			endif;
		endif;

		the_title( '<h1 class="entry-title">', '</h1>' );

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				elita_tour_posted_on();
				elita_tour_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php elita_tour_post_thumbnail( 'elita-tour-wide' ); ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'elita-tour' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php elita_tour_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
