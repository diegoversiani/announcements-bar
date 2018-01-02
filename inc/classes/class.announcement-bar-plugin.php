<?php
/*
* Plugin main class
*/

if ( !defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

class ANNB_AnnouncementBar_Plugin {
  
  protected static $_instance = null;

  protected $templates = array();

  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }

    return self::$_instance;
  }




  public function get_templates() {
    return $this->templates;
  }




  public function register_template( $template_args ) {
    if ( !is_array( $template_args ) ) {
      trigger_error( 'Wrong type for `$template_args`, should be array.', E_USER_NOTICE );
      exit;
    }

    if ( !array_key_exists( 'group', $template_args ) ) {
      trigger_error( 'Missing template group name.', E_USER_NOTICE );
      exit;
    }

    if ( !array_key_exists( 'name', $template_args ) ) {
      trigger_error( 'Missing template name.', E_USER_NOTICE );
      exit;
    }

    if ( !array_key_exists( 'label', $template_args ) ) {
      $template_args = $template_args['name'];
    }



    $template_id = sprintf( '%s-%s', $template_args['group'], $template_args['name'] );

    if ( array_key_exists( $template_id, $this->templates ) ) {
      trigger_error( 'Template "' . $template_id . '" already exists.', E_USER_NOTICE );
      exit;
    }

    $template_args['id'] = $template_id;
    $this->templates[ $template_id ] = $template_args;

  }




  public function locate_template( $group, $name = 'simple', $path = null ) {
    $template = '';

    // look in theme directory
    if ( empty( $template ) ) {
      $template = locate_template( sprintf( '%1$s%2$s-%3$s.php',
      ANNB_THEME_TEMPLATES_FOLDER,
      $group,
      $name ) );
    }

    // look in custom path
    if ( empty( $template ) && !empty( $path ) ) {
      $template = locate_template( sprintf( '%1$s%2$s-%3$s.php',
      $path,
      $group,
      $name ) );
    }

    // look in plugin path
    if ( empty( $template ) ) {
      $template = sprintf( '%1$s%2$s-%3$s.php',
      ANNB_TEMPLATES_FOLDER,
      $group,
      $name );
      
      if ( !file_exists( $template ) ) {
        $template = '';
      }
    }


    // fall back to plugin template directory
    if ( empty( $template ) || !file_exists( $template ) ) {
      if ( $name != 'simple' ) {
        trigger_error("ANNB_AnnouncementBar_Plugin: Template '{$name}' not found, using 'simple' instead.", E_USER_NOTICE);
        $name = 'simple';
      }

      $template = sprintf( '%1$s%2$s-%3$s.php',
      ANNB_TEMPLATES_FOLDER,
      $group,
      $name );
    }

    return $template;
  }



  /**
   * Get post meta data from custom post types registered by this plugin.
   * @param  int $post_id       Post ID.
   * @param  string $post_type  Post type.
   * @return array              Post meta data array saved for the post.
   */
  public function get_post_meta( $post_id, $post_type ) {
    $post_meta = get_post_meta( $post_id,  $post_type.'_meta', true );
    if ( !is_array( $post_meta ) ) { $post_meta = array(); }

    return $post_meta;
  }


  /**
   * Update post meta data for custom post types registered by this plugin.
   * @param  [type] $post_id   Post ID.
   * @param  [type] $post_type Post type.
   * @param  [type] $post_meta Post meta data array to be saved for the post.
   */
  public function update_post_meta( $post_id, $post_type, $post_meta ) {
    update_post_meta( $post_id, $post_type.'_meta', $post_meta );
  }

}



function ANNB_AnnouncementBar_Plugin() {
  return ANNB_AnnouncementBar_Plugin::instance();
}
