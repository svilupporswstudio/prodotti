<?php
/**
 * Gestione dei plugin richiesti e consigliati tramite API standard di WordPress.
 *
 * Sostituisce TGM_Plugin_Activation utilizzando esclusivamente funzioni native
 * di WordPress (Requires Plugins header, get_plugins, is_plugin_active,
 * admin_notices, wp_nonce_url e Thickbox).
 *
 * @package Prodotti
 */

defined( 'ABSPATH' ) || exit;

/**
 * Restituisce l'elenco dei plugin richiesti e consigliati per Prodotti.
 *
 * @return array<int, array{name: string, slug: string, file: string, required: bool, min_version: string}>
 */
function prodotti_get_plugin_dependencies(): array {
	return array(
		array(
			'name'        => 'Meta Box',
			'slug'        => 'meta-box',
			'file'        => 'meta-box/meta-box.php',
			'required'    => true,
			'min_version' => '5.9.0',
		),
		array(
			'name'        => 'WP-PostViews',
			'slug'        => 'wp-postviews',
			'file'        => 'wp-postviews/wp-postviews.php',
			'required'    => false,
			'min_version' => '1.77',
		),
		array(
			'name'        => 'WP-PageNavi',
			'slug'        => 'wp-pagenavi',
			'file'        => 'wp-pagenavi/wp-pagenavi.php',
			'required'    => false,
			'min_version' => '2.94',
		),
		array(
			'name'        => 'Yoast SEO',
			'slug'        => 'wordpress-seo',
			'file'        => 'wordpress-seo/wp-seo.php',
			'required'    => false,
			'min_version' => '22.0',
		),
	);
}

/**
 * Trova il file principale di un plugin installato a partire dallo slug.
 *
 * @param string $slug          Slug della cartella del plugin su WordPress.org.
 * @param string $fallback_file Percorso relativo atteso di default.
 * @param array  $all_plugins   Elenco restituito da get_plugins().
 * @return string|null
 */
function prodotti_find_installed_plugin_file( string $slug, string $fallback_file, array $all_plugins ): ?string {
	if ( isset( $all_plugins[ $fallback_file ] ) ) {
		return $fallback_file;
	}

	foreach ( array_keys( $all_plugins ) as $plugin_file ) {
		if ( str_starts_with( (string) $plugin_file, $slug . '/' ) ) {
			return (string) $plugin_file;
		}
	}

	return null;
}

/**
 * Gestisce la chiusura persistente dell'avviso per i plugin consigliati.
 *
 * @return void
 */
function prodotti_dismiss_dependencies_notice(): void {
	if ( ! isset( $_GET['prodotti-dismiss-deps'] ) ) {
		return;
	}

	check_admin_referer( 'prodotti_dismiss_deps' );

	if ( current_user_can( 'install_plugins' ) || current_user_can( 'activate_plugins' ) ) {
		update_user_meta( get_current_user_id(), 'prodotti_dismissed_deps_notice', 1 );
	}

	wp_safe_redirect( remove_query_arg( array( 'prodotti-dismiss-deps', '_wpnonce' ) ) );
	exit;
}
add_action( 'admin_init', 'prodotti_dismiss_dependencies_notice' );

/**
 * Carica Thickbox nelle schermate admin quando serve mostrare i dettagli del plugin.
 *
 * @return void
 */
function prodotti_enqueue_plugin_info_thickbox(): void {
	if ( current_user_can( 'install_plugins' ) ) {
		add_thickbox();
	}
}
add_action( 'admin_enqueue_scripts', 'prodotti_enqueue_plugin_info_thickbox' );

/**
 * Mostra un avviso standard WordPress per plugin richiesti o consigliati mancanti/inattivi.
 *
 * Nota: in WordPress 6.5+ e 7.x il plugin richiesto "meta-box" è dichiarato anche
 * tramite header nativo "Requires Plugins: meta-box".
 *
 * @return void
 */
