<?php
/**
 * Registrazione dei meta box del plugin Prodotti (integrazione Meta Box).
 *
 * @package Prodotti
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'rwmb_meta_boxes', 'prodotti_register_meta_boxes' );

/**
 * Registra i meta box per il Custom Post Type "product".
 *
 * @param array<int, array<string, mixed>> $meta_boxes Elenco dei meta box registrati.
 * @return array<int, array<string, mixed>>
 */
function prodotti_register_meta_boxes( array $meta_boxes ): array {
	$prefix = 'prodotti_';

	$meta_boxes[] = array(
		'id'         => 'prodotto',
		'title'      => __( 'Prodotto', 'prodotti' ),
		'post_types' => array( 'product' ),
		'pages'      => array( 'product' ), // Retrocompatibilità con versioni precedenti di Meta Box.
		'context'    => 'normal',
		'priority'   => 'high',
		'autosave'   => true,
		'fields'     => array(
			array(
				'type' => 'heading',
				'name' => __( 'Dati del prodotto', 'prodotti' ),
				'id'   => 'datiheading',
			),
			array(
				'name'  => __( 'Codice', 'prodotti' ),
				'id'    => "{$prefix}codice",
				'type'  => 'text',
				'std'   => '',
				'clone' => false,
			),
			array(
				'name' => __( 'In vetrina', 'prodotti' ),
				'id'   => "{$prefix}vetrina",
				'type' => 'checkbox',
				'std'  => 0,
			),
			array(
				'type' => 'heading',
				'name' => __( 'Gallery', 'prodotti' ),
				'desc' => __( 'Selezione multipla della galleria delle foto del prodotto', 'prodotti' ),
				'id'   => 'galleryheading',
			),
			array(
				'name' => __( 'Galleria immagini', 'prodotti' ),
				'id'   => "{$prefix}galleria",
				'type' => 'image_advanced',
			),
			array(
				'type' => 'heading',
				'name' => __( 'Allegati (solo PDF)', 'prodotti' ),
				'desc' => __( 'Allegati quali schede tecniche, manuali, certificazioni', 'prodotti' ),
				'id'   => 'allegatiheading',
			),
			array(
				'name'             => __( 'File PDF da allegare', 'prodotti' ),
				'id'               => "{$prefix}allegati",
				'type'             => 'file_advanced',
				'max_file_uploads' => 5,
			),
			array(
				'type' => 'heading',
				'name' => __( 'Video', 'prodotti' ),
				'desc' => __( 'Inserisci il link del video caricato su YouTube', 'prodotti' ),
				'id'   => 'videoheading',
			),
			array(
				'name' => __( 'Video YouTube', 'prodotti' ),
				'id'   => "{$prefix}video",
				'type' => 'oembed',
			),
		),
	);

	return $meta_boxes;
}
