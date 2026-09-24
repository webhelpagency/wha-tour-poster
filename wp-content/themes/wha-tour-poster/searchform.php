<?php
/**
 * Template for displaying search forms
 *
 * Uses the .search / .search__field / .btn--primary markup of the reference layout.
 *
 * @package WHA_Tour_Poster
 */

$wha_tour_poster_search_id = wp_unique_id( 'wha-tour-poster-search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="search">
		<div class="search__field">
			<label for="<?php echo esc_attr( $wha_tour_poster_search_id ); ?>"><?php echo esc_html_x( 'Search', 'label', 'wha-tour-poster' ); ?></label>
			<input
				type="search"
				id="<?php echo esc_attr( $wha_tour_poster_search_id ); ?>"
				name="s"
				value="<?php echo esc_attr( get_search_query() ); ?>"
				placeholder="<?php echo esc_attr_x( 'Where are we going?', 'placeholder', 'wha-tour-poster' ); ?>"
			/>
		</div>
		<button type="submit" class="btn btn--primary">
			<?php echo esc_html_x( 'Search', 'submit button', 'wha-tour-poster' ); ?>
		</button>
	</div>
</form>
