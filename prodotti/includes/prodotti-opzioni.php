<?php
/**
 * Pagina delle opzioni del plugin Prodotti.
 *
 * @package Prodotti
 */

defined( 'ABSPATH' ) || exit;

/**
 * Aggiunge la voce di menu sotto Impostazioni.
 *
 * @return void
 */
function prodotti_menu(): void {
	add_options_page(
		__( 'Opzioni Prodotti', 'prodotti' ),
		__( 'Prodotti', 'prodotti' ),
		'manage_options',
		'prodotti',
		'prodotti_opzioni'
	);
}

/**
 * Registra le impostazioni del plugin con sanitizzazione PHP 8.3 / WP 7.x.
 *
 * @return void
 */
function prodotti_registraopzioni(): void {
	$url_args = array(
		'type'              => 'string',
		'sanitize_callback' => 'esc_url_raw',
		'default'           => '',
	);

	$text_args = array(
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => '',
	);

	register_setting( 'prodotti_opzioni', 'prodotti_facebook', $url_args );
	register_setting( 'prodotti_opzioni', 'prodotti_youtube', $url_args );
	register_setting( 'prodotti_opzioni', 'prodotti_twitter', $url_args );
	register_setting( 'prodotti_opzioni', 'prodotti_googleplus', $url_args );
	register_setting( 'prodotti_opzioni', 'prodotti_smtphost', $text_args );
	register_setting( 'prodotti_opzioni', 'prodotti_smtpuser', $text_args );
	register_setting( 'prodotti_opzioni', 'prodotti_smtppassword', $text_args );
}

/**
 * Renderizza la pagina delle opzioni in area amministrativa.
 *
 * @return void
 */
function prodotti_opzioni(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Non hai i permessi sufficienti per accedere a questa pagina.', 'prodotti' ) );
	}

	$facebook     = (string) get_option( 'prodotti_facebook', '' );
	$youtube      = (string) get_option( 'prodotti_youtube', '' );
	$twitter      = (string) get_option( 'prodotti_twitter', '' );
	$googleplus   = (string) get_option( 'prodotti_googleplus', '' );
	$smtphost     = (string) get_option( 'prodotti_smtphost', '' );
	$smtpuser     = (string) get_option( 'prodotti_smtpuser', '' );
	$smtppassword = (string) get_option( 'prodotti_smtppassword', '' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Opzioni plugin Prodotti', 'prodotti' ); ?></h1>
		<p><?php esc_html_e( 'Configura i collegamenti social e i parametri SMTP del catalogo prodotti.', 'prodotti' ); ?></p>

		<form method="post" action="options.php">
			<?php settings_fields( 'prodotti_opzioni' ); ?>
			<?php do_settings_sections( 'prodotti' ); ?>

			<h2 class="title"><?php esc_html_e( 'Social', 'prodotti' ); ?></h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="prodotti_facebook"><?php esc_html_e( 'Indirizzo Facebook', 'prodotti' ); ?></label></th>
						<td>
							<input name="prodotti_facebook" type="url" id="prodotti_facebook" value="<?php echo esc_attr( $facebook ); ?>" class="regular-text" placeholder="https://" />
							<p class="description"><?php esc_html_e( 'Indirizzo del profilo o della pagina Facebook.', 'prodotti' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="prodotti_youtube"><?php esc_html_e( 'Indirizzo YouTube', 'prodotti' ); ?></label></th>
						<td>
							<input name="prodotti_youtube" type="url" id="prodotti_youtube" value="<?php echo esc_attr( $youtube ); ?>" class="regular-text" placeholder="https://" />
							<p class="description"><?php esc_html_e( 'Indirizzo del canale YouTube.', 'prodotti' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="prodotti_twitter"><?php esc_html_e( 'Indirizzo X / Twitter', 'prodotti' ); ?></label></th>
						<td>
							<input name="prodotti_twitter" type="url" id="prodotti_twitter" value="<?php echo esc_attr( $twitter ); ?>" class="regular-text" placeholder="https://" />
							<p class="description"><?php esc_html_e( 'Indirizzo del profilo X / Twitter.', 'prodotti' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="prodotti_googleplus"><?php esc_html_e( 'Indirizzo profilo social aggiuntivo', 'prodotti' ); ?></label></th>
						<td>
							<input name="prodotti_googleplus" type="url" id="prodotti_googleplus" value="<?php echo esc_attr( $googleplus ); ?>" class="regular-text" placeholder="https://" />
							<p class="description"><?php esc_html_e( 'Campo legacy (ex Google+), utilizzabile per un collegamento social alternativo.', 'prodotti' ); ?></p>
						</td>
					</tr>
				</tbody>
			</table>

			<h2 class="title"><?php esc_html_e( 'Configurazioni di invio email (SMTP)', 'prodotti' ); ?></h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="prodotti_smtphost"><?php esc_html_e( 'Host SMTP', 'prodotti' ); ?></label></th>
						<td>
							<input name="prodotti_smtphost" type="text" id="prodotti_smtphost" value="<?php echo esc_attr( $smtphost ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'Server della posta in uscita.', 'prodotti' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="prodotti_smtpuser"><?php esc_html_e( 'Utente SMTP', 'prodotti' ); ?></label></th>
						<td>
							<input name="prodotti_smtpuser" type="text" id="prodotti_smtpuser" value="<?php echo esc_attr( $smtpuser ); ?>" class="regular-text" autocomplete="username" />
							<p class="description"><?php esc_html_e( 'Nome utente per invio posta SMTP.', 'prodotti' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="prodotti_smtppassword"><?php esc_html_e( 'Password SMTP', 'prodotti' ); ?></label></th>
						<td>
							<input name="prodotti_smtppassword" type="password" id="prodotti_smtppassword" value="<?php echo esc_attr( $smtppassword ); ?>" class="regular-text" autocomplete="current-password" />
							<p class="description"><?php esc_html_e( 'Password per invio posta SMTP.', 'prodotti' ); ?></p>
						</td>
					</tr>
				</tbody>
			</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

if ( is_admin() ) {
	add_action( 'admin_menu', 'prodotti_menu' );
	add_action( 'admin_init', 'prodotti_registraopzioni' );
}
