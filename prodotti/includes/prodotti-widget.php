<?php
/**
 * Widget di ricerca prodotti per categoria.
 *
 * @package Prodotti
 */

defined( 'ABSPATH' ) || exit;

/**
 * Classe Prodotti_Widget compatibile con PHP 8.3 e WordPress 7.x.
 */
class Prodotti_Widget extends WP_Widget {

	/**
	 * Costruttore del widget.
	 */
	public function __construct() {
		parent::__construct(
			'prodotti_widget',
			__( 'Prodotti Widget', 'prodotti' ),
			array(
				'description' => __( 'Widget per il modulo di ricerca prodotti del plugin Prodotti.', 'prodotti' ),
			)
		);
	}

	/**
	 * Modulo di configurazione del widget in backend.
	 *
	 * @param array<string, mixed> $instance Impostazioni salvate.
	 * @return void
	 */
	public function form( $instance ): void {
		$title = isset( $instance['title'] ) ? (string) $instance['title'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Titolo ricerca', 'prodotti' ); ?>
			</label>
			<input
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				type="text"
				value="<?php echo esc_attr( $title ); ?>"
			/>
		</p>
		<p class="description">
			<?php esc_html_e( 'Widget per il modulo di ricerca prodotti del plugin Prodotti.', 'prodotti' ); ?>
		</p>
		<?php
	}

	/**
	 * Aggiorna e sanitizza le impostazioni del widget.
	 *
	 * @param array<string, mixed> $new_instance Nuovi valori.
	 * @param array<string, mixed> $old_instance Valori precedenti.
	 * @return array<string, string>
	 */
	public function update( $new_instance, $old_instance ): array {
		$instance          = is_array( $old_instance ) ? $old_instance : array();
		$instance['title'] = isset( $new_instance['title'] )
			? sanitize_text_field( (string) $new_instance['title'] )
			: '';

		return $instance;
	}

	/**
	 * Stampa il widget nel frontend.
	 *
	 * @param array<string, mixed> $args     Argomenti dell'area widget.
	 * @param array<string, mixed> $instance Impostazioni del widget.
	 * @return void
	 */
	public function widget( $args, $instance ): void {
		$before_widget = isset( $args['before_widget'] ) ? (string) $args['before_widget'] : '';
		$after_widget  = isset( $args['after_widget'] ) ? (string) $args['after_widget'] : '';
		$before_title  = isset( $args['before_title'] ) ? (string) $args['before_title'] : '';
		$after_title   = isset( $args['after_title'] ) ? (string) $args['after_title'] : '';

		$raw_title = isset( $instance['title'] ) ? (string) $instance['title'] : '';
		$title     = (string) apply_filters( 'widget_title', $raw_title, $instance, $this->id_base );

		$archive_url = get_post_type_archive_link( 'product' );
		if ( ! is_string( $archive_url ) || '' === $archive_url ) {
			$archive_url = home_url( '/shop/' );
		}

		$selected_cat = isset( $_GET['product_cat'] )
			? sanitize_text_field( (string) wp_unslash( $_GET['product_cat'] ) )
			: '';

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
			)
		);

		echo wp_kses_post( $before_widget );
		echo '<div class="widget-text wp_widget_plugin_box">';
		?>
		<form method="get" name="cercaprodotti" class="formcercaprodotti" action="<?php echo esc_url( $archive_url ); ?>">
			<h5>
				<?php
				$heading = '' !== $title ? $title : __( 'Ricerca Prodotti', 'prodotti' );
				echo wp_kses_post( $before_title ) . esc_html( $heading ) . wp_kses_post( $after_title );
				?>
			</h5>
			<label for="<?php echo esc_attr( $this->get_field_id( 'product_cat' ) ); ?>">
				<select name="product_cat" id="<?php echo esc_attr( $this->get_field_id( 'product_cat' ) ); ?>">
					<option value=""><?php esc_html_e( 'Scegli una categoria', 'prodotti' ); ?></option>
					<?php
					if ( is_array( $terms ) && ! empty( $terms ) ) {
						foreach ( $terms as $term ) {
							if ( ! $term instanceof WP_Term ) {
								continue;
							}
							printf(
								'<option value="%1$s"%2$s>%3$s</option>',
								esc_attr( $term->slug ),
								selected( $selected_cat, $term->slug, false ),
								esc_html( $term->name )
							);
						}
					}
					?>
				</select>
			</label>
			<div class="row">
				<div class="large-12 medium-12 small-12 columns">
					<button type="submit" class="button round ricerca expand">
						<span class="dashicons dashicons-search" aria-hidden="true"></span>
						<?php esc_html_e( 'Cerca', 'prodotti' ); ?>
					</button>
				</div>
			</div>
		</form>
		<?php
		echo '</div>';
		echo wp_kses_post( $after_widget );
	}
}

/**
 * Registra il widget senza usare la funzione rimossa create_function().
 *
 * @return void
 */
function prodotti_register_widgets(): void {
	register_widget( Prodotti_Widget::class );
}
add_action( 'widgets_init', 'prodotti_register_widgets' );
