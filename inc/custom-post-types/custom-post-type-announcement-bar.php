<?php
/**
 * Register Announcement Bar post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */

if ( !defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}


/**
 * Register post type.
 */
function annb_custom_post_type_announcement_bar() {
  
  $labels = array(
    'name'               => _x( 'Announcement Bars', 'post type general name', 'annb' ),
    'singular_name'      => _x( 'Announcement Bar', 'post type singular name', 'annb' ),
    'menu_name'          => _x( 'Announcements', 'admin menu', 'annb' ),
    'name_admin_bar'     => _x( 'Announcement Bar', 'add new on admin bar', 'annb' ),
    'add_new'            => _x( 'Add New', 'Announcement Bar', 'annb' ),
    'add_new_item'       => __( 'Add New Announcement Bar', 'annb' ),
    'new_item'           => __( 'New Announcement Bar', 'annb' ),
    'edit_item'          => __( 'Edit Announcement Bar', 'annb' ),
    'view_item'          => __( 'View Announcement Bar', 'annb' ),
    'all_items'          => __( 'Announcement Bars', 'annb' ),
    'search_items'       => __( 'Search Announcement Bars', 'annb' ),
    'parent_item_colon'  => __( 'Parent Announcement Bars:', 'annb' ),
    'not_found'          => __( 'No Announcement Bars found.', 'annb' ),
    'not_found_in_trash' => __( 'No Announcement Bars found in Trash.', 'annb' )
  );

  $args = array(
    'labels'             => $labels,
    'public'             => false,
    'publicly_queryable' => false,
    'show_ui'            => true,
    'menu_icon'          => 'dashicons-megaphone',
    'show_in_menu'       => true,
    'show_in_admin_bar'  => false,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'announcement' ),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => 80, // Below WooCommerce
    'supports'           => array( 'title' )
  );

  // Register post type
  register_post_type( 'announcement_bar', $args );

}
add_action( 'init', 'annb_custom_post_type_announcement_bar' );



/**
 * Register metaboxes.
 */
function annb_announcement_bar_metaboxes() {
  // ANNOUNCEMENTS
  add_meta_box(
    'annb_announcement_bar_announcements',
    __( 'Announcements', 'annb' ),
    'annb_announcement_bar_announcements_metabox_render',
    'announcement_bar',
    'normal',
    'high'
  );

  // OPTIONS
  add_meta_box(
    'annb_announcement_bar_options',
    __( 'Options', 'annb' ),
    'annb_announcement_bar_options_metabox_render',
    'announcement_bar',
    'side',
    'low'
  );
}
add_action( 'add_meta_boxes_announcement_bar', 'annb_announcement_bar_metaboxes' );



/**
 * Render ANNOUNCEMENTS metabox.
 */
function annb_announcement_bar_announcements_metabox_render() {
  global $post;
  $post_meta = ANNB_AnnouncementBar_Plugin()->get_post_meta( $post->ID, 'announcement_bar' );

  // Get announcements
  $announcements_array = $post_meta['announcement-content'];
  if ( !isset( $announcements_array ) 
        || !is_array( $announcements_array )
        || count( $announcements_array ) <= 0 ) {
    $announcements_array = array( '' );
  }

  // create nonce
  wp_nonce_field( basename( __FILE__ ), 'annb_meta_nonce' );
  ?>
    <p class="description"><?php _e( 'Add as many announcements as you wish. It supports basic HTML tags such as <code>strong</code>, <code>em</code> and <code>a</code>.', 'annb' ); ?></p>
    
    <?php foreach ( $announcements_array as $announcement ) : ?>
    <div class="announcement-content">
      <input type="text" name="post_meta[announcement-content][]" class="widefat" value="<?php echo esc_attr( $announcement ); ?>">
      <button type="button" class="button-remove button button-small"><span class="dashicons dashicons-minus"></span></button>
      <button type="button" class="button-add button button-small"><span class="dashicons dashicons-plus"></span></button>
    </div>
    <?php endforeach; ?>
  <?php
}



/**
 * Render OPTIONS metabox.
 */