function prodotti_admin_dependencies_notice(): void {
	if ( ! current_user_can( 'install_plugins' ) && ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen instanceof WP_Screen && 'update' === $screen->id ) {
		return;
	}

	if ( ! function_exists( 'get_plugins' ) || ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$installed_plugins = get_plugins();
	$missing_required  = array();
	$missing_optional  = array();
	$outdated_plugins  = array();

	foreach ( prodotti_get_plugin_dependencies() as $dep ) {
		$installed_file = prodotti_find_installed_plugin_file( $dep['slug'], $dep['file'], $installed_plugins );

		if ( null === $installed_file ) {
			$action_html = '';
			if ( current_user_can( 'install_plugins' ) ) {
				$install_url = wp_nonce_url(
					self_admin_url( 'update.php?action=install-plugin&plugin=' . rawurlencode( $dep['slug'] ) ),
					'install-plugin_' . $dep['slug']
				);
				$info_url    = self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . rawurlencode( $dep['slug'] ) . '&TB_iframe=true&width=640&height=500' );
				$action_html = sprintf(
					'<a href="%1$s" class="thickbox open-plugin-details-modal">%2$s</a> (<a href="%3$s">%4$s</a>)',
					esc_url( $info_url ),
					esc_html( $dep['name'] ),
					esc_url( $install_url ),
					esc_html__( 'Installa', 'prodotti' )
				);
			} else {
				$action_html = esc_html( $dep['name'] );
			}

			if ( $dep['required'] ) {
				$missing_required[] = $action_html;
			} else {
				$missing_optional[] = $action_html;
			}
			continue;
		}

		if ( ! is_plugin_active( $installed_file ) ) {
			$action_html = '';
			if ( current_user_can( 'activate_plugins' ) ) {
				$activate_url = wp_nonce_url(
					self_admin_url( 'plugins.php?action=activate&plugin=' . rawurlencode( $installed_file ) ),
					'activate-plugin_' . $installed_file
				);
				$action_html  = sprintf(
					'%1$s (<a href="%2$s">%3$s</a>)',
					esc_html( $dep['name'] ),
					esc_url( $activate_url ),
					esc_html__( 'Attiva', 'prodotti' )
				);
			} else {
				$action_html = esc_html( $dep['name'] );
			}

			if ( $dep['required'] ) {
				$missing_required[] = $action_html;
			} else {
				$missing_optional[] = $action_html;
			}
			continue;
		}

		$current_version = isset( $installed_plugins[ $installed_file ]['Version'] )
			? (string) $installed_plugins[ $installed_file ]['Version']
			: '0.0.0';

		if ( version_compare( $current_version, $dep['min_version'], '<' ) ) {
			$outdated_plugins[] = sprintf(
				'%1$s (%2$s)',
				esc_html( $dep['name'] ),
				esc_html( $dep['min_version'] . '+' )
			);
		}
	}

	if ( ! empty( $missing_required ) ) {
		$message = sprintf(
			/* translators: %s: elenco plugin richiesti */
			esc_html__( 'Il plugin Prodotti richiede il seguente plugin attivo per gestire gallerie, allegati PDF e campi personalizzati: %s.', 'prodotti' ),
			implode( ', ', $missing_required )
		);

		if ( function_exists( 'wp_admin_notice' ) ) {
			wp_admin_notice(
				$message,
				array(
					'type'        => 'error',
					'dismissible' => false,
				)
			);
		} else {
			echo '<div class="notice notice-error"><p>' . wp_kses_post( $message ) . '</p></div>';
		}
	}

	$dismissed = (bool) get_user_meta( get_current_user_id(), 'prodotti_dismissed_deps_notice', true );
	if ( ! $dismissed && ( ! empty( $missing_optional ) || ! empty( $outdated_plugins ) ) ) {
		$parts = array();

		if ( ! empty( $missing_optional ) ) {
			$parts[] = sprintf(
				/* translators: %s: elenco plugin consigliati */
				esc_html__( 'Plugin consigliati per Prodotti: %s.', 'prodotti' ),
				implode( ', ', $missing_optional )
			);
		}

		if ( ! empty( $outdated_plugins ) ) {
			$parts[] = sprintf(
				/* translators: %s: elenco plugin da aggiornare */
				esc_html__( 'Aggiorna alla versione minima consigliata: %s.', 'prodotti' ),
				implode( ', ', $outdated_plugins )
			);
		}

		$dismiss_url = wp_nonce_url(
			add_query_arg( 'prodotti-dismiss-deps', '1' ),
			'prodotti_dismiss_deps'
		);

		$parts[] = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( $dismiss_url ),
			esc_html__( 'Ignora avviso', 'prodotti' )
		);

		$optional_message = implode( ' ', $parts );

		if ( function_exists( 'wp_admin_notice' ) ) {
			wp_admin_notice(
				$optional_message,
				array(
					'type'        => 'info',
					'dismissible' => false,
				)
			);
		} else {
			echo '<div class="notice notice-info"><p>' . wp_kses_post( $optional_message ) . '</p></div>';
		}
	}
}
add_action( 'admin_notices', 'prodotti_admin_dependencies_notice' );
