<?php
/*
* Output frontend announcement bar
* Template: Simple
*/

$style_attr = sprintf( 'style="background-color: %s; color: %s;"', $background_color, $text_color );
$button_style_attr = sprintf( 'style="background-color: %s; color: %s;"', $button_background_color, $button_text_color );
?>

<aside class="announcement-bar <?php echo esc_attr( $position ); ?> animated" data-velocity="<?php echo esc_attr( $velocity ); ?>" <?php echo $style_attr; ?>>
  <ul class="announcements">
    <?php foreach ( $announcements_array as $announcement ) : ?>
    <li class="announcement-item">
      <?php echo $announcement; ?>
    </li>
    <?php endforeach; ?>
  </ul>
  <button type="button" class="dismiss" <?php echo $button_style_attr; ?>><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
</aside>