function annb_announcement_bar_options_metabox_render() {
  global $post;
  $post_meta = ANNB_AnnouncementBar_Plugin()->get_post_meta( $post->ID, 'announcement_bar' );
  $display_on = isset( $post_meta['display-on'] ) && $post_meta['display-on'] != '' ? $post_meta['display-on'] : 'all';
  $position = isset( $post_meta['position'] ) && $post_meta['position'] != '' ? $post_meta['position'] : 'top';
  $velocity = isset( $post_meta['velocity'] ) && intval( $post_meta['velocity'] ) > 0 ? intval( $post_meta['velocity'] ) : 20;

  // create nonce
  wp_nonce_field( basename( __FILE__ ), 'annb_meta_nonce' );
  ?>
    <p>
      <label for="post_meta[position]"><strong><?php _e( 'Position:', 'annb' ); ?></strong></label><br>
      <select id="post_meta[position]" name="post_meta[position]" class="widefat">
        <option value="top" <?php selected( 'top', $position, true ); ?>><?php _e( 'Top', 'annb' ); ?></option>
        <option value="bottom" <?php selected( 'bottom', $position, true ); ?>><?php _e( 'Bottom', 'annb' ); ?></option>
      </select>
    </p>

    <p>
      <label for="post_meta[velocity]"><strong><?php _e( 'Velocity:', 'annb' ); ?></strong></label><br>
      <input type="number" min="1" id="post_meta[velocity]" name="post_meta[velocity]" class="widefat" value="<?php echo esc_attr( $velocity ); ?>">
      <small class="description"><?php _e( 'pixels per second', 'annb' ) ?></small>
    </p>

    <strong><?php _e( 'Display on:', 'annb' ) ?></strong><br>
    <p>
      <label title="<?php _e( 'All pages, including the homepage.', 'annb' ); ?>"><input id="post_meta[display-on][all]" type="radio" name="post_meta[display-on]" value="all" <?php checked( 'all', $display_on, true ); ?>> <?php _e( 'All pages', 'annb' ); ?></label>
    </p>
    <p>
      <label title="<?php _e( 'All other pages, except homepage.', 'annb' ); ?>"><input id="post_meta[display-on][except-homepage]" type="radio" name="post_meta[display-on]" value="except-homepage" <?php checked( 'except-homepage', $display_on, true ); ?>> <?php _e( 'Except homepage', 'annb' ); ?></label>
    </p>
    <p>
      <label title="<?php _e( 'All other pages, except homepage.', 'annb' ); ?>"><input id="post_meta[display-on][homepage]" type="radio" name="post_meta[display-on]" value="homepage" <?php checked( 'homepage', $display_on, true ); ?>> <?php _e( 'Homepage only', 'annb' ); ?></label>
    </p>
  <?php
}




/**
 * Save metaboxes content.
 */
function annb_announcement_bar_metaboxes_save( $post_id ) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  // Check the user's permissions
  if ( !current_user_can( 'edit_post', $post_id ) ) {
    return;
  }

  // Check nonce
  if ( isset( $_POST['annb_meta_nonce'] )
        && wp_verify_nonce( $_POST['annb_meta_nonce'], basename( __FILE__ ) ) ) {
    
    // Get saved meta
    $post_meta = ANNB_AnnouncementBar_Plugin()->get_post_meta( $post_id, 'announcement_bar' );

    // ANNOUNCEMENTS
    if ( isset( $_POST['post_meta']['announcement-content'] ) ) {
      // Reset announcements array
      $post_meta['announcement-content'] = array();
      
      $allowed_html = array(
        'a' => array(
          'href' => array(),
          'title' => array(),
          'target' => array(),
        ),
        'em' => array(),
        'strong' => array(),
      );

      // Get announcements values
      $announcements_array = $_POST['post_meta']['announcement-content'];
      if ( !is_array( $announcements_array ) ) {
        $announcements_array = array( $_POST['post_meta']['announcement-content'] );
      }

      // Add announcements if not empty
      foreach ( $announcements_array as $announcement_content ) {
        $announcement_content = wp_kses( $announcement_content, $allowed_html);
        if ( $announcement_content != '' ) {
          $post_meta['announcement-content'][] = $announcement_content;
        }
      }
    }

    // OPTIONS
    
    // Display-on
    if ( isset( $_POST['post_meta']['position'] ) ) {
      $allowed_position = array( 'top', 'bottom' );
      $position = sanitize_title( $_POST['post_meta']['position'] );
      if ( in_array( $position, $allowed_position ) ){
        $post_meta['position'] = $position;
      }
      else {
        $post_meta['position'] = 'top';
      }
    }

    // Display-on
    if ( isset( $_POST['post_meta']['display-on'] ) ) {
      $allowed_display_on = array( 'all', 'except-homepage', 'homepage' );
      $display_on = sanitize_title( $_POST['post_meta']['display-on'] );
      if ( in_array( $display_on, $allowed_display_on ) ){
        $post_meta['display-on'] = $display_on;
      }
      else {
        $post_meta['display-on'] = 'all';
      }
    }

    // Display-on
    if ( isset( $_POST['post_meta']['velocity'] ) ) {
      $velocity = intval( $_POST['post_meta']['velocity'] );
      $velocity = $velocity > 0 ? $velocity : 20;
      $post_meta['velocity'] = $velocity;
    }

    // Save data
    ANNB_AnnouncementBar_Plugin()->update_post_meta( $post_id, 'announcement_bar', $post_meta );
  }

}
add_action( 'save_post_announcement_bar', 'annb_announcement_bar_metaboxes_save' );


