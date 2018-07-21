<?php
/**
 * @file
 * Contains \Drupal\clubulcalatorilor_sendgrid\Form\SendGridEmailRegistrationForm.
 */

namespace Drupal\clubulcalatorilor_sendgrid\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\clubulcalatorilor_sendgrid\Controller\ClubulCalatorilorSendgridController;

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

    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="result_message"></div>'
    ];

    $form['email'] = array(
      '#type' => 'email',
      '#title' => t('Adresa ta de email'),
      '#attributes' => array(
        'placeholder' => t('Adresa ta de email'),
      ),
      '#required' => TRUE
    );

    $form['markup'] = [
      '#type' => 'markup',
      '#markup' => '<div class="submit-wrapper">'
    ];

    // Submit button
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Înscrie-te'),
      '#ajax' => [
        'callback' => '::setMessage',
      ],
    );

    $form['markup1'] = [
      '#type' => 'markup',
      '#markup' => '</div>'
    ];

    \Drupal::service('page_cache_kill_switch')->trigger();

    $form['#cache'] = array(
      'max-age' => 0
    );

    return $form;
  }

  public function setMessage(array $form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $response = new AjaxResponse();
    //var_dump($email);exit;

    // No email address provided
    if (empty($email)) {

      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          'Adresa de email este necesară pentru a te înscrie.'
        )
      );

      return $response;
    }

    // Email address is not valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          'Adresa de email nu este validă. Verifică adresa de email și mai încearcă odată.'
        )
      );

      return $response;
    }

    // Check if the user is already subscribed
    $sendgrid = new \SendGrid(\Drupal::state()->get('sendgrid_api_key') ? \Drupal::state()->get('sendgrid_api_key') : '');
    if(ClubulCalatorilorSendgridController::checkIfUserIsSubscribed($sendgrid, $email)) {

      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          'Se pare că ești înregistrat deja în club! Dacă nu primești emailurile cu oferte contactează-ne la <a href="mailto:info@clubulcalatorilor.ro">info@clubulcalatorilor.ro</a>'
        )
      );

      return $response;
    }


    // $response = new AjaxResponse();
    // $response->addCommand(
    //   new HtmlCommand(
    //     '.result_message',
    //     '<div class="my_top_message"> The results is </div>')
    //   );

    return $response;

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
