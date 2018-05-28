<?php
/**
 * Provides the header sign up block
 *
 * @Block(
 *   id = "header_sign_up_block",
 *   admin_label = @Translation("Header sign up block"),
 * )
 */

namespace Drupal\clubulcalatorilor\Plugin\Block;
use Drupal\block\Entity\Block;
use Drupal\Core\Block\BlockBase;

class HeaderSignUpBlock extends BlockBase
{
  /**
   * @return array
   */
  public function build()
  {
    if($node = \Drupal::routeMatch()->getParameter('node')) {
      $slogan = (\Drupal::state()->get('site_slogan') ? \Drupal::state()->get('site_slogan')['value'] : '');
      $sign_up_text = (\Drupal::state()->get('sign_up_text') ? \Drupal::state()->get('sign_up_text')['value'] : '');

      $block = Block::load('clubulcalatorilor_theme_mailchimpsubscriptionformclubulcalatorilor');
      $mailchimp_block = \Drupal::entityTypeManager()->getViewBuilder('block')->view($block);

      return array(
        '#theme' => 'header_sign_up_template',
        '#vars' => array(
          'slogan' => $slogan,
          'sign_up_text' => $sign_up_text,
          'mailchimp_block' => $mailchimp_block,
        ),
        '#cache' => array('max-age' => 0),
      );
    }

    return array();
  }
}
