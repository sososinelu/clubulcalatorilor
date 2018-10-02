<?php
/**
 * Provides the homepage about us block
 *
 * @Block(
 *   id = "homepage_about_us_block",
 *   admin_label = @Translation("Homepage about us block"),
 * )
 */

namespace Drupal\clubulcalatorilor\Plugin\Block;
use Drupal\Core\Block\BlockBase;

class HomepageAboutUsBlock extends BlockBase
{
  /**
   * @return array
   */
  public function build()
  {
    if($node = \Drupal::routeMatch()->getParameter('node')) {

      return array(
        '#theme' => 'homepage_about_us_template',
        '#vars' => array(
          'sections' => ($node->field_text_image ? $node->field_text_image : '')
        ),
        '#cache' => array('max-age' => 0),
      );
    }

    return array();
  }
}
