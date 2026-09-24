<?php
/**
 * Homepage filter bar.
 *
 * Ported from `div.searchbar` of the reference layout. The three selects are
 * plain query vars of the tour taxonomies, so the archive filters natively.
 * Without the companion plugin the bar falls back to the theme search form.
 *
 * @package WHA_Tour_Poster
 */

if ( ! wha_tour_poster_has_core() ) :
	?>
	<div class="searchbar">
		<div class="container">
			<?php get_search_form(); ?>
		</div>
	</div>
	<?php
	return;
endif;

$wha_tour_poster_filters = array(
	'tour_category' => array(
		'label'   => __( 'Where', 'wha-tour-poster' ),
		'any'     => __( 'Any category', 'wha-tour-poster' ),
		'orderby' => 'name',
	),
	'tour_season'   => array(
		'label'    => __( 'When', 'wha-tour-poster' ),
		'any'      => __( 'Any season', 'wha-tour-poster' ),
		'orderby'  => 'meta_value_num',
		'meta_key' => '_wha_tour_order', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Four season terms, ordered by the plugin's sort index.
	),
	'tour_country'  => array(
		'label'   => __( 'Country', 'wha-tour-poster' ),
		'any'     => __( 'All countries', 'wha-tour-poster' ),
		'orderby' => 'name',
	),
);

$wha_tour_poster_fields = array();

foreach ( $wha_tour_poster_filters as $wha_tour_poster_taxonomy => $wha_tour_poster_filter ) {
	if ( ! taxonomy_exists( $wha_tour_poster_taxonomy ) ) {
		continue;
	}

	$wha_tour_poster_dropdown_args = array(
		'taxonomy'          => $wha_tour_poster_taxonomy,
		'name'              => $wha_tour_poster_taxonomy,
		'id'                => 'wha-tour-poster-filter-' . str_replace( '_', '-', $wha_tour_poster_taxonomy ),
		'class'             => '',
		'value_field'       => 'slug',
		'selected'          => (string) get_query_var( $wha_tour_poster_taxonomy ),
		// `show_option_none` is used instead of `show_option_all`, because only
		// it accepts an empty value: `show_option_all` is hard-coded to `0`,
		// which would end up in the query string of every submitted search.
		'show_option_none'  => $wha_tour_poster_filter['any'],
		'option_none_value' => '',
		'hide_empty'        => true,
		'hide_if_empty'     => true,
		'hierarchical'      => is_taxonomy_hierarchical( $wha_tour_poster_taxonomy ),
		'orderby'           => $wha_tour_poster_filter['orderby'],
		'echo'              => false,
	);

	if ( isset( $wha_tour_poster_filter['meta_key'] ) ) {
		$wha_tour_poster_dropdown_args['meta_key'] = $wha_tour_poster_filter['meta_key']; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Four season terms, ordered by the plugin's sort index.
	}

	$wha_tour_poster_dropdown = wp_dropdown_categories( $wha_tour_poster_dropdown_args );

	if ( ! $wha_tour_poster_dropdown ) {
		continue;
	}

	$wha_tour_poster_fields[] = array(
		'id'       => $wha_tour_poster_dropdown_args['id'],
		'label'    => $wha_tour_poster_filter['label'],
		'dropdown' => $wha_tour_poster_dropdown,
	);
}

if ( empty( $wha_tour_poster_fields ) ) {
	return;
}

// A GET form drops the query string of its action, so plain permalinks need the
// archive arguments as hidden fields.
$wha_tour_poster_action = wha_tour_poster_archive_link();
$wha_tour_poster_hidden = array();
$wha_tour_poster_query  = wp_parse_url( $wha_tour_poster_action, PHP_URL_QUERY );

if ( $wha_tour_poster_query ) {
	wp_parse_str( $wha_tour_poster_query, $wha_tour_poster_hidden );
	$wha_tour_poster_action = remove_query_arg( array_keys( $wha_tour_poster_hidden ), $wha_tour_poster_action );
}
?>
<div class="searchbar">
	<div class="container">
		<form class="search" action="<?php echo esc_url( $wha_tour_poster_action ); ?>" method="get" aria-label="<?php esc_attr_e( 'Filter programmes', 'wha-tour-poster' ); ?>">
			<?php foreach ( $wha_tour_poster_hidden as $wha_tour_poster_key => $wha_tour_poster_value ) : ?>
				<input type="hidden" name="<?php echo esc_attr( $wha_tour_poster_key ); ?>" value="<?php echo esc_attr( is_scalar( $wha_tour_poster_value ) ? $wha_tour_poster_value : '' ); ?>">
			<?php endforeach; ?>

			<?php foreach ( $wha_tour_poster_fields as $wha_tour_poster_field ) : ?>
				<div class="search__field">
					<label for="<?php echo esc_attr( $wha_tour_poster_field['id'] ); ?>"><?php echo esc_html( $wha_tour_poster_field['label'] ); ?></label>
					<?php
					echo wp_kses(
						$wha_tour_poster_field['dropdown'],
						array(
							'select' => array(
								'name'  => true,
								'id'    => true,
								'class' => true,
							),
							'option' => array(
								'value'    => true,
								'selected' => true,
								'class'    => true,
							),
						)
					);
					?>
				</div>
			<?php endforeach; ?>

			<button class="btn btn--primary" type="submit"><?php esc_html_e( 'Show programmes', 'wha-tour-poster' ); ?></button>
		</form>
	</div>
</div><!-- .searchbar -->
