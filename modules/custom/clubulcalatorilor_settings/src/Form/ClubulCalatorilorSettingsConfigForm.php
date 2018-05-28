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

    // No reply email address
    $form['general']['no_reply_email'] = array(
      '#type' => 'textfield',
      '#title' => t('No reply email address'),
      '#default_value' => (\Drupal::state()->get('no_reply_email')) ? \Drupal::state()->get('no_reply_email'): '',
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
