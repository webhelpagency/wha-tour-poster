<?php
/**
 * Template for displaying search forms
 *
 * Uses the .search / .search__field / .btn--primary markup of the reference layout.
 *
 * @package Elita_Tour
 */

$elita_tour_search_id = wp_unique_id( 'elita-tour-search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="search">
		<div class="search__field">
			<label for="<?php echo esc_attr( $elita_tour_search_id ); ?>"><?php echo esc_html_x( 'Search', 'label', 'elita-tour' ); ?></label>
			<input
				type="search"
				id="<?php echo esc_attr( $elita_tour_search_id ); ?>"
				name="s"
				value="<?php echo esc_attr( get_search_query() ); ?>"
				placeholder="<?php echo esc_attr_x( 'Where are we going?', 'placeholder', 'elita-tour' ); ?>"
			/>
		</div>
		<button type="submit" class="btn btn--primary">
			<?php echo esc_html_x( 'Search', 'submit button', 'elita-tour' ); ?>
		</button>
	</div>
</form>
