<?php
/**
 * Block to display the email example overlay.
 *
 * @Block(
 *   id = "overlay_block",
 *   admin_label = @Translation("overlay notification")
 * )
 */

namespace Drupal\clubulcalatorilor\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;

class OverlayBlock extends BlockBase {
  public function build() {

    $overlay_title = \Drupal::state()->get('overlay_title');
    $overlay_text = \Drupal::state()->get('overlay_text');

    if ($overlay_title || $overlay_text) {

      return array(
        '#theme' => 'overlay_block_template',
        '#vars' => array(
          'overlay_title' => $overlay_title,
          'overlay_text' => $overlay_text,
        ),
        '#cache' => array(
          'max-age' => 0,
        ),
        '#attached' => array(
          'library' => array(
            'clubulcalatorilor_theme/overlay',
          ),
        ),
      );
    }

    return array();
  }
}
