<?php
/**
 * @file
 * Contains \Drupal\clubulcalatorilor_sendgrid\Form\SendGridEmailRegistrationForm.
 */

namespace Drupal\clubulcalatorilor_sendgrid\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * SendGrid email registration form.
 */
class SendGridEmailRegistrationForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sendgrid_email_registration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['details']['email'] = array(
      '#type' => 'email',
      '#title' => t('Adresa ta de email'),
      '#attributes' => array(
        'placeholder' => t('Adresa ta de email'),
      ),
      '#required' => TRUE
    );

    // Submit button
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Înscrie-te'),
    );

    \Drupal::service('page_cache_kill_switch')->trigger();

    $form['#cache'] = array(
      'max-age' => 0
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    if ($form_state->getValue('role_id') == '_none') {
      $form_state->setErrorByName('role_id', $this->t('Please select your role.'));
    }

    $email = $form_state->getValue('email');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Email is not valid. Please check your email address and try again.'));
    }

//    try {
//      // contact sendgrid and check if the user is already registered
//    } catch (InvalidPluginDefinitionException $e) {}
//
//    // if the user is alredy registered return error
//    if(!empty($not_details)) {
//      $form_state->setErrorByName('email', $this->t('You have already subscribed to email alerts with this email.
//        <br>If you would like to unsubscribe or edit your preferences, follow the link in the email digest.'));
//    }

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();

    $email = $form_values['email'];

    // contact sendgrid and add a new user

    // check if sendgrid sends the confirmation email automatically or send the confirmation email

    // Confirmation message
//    drupal_set_message(t('Thank you for subscribing to our email digest.<br>
//        Please click the link in the confirmation email to verify your email address.'));
//    $form_state->setRedirect('<front>');
  }
}
