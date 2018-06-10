<?php
/**
 * @file
 * Contains \Drupal\clubulcalatorilor_settings\Form\ClubulCalatorilorSettingsConfigForm.
 */

namespace Drupal\clubulcalatorilor_settings\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Clubul Calatorilor Settings Config form.
 */
class ClubulCalatorilorSettingsConfigForm extends FormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'clubulcalatorilor_settings_forms_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $form['clubulcalatorilor'] = array(
        '#type' => 'vertical_tabs',
    );

    $form['general'] = array(
      '#type' => 'details',
      '#title' => t('General'),
      '#collapsible' => TRUE,
      '#group'       => 'clubulcalatorilor'
    );

    // Info email address
    $form['general']['info_email'] = array(
      '#type' => 'textfield',
      '#title' => t('Info email address'),
      '#default_value' => (\Drupal::state()->get('info_email')) ? \Drupal::state()->get('info_email'): '',
    );

    // Facebook page
    $form['general']['facebook'] = array(
      '#type' => 'textfield',
      '#title' => t('Facebook page'),
      '#default_value' => (\Drupal::state()->get('facebook')) ? \Drupal::state()->get('facebook'): '',
    );

    // Instagram page
    $form['general']['instagram'] = array(
      '#type' => 'textfield',
      '#title' => t('Instagram page'),
      '#default_value' => (\Drupal::state()->get('instagram')) ? \Drupal::state()->get('instagram'): '',
    );

    // Site slogan
    $site_slogan_default = \Drupal::state()->get('site_slogan');
    $form['general']['site_slogan'] = array(
      '#type' => 'text_format',
      '#title' => t('Site slogan'),
      '#format' => 'basic_html',
      '#allowed_formats' => array('basic_html'),
      '#default_value' => ($site_slogan_default) ? $site_slogan_default['value'] : '',
    );

    // Sign up text
    $sign_up_text_default = \Drupal::state()->get('sign_up_text');
    $form['general']['sign_up_text'] = array(
      '#type' => 'text_format',
      '#title' => t('Sign up text'),
      '#format' => 'basic_html',
      '#allowed_formats' => array('basic_html'),
      '#default_value' => ($sign_up_text_default) ? $sign_up_text_default['value'] : '',
    );

    $form['footer'] = array(
      '#type' => 'details',
      '#title' => t('Footer'),
      '#collapsible' => TRUE,
      '#group'       => 'clubulcalatorilor'
    );

    // Footer sign up text
    $footer_sign_up_text_default = \Drupal::state()->get('footer_sign_up_text');
    $form['footer']['footer_sign_up_text'] = array(
      '#type' => 'text_format',
      '#title' => t('Footer sign up text'),
      '#format' => 'basic_html',
      '#allowed_formats' => array('basic_html'),
      '#default_value' => ($footer_sign_up_text_default) ? $footer_sign_up_text_default['value'] : '',
    );

    // Info email address
    $form['footer']['footer_contact_text'] = array(
      '#type' => 'textfield',
      '#title' => t('Footer contact text'),
      '#default_value' => (\Drupal::state()->get('footer_contact_text')) ? \Drupal::state()->get('footer_contact_text'): '',
    );

    // Submit button
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $form_values = $form_state->getValues();
    foreach ($form_values as $key => $value) {
      \Drupal::state()->set($key, $value);
      if ($key == 'hub_default_image' || $key == 'article_default_image') {
        if (isset($value[0])) {
          $file = File::load($value[0]);
          $file->setPermanent();
          $file->save();
        }
      }
    }
  }
}
?>
