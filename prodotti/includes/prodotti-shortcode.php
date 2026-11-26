<?php
/**
 * Shortcode del plugin Prodotti.
 *
 * @package Prodotti
 */

defined( 'ABSPATH' ) || exit;

add_shortcode( 'prodotti_ultimiinseriti', 'prodotti_ultimiinseriti' );

/**
 * Shortcode [prodotti_ultimiinseriti] per mostrare gli ultimi prodotti pubblicati.
 *
 * @param array<string, mixed>|string $atts Attributi dello shortcode.
 * @return string
 */
function prodotti_ultimiinseriti( array|string $atts = array() ): string {
	$atts = shortcode_atts(
		array(
			'limit'     => 10,
			'categoria' => '',
		),
		is_array( $atts ) ? $atts : array(),
		'prodotti_ultimiinseriti'
	);

	$limit = absint( $atts['limit'] );
	if ( $limit < 1 ) {
		$limit = 10;
	}

	$args = array(
		'posts_per_page' => $limit,
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$categoria = sanitize_text_field( (string) $atts['categoria'] );
	if ( '' !== $categoria ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $categoria,
			),
		);
	}

	$ultimi_prodotti = get_posts( $args );

	$output = '<div class="prodotti-ultimi-inseriti">';
	$output .= '<h2>' . esc_html__( 'Ultimi prodotti inseriti', 'prodotti' ) . '</h2>';

	if ( empty( $ultimi_prodotti ) ) {
		$output .= '<p>' . esc_html__( 'Nessun prodotto disponibile al momento.', 'prodotti' ) . '</p>';
		$output .= '</div>';
		return $output;
	}

	$output .= '<ul class="prodotti-lista">';
	foreach ( $ultimi_prodotti as $prodotto ) {
		if ( ! $prodotto instanceof WP_Post ) {
			continue;
		}

		$permalink = get_permalink( $prodotto );
		$title     = get_the_title( $prodotto );
		$date      = get_the_date( 'j F Y', $prodotto );

		$output .= '<li class="prodotti-lista-item">';
		$output .= '<p class="prodotti-data">' . esc_html( (string) $date ) . '</p>';
		$output .= '<a href="' . esc_url( (string) $permalink ) . '">' . esc_html( $title ) . '</a>';
		$output .= '<hr />';
		$output .= '</li>';
	}
	$output .= '</ul>';
	$output .= '</div>';

	return $output;
}
