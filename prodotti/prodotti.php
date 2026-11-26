<?php
/**
 * Plugin Name:       Prodotti
 * Plugin URI:        https://www.rswstudio.it/wordpress/plugin-prodotti-per-wordpress/
 * Description:       Gestione catalogo prodotti per WordPress compatibile con gli slug di WooCommerce, categorie, attributi, gallerie, allegati PDF e shortcode.
 * Version:           2.0.0
 * Requires at least: 7.0
 * Tested up to:      7.2
 * Requires PHP:      8.3
 * Requires Plugins:  meta-box
 * Author:            RSW Studio
 * Author URI:        https://www.rswstudio.it
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       prodotti
 * Domain Path:       /languages
 *
 * @package Prodotti
 */

defined( 'ABSPATH' ) || exit;

define( 'PRODOTTI_VERSION', '2.0.0' );
define( 'PRODOTTI_DIRECTORY', dirname( plugin_basename( __FILE__ ) ) );
define( 'PRODOTTI_PATH', plugin_dir_path( __FILE__ ) );
define( 'PRODOTTI_URL', plugin_dir_url( __FILE__ ) );

// Costanti legacy mantenute per retrocompatibilità.
if ( ! defined( 'Prodotti_Version' ) ) {
	define( 'Prodotti_Version', PRODOTTI_VERSION );
}
if ( ! defined( 'Prodotti_Directory' ) ) {
	define( 'Prodotti_Directory', PRODOTTI_DIRECTORY );
}
if ( ! defined( 'Prodotti_Path' ) ) {
	define( 'Prodotti_Path', PRODOTTI_PATH );
}
if ( ! defined( 'Prodotti_URL' ) ) {
	define( 'Prodotti_URL', PRODOTTI_URL );
}

require_once PRODOTTI_PATH . 'includes/prodotti-metabox.php';
require_once PRODOTTI_PATH . 'includes/prodotti-pluginaggiuntivi.php';
require_once PRODOTTI_PATH . 'includes/prodotti-opzioni.php';
require_once PRODOTTI_PATH . 'includes/prodotti-tassonomie.php';
require_once PRODOTTI_PATH . 'includes/prodotti-widget.php';
require_once PRODOTTI_PATH . 'includes/prodotti-shortcode.php';

/**
 * Carica il textdomain del plugin al momento corretto (compatibile WordPress 7.x).
 *
 * @return void
 */
function prodotti_load_textdomain(): void {
	load_plugin_textdomain(
		'prodotti',
		false,
		PRODOTTI_DIRECTORY . '/languages'
	);
}
add_action( 'init', 'prodotti_load_textdomain', 1 );

/**
 * Registra il Custom Post Type "product" con slug compatibili con WooCommerce.
 *
 * @return void
 */
function prodotti_init(): void {
	if ( post_type_exists( 'product' ) ) {
		return;
	}

	$labels = array(
		'name'                  => _x( 'Prodotti', 'Post type general name', 'prodotti' ),
		'singular_name'         => _x( 'Prodotto', 'Post type singular name', 'prodotti' ),
		'add_new'               => __( 'Aggiungi Prodotto', 'prodotti' ),
		'add_new_item'          => __( 'Aggiungi Prodotto', 'prodotti' ),
		'edit_item'             => __( 'Modifica Prodotto', 'prodotti' ),
		'new_item'              => __( 'Nuovo Prodotto', 'prodotti' ),
		'all_items'             => __( 'Tutti i Prodotti', 'prodotti' ),
		'view_item'             => __( 'Vedi la pagina', 'prodotti' ),
		'search_items'          => __( 'Cerca Prodotti', 'prodotti' ),
		'not_found'             => __( 'Nessun Prodotto trovato', 'prodotti' ),
		'not_found_in_trash'    => __( 'Nessun Prodotto trovato nel cestino', 'prodotti' ),
		'parent_item_colon'     => '',
		'menu_name'             => __( 'Prodotti', 'prodotti' ),
		'featured_image'        => __( 'Immagine Prodotto', 'prodotti' ),
		'set_featured_image'    => __( 'Imposta immagine prodotto', 'prodotti' ),
		'remove_featured_image' => __( 'Rimuovi immagine prodotto', 'prodotti' ),
		'use_featured_image'    => __( 'Usa come immagine prodotto', 'prodotti' ),
	);

	$custom_icon_path = PRODOTTI_PATH . 'assets/img/prodotti.png';
	$menu_icon        = file_exists( $custom_icon_path )
		? PRODOTTI_URL . 'assets/img/prodotti.png'
		: 'dashicons-cart';

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'query_var'          => true,
		'menu_icon'          => $menu_icon,
		'rewrite'            => array(
			'slug'       => 'product',
			'with_front' => false,
		),
		'capability_type'    => 'post',
		'has_archive'        => 'shop',
		'hierarchical'       => false,
		'menu_position'      => 20,
		'supports'           => array(
			'title',
			'excerpt',
			'editor',
			'thumbnail',
			'page-attributes',
			'custom-fields',
		),
	);

	/**
	 * Filtra gli argomenti di registrazione del Custom Post Type product.
	 *
	 * @param array<string, mixed> $args Argomenti CPT.
	 */
	$args = (array) apply_filters( 'prodotti_post_type_args', $args );

	register_post_type( 'product', $args );
}
add_action( 'init', 'prodotti_init', 5 );

