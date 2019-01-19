<?php
/**
 * Provides the stripe test form block
 *
 * @Block(
 *   id = "stripe_test_form_block",
 *   admin_label = @Translation("Stripe test form block"),
 * )
 */

namespace Drupal\clubulcalatorilor_stripe\Plugin\Block;
use Drupal\Core\Block\BlockBase;

class StripeTestFormBlock extends BlockBase
{
  /**
   * @return array
   */
  public function build()
  {
    if($node = \Drupal::routeMatch()->getParameter('node')) {

      return [
        '#theme' => 'stripe_test_form',
        '#vars' => [],
        '#cache' => array('max-age' => 0),
      ];
    }

    return array();
  }
}
