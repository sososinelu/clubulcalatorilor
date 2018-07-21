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
      $signup_form = \Drupal::formBuilder()->getForm('Drupal\clubulcalatorilor_sendgrid\Form\SendGridEmailRegistrationForm');

      return array(
        '#theme' => 'header_sign_up_template',
        '#vars' => array(
          'slogan' => $slogan,
          'sign_up_text' => $sign_up_text,
          'sign_up_form' => $signup_form,
        ),
        '#cache' => array('max-age' => 0),
      );
    }

    return array();
  }
}
