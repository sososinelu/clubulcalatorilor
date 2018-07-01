<?php
/**
 * Provides the homepage premium block
 *
 * @Block(
 *   id = "homepage_premium_block",
 *   admin_label = @Translation("Homepage premium block"),
 * )
 */

namespace Drupal\clubulcalatorilor\Plugin\Block;
use Drupal\Core\Block\BlockBase;

class HomepagePremiumBlock extends BlockBase
{
  /**
   * @return array
   */
  public function build()
  {
    if($node = \Drupal::routeMatch()->getParameter('node')) {

      return array(
        '#theme' => 'homepage_premium_template',
        '#vars' => array(
        ),
        '#cache' => array('max-age' => 0),
      );
    }

    return array();
  }
}
