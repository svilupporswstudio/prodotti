<?php
/**
 * Rimozione pulita delle opzioni del plugin alla disinstallazione.
 *
 * @package Prodotti
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

$prodotti_options = array(
	'prodotti_facebook',
	'prodotti_youtube',
	'prodotti_twitter',
	'prodotti_googleplus',
	'prodotti_agoogleplus',
	'prodotti_smtphost',
	'prodotti_smtpuser',
	'prodotti_smtppassword',
);

foreach ( $prodotti_options as $prodotti_option ) {
	delete_option( $prodotti_option );
}
