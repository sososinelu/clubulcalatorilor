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
      $slogan = ($node->field_site_slogan ? $node->field_site_slogan->value : '');
      $intro_text = ($node->field_intro_text ? $node->field_intro_text->value : '');

      $block = Block::load('clubulcalatorilor_theme_mailchimpsubscriptionformclubulcalatorilor');
      $mailchimp_block = \Drupal::entityTypeManager()->getViewBuilder('block')->view($block);

      return array(
        '#theme' => 'header_sign_up_template',
        '#vars' => array(
          'slogan' => $slogan,
          'intro_text' => $intro_text,
          'mailchimp_block' => $mailchimp_block,
        ),
        '#cache' => array('max-age' => 0),
      );
    }

    return array();
  }
}
