<?php
/**
 * Enqueue scripts and styles.
 */


/**
 * Enqueue front-end scripts and styles.
 */
function annb_enqueue_scripts_styles() {
  // Styles
  // wp_register_style( 'annb-frontend-styles', ANNB_PLUGIN_URL . 'css/frontend.min.css', array(), ANNB_PLUGIN_VERSION );

  // Scripts
  // wp_register_script( 'annb-frontend-script', ANNB_PLUGIN_URL . 'js/frontend.min.js', array(), ANNB_PLUGIN_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'annb_enqueue_scripts_styles' );



/**
 * Enqueue admin scripts and styles.
 */
function annb_admin_scripts( $hook_suffix ) {
  // REGISTER
  
  // Styles
  wp_register_style( 'annb-admin-styles',
    ANNB_PLUGIN_URL . 'css/admin.min.css', ANNB_PLUGIN_VERSION );

  // Scripts  
  wp_register_script( 'annb-admin-scripts', ANNB_PLUGIN_URL . 'js/admin-announcements.min.js', null, ANNB_PLUGIN_VERSION, true );


  // ENQUEUE
  $screen = get_current_screen();
  

  // POST TYPE: announcement_bar
  if ( in_array( $screen->post_type, array( 'announcement_bar' ) )
        && in_array( $screen->base, array( 'post' ) ) ) {
    wp_enqueue_style( 'annb-admin-styles' );
    wp_enqueue_script( 'annb-admin-scripts' );
  }

}
add_action( 'admin_enqueue_scripts', 'annb_admin_scripts' );

