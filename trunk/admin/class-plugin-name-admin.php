<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function remove_post_type_support() {
		remove_post_type_support( 'wooslickcarousel', 'comments' );
		remove_post_type_support( 'wooslickcarousel', 'thumbnail' );
		remove_post_type_support( 'wooslickcarousel', 'excerpt' );
	}

	public function carousel_meta_box() {
		add_meta_box( 
			'wooslickcarousel_meta_box',
			__( 'Slick Carousel Settings', $this->plugin_name ),
			array( $this, 'render_carousel_meta_box_content' ),
			'wooslickcarousel',
			'advanced',
			'high'
			);
	}

	public function render_carousel_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'wooslickcarousel_inner_meta_box', 'wooslickcarousel_inner_meta_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$accessibility = (get_post_meta( $post->ID, 'accessibility', true ) == "1") ? "checked" : "";
		$autoplay = (get_post_meta( $post->ID, 'autoplay', true ) == "1") ? "checked" : "";
		$autoplaySpeed = (get_post_meta( $post->ID, 'autoplaySpeed', true ) == "")?"3000":get_post_meta( $post->ID, 'autoplaySpeed', true );
		$pause_on_hover = (get_post_meta( $post->ID, 'pause_on_hover', true ) == "1") ? "checked" : "";
		$dot = (get_post_meta( $post->ID, 'dot', true ) == "1") ? "checked" : "";
		$pause_on_dots_hover = (get_post_meta( $post->ID, 'pause_on_dots_hover', true ) == "1") ? "checked" : "";
		$draggable = (get_post_meta( $post->ID, 'draggable', true ) == "1") ? "checked" : "";
		$infinite = (get_post_meta( $post->ID, 'infinite', true ) == "1") ? "checked" : "";
		
		/*
		// Display the form, using the current value.
		echo '<ul>';

		echo '<li>';

		echo '	<legend class="screen-reader-text"><span>'._e( 'Accessibility', $this->plugin_name ).'</span> </legend>';
		echo '	<label for="accessibility">';
		echo '		<input name="accessibility" type="checkbox" id="accessibility" value="1" '.$accessibility.'/>';
		echo '	</label>';

		echo '</li>';

		echo '<li>';

		echo '	<legend class="screen-reader-text"><span>'._e( 'Autoplay', $this->plugin_name ).'</span> </legend>';
		echo '	<label for="autoplay">';
		echo '		<input name="autoplay" type="checkbox" id="autoplay" value="1" '.$autoplay.'/>';
		echo '	</label>';

		echo '</li>';

		echo '<li>';

		echo '	<legend class="screen-reader-text"><span>'._e( 'Autoplay Speed', $this->plugin_name ).'</span></legend>';
		echo '	<label for="autoplaySpeed">';
		echo '		<input name="autoplaySpeed" type="text" id="autoplaySpeed" value="'.esc_attr( $autoplaySpeed ).'" class="small-text" />';
		echo '	</label>';
		echo '	<span class="description">ms (Autoplay Speed in milliseconds)</span>';

		echo '</li>';

		echo '<li>';
		echo '<fieldset>';
		echo '	<legend class="screen-reader-text"><span>'._e( 'Pause On Hover', $this->plugin_name ).'</span> </legend>';
		echo '	<label for="pause_on_hover">';
		echo '		<input name="pause_on_hover" type="checkbox" id="pause_on_hover" value="1" '.$pause_on_hover.'/>';
		echo '	</label>';
		echo '	<span class="description">Pause Autoplay On Hover</span>';
		echo '</fieldset>';
		echo '</li>';

		echo '<li>';
		echo '<fieldset>';
		echo '	<legend class="screen-reader-text"><span>'._e( 'Dot', $this->plugin_name ).'</span> </legend>';
		echo '	<label for="dot">';
		echo '		<input name="dot" type="checkbox" id="dot" value="1" '.$dot.'/>';
		echo '	</label>';
		echo '	<span class="description">Show dot indicators</span>';
		echo '</fieldset>';
		echo '</li>';

		echo '<li>';
		echo '<fieldset>';
		echo '	<legend class="screen-reader-text"><span>'._e( 'Pause On Dots Hover', $this->plugin_name ).'</span> </legend>';
		echo '	<label for="pause_on_dots_hover">';
		echo '		<input name="pause_on_dots_hover" type="checkbox" id="pause_on_dots_hover" value="1" '.$pause_on_dots_hover.'/>';
		echo '	</label>';
		echo '	<span class="description">Pause Autoplay when a dot is hovered</span>';
		echo '</fieldset>';
		echo '</li>';

		echo '<li>';
		echo '<fieldset>';
		echo '	<legend class="screen-reader-text"><span>'._e( 'Draggable', $this->plugin_name ).'</span> </legend>';
		echo '	<label for="draggable">';
		echo '		<input name="draggable" type="checkbox" id="draggable" value="1" '.$draggable.'/>';
		echo '	</label>';
		echo '	<span class="description">Enable mouse dragging</span>';
		echo '</fieldset>';
		echo '</li>';

		echo '<li>';
		echo '<fieldset>';
		echo '	<legend class="screen-reader-text"><span>'._e( 'Infinite', $this->plugin_name ).'</span> </legend>';
		echo '	<label for="infinite">';
		echo '		<input name="infinite" type="checkbox" id="infinite" value="1" '.$infinite.'/>';
		echo '	</label>';
		echo '	<span class="description">Infinite loop sliding</span>';
		echo '</fieldset>';
		echo '</li>';

		echo '</ul>';
		*/
		$xx = new Carousel($post->ID);
		$xx->edit_form($this->plugin_name);

	}


		/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
		public function save( $post_id ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['wooslickcarousel_inner_meta_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['wooslickcarousel_inner_meta_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'wooslickcarousel_inner_meta_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */
		$carousel = new Carousel(post_id);
		$carousel->save($post_id, $_POST);

/*
		// Sanitize the user input.
		$accessibility = sanitize_text_field( $_POST['accessibility'] );
		$autoplay = sanitize_text_field( $_POST['autoplay'] );
		$autoplaySpeed = sanitize_text_field( $_POST['autoplaySpeed'] );

		// Update the meta field.
		update_post_meta( $post_id, 'accessibility', $accessibility );
		update_post_meta( $post_id, 'autoplay', $autoplay );
		update_post_meta( $post_id, 'autoplaySpeed', $autoplaySpeed );
		*/
	}

		//[woosc]
	public function shortcode( $atts ){
		$carousel_id = get_post( $atts['id'] );
		if (sizeof($carousel_id) > 0) {
				// La Query
			$query_args =  array( 'post_type' => array( 'product' ) );
			$carousel_query = new WP_Query( $query_args );

			echo '<div class="slider multiple-items">';

// Il Loop
			while ( $carousel_query->have_posts() ) :
				$carousel_query->next_post();
			echo '<div>';
			// echo '<li>' . get_the_title( $carousel_query->post->ID ) . '</li>';
			echo get_the_post_thumbnail($carousel_query->post->ID, 'thumbnail');
			echo '<span class="title">'.get_the_title($carousel_query->post->ID).'</span>';
			echo '</div>';
			endwhile;

// Ripristina Query & Post Data originali
			wp_reset_query();
			wp_reset_postdata();
echo '</div>';


			//Generate js script
		$accessibility = ($carousel_id->accessibility == "1") ? "true" : "false";
		$autoplay = ($carousel_id->autoplay == "1") ? "true" : "false";
		$autoplaySpeed = $carousel_id->autoplaySpeed;
		$infinite = ($carousel_id->infinite == "1") ? "true" : "false";

		$carousel = new Carousel( $atts['id'] );
		$slick_arg = $carousel->get_js_carousel_option();  
	

			echo '<script>';
			//$slick_arg = 'dots: true, 	infinite: '.$infinite.', slidesToShow: 3, slidesToScroll: 3, autoplay: '.$autoplay.'';
			echo 	"jQuery(document).ready(function() {jQuery('.multiple-items').owlCarousel({".$slick_arg."});});";
			echo '</script>';


			return "Trovato: " . $carousel_id->_my_meta_value_key;
		}
		

	}


		public function set_custom_edit_carousel_columns( $columns ) {
		unset( $columns['date'] );
		$columns['shortcut'] = __( 'Shortcut', $this->plugin_name );
		$columns['date'] = _x('Date', 'column name');
		return $columns;
	}

	function custom_carousel_column( $column, $post_id ) {
		switch ( $column ) {
		case 'shortcut' :
			echo "[woosc id=$post_id]";
			break;

		}
	}
}
