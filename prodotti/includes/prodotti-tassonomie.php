<?php
/**
 * Registrazione delle tassonomie per il Custom Post Type "product".
 *
 * Slug allineati alle convenzioni dell'ultima versione di WooCommerce:
 * - product_cat (rewrite: product-category)
 * - product_tag (rewrite: product-tag)
 * - attributi prodotto con prefisso WooCommerce pa_* (pa_colore, pa_tecnologia, pa_caratteristica, pa_taglia)
 *
 * @package Prodotti
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registra la tassonomia Categorie Prodotto (compatibile WooCommerce).
 *
 * @return void
 */
function prodotti_tassonomia_categoria(): void {
	if ( taxonomy_exists( 'product_cat' ) ) {
		register_taxonomy_for_object_type( 'product_cat', 'product' );
		return;
	}

	$labels = array(
		'name'              => _x( 'Categorie', 'taxonomy general name', 'prodotti' ),
		'singular_name'     => _x( 'Categoria', 'taxonomy singular name', 'prodotti' ),
		'search_items'      => __( 'Cerca categoria', 'prodotti' ),
		'all_items'         => __( 'Tutte le categorie', 'prodotti' ),
		'parent_item'       => __( 'Categoria padre', 'prodotti' ),
		'parent_item_colon' => __( 'Categoria padre:', 'prodotti' ),
		'edit_item'         => __( 'Modifica categoria', 'prodotti' ),
		'update_item'       => __( 'Aggiorna categoria', 'prodotti' ),
		'add_new_item'      => __( 'Aggiungi nuova categoria', 'prodotti' ),
		'new_item_name'     => __( 'Nome della nuova categoria', 'prodotti' ),
		'menu_name'         => __( 'Categorie', 'prodotti' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'query_var'         => true,
		'rewrite'           => array(
			'slug'         => 'product-category',
			'with_front'   => false,
			'hierarchical' => true,
		),
	);

	register_taxonomy( 'product_cat', 'product', $args );
}
add_action( 'init', 'prodotti_tassonomia_categoria', 5 );

/**
 * Registra la tassonomia Tag Prodotto (compatibile WooCommerce).
 *
 * @return void
 */
function prodotti_tassonomia_tag(): void {
	if ( taxonomy_exists( 'product_tag' ) ) {
		register_taxonomy_for_object_type( 'product_tag', 'product' );
		return;
	}

	$labels = array(
		'name'              => _x( 'Tag', 'taxonomy general name', 'prodotti' ),
		'singular_name'     => _x( 'Tag', 'taxonomy singular name', 'prodotti' ),
		'search_items'      => __( 'Cerca tag', 'prodotti' ),
		'all_items'         => __( 'Tutti i tag', 'prodotti' ),
		'parent_item'       => __( 'Tag padre', 'prodotti' ),
		'parent_item_colon' => __( 'Tag padre:', 'prodotti' ),
		'edit_item'         => __( 'Modifica tag', 'prodotti' ),
		'update_item'       => __( 'Aggiorna tag', 'prodotti' ),
		'add_new_item'      => __( 'Aggiungi nuovo tag', 'prodotti' ),
		'new_item_name'     => __( 'Nome del tag', 'prodotti' ),
		'menu_name'         => __( 'Tag', 'prodotti' ),
		'not_found'         => __( 'Nessun Tag Prodotto trovato', 'prodotti' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'query_var'         => true,
		'rewrite'           => array(
			'slug'       => 'product-tag',
			'with_front' => false,
		),
	);

	register_taxonomy( 'product_tag', 'product', $args );
}
add_action( 'init', 'prodotti_tassonomia_tag', 5 );

/**
 * Registra la tassonomia Attributo Colore (prefisso pa_ compatibile WooCommerce).
 *
 * @return void
 */
function prodotti_tassonomia_colore(): void {
	if ( taxonomy_exists( 'pa_colore' ) ) {
		register_taxonomy_for_object_type( 'pa_colore', 'product' );
		return;
	}

	$labels = array(
		'name'              => _x( 'Colori', 'taxonomy general name', 'prodotti' ),
		'singular_name'     => _x( 'Colore', 'taxonomy singular name', 'prodotti' ),
		'search_items'      => __( 'Cerca colore', 'prodotti' ),
		'all_items'         => __( 'Tutti i colori', 'prodotti' ),
		'parent_item'       => __( 'Colore padre', 'prodotti' ),
		'parent_item_colon' => __( 'Colore padre:', 'prodotti' ),
		'edit_item'         => __( 'Modifica colore', 'prodotti' ),
		'update_item'       => __( 'Aggiorna colore', 'prodotti' ),
		'add_new_item'      => __( 'Aggiungi nuovo colore', 'prodotti' ),
		'new_item_name'     => __( 'Nome del nuovo colore', 'prodotti' ),
		'menu_name'         => __( 'Colori', 'prodotti' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'query_var'         => true,
		'rewrite'           => array(
			'slug'       => 'colore',
			'with_front' => false,
		),
	);

	register_taxonomy( 'pa_colore', 'product', $args );
}
add_action( 'init', 'prodotti_tassonomia_colore', 5 );

/**
 * Registra la tassonomia Attributo Tecnologia (prefisso pa_ compatibile WooCommerce).
 *
 * @return void
 */
function prodotti_tassonomia_tecnologia(): void {
	if ( taxonomy_exists( 'pa_tecnologia' ) ) {
		register_taxonomy_for_object_type( 'pa_tecnologia', 'product' );
		return;
	}

	$labels = array(
		'name'              => _x( 'Tecnologie', 'taxonomy general name', 'prodotti' ),
		'singular_name'     => _x( 'Tecnologia', 'taxonomy singular name', 'prodotti' ),
		'search_items'      => __( 'Cerca tecnologia', 'prodotti' ),
		'all_items'         => __( 'Tutte le tecnologie', 'prodotti' ),
		'parent_item'       => __( 'Tecnologia padre', 'prodotti' ),
		'parent_item_colon' => __( 'Tecnologia padre:', 'prodotti' ),
		'edit_item'         => __( 'Modifica tecnologia', 'prodotti' ),
		'update_item'       => __( 'Aggiorna tecnologia', 'prodotti' ),
		'add_new_item'      => __( 'Aggiungi nuova tecnologia', 'prodotti' ),
		'new_item_name'     => __( 'Nome della nuova tecnologia', 'prodotti' ),
		'menu_name'         => __( 'Tecnologie', 'prodotti' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'query_var'         => true,
		'rewrite'           => array(
			'slug'       => 'tecnologia',
			'with_front' => false,
		),
	);

	register_taxonomy( 'pa_tecnologia', 'product', $args );
}
add_action( 'init', 'prodotti_tassonomia_tecnologia', 5 );

/**
 * Registra la tassonomia Attributo Caratteristica (prefisso pa_ compatibile WooCommerce).
 *
 * @return void
 */
function prodotti_tassonomia_caratteristica(): void {
	if ( taxonomy_exists( 'pa_caratteristica' ) ) {
		register_taxonomy_for_object_type( 'pa_caratteristica', 'product' );
		return;
	}

	$labels = array(
		'name'              => _x( 'Caratteristiche', 'taxonomy general name', 'prodotti' ),
		'singular_name'     => _x( 'Caratteristica', 'taxonomy singular name', 'prodotti' ),
		'search_items'      => __( 'Cerca caratteristiche', 'prodotti' ),
		'all_items'         => __( 'Tutte le caratteristiche', 'prodotti' ),
		'parent_item'       => __( 'Caratteristica padre', 'prodotti' ),
		'parent_item_colon' => __( 'Caratteristica padre:', 'prodotti' ),
		'edit_item'         => __( 'Modifica caratteristica', 'prodotti' ),
		'update_item'       => __( 'Aggiorna caratteristica', 'prodotti' ),
		'add_new_item'      => __( 'Aggiungi nuova caratteristica', 'prodotti' ),
		'new_item_name'     => __( 'Nome della caratteristica', 'prodotti' ),
		'menu_name'         => __( 'Caratteristiche', 'prodotti' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'query_var'         => true,
		'rewrite'           => array(
			'slug'       => 'caratteristica',
			'with_front' => false,
		),
	);

	register_taxonomy( 'pa_caratteristica', 'product', $args );
}
add_action( 'init', 'prodotti_tassonomia_caratteristica', 5 );

/**
 * Registra la tassonomia Attributo Taglia (prefisso pa_ compatibile WooCommerce).
 *
 * @return void
 */
function prodotti_tassonomia_taglia(): void {
	if ( taxonomy_exists( 'pa_taglia' ) ) {
		register_taxonomy_for_object_type( 'pa_taglia', 'product' );
		return;
	}

	$labels = array(
		'name'              => _x( 'Taglie', 'taxonomy general name', 'prodotti' ),
		'singular_name'     => _x( 'Taglia', 'taxonomy singular name', 'prodotti' ),
		'search_items'      => __( 'Cerca taglia', 'prodotti' ),
		'all_items'         => __( 'Tutte le taglie', 'prodotti' ),
		'parent_item'       => __( 'Taglia padre', 'prodotti' ),
		'parent_item_colon' => __( 'Taglia padre:', 'prodotti' ),
		'edit_item'         => __( 'Modifica taglia', 'prodotti' ),
		'update_item'       => __( 'Aggiorna taglia', 'prodotti' ),
		'add_new_item'      => __( 'Aggiungi nuova taglia', 'prodotti' ),
		'new_item_name'     => __( 'Nome della taglia', 'prodotti' ),
		'menu_name'         => __( 'Taglie', 'prodotti' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'query_var'         => true,
		'rewrite'           => array(
			'slug'       => 'taglia',
			'with_front' => false,
		),
	);

	register_taxonomy( 'pa_taglia', 'product', $args );
}
add_action( 'init', 'prodotti_tassonomia_taglia', 5 );