/**
 * Aggiorna i messaggi di feedback nell'editor per il post type "product".
 *
 * @param array<string, mixed> $messages Messaggi esistenti.
 * @return array<string, mixed>
 */
function prodotti_updated_messages( array $messages ): array {
	$post = get_post();
	if ( ! $post instanceof WP_Post ) {
		return $messages;
	}

	$post_type        = get_post_type( $post );
	$post_type_object = $post_type ? get_post_type_object( $post_type ) : null;

	if ( 'product' !== $post_type || ! $post_type_object instanceof WP_Post_Type ) {
		return $messages;
	}

	$revision_id = isset( $_GET['revision'] ) ? absint( wp_unslash( $_GET['revision'] ) ) : 0;

	$messages['product'] = array(
		0  => '',
		1  => __( 'Prodotto aggiornato.', 'prodotti' ),
		2  => __( 'Campo personalizzato aggiornato.', 'prodotti' ),
		3  => __( 'Campo personalizzato eliminato.', 'prodotti' ),
		4  => __( 'Prodotto aggiornato.', 'prodotti' ),
		/* translators: %s: data e ora della revisione */
		5  => $revision_id > 0 ? sprintf( __( 'Prodotto ripristinato alla revisione %s', 'prodotti' ), wp_post_revision_title( $revision_id, false ) ) : false,
		6  => __( 'Prodotto pubblicato.', 'prodotti' ),
		7  => __( 'Prodotto salvato.', 'prodotti' ),
		8  => __( 'Prodotto inviato.', 'prodotti' ),
		9  => sprintf(
			/* translators: %1$s: data programmata */
			__( 'Prodotto schedulato per: <strong>%1$s</strong>.', 'prodotti' ),
			date_i18n( __( 'M j, Y @ G:i', 'prodotti' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Bozza prodotto aggiornata.', 'prodotti' ),
	);

	if ( $post_type_object->publicly_queryable ) {
		$permalink = get_permalink( $post->ID );
		if ( is_string( $permalink ) ) {
			$view_link = sprintf(
				' <a href="%s">%s</a>',
				esc_url( $permalink ),
				esc_html__( 'Visualizza prodotto', 'prodotti' )
			);
			$messages['product'][1] .= $view_link;
			$messages['product'][6] .= $view_link;
			$messages['product'][9] .= $view_link;

			$preview_permalink = get_preview_post_link( $post );
			if ( is_string( $preview_permalink ) ) {
				$preview_link = sprintf(
					' <a target="_blank" rel="noopener noreferrer" href="%s">%s</a>',
					esc_url( $preview_permalink ),
					esc_html__( 'Anteprima prodotto', 'prodotti' )
				);
				$messages['product'][8]  .= $preview_link;
				$messages['product'][10] .= $preview_link;
			}
		}
	}

	return $messages;
}
add_filter( 'post_updated_messages', 'prodotti_updated_messages' );

/**
 * Aggiunge le schede di aiuto contestuali (sostituisce il deprecato hook contextual_help).
 *
 * @param WP_Screen $screen Oggetto screen corrente.
 * @return void
 */
function prodotti_add_help_tabs( WP_Screen $screen ): void {
	if ( 'product' !== $screen->post_type ) {
		return;
	}

	if ( 'product' === $screen->id ) {
		$screen->add_help_tab(
			array(
				'id'      => 'prodotti_edit_help',
				'title'   => __( 'Guida Prodotto', 'prodotti' ),
				'content' =>
					'<p>' . esc_html__( 'Cose da ricordare nella modifica di un prodotto:', 'prodotti' ) . '</p>' .
					'<ul>' .
					'<li>' . esc_html__( 'Specifica dettagliatamente le categorie in cui può essere inserito.', 'prodotti' ) . '</li>' .
					'<li>' . esc_html__( 'Specifica nel titolo la tipologia e il codice identificativo.', 'prodotti' ) . '</li>' .
					'</ul>' .
					'<p>' . esc_html__( 'Per programmare una pubblicazione futura, imposta la data nel pannello Pubblica.', 'prodotti' ) . '</p>',
			)
		);
	} elseif ( 'edit-product' === $screen->id ) {
		$screen->add_help_tab(
			array(
				'id'      => 'prodotti_list_help',
				'title'   => __( 'Elenco Prodotti', 'prodotti' ),
				'content' => '<p>' . esc_html__( 'Elenco prodotti inseriti con dettaglio di categoria, tag e tassonomie personalizzate.', 'prodotti' ) . '</p>',
			)
		);
	}

	$screen->set_help_sidebar(
		'<p><strong>' . esc_html__( 'Per maggiori informazioni:', 'prodotti' ) . '</strong></p>' .
		'<p><a href="' . esc_url( 'https://www.rswstudio.it/wordpress/plugin-prodotti-per-wordpress/' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Documentazione RSW Studio', 'prodotti' ) . '</a></p>' .
		'<p><a href="' . esc_url( 'https://wordpress.org/plugins/prodotti/' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Pagina WordPress.org', 'prodotti' ) . '</a></p>'
	);
}
add_action( 'current_screen', 'prodotti_add_help_tabs' );

/**
 * Helper opzionale per aggiungere galleria e allegati PDF al contenuto del prodotto.
 *
 * @param string $content Contenuto del post.
 * @return string
 */
function prodotti_aggiungiattributialcontenuto( string $content ): string {
	if ( ! is_singular( 'product' ) || ! function_exists( 'rwmb_meta' ) ) {
		return $content;
	}

	$prodotto_galleria = '';
	$lista_galleria    = rwmb_meta( 'prodotti_galleria', array( 'type' => 'image' ) );

	if ( is_array( $lista_galleria ) && count( $lista_galleria ) > 0 ) {
		$prodotto_galleria .= '<ul class="prodotti-gallery clearing-thumbs" data-clearing>';
		foreach ( $lista_galleria as $image ) {
			if ( ! is_array( $image ) || empty( $image['full_url'] ) || empty( $image['url'] ) ) {
				continue;
			}
			$title = isset( $image['title'] ) ? (string) $image['title'] : '';
			$alt   = isset( $image['alt'] ) && '' !== $image['alt'] ? (string) $image['alt'] : $title;
			$prodotto_galleria .= sprintf(
				'<li><a href="%1$s" title="%2$s"><img src="%3$s" class="th" data-caption="%2$s" alt="%4$s" loading="lazy" /></a></li>',
				esc_url( (string) $image['full_url'] ),
				esc_attr( $title ),
				esc_url( (string) $image['url'] ),
				esc_attr( $alt )
			);
		}
		$prodotto_galleria .= '</ul>';
	}

	$prodotto_allegati = '';
	$lista_allegati    = rwmb_meta( 'prodotti_allegati', array( 'type' => 'file' ) );

	if ( is_array( $lista_allegati ) && count( $lista_allegati ) > 0 ) {
		$prodotto_allegati .= '<ul class="prodotti-attachments inline-list">';
		foreach ( $lista_allegati as $allegato ) {
			if ( ! is_array( $allegato ) || empty( $allegato['url'] ) ) {
				continue;
			}
			$title = isset( $allegato['title'] ) && '' !== $allegato['title']
				? (string) $allegato['title']
				: __( 'Scarica allegato', 'prodotti' );
			$prodotto_allegati .= sprintf(
				'<li><a href="%1$s" title="%2$s" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-pdf" aria-hidden="true"></span> %3$s</a></li>',
				esc_url( (string) $allegato['url'] ),
				esc_attr( $title ),
				esc_html( $title )
			);
		}
		$prodotto_allegati .= '</ul>';
	}

	return $content . $prodotto_galleria . $prodotto_allegati;
}
// Decommenta per abilitare automaticamente galleria e allegati nel contenuto:
// add_filter( 'the_content', 'prodotti_aggiungiattributialcontenuto' );

/**
 * Operazioni di attivazione del plugin (registra CPT/tassonomie e aggiorna i permalink).
 *
 * @return void
 */
function prodotti_activate(): void {
	prodotti_init();
	prodotti_tassonomia_categoria();
	prodotti_tassonomia_tag();
	prodotti_tassonomia_colore();
	prodotti_tassonomia_tecnologia();
	prodotti_tassonomia_caratteristica();
	prodotti_tassonomia_taglia();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'prodotti_activate' );

/**
 * Operazioni di disattivazione del plugin.
 *
 * @return void
 */
function prodotti_deactivate(): void {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'prodotti_deactivate' );
