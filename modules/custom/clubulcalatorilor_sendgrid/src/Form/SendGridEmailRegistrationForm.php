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
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Component\Utility\Crypt;
use Drupal\clubulcalatorilor_sendgrid\Controller\ClubulCalatorilorSendgridController;
use Drupal\clubulcalatorilor_sendgrid\Entity\ClubulCalatorilorUserConfirmation;

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
      '#required' => FALSE
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
        'callback' => '::processSubmit',
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

  public function processSubmit(array $form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $response = new AjaxResponse();

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
          'Se pare că ești înregistrat deja în club! <br> Dacă nu primești emailurile cu oferte contactează-ne la <a href="mailto:info@clubulcalatorilor.ro">info@clubulcalatorilor.ro</a>'
        )
      );

      return $response;
    }

    $local_user_record = ClubulCalatorilorUserConfirmation::getUserByEmail($email);

    // Check if the user already tried to register
    if(!$local_user_record) {
      $token = Crypt::hashBase64($email);
      $details = ClubulCalatorilorUserConfirmation::create([
        'email' => $email,
        'token' => $token,
      ]);
      $details->save();
    }else{
      $token = $local_user_record->get('token')->value;
    }

    if(ClubulCalatorilorSendgridController::sendConfirmationEmail($sendgrid, $token, $email)) {
      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          'Emailul de confirmare a fost trimis. <br> Verifică căsuța ta de email și confirmă abonarea.'
        )
      );

      $response->addCommand(new InvokeCommand('.form-email', 'val', ['']));

      return $response;
    }else {
      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          'Emailul de confirmare nu a fost trimis. Te rugăm mai încearcă odată.'
        )
      );

      return $response;
    }

    return $response;

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}
