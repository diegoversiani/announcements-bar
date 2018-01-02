<?php
/**
 * Output announcements bar html.
 */


/**
 * Output frontend announcements bar html.
 */
function annb_announcement_bars_output_html() {
  $args = array(
    'suppress_filters'  => false,
    'post_per_page'     => -1, // all
    'post_status'       => 'publish',
    'post_type'         => 'announcement_bar',
  );

  $announcements = get_posts( $args );

  if ( $announcements ) :
    wp_enqueue_script( 'annb-frontend-script' );
    wp_enqueue_style( 'annb-frontend-styles' );
    
    foreach ( $announcements as $announcement ) {
      $post_meta = ANNB_AnnouncementBar_Plugin()->get_post_meta( $announcement->ID, 'announcement_bar' );

      // Get announcements
      $announcements_array = $post_meta['announcement-content'];
      if ( !isset( $announcements_array ) 
            || !is_array( $announcements_array )
            || count( $announcements_array ) <= 0 ) {
        $announcements_array = array( '' );
      }

      // Get options
      $display_on = isset( $post_meta['display-on'] ) && $post_meta['display-on'] != '' ? $post_meta['display-on'] : 'all';
      $position = isset( $post_meta['position'] ) && $post_meta['position'] != '' ? $post_meta['position'] : 'top';
      $velocity = intval( $post_meta['velocity'] ) > 0 ? intval( $post_meta['velocity'] ) : 20;
      $background_color = sanitize_hex_color( $post_meta['background-color'] );
      $background_color = $background_color != '' ? $background_color : '#000000';
      $text_color = sanitize_hex_color( $post_meta['text-color'] );
      $text_color = $text_color != '' ? $text_color : '#ffffff';
      $button_background_color = sanitize_hex_color( $post_meta['button-background-color'] );
      $button_background_color = $button_background_color != '' ? $button_background_color : '#000000';
      $button_text_color = sanitize_hex_color( $post_meta['button-text-color'] );
      $button_text_color = $button_text_color != '' ? $button_text_color : '#ffffff';

      if ( $display_on == 'all'
            || ( $display_on == 'homepage' && is_front_page() )
            || ( $display_on == 'except-homepage' && !is_front_page() ) ) {
        // Include template
        include ( ANNB_AnnouncementBar_Plugin()->locate_template( 'announcement-bar', 'simple' ) );
      }
    }
  endif;

}
add_action( 'wp_footer', 'annb_announcement_bars_output_html' );

