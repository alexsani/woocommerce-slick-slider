<?php

/**
 * Define the carousel object
 *
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Carousel {
	
	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	
	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;
	private $attrs_array = array (
			"owlc_items" => 3, // The number of items you want to see on the screen.
			"owlc_margin" => 10, // Margin-right(px) on item.
			"owlc_loop" => 0, // Inifnity loop. Duplicate last and first items to get loop illusion.
			"owlc_center" => 0, // Center item. Works well with even an odd number of items.
			"owlc_mouseDrag" => true, // Mouse drag enabled.
			"owlc_touchDrag" => true, // Touch drag enabled.
			"owlc_pullDrag" => true, // Stage pull to edge.
			"owlc_freeDrag" => 0, // Item pull to edge.
			"owlc_stagePadding" => 0, // Padding left and right on stage (can see neighbours).
			"owlc_merge" => 0, // Merge items. Looking for data-merge='{number}' inside item..
			"owlc_mergeFit" => true, // Fit merged items if screen is smaller than items value.
			"owlc_autoWidth" => 0, // Set non grid content. Try using width style on divs.
			"owlc_startPosition" => 0, // Start position.
			"owlc_nav" => 0, // Show next/prev buttons.
			"owlc_navRewind" => true, // Go to first/last.
			"owlc_navText_prev" => "", // Prev buttons label.
			"owlc_navText_next" => "", // Next buttons label.
			"owlc_slideBy" => "1", // Navigation slide by x. 'page' string can be set to slide by page.
			"owlc_dots" => true, // Show dots navigation.
			"owlc_autoplay" => 0, // Autoplay.
			"owlc_autoplayTimeout" => 5000, // Autoplay interval timeout.
			"owlc_autoplayHoverPause" => 0, // Pause on mouse hover.
			                                // Object containing responsive options. Can be set to false to remove responsive capabilities.
			"owlc_responsive" => 0 
	);
	private $owlc_js_arg = array ();
	
	// private $owlc_URLhashListener = false; //Listen to url hash changes. data-hash on items is required.
	// private $owlc_dotsEach = false; //Show dots each x item.
	// private $owlc_dotData = false; //Used by data-dot content.
	// private $owlc_lazyLoad = false; //Lazy load images. data-src and data-src-retina for highres. Also load images into background inline style if element is not <img>
	// private $owlc_lazyContent = false; //lazyContent was introduced during beta tests but i removed it from the final release due to bad implementation. It is a nice options so i will work on it in the nearest feature.
	
	// private $owlc_smartSpeed = 250; //Speed Calculate. More info to come..
	// private $owlc_fluidSpeed; //Speed Calculate. More info to come..
	// private $owlc_autoplaySpeed = false; //Autoplay speed.
	private $owlc_navSpeed = false;
	private $owlc_dotsSpeed;
	private $owlc_dragEndSpeed;
	private $owlc_callbacks;
	private $owlc_responsiveRefreshRate;
	private $owlc_responsiveBaseElement;
	private $owlc_responsiveClass;
	private $owlc_video;
	private $owlc_videoHeight;
	private $owlc_videoWidth;
	private $owlc_animateOut;
	private $owlc_animateIn;
	private $owlc_fallbackEasing;
	private $owlc_info;
	private $owlc_nestedItemSelector;
	private $owlc_itemElement;
	private $owlc_stageElement;
	private $owlc_navContainer;
	private $owlc_dotsContainer;
	private $owlc_responsive_attr_avaible = array (
			"owlc_items",
			"owlc_loop",
			"owlc_center",
			"owlc_mouseDrag",
			"owlc_touchDrag",
			"owlc_pullDrag",
			"owlc_freeDrag",
			"owlc_margin",
			"owlc_stagePadding",
			"owlc_merge",
			"owlc_mergeFit",
			"owlc_autoWidth",
			"owlc_autoHeight",
			"owlc_nav",
			"owlc_navRewind",
			"owlc_slideBy",
			"owlc_dots",
			"owlc_dotsEach",
			"owlc_autoplay",
			"owlc_autoplayTimeout",
			"owlc_smartSpeed",
			"owlc_fluidSpeed",
			"owlc_autoplaySpeed",
			"owlc_navSpeed",
			"owlc_dotsSpeed",
			"owlc_dragEndSpeed",
			"owlc_responsiveRefreshRate",
			"owlc_animateOut",
			"owlc_animateIn",
			"owlc_fallbackEasing",
			"owlc_callbacks",
			"owlc_info" 
	);
	private $owlc_responsive_attrs = array ();
	private function __construct() {
	}
	
	// $carousel = Carousel::loadByID( $this->plugin_name, $this->version, $post->ID );
	public static function loadByID($plugin_name, $version, $post_id) {
		$instance = new self ();
		
		$instance->plugin_name = $plugin_name;
		$instance->version = $version;
		
		$carousel_fields = get_post_meta ( $post_id, "owlc_data", false )[0];

		echo sizeof($carousel_fields);
		foreach ( $carousel_fields as $attribute_name => $value ) {
			$instance->attrs_array [$attribute_name] = $value;
			//Prepare here  js carousel arg excluding default value
			if ($attribute_name != "owlc_responsive") {
				array_push ( $instance->owlc_js_arg, str_replace ( "owlc_", "", $attribute_name ) . ": " . $carousel_fields [$attribute_name] );
			} else {
				$width_array = array();
				foreach ( $carousel_fields [$attribute_name] as $width => $responsive_attr ) {
					$attr_array = array();
					foreach ( $carousel_fields [$attribute_name] [$width] as $attr => $attr_val ) {
						$attr_array[] = str_replace ("'","",str_replace ( "owlc_", "", $attr )) . " : " . $attr_val ;
					}
					$width_array[] = $width . " : {" . PHP_EOL . implode(",".PHP_EOL , $attr_array) . "}";
				}
				$responsive .= "responsive : {" . PHP_EOL . implode(",".PHP_EOL , $width_array) ."}" . PHP_EOL;
				array_push ( $instance->owlc_js_arg, $responsive );
			}
		}
		/*
		foreach ( $instance->attrs_array as $attribute_name => $value ) {
			if (array_key_exists ( $attribute_name, $carousel_fields )) {
				$instance->attrs_array [$attribute_name] = $carousel_fields [$attribute_name];
				if ($attribute_name != "owlc_responsive") {
					array_push ( $instance->owlc_js_arg, str_replace ( "owlc_", "", $attribute_name ) . ": " . $carousel_fields [$attribute_name] );
				} else {
					$responsive = "responsive : {" . PHP_EOL;
					foreach ( $carousel_fields [$attribute_name] as $width => $responsive_attr ) {
						$responsive .= $width . " : {" . PHP_EOL;
						$attr_array = array();
						foreach ( $carousel_fields [$attribute_name] [$width] as $attr => $attr_val ) {
							$attr_array[] = str_replace ("'","",str_replace ( "owlc_", "", $attr )) . " : " . $attr_val ;
						}
						$responsive .= implode(",".PHP_EOL , $attr_array);
						$responsive .= "}";
					}
					$responsive .= "}";
					array_push ( $instance->owlc_js_arg, $responsive );
				}
			}
		}
		*/
		return $instance;
	}
	public function edit_form($plugin_i18n) {
		?>
<div id="owlc-admin-tab">
	<h2 class="nav-tab-wrapper">
		<a href="#owlc-responsice-tab" class="nav-tab nav-tab-active">Responsive</a>
		<a href="#general" class="nav-tab">Tab #2</a>
		<a href="#frag2" class="nav-tab">Tab #3</a>
	</h2>
	<div id="owlc-responsice-tab">
		<table class="form-table owlc-responsice-table">
			<tbody>
				<tr>
					<td>
						<label>Options applicate to resolution upper :</label>
						<input type="text" class="small-text" id="owlc-add-breakpoint-value" value="" />
						px
						<input type="button" class="button button-small" value="Add breakpoint" id="owlc-add-breakpoint" />
					</td>
					<td></td>
				</tr>
			</tbody>
		</table>
		<div id="owlc-responsive-base" style="display: none;">
			<h3>
				Resolution >=
				<span></span>
				px
				<a href="#" class="dashicons dashicons-no owlc-responsive-delete"></a>
			</h3>
			<div>
				<input type="hidden" name="breakpoint-id[]" class="breakpoint-id" value="" />
				Add option: <select>
            <?php foreach ( $this->owlc_responsive_attr_avaible as $index => $value ) { ?>
              <option value="<?php echo $value ?>"><?php echo $this->get_label($value) ?></option>
            <?php }?>
            </select>
				<a href="#" class="owlc-responsive-add-attribute ">Add</a>
				<table class="form-table">
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<div id="owlc-responsive-accordion">
			<div id="owlc-responsive-1024" style="display: block;">
				<h3>
					<span></span>
					Resolution &gt;=
					<span>1024</span>
					px
					<a href="1024" class="dashicons dashicons-no owlc-responsive-delete"></a>
				</h3>
				<div>
					<input type="hidden" name="breakpoint-id[]" class="breakpoint-id" value="1024">
					Add option: <select>

						<option value="owlc_loop">owlc_loop_label</option>
						<option value="owlc_center">owlc_center_label</option>
						<option value="owlc_mouseDrag">owlc_mouseDrag_label</option>
						<option value="owlc_touchDrag">owlc_touchDrag_label</option>
						<option value="owlc_pullDrag">owlc_pullDrag_label</option>
						<option value="owlc_freeDrag">owlc_freeDrag_label</option>
						<option value="owlc_margin">owlc_margin_label</option>
						<option value="owlc_stagePadding">owlc_stagePadding_label</option>
						<option value="owlc_merge">owlc_merge_label</option>
						<option value="owlc_mergeFit">owlc_mergeFit_label</option>
						<option value="owlc_autoWidth">owlc_autoWidth_label</option>
						<option value="owlc_autoHeight">owlc_autoHeight</option>
						<option value="owlc_nav">owlc_nav_label</option>
						<option value="owlc_navRewind">owlc_navRewind_label</option>
						<option value="owlc_slideBy">owlc_slideBy_label</option>
						<option value="owlc_dots">owlc_dots_label</option>
						<option value="owlc_dotsEach">owlc_dotsEach</option>
						<option value="owlc_autoplay">owlc_autoplay_label</option>
						<option value="owlc_autoplayTimeout">owlc_autoplayTimeout_label</option>
						<option value="owlc_smartSpeed">owlc_smartSpeed</option>
						<option value="owlc_fluidSpeed">owlc_fluidSpeed</option>
						<option value="owlc_autoplaySpeed">owlc_autoplaySpeed</option>
						<option value="owlc_navSpeed">owlc_navSpeed</option>
						<option value="owlc_dotsSpeed">owlc_dotsSpeed</option>
						<option value="owlc_dragEndSpeed">owlc_dragEndSpeed</option>
						<option value="owlc_responsiveRefreshRate">owlc_responsiveRefreshRate</option>
						<option value="owlc_animateOut">owlc_animateOut</option>
						<option value="owlc_animateIn">owlc_animateIn</option>
						<option value="owlc_fallbackEasing">owlc_fallbackEasing</option>
						<option value="owlc_callbacks">owlc_callbacks</option>
						<option value="owlc_info">owlc_info</option>
					</select>
					<a href="1024" class="owlc-responsive-add-attribute ">Add</a>
					<table class="form-table">
						<tbody>
							<tr class="owlc_items">
								<th scope="row">
									<label for="owlc_items">Items:</label>
								</th>
								<td>
									<legend class="screen-reader-text">
										<span>Items :</span>
									</legend>
									<input type="text" class="" name="responsive[1024]['owlc_items']" value="3">
									<span class="description">The number of items you want to see on the screen.</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>

	<div class="hidden" id="general">
		<table class="form-table">
			<tbody>
				<tr class="owlc_items">
					<th scope="row">
						<label for="owlc_items"><?php _e( 'Items' ) ?>:</label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Items' ) ?> :</span>
						</legend>
						<input type="text" class="" name="owlc_items" value="<?php echo $this->attrs_array['owlc_items'] ?>" />
						<span class="description"><?php _e( 'The number of items you want to see on the screen.' ) ?></span>
					</td>
				</tr>
				<tr class="owlc_margin">
					<th scope="row">
						<label for="owlc_margin"><?php _e( 'Margin' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span>><?php _e( 'Items Margin Right' ) ?>: </span>
						</legend>
						<input type="text" class="" name="owlc_margin" value="<?php echo $this->attrs_array['owlc_margin'] ?>" />
						<span class="description"><?php _e( 'Margin-right in px on item.' ) ?></span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_loop"><?php _e( 'Loop' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Infinity Loop' ) ?>: </span>
						</legend>
						<select name="owlc_loop">
							<option value="0" <?php echo ( !$this->attrs_array['owlc_loop'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
							<option value="1" <?php echo ( $this->attrs_array['owlc_loop'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
						</select>
						<span class="description"><?php _e( 'Duplicate last and first items to get loop illusion.' )?></span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_center"><?php _e( 'Center' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Center Item' ) ?>: </span>
						</legend>
						<select name="owlc_center">
							<option value="0" <?php echo ( !$this->attrs_array['owlc_center'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
							<option value="1" <?php echo ( $this->attrs_array['owlc_center'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
						</select>
						<span class="description"><?php _e( 'Works well with even an odd number of items.' ) ?> </span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_mouseDrag"><?php _e( 'Mouse Drag' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Mouse Drag' ) ?>: </span>
						</legend>
						<select name="owlc_mouseDrag">
							<option value="0" <?php echo ( !$this->attrs_array['owlc_mouseDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
							<option value="1" <?php echo ( $this->attrs_array['owlc_mouseDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
						</select>
						<span class="description"><?php _e( 'Mouse drag enabled.' ) ?> </span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_touchDrag"><?php _e( 'Touch Drag' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Touch Drag' ) ?>: </span>
						</legend>
						<select name="owlc_touchDrag">
							<option value="0" <?php echo ( !$this->attrs_array['owlc_touchDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
							<option value="1" <?php echo ( $this->attrs_array['owlc_touchDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
						</select>
						<span class="description"><?php _e( 'Touch drag enabled.' ) ?> </span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_pullDrag"><?php _e( 'Pull Drag' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Pull Drag' ) ?>: </span>
						</legend>
						<select name="owlc_pullDrag">
							<option value="0" <?php echo ( !$this->attrs_array['owlc_pullDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
							<option value="1" <?php echo ( $this->attrs_array['owlc_pullDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
						</select>
						<span class="description"><?php _e( 'Stage pull to edge.' ) ?> </span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_freeDrag"><?php _e( 'Free Drag' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Free Drag' ) ?>: </span>
						</legend>
						<select name="owlc_freeDrag">
							<option value="0" <?php echo ( !$this->attrs_array['owlc_freeDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
							<option value="1" <?php echo ( $this->attrs_array['owlc_freeDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
						</select>
						<span class="description"><?php _e( 'Item pull to edge.' ) ?> </span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_stagePadding"><?php _e( 'Stage Padding' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Stage Padding' ) ?>: </span>
						</legend>
						<input type="text" class="" name="owlc_stagePadding" value="<?php echo $this->attrs_array['owlc_stagePadding'] ?>" />
						<span class="description"><?php _e( 'Padding left and right on stage (can see neighbours).' ) ?></span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_autoWidth"><?php _e( 'Auto Width' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Auto Width' ) ?>: </span>
						</legend>
						<select name="owlc_autoWidth">
							<option value="0" <?php echo ( !$this->attrs_array['owlc_autoWidth'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
							<option value="1" <?php echo ( $this->attrs_array['owlc_autoWidth'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
						</select>
						<span class="description"><?php _e( 'Set non grid content. Try using width style on divs.' ) ?> </span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_startPosition"><?php _e( 'Start position' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Start position' ) ?>: </span>
						</legend>
						<input type="text" class="" name="owlc_startPosition" value="<?php echo $this->attrs_array['owlc_startPosition'] ?>" />
						<span class="description"><?php _e( 'Item number to start' ) ?> </span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_nav"><?php _e( 'Navigation Buttons' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Navigation Buttons' ) ?>: </span>
						</legend>
						<select name="owlc_nav">
							<option value="0" <?php echo ( !$this->attrs_array['owlc_nav'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
							<option value="1" <?php echo ( $this->attrs_array['owlc_nav'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
						</select>
						<span class="description"><?php _e( 'Show next/prev buttons.' ) ?> </span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_navRewind"><?php _e( 'Navigation Button Loop' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Navigation Button Loop' ) ?>: </span>
						</legend>
						<select name="owlc_navRewind">
							<option value="0" <?php echo ( !$this->attrs_array['owlc_navRewind'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
							<option value="1" <?php echo ( $this->attrs_array['owlc_navRewind'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
						</select>
						<span class="description"><?php _e( 'Go to first/last.' ) ?> </span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_navText_prev"><?php _e( 'Label Previous Button' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Label Previous Button' ) ?>: </span>
						</legend>
						<input type="text" class="owlc_navText_prev" name="owlc_navText_prev" value="<?php echo $this->attrs_array['owlc_navText_prev'] ?>" />
						<span class="description"><?php _e( 'If empty, default value is &#x27;next&#x27;' ) ?> </span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_navText_next"><?php _e( 'Label Next Button' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Label Next Button' ) ?>: </span>
						</legend>
						<input type="text" class="owlc_navText_next" name="owlc_navText_next" value="<?php echo $this->attrs_array['owlc_navText_next'] ?>" />
						<span class="description"><?php _e( 'If empty, default value is &#x27;prev&#x27;' ) ?> </span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_slideBy"><?php _e( 'Slide by' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Slide by' ) ?>: </span>
						</legend>
						<input type="text" class="owlc_slideBy" name="owlc_slideBy" value="<?php echo $this->attrs_array['owlc_slideBy'] ?>" />
						<span class="description"><?php _e( 'Navigation slide by x. "Page" string can be set to slide by page.' ) ?> </span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_dots"><?php _e( 'Show dots navigation' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Show dots navigation' ) ?>: </span>
						</legend>
						<select name="owlc_dots">
							<option value="0" <?php echo ( !$this->attrs_array['owlc_dots'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
							<option value="1" <?php echo ( $this->attrs_array['owlc_dots'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_autoplay"><?php _e( 'Autoplay' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Autoplay' ) ?>: </span>
						</legend>
						<select name="owlc_autoplay">
							<option value="0" <?php echo ( !$this->attrs_array['owlc_autoplay'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
							<option value="1" <?php echo ( $this->attrs_array['owlc_autoplay'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_autoplayTimeout"><?php _e( 'Autoplay interval timeout' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Autoplay interval timeout' ) ?>: </span>
						</legend>
						<input type="text" class="owlc_autoplayTimeout" name="owlc_autoplayTimeout" value="<?php echo $this->attrs_array['owlc_autoplayTimeout'] ?>" />
						<span class="description"><?php _e( 'In milliseconds.' ) ?> </span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="owlc_autoplayHoverPause"><?php _e( 'Autoplay pause' ) ?>: </label>
					</th>
					<td>
						<legend class="screen-reader-text">
							<span><?php _e( 'Autoplay pause' ) ?>: </span>
						</legend>
						<select name="owlc_autoplayHoverPause">
							<option value="0" <?php echo ( !$this->attrs_array['owlc_autoplayHoverPause'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
							<option value="1" <?php echo ( $this->attrs_array['owlc_autoplayHoverPause'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
						</select>
						<span class="description"><?php _e( 'Pause on mouse hover.' ) ?> </span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="hidden" id="frag2">
		<p>#2 - Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
	</div>
</div>

<?php
	}
	public function save($post_id, $post) {
		$default = new Carousel ();
		$data = array ();
		foreach ( $default->attrs_array as $attribute_name => $value ) {
			if (isset ( $_POST [$attribute_name] )) {
				error_log ( sanitize_text_field ( $post [$attribute_name] ) . " == " . $default->attrs_array [$attribute_name] );
				if (sanitize_text_field ( $post [$attribute_name] ) != $default->attrs_array [$attribute_name])
					$data [$attribute_name] = sanitize_text_field ( $post [$attribute_name] );
			}
		}
		$data ['owlc_responsive'] = $post ['responsive'];
		error_log ( "-----------SAVE---------------" );
		foreach ( $data ['owlc_responsive'] as $index => $value ) {
			foreach ( $data ['owlc_responsive'] [$index] as $index1 => $value1 ) {
				error_log ( $index1 . " => " . $value1 );
			}
		}
		error_log ( "0: " . implode ( " -- ", $post ['responsive'] ) );
		
		error_log ( "------------------------------" );
		update_post_meta ( $post_id, 'owlc_data', $data );
		
		/*
		 * update_post_meta ( $post_id, 'owlc_responsive_attrs', $post ['responsive'] );
		 *
		 * $responsive_array = array ();
		 * error_log ( implode ( ",", array_filter ( $post ['breakpoint-id'] ) ) );
		 * foreach ( array_filter ( $post ['breakpoint-id'] ) as $breakpoint_id ) {
		 * // error_log ( $post['responsive'][$breakpoint_id]);
		 * error_log ( "width" . $value . ": " . implode ( ",", array_filter ( $post ['responsive'] [$breakpoint_id] ) ) );
		 * }
		 */
	}
	public function get_js_carousel_option() {
		return " items: " . $this->attrs_array ['owlc_items'] . "\n" . ",margin: " . $this->attrs_array ['owlc_margin'] . "\n" . ",loop: " . $this->attrs_array ['owlc_loop'] . "\n" . ",center: " . $this->attrs_array ['owlc_center'] . "\n" . ",mouseDrag: " . $this->attrs_array ['owlc_mouseDrag'] . "\n" . ",touchDrag: " . $this->attrs_array ['owlc_touchDrag'] . "\n" . ",pullDrag: " . $this->attrs_array ['owlc_pullDrag'] . "\n" . ",freeDrag: " . $this->attrs_array ['owlc_freeDrag'] . "\n" . ",stagePadding: " . $this->attrs_array ['owlc_stagePadding'] . "\n" . ",autoWidth: " . $this->attrs_array ['owlc_autoWidth'] . "\n" . ",startPosition: " . $this->attrs_array ['owlc_startPosition'] . "\n" . ",nav: " . $this->attrs_array ['owlc_nav'] . "\n" . ",navRewind: " . $this->attrs_array ['owlc_navRewind'] . "\n" . ",navText: " . $this->get_owlc_navText () . "\n" . ",slideBy: '" . $this->attrs_array ['owlc_slideBy'] . "'\n" . ",dots: " . $this->attrs_array ['owlc_dots'] . "\n" . ",autoplay: " . $this->attrs_array ['owlc_autoplay'] . "\n" . ",autoplayTimeout: " . $this->attrs_array ['owlc_autoplayTimeout'] . "\n" . ",autoplayHoverPause: " . $this->attrs_array ['owlc_autoplayHoverPause'] . "\n";
	}
	public function get_owlc_navText() {
		$value = array ();
		$value [0] = ($this->attrs_array ['owlc_navText_prev'] == "") ? __ ( "prev" ) : sanitize_text_field ( $this->attrs_array ['owlc_navText_prev'] );
		$value [1] = ($this->attrs_array ['owlc_navText_next'] == "") ? __ ( "next" ) : sanitize_text_field ( $this->attrs_array ['owlc_navText_next'] );
		return "['" . implode ( "','", $value ) . "']";
	}
	/**
	 */
	public function get_label($attribute) {
		switch ($attribute) {
			case "owlc_items" :
				return _x ( "owlc_items_label", $this->plugin_name );
				break;
			case "owlc_margin" :
				return _x ( "owlc_margin_label", $this->plugin_name );
				break;
			case "owlc_loop" :
				return _x ( "owlc_loop_label", $this->plugin_name );
				break;
			case "owlc_center" :
				return _x ( "owlc_center_label", $this->plugin_name );
				break;
			case "owlc_mouseDrag" :
				return _x ( "owlc_mouseDrag_label", $this->plugin_name );
				break;
			case "owlc_touchDrag" :
				return _x ( "owlc_touchDrag_label", $this->plugin_name );
				break;
			case "owlc_pullDrag" :
				return _x ( "owlc_pullDrag_label", $this->plugin_name );
				break;
			case "owlc_freeDrag" :
				return _x ( "owlc_freeDrag_label", $this->plugin_name );
				break;
			case "owlc_stagePadding" :
				return _x ( "owlc_stagePadding_label", $this->plugin_name );
				break;
			case "owlc_merge" :
				return _x ( "owlc_merge_label", $this->plugin_name );
				break;
			case "owlc_mergeFit" :
				return _x ( "owlc_mergeFit_label", $this->plugin_name );
				break;
			case "owlc_autoWidth" :
				return _x ( "owlc_autoWidth_label", $this->plugin_name );
				break;
			case "owlc_startPosition" :
				return _x ( "owlc_startPosition_label", $this->plugin_name );
				break;
			case "owlc_nav" :
				return _x ( "owlc_nav_label", $this->plugin_name );
				break;
			case "owlc_navRewind" :
				return _x ( "owlc_navRewind_label", $this->plugin_name );
				break;
			case "owlc_navText_prev" :
				return _x ( "owlc_navText_prev_label", $this->plugin_name );
				break;
			case "owlc_navText_next" :
				return _x ( "owlc_navText_next_label", $this->plugin_name );
				break;
			case "owlc_slideBy" :
				return _x ( "owlc_slideBy_label", $this->plugin_name );
				break;
			case "owlc_dots" :
				return _x ( "owlc_dots_label", $this->plugin_name );
				break;
			case "owlc_autoplay" :
				return _x ( "owlc_autoplay_label", $this->plugin_name );
				break;
			case "owlc_autoplayTimeout" :
				return _x ( "owlc_autoplayTimeout_label", $this->plugin_name );
				break;
			case "owlc_autoplayHoverPause" :
				return _x ( "owlc_autoplayHoverPause_label", $this->plugin_name );
				break;
			default :
				return $attribute;
		}
	}
	public function get_owlc_js_arg() {
		return implode ( ",", $this->owlc_js_arg );
	}
}
