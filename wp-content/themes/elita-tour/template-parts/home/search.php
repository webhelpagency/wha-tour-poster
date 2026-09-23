<?php
/**
 * Homepage filter bar.
 *
 * Ported from `div.searchbar` of the reference layout. The three selects are
 * plain query vars of the tour taxonomies, so the archive filters natively.
 * Without the companion plugin the bar falls back to the theme search form.
 *
 * @package Elita_Tour
 */

if ( ! elita_tour_has_core() ) :
	?>
	<div class="searchbar">
		<div class="container">
			<?php get_search_form(); ?>
		</div>
	</div>
	<?php
	return;
endif;

$elita_tour_filters = array(
	'tour_category' => array(
		'label'   => __( 'Where', 'elita-tour' ),
		'any'     => __( 'Any category', 'elita-tour' ),
		'orderby' => 'name',
	),
	'tour_season'   => array(
		'label'    => __( 'When', 'elita-tour' ),
		'any'      => __( 'Any season', 'elita-tour' ),
		'orderby'  => 'meta_value_num',
		'meta_key' => '_elita_tour_order', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Four season terms, ordered by the plugin's sort index.
	),
	'tour_country'  => array(
		'label'   => __( 'Country', 'elita-tour' ),
		'any'     => __( 'All countries', 'elita-tour' ),
		'orderby' => 'name',
	),
);

$elita_tour_fields = array();

foreach ( $elita_tour_filters as $elita_tour_taxonomy => $elita_tour_filter ) {
	if ( ! taxonomy_exists( $elita_tour_taxonomy ) ) {
		continue;
	}

	$elita_tour_dropdown_args = array(
		'taxonomy'          => $elita_tour_taxonomy,
		'name'              => $elita_tour_taxonomy,
		'id'                => 'elita-tour-filter-' . str_replace( '_', '-', $elita_tour_taxonomy ),
		'class'             => '',
		'value_field'       => 'slug',
		'selected'          => (string) get_query_var( $elita_tour_taxonomy ),
		// `show_option_none` is used instead of `show_option_all`, because only
		// it accepts an empty value: `show_option_all` is hard-coded to `0`,
		// which would end up in the query string of every submitted search.
		'show_option_none'  => $elita_tour_filter['any'],
		'option_none_value' => '',
		'hide_empty'        => true,
		'hide_if_empty'     => true,
		'hierarchical'      => is_taxonomy_hierarchical( $elita_tour_taxonomy ),
		'orderby'           => $elita_tour_filter['orderby'],
		'echo'              => false,
	);

	if ( isset( $elita_tour_filter['meta_key'] ) ) {
		$elita_tour_dropdown_args['meta_key'] = $elita_tour_filter['meta_key']; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Four season terms, ordered by the plugin's sort index.
	}

	$elita_tour_dropdown = wp_dropdown_categories( $elita_tour_dropdown_args );

	if ( ! $elita_tour_dropdown ) {
		continue;
	}

	$elita_tour_fields[] = array(
		'id'       => $elita_tour_dropdown_args['id'],
		'label'    => $elita_tour_filter['label'],
		'dropdown' => $elita_tour_dropdown,
	);
}

if ( empty( $elita_tour_fields ) ) {
	return;
}

// A GET form drops the query string of its action, so plain permalinks need the
// archive arguments as hidden fields.
$elita_tour_action = elita_tour_archive_link();
$elita_tour_hidden = array();
$elita_tour_query  = wp_parse_url( $elita_tour_action, PHP_URL_QUERY );

if ( $elita_tour_query ) {
	wp_parse_str( $elita_tour_query, $elita_tour_hidden );
	$elita_tour_action = remove_query_arg( array_keys( $elita_tour_hidden ), $elita_tour_action );
}
?>
<div class="searchbar">
	<div class="container">
		<form class="search" action="<?php echo esc_url( $elita_tour_action ); ?>" method="get" aria-label="<?php esc_attr_e( 'Filter programmes', 'elita-tour' ); ?>">
			<?php foreach ( $elita_tour_hidden as $elita_tour_key => $elita_tour_value ) : ?>
				<input type="hidden" name="<?php echo esc_attr( $elita_tour_key ); ?>" value="<?php echo esc_attr( is_scalar( $elita_tour_value ) ? $elita_tour_value : '' ); ?>">
			<?php endforeach; ?>

			<?php foreach ( $elita_tour_fields as $elita_tour_field ) : ?>
				<div class="search__field">
					<label for="<?php echo esc_attr( $elita_tour_field['id'] ); ?>"><?php echo esc_html( $elita_tour_field['label'] ); ?></label>
					<?php
					echo wp_kses(
						$elita_tour_field['dropdown'],
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

			<button class="btn btn--primary" type="submit"><?php esc_html_e( 'Show programmes', 'elita-tour' ); ?></button>
		</form>
	</div>
</div><!-- .searchbar -->
