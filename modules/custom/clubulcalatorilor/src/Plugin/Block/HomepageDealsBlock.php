<?php
/**
 * Provides the homepage deals block
 *
 * @Block(
 *   id = "homepage_deals_block",
 *   admin_label = @Translation("Homepage deals block"),
 * )
 */

namespace Drupal\clubulcalatorilor\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\image\Entity\ImageStyle;

class HomepageDealsBlock extends BlockBase
{
  /**
   * @return array
   */
  public function build()
  {
    if($node = \Drupal::routeMatch()->getParameter('node')) {
      $deal_text = ($node->field_deal_text ? $node->field_deal_text->value : '');
      $deal_image = ImageStyle::load('homepage_deal')->buildUrl($node->field_deal_image->entity->getFileUri());

      return array(
        '#theme' => 'homepage_deal_block_template',
        '#vars' => array(
          'deal_text' => $deal_text,
          'deal_image' => $deal_image,
        ),
        '#cache' => array('max-age' => 0),
      );
    }

    return array();
  }
}
