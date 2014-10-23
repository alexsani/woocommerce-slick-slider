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

  private $owlc_items = 3;                    //The number of items you want to see on the screen.
  private $owlc_margin = 10;                  //Margin-right(px) on item.
  private $owlc_loop = false;                 //Inifnity loop. Duplicate last and first items to get loop illusion.
  private $owlc_center = false;               //Center item. Works well with even an odd number of items.
  private $owlc_mouseDrag = true;             //Mouse drag enabled.
  private $owlc_touchDrag = true;             //Touch drag enabled.
  private $owlc_pullDrag = true;              //Stage pull to edge.
  private $owlc_freeDrag = false;             //Item pull to edge.
  private $owlc_stagePadding = 0;             //Padding left and right on stage (can see neighbours).
  private $owlc_merge = false;                //Merge items. Looking for data-merge='{number}' inside item..
  private $owlc_mergeFit = true;              //Fit merged items if screen is smaller than items value.
  private $owlc_autoWidth = false;            //Set non grid content. Try using width style on divs.
  private $owlc_startPosition = 0;            //Start position.
  //private $owlc_URLhashListener = false;     //Listen to url hash changes. data-hash on items is required.
  private $owlc_nav = false;                  //Show next/prev buttons.
  private $owlc_navRewind = true;             //Go to first/last.
  private $owlc_navText_prev = "";            //Prev buttons label.
  private $owlc_navText_next =  "";           //Next buttons label.

  private $owlc_slideBy = "1";                  //Navigation slide by x. 'page' string can be set to slide by page.
  private $owlc_dots = true;                  //Show dots navigation.
  //private $owlc_dotsEach = false;           //Show dots each x item.
  //private $owlc_dotData = false;            //Used by data-dot content.
  //private $owlc_lazyLoad = false;           //Lazy load images. data-src and data-src-retina for highres. Also load images into background inline style if element is not <img>
  //private $owlc_lazyContent = false;        //lazyContent was introduced during beta tests but i removed it from the final release due to bad implementation. It is a nice options so i will work on it in the nearest feature.
  private $owlc_autoplay  = false;            //Autoplay.
  private $owlc_autoplayTimeout = 5000;       //Autoplay interval timeout.
  private $owlc_autoplayHoverPause = false;   //Pause on mouse hover.
  //private $owlc_smartSpeed = 250;           //Speed Calculate. More info to come..
  //private $owlc_fluidSpeed;                 //Speed Calculate. More info to come..
  //private $owlc_autoplaySpeed = false;      //Autoplay speed.
  private $owlc_navSpeed = false;
  private $owlc_dotsSpeed;
  private $owlc_dragEndSpeed;
  private $owlc_callbacks;
  private $owlc_responsive;                   //Object containing responsive options. Can be set to false to remove responsive capabilities.
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

  private $owlc_responsive_attr = array( "items", "loop", "center", "mouseDrag", "touchDrag", "pullDrag", "freeDrag", "margin", "stagePadding", "merge", "mergeFit", "autoWidth", "autoHeight", "nav", "navRewind", "slideBy", "dots", "dotsEach", "autoplay", "autoplayTimeout", "smartSpeed", "fluidSpeed", "autoplaySpeed", "navSpeed", "dotsSpeed", "dragEndSpeed", "responsiveRefreshRate", "animateOut", "animateIn", "fallbackEasing", "callbacks", "info", "and all events" );

  public function __construct( $post_id ) {
    $carousel_fields = get_post_custom( $post_id );
    foreach ( $this as $attribute_name => $value ) {
      if ( array_key_exists( $attribute_name, $carousel_fields ) ) {
        switch ( gettype( $this->$attribute_name ) ) {
        case "boolean":
          $this->$attribute_name = strtoupper( $carousel_fields[$attribute_name][0] );
          break;
        case "integer":
          if ( is_numeric( $this->$attribute_name ) ) {
            $this->$attribute_name = intval( $carousel_fields[$attribute_name][0] );
          }
          break;
        default:
          $this->$attribute_name = sanitize_text_field ( $carousel_fields[$attribute_name][0] );
        }
      }
    }
  }

  public function edit_form( $plugin_i18n ) {
?>
   <div id="owlc-admin-tab">
<h2 class="nav-tab-wrapper">
  <a href="#owlc-responsice-tab" class="nav-tab nav-tab-active">Responsive</a>
  <a href="#frag1" class="nav-tab">Tab #2</a>
  <a href="#frag2" class="nav-tab">Tab #3</a>
</h2>
  <div id="owlc-responsice-tab">
    <table class="form-table owlc-responsice-table">
      <tbody>
        <tr>
          <td>
            <label>Options applicate to resolution upper :</label>
            <input type="text" class="" name="owlc-breakpoint[]" value="" /> px
            <input type="button" value="Add breakpoint" id="owlc-add-breakpoint"/>
          </td>
          <td></td>
        </tr>
        <tr>
          <td>
            <select>
            <?php foreach ( $this->owlc_responsive_attr as $index => $value ) { ?>
              <option value=""><?php echo $value ?></option>
            <?php }?>
            </select>
          </td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="hidden" id="frag1">
    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row">
            <label for="owlc_items"><?php _e( 'Items' ) ?>:</label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Items' ) ?> :</span></legend>
            <input type="text" class="" name="owlc_items" value="<?php echo $this->owlc_items ?>" />
            <span class="description"><?php _e( 'The number of items you want to see on the screen.' ) ?></span>
          </td>
        </tr>
        <tr>
         <th scope="row">
            <label for="owlc_margin"><?php _e( 'Margin' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span>><?php _e( 'Items Margin Right' ) ?>: </span></legend>
            <input type="text" class="" name="owlc_margin" value="<?php echo $this->owlc_margin ?>" />
            <span class="description"><?php _e( 'Margin-right in px on item.' ) ?></span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_loop"><?php _e( 'Loop' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Infinity Loop' ) ?>: </span></legend>
            <select name="owlc_loop">
              <option value="0" <?php echo ( !$this->owlc_loop ) ? 'selected="selected"' : ''?> ><?php _e( 'No' )?></option>
              <option value="1" <?php echo ( $this->owlc_loop ) ? 'selected="selected"' : ''?> ><?php _e( 'Yes' )?></option>
            </select>
            <span class="description"><?php _e( 'Duplicate last and first items to get loop illusion.' )?></span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_center"><?php _e( 'Center' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Center Item' ) ?>: </span></legend>
            <select name="owlc_center">
              <option value="0" <?php echo ( !$this->owlc_center ) ? 'selected="selected"' : ''?> ><?php _e( 'No' )?></option>
              <option value="1" <?php echo ( $this->owlc_center ) ? 'selected="selected"' : ''?> ><?php _e( 'Yes' )?></option>
            </select>
            <span class="description"><?php _e( 'Works well with even an odd number of items.' ) ?> </span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_mouseDrag"><?php _e( 'Mouse Drag' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Mouse Drag' ) ?>: </span></legend>
            <select name="owlc_mouseDrag">
              <option value="0" <?php echo ( !$this->owlc_mouseDrag ) ? 'selected="selected"' : ''?> ><?php _e( 'No' )?></option>
              <option value="1" <?php echo ( $this->owlc_mouseDrag ) ? 'selected="selected"' : ''?> ><?php _e( 'Yes' )?></option>
            </select>
            <span class="description"><?php _e( 'Mouse drag enabled.' ) ?> </span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_touchDrag"><?php _e( 'Touch Drag' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Touch Drag' ) ?>: </span></legend>
            <select name="owlc_touchDrag">
              <option value="0" <?php echo ( !$this->owlc_touchDrag ) ? 'selected="selected"' : ''?> ><?php _e( 'No' )?></option>
              <option value="1" <?php echo ( $this->owlc_touchDrag ) ? 'selected="selected"' : ''?> ><?php _e( 'Yes' )?></option>
            </select>
            <span class="description"><?php _e( 'Touch drag enabled.' ) ?> </span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_pullDrag"><?php _e( 'Pull Drag' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Pull Drag' ) ?>: </span></legend>
            <select name="owlc_pullDrag">
              <option value="0" <?php echo ( !$this->owlc_pullDrag ) ? 'selected="selected"' : ''?> ><?php _e( 'No' )?></option>
              <option value="1" <?php echo ( $this->owlc_pullDrag ) ? 'selected="selected"' : ''?> ><?php _e( 'Yes' )?></option>
            </select>
            <span class="description"><?php _e( 'Stage pull to edge.' ) ?> </span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_freeDrag"><?php _e( 'Free Drag' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Free Drag' ) ?>: </span></legend>
            <select name="owlc_freeDrag">
              <option value="0" <?php echo ( !$this->owlc_freeDrag ) ? 'selected="selected"' : ''?> ><?php _e( 'No' )?></option>
              <option value="1" <?php echo ( $this->owlc_freeDrag ) ? 'selected="selected"' : ''?> ><?php _e( 'Yes' )?></option>
            </select>
            <span class="description"><?php _e( 'Item pull to edge.' ) ?> </span>
          </td>
        </tr>
        <tr>
         <th scope="row">
            <label for="owlc_stagePadding"><?php _e( 'Stage Padding' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Stage Padding' ) ?>: </span></legend>
            <input type="text" class="" name="owlc_stagePadding" value="<?php echo $this->owlc_stagePadding ?>" />
            <span class="description"><?php _e( 'Padding left and right on stage (can see neighbours).' ) ?></span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_autoWidth"><?php _e( 'Auto Width' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Auto Width' ) ?>: </span></legend>
            <select name="owlc_autoWidth">
              <option value="0" <?php echo ( !$this->owlc_autoWidth ) ? 'selected="selected"' : ''?> ><?php _e( 'No' )?></option>
              <option value="1" <?php echo ( $this->owlc_autoWidth ) ? 'selected="selected"' : ''?> ><?php _e( 'Yes' )?></option>
            </select>
            <span class="description"><?php _e( 'Set non grid content. Try using width style on divs.' ) ?> </span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_startPosition"><?php _e( 'Start position' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Start position' ) ?>: </span></legend>
            <input type="text" class="" name="owlc_startPosition" value="<?php echo $this->owlc_startPosition ?>" />
            <span class="description"><?php _e( 'Item number to start' ) ?> </span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_nav"><?php _e( 'Navigation Buttons' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Navigation Buttons' ) ?>: </span></legend>
            <select name="owlc_nav">
              <option value="0" <?php echo ( !$this->owlc_nav ) ? 'selected="selected"' : ''?> ><?php _e( 'No' )?></option>
              <option value="1" <?php echo ( $this->owlc_nav ) ? 'selected="selected"' : ''?> ><?php _e( 'Yes' )?></option>
            </select>
            <span class="description"><?php _e( 'Show next/prev buttons.' ) ?> </span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_navRewind"><?php _e( 'Navigation Button Loop' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Navigation Button Loop' ) ?>: </span></legend>
            <select name="owlc_navRewind">
              <option value="0" <?php echo ( !$this->owlc_navRewind ) ? 'selected="selected"' : ''?> ><?php _e( 'No' )?></option>
              <option value="1" <?php echo ( $this->owlc_navRewind ) ? 'selected="selected"' : ''?> ><?php _e( 'Yes' )?></option>
            </select>
            <span class="description"><?php _e( 'Go to first/last.' ) ?> </span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_navText_prev"><?php _e( 'Label Previous Button' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Label Previous Button' ) ?>: </span></legend>
            <input type="text" class="owlc_navText_prev" name="owlc_navText_prev" value="<?php echo $this->owlc_navText_prev ?>" />
            <span class="description"><?php _e( 'If empty, default value is &#x27;next&#x27;' ) ?> </span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_navText_next"><?php _e( 'Label Next Button' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Label Next Button' ) ?>: </span></legend>
            <input type="text" class="owlc_navText_next" name="owlc_navText_next" value="<?php echo $this->owlc_navText_next ?>" />
            <span class="description"><?php _e( 'If empty, default value is &#x27;prev&#x27;' ) ?> </span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_slideBy"><?php _e( 'Slide by' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Slide by' ) ?>: </span></legend>
            <input type="text" class="owlc_slideBy" name="owlc_slideBy" value="<?php echo $this->owlc_slideBy ?>" />
            <span class="description"><?php _e( 'Navigation slide by x. "Page" string can be set to slide by page.' ) ?> </span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_dots"><?php _e( 'Show dots navigation' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Show dots navigation' ) ?>: </span></legend>
            <select name="owlc_dots">
              <option value="0" <?php echo ( !$this->owlc_dots ) ? 'selected="selected"' : ''?> ><?php _e( 'No' )?></option>
              <option value="1" <?php echo ( $this->owlc_dots ) ? 'selected="selected"' : ''?> ><?php _e( 'Yes' )?></option>
            </select>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_autoplay"><?php _e( 'Autoplay' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Autoplay' ) ?>: </span></legend>
            <select name="owlc_autoplay">
              <option value="0" <?php echo ( !$this->owlc_autoplay ) ? 'selected="selected"' : ''?> ><?php _e( 'No' )?></option>
              <option value="1" <?php echo ( $this->owlc_autoplay ) ? 'selected="selected"' : ''?> ><?php _e( 'Yes' )?></option>
            </select>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_autoplayTimeout"><?php _e( 'Autoplay interval timeout' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Autoplay interval timeout' ) ?>: </span></legend>
            <input type="text" class="owlc_autoplayTimeout" name="owlc_autoplayTimeout" value="<?php echo $this->owlc_autoplayTimeout ?>" />
            <span class="description"><?php _e( 'In milliseconds.' ) ?> </span>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="owlc_autoplayHoverPause"><?php _e( 'Autoplay pause' ) ?>: </label>
          </th>
          <td>
            <legend class="screen-reader-text"><span><?php _e( 'Autoplay pause' ) ?>: </span></legend>
            <select name="owlc_autoplayHoverPause">
              <option value="0" <?php echo ( !$this->owlc_autoplayHoverPause ) ? 'selected="selected"' : ''?> ><?php _e( 'No' )?></option>
              <option value="1" <?php echo ( $this->owlc_autoplayHoverPause ) ? 'selected="selected"' : ''?> ><?php _e( 'Yes' )?></option>
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

  public function save( $post_id, $post ) {
    update_post_meta( $post_id, 'owlc_items', sanitize_text_field( $post['owlc_items'] ) );
    update_post_meta( $post_id, 'owlc_margin', sanitize_text_field( $post['owlc_margin'] ) );
    update_post_meta( $post_id, 'owlc_loop', sanitize_text_field( $post['owlc_loop'] ) );
    update_post_meta( $post_id, 'owlc_center', sanitize_text_field( $post['owlc_center'] ) );
    update_post_meta( $post_id, 'owlc_mouseDrag', sanitize_text_field( $post['owlc_mouseDrag'] ) );
    update_post_meta( $post_id, 'owlc_touchDrag', sanitize_text_field( $post['owlc_touchDrag'] ) );
    update_post_meta( $post_id, 'owlc_pullDrag', sanitize_text_field( $post['owlc_pullDrag'] ) );
    update_post_meta( $post_id, 'owlc_freeDrag', sanitize_text_field( $post['owlc_freeDrag'] ) );
    update_post_meta( $post_id, 'owlc_stagePadding', sanitize_text_field( $post['owlc_stagePadding'] ) );
    update_post_meta( $post_id, 'owlc_autoWidth', sanitize_text_field( $post['owlc_autoWidth'] ) );
    update_post_meta( $post_id, 'owlc_startPosition', sanitize_text_field( $post['owlc_startPosition'] ) );
    update_post_meta( $post_id, 'owlc_nav', sanitize_text_field( $post['owlc_nav'] ) );
    update_post_meta( $post_id, 'owlc_navRewind', sanitize_text_field( $post['owlc_navRewind'] ) );
    update_post_meta( $post_id, 'owlc_navText_prev', sanitize_text_field( $post['owlc_navText_prev'] ) );
    update_post_meta( $post_id, 'owlc_navText_next', sanitize_text_field( $post['owlc_navText_next'] ) );
    update_post_meta( $post_id, 'owlc_slideBy', sanitize_text_field( $post['owlc_slideBy'] ) );
    update_post_meta( $post_id, 'owlc_dots', sanitize_text_field( $post['owlc_dots'] ) );
    update_post_meta( $post_id, 'owlc_autoplay', sanitize_text_field( $post['owlc_autoplay'] ) );
    update_post_meta( $post_id, 'owlc_autoplayTimeout', sanitize_text_field( $post['owlc_autoplayTimeout'] ) );
    update_post_meta( $post_id, 'owlc_autoplayHoverPause', sanitize_text_field( $post['owlc_autoplayHoverPause'] ) );
  }

  public function get_js_carousel_option() {
    return " items: " . $this->owlc_items . "\n" .
      ",margin: " . $this->owlc_margin . "\n" .
      ",loop: " . $this->owlc_loop. "\n" .
      ",center: " . $this->owlc_center. "\n" .
      ",mouseDrag: " . $this->owlc_mouseDrag. "\n" .
      ",touchDrag: " . $this->owlc_touchDrag. "\n" .
      ",pullDrag: " . $this->owlc_pullDrag. "\n" .
      ",freeDrag: " . $this->owlc_freeDrag. "\n" .
      ",stagePadding: " . $this->owlc_stagePadding. "\n" .
      ",autoWidth: " . $this->owlc_autoWidth. "\n" .
      ",startPosition: " . $this->owlc_startPosition. "\n" .
      ",nav: " . $this->owlc_nav. "\n" .
      ",navRewind: " . $this->owlc_navRewind. "\n" .
      ",navText: " . $this->get_owlc_navText(). "\n" .
      ",slideBy: '" . $this->owlc_slideBy. "'\n" .
      ",dots: " . $this->owlc_dots. "\n" .
      ",autoplay: " . $this->owlc_autoplay. "\n" .
      ",autoplayTimeout: " . $this->owlc_autoplayTimeout. "\n" .
      ",autoplayHoverPause: " . $this->owlc_autoplayHoverPause. "\n";
  }

  public function get_owlc_navText() {
    $value = array();
    $value[0] = ( $this->owlc_navText_prev == "" ) ? __( "prev" ) : sanitize_text_field( $this->owlc_navText_prev );
    $value[1] = ( $this->owlc_navText_next == "" ) ? __( "next" ) : sanitize_text_field( $this->owlc_navText_next );
    return "['" . implode( "','", $value ) . "']";
  }



}
