<?php
/**
 * Provides the footer sign up block
 *
 * @Block(
 *   id = "footer_sign_up_block",
 *   admin_label = @Translation("Footer sign up block"),
 * )
 */

namespace Drupal\clubulcalatorilor\Plugin\Block;
use Drupal\block\Entity\Block;
use Drupal\Core\Block\BlockBase;

class FooterSignUpBlock extends BlockBase
{
  /**
   * @return array
   */
  public function build()
  {
    if($node = \Drupal::routeMatch()->getParameter('node')) {
      $footer_sign_up_text = (\Drupal::state()->get('footer_sign_up_text') ? \Drupal::state()->get('footer_sign_up_text')['value'] : '');
      $sign_up_text = (\Drupal::state()->get('sign_up_text') ? \Drupal::state()->get('sign_up_text')['value'] : '');
      $signup_form = \Drupal::formBuilder()->getForm('Drupal\clubulcalatorilor_sendgrid\Form\SendGridEmailRegistrationForm');

      return array(
        '#theme' => 'footer_sign_up_template',
        '#vars' => array(
          'footer_sign_up_text' => $footer_sign_up_text,
          'sign_up_form' => $signup_form,
        ),
        '#cache' => array('max-age' => 0),
      );
    }

    return array();
  }
}
