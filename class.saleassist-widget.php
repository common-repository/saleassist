<?php
/**
 * @package Saleassist
 */
class Saleassist_Widget extends WP_Widget {

	function __construct() {
		load_plugin_textdomain( 'saleassist' );
		
		parent::__construct(
			'saleassist_widget',
			__( 'Saleassist Widget' , 'saleassist'),
			array( 'description' => __( 'Intigrate SaleAssist Live Chat enviornment in iFrame.' , 'saleassist') )
		);

		if ( is_active_widget( false, false, $this->id_base ) ) {
			//add_action( 'wp_head', array( $this, 'css' ) );
		}
	}


	function form( $instance ) {
		if ( $instance && isset( $instance['title'] ) ) {
			$title = $instance['title'];
		}
		else {
			$title = __( 'Live Chat iFrame' , 'saleassist' );
		}
		?>
		<p>
			<label for="<?php esc_attr_e($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:' , 'saleassist'); ?></label>
			<input class="widefat" id="<?php esc_attr_e($this->get_field_id( 'title' )); ?>" name="<?php esc_attr_e($this->get_field_name( 'title' )); ?>" type="text" value="<?php esc_attr_e( $title ); ?>" />
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	function widget( $args, $instance ) {
		$widget_key   	= Saleassist::get_widget_key();

		if ( ! isset( $instance['title'] ) ) {
			$instance['title'] = __( 'Live Chat' , 'saleassist' );
		}

		esc_html_e( $args['before_widget'] );
		if ( ! empty( $instance['title'] ) ) {
			esc_html_e( $args['before_title'] );
			esc_html_e( $instance['title'] );
			esc_html_e( $args['after_title'] );
		}
		if(!empty($widget_key)) {
			?>
			<div id="saleassistEmbed"></div>
			<script>EmbeddableWidget.mount({source_key: "<?php esc_attr_e($widget_key); ?>", parentElementId: "saleassistEmbed", form_factor: "embed"});</script>
			<?php
		} else {
			esc_html_e("<span>Widget Not Activated.</span>");
		}

		esc_html_e($args['after_widget']);
	}
}

function saleassist_register_widgets() {
	register_widget( 'Saleassist_Widget' );
}

//add_action( 'widgets_init', 'saleassist_register_widgets' );
