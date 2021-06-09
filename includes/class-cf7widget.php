<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Manage Widget Class
 * 
 * @package Contact Form 7 Widget
 * @since 1.0.0
 */
if( !class_exists( 'cf7widget_widget' ) ) {

	class CF7Widget_Widget extends WP_Widget {

		function __construct() {
			
			parent::__construct( 'cf7widget_widget', __( 'Contact Form 7 Widget', 'contact-form-7-widget'), array( 'description' => __( 'Add Contact form via widget.', 'contact-form-7-widget' ) ) );
		}

		/**
		 * Widget From HTML
		 *
		 * @package Contact Form 7 Widget
		 * @since 1.0.0
		 */
		public function form( $instance ) {

			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			} else {
				$title = '';
			}

			$forms_args = array( 'posts_per_page' => -1, 'post_type'=> 'wpcf7_contact_form' );
			$forms = get_posts( $forms_args );

			if ( isset( $instance[ 'form_id' ] ) ) {
				$form_id = $instance[ 'form_id' ];
			} else {
				$form_id = "-1";
			}?>
			
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'contact-form-7-widget' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'form_id' ); ?>"><?php _e( 'Contact Form:', 'contact-form-7-widget' ); ?></label>
				<select  class="widefat" id="<?php echo $this->get_field_id( 'form_id' ); ?>" name="<?php echo $this->get_field_name( 'form_id' ); ?>">
					<option value="-1"><?php echo __( 'Select Contact Form', 'contact-form-7-widget' );?></option><?php 
					
					foreach( $forms as $form ) {?>
						<option value="<?php echo $form->ID;?>" <?php if( $form_id == $form->ID ) { echo 'selected="selected"';}?>><?php echo $form->post_title;?></option><?php 
					}?>
				</select>
			</p><?php 
		}

		/**
		 * Update Widget From Data in DB
		 *
		 * @package Contact Form 7 Widget
		 * @since 1.0.0
		 */
		public function update( $new_instance, $old_instance ) {

			$instance = $old_instance;
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['form_id'] = ( ! empty( $new_instance['form_id'] ) ) ? strip_tags( $new_instance['form_id'] ) : '';

			return $instance;
		}

		/**
		 * Display Contact Form in front
		 * 
		 * @package Contact Form 7 Widget
		 * @since 1.0.0
		 */
		public function widget( $args, $instance ) {
			
			$title = apply_filters( 'widget_title', $instance['title'] );
			$form_id = $instance['form_id'];

			// before and after widget arguments are defined by themes
			echo $args['before_widget'];
			if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

			// This is where you run the code and display the output
			if( !empty( $form_id ) && $form_id != -1 ) {
				$shortcode = '[contact-form-7 id="' . $form_id . '"]';
				echo do_shortcode( $shortcode );
			}

			echo $args['after_widget'];
		}
	}
}