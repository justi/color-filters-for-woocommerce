<?php

/**
 * Product Color Filters widget.
 *
 */
class NM_Color_Filters_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress
	 */
	function __construct() {
		parent::__construct(
			'nm_color_filters', // Base ID
			__('WooCommerce Color Filters', 'elm'), // Name
			array( 'description' => __( 'WooCommerce product color filters.', 'elm' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments
	 * @param array $instance Saved values from database
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
			
		$get_terms = get_terms( 'product_color', array( 'hide_empty' => false ) );
		
		if ( $get_terms ) {
		
		$saved_colors = get_option( 'nm_taxonomy_colors' );
?>
<div class="color-filters-wrap">
<?php 

	foreach( $get_terms as $term ) { 
		$color = @$saved_colors[$term->term_id];
		
		if ( !empty( $color ) ) {
			$style = 'style="background: ' . $color . ';"';
		} else {
			$style = '';
		}
?>

		<div class="color-item">
			<div class="color-wrap">
				<div class="rcorners" <?php echo $style; ?>></div>
			</div> <span class="color-link"><a href="<?php echo esc_url( get_term_link( $term ) ); ?> "><?php echo $term->name; ?></a></span>
		</div>

<?php } ?>
		</div>
	</aside>
<?php
		}
	}

	/**
	 * Back-end widget form
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'Color Filters', 'elm' );
		}
		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved
	 * @param array $old_instance Previously saved values from database
	 *
	 * @return array Updated safe values to be saved
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		
		return $instance;
	}
}

add_action('widgets_init',
     create_function('', 'return register_widget("NM_Color_Filters_Widget");')
);