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
      $title = ($node->field_premium_title ? $node->field_premium_title->value : '');
      $text = ($node->field_premium_text ? $node->field_premium_text : '');


      return array(
        '#theme' => 'homepage_premium_template',
        '#vars' => array(
          'title' => $title,
          'text' => $text
        ),
        '#cache' => array('max-age' => 0),
      );
    }

    return array();
  }
}
