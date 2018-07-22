<?php
/**
 * Provides the footer block
 *
 * @Block(
 *   id = "footer_block",
 *   admin_label = @Translation("Footer block"),
 * )
 */

namespace Drupal\clubulcalatorilor\Plugin\Block;
use Drupal\block\Entity\Block;
use Drupal\Core\Block\BlockBase;

class FooterBlock extends BlockBase
{
  /**
   * @return array
   */
  public function build()
  {
    $footer_contact_text = (\Drupal::state()->get('footer_contact_text') ? \Drupal::state()->get('footer_contact_text') : '');
    $info_email = (\Drupal::state()->get('info_email') ? \Drupal::state()->get('info_email') : '');
    $facebook = (\Drupal::state()->get('facebook') ? \Drupal::state()->get('facebook') : '');
    $instagram = (\Drupal::state()->get('instagram') ? \Drupal::state()->get('instagram') : '');

    $footer_menu = Block::load('footer');
    $footer_menu_block = \Drupal::entityTypeManager()->getViewBuilder('block')->view($footer_menu);

    return array(
      '#theme' => 'footer_block_template',
      '#vars' => array(
        'footer_contact_text' => $footer_contact_text,
        'info_email' => $info_email,
        'facebook' => $facebook,
        'instagram' => $instagram,
        'footer_menu_block' => $footer_menu_block,
      ),
      '#cache' => array('max-age' => 0),
    );
  }
}
