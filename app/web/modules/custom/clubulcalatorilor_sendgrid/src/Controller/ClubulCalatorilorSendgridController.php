<?php

namespace Drupal\clubulcalatorilor_sendgrid\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\clubulcalatorilor_sendgrid\Entity\ClubulCalatorilorUserConfirmation as CCUCEntity;


class ClubulCalatorilorSendgridController extends ControllerBase
{
  public function emailConfirmationProcessing()
  {
    $token = \Drupal::request()->query->get('token');
    $sendgrid = new \SendGrid(\Drupal::state()->get('sendgrid_api_key') ? \Drupal::state()->get('sendgrid_api_key') : '');
    $markup = '<div class="email-confirmation outer-wrapper">';

    if($token) {
      if($local_user_record = CCUCEntity::getUserByToken($token)) {

        $email = $local_user_record->get('email')->value;

        if($sendgrid_id = self::sendUserToSendgrid($sendgrid, $email)) {
          if(self::moveUserToList($sendgrid, $sendgrid_id[0])) {
            try {
              $local_user_record->delete();

              $markup .= '<h4>Adresa ta de email a fost confirmată. <br> Bine ai venit în club! </h4>';
              $markup .= '<p class="back-link"><a href="/">Înapoi la pagina principală.</a></p>';
              $markup .= '</div>';

              return [
                '#type' => 'markup',
                '#markup' => $markup,
                '#cache' => ['max-age' => 0],
              ];

            }catch (Exception $exception) {
              \Drupal::logger('clubulcalatorilor_sendgrid')->notice('Delete local user error: $email >>> '.$exception);
            }
          }
        }
      }
    }

    $markup .= '<h4>Nu ți-am găsit detaliile ca să te putem înscrie! <br> Mai încearcă să te înscrii odată sau contactează-ne la <a class="info" href="mailto:info@clubulcalatorilor.ro">info@clubulcalatorilor.ro</a></h4>';
    $markup .= '<p class="back-link"><a href="/">Înapoi la pagina principală.</a></p>';
    $markup .= '</div>';

    return [
      '#type' => 'markup',
      '#markup' => $markup,
      '#cache' => ['max-age' => 0],
    ];
  }

  public function testSendgrid()
  {
    $sendgrid = new \SendGrid(\Drupal::state()->get('sendgrid_api_key') ? \Drupal::state()->get('sendgrid_api_key') : '');
    $sendgrid_id = self::sendUserToSendgrid($sendgrid, "example3@email.com");

    var_dump($sendgrid_id);exit;

    return true;
  }

  public static function sendEmail($sendgrid, $token, $email_address, $emailTemplate, $subject)
  {
    $email = new \SendGrid\Mail\Mail();

    $email->setFrom("info@clubulcalatorilor.ro", "Clubul Călătorilor");
    $email->setSubject($subject);
    $email->addTo($email_address, "");

    $body_data = [
      '#theme' => $emailTemplate
    ];

    if ($token) {
      $body_data['#vars'] = [
        "unique_url" => \Drupal::request()->getSchemeAndHttpHost().'/email-confirmation?token='.$token
      ];
    }

    $body =  \Drupal::service('renderer')->render($body_data);
    $email->addContent("text/html", $body->__toString());

    try {
      $response = $sendgrid->send($email);

      // print $response->statusCode() . "\n";
      // print_r($response->headers());
      // print $response->body() . "\n";

      if (strpos($response->statusCode(), '20') !== false) {
        return true;
      }else {
        return false;
      }

    } catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
  }

  public static function checkIfUserIsSubscribed($sendgrid, $email)
  {
    $query_params = json_decode('{"email": "'.$email.'"}');

    try {
      $response = $sendgrid->client->contactdb()->recipients()->search()->get(null, $query_params);
      // print $response->statusCode() . "\n";
      // print_r($response->headers());
      // print $response->body() . "\n";

      $recipient_count = json_decode($response->body())->{'recipient_count'};

      if($recipient_count !== 0) {
        return true;
      }
    } catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

    return false;
  }

  public static function moveUserToList($sendgrid, $user_id)
   {
    $list_id = (\Drupal::state()->get('sendgrid_cc_list_id')) ? \Drupal::state()->get('sendgrid_cc_list_id'): '';

    if($user_id && $list_id) {
      try {

        $response = $sendgrid->client->contactdb()->lists()->_($list_id)->recipients()->_($user_id)->post();
        // print $response->statusCode() . "\n";
        // print_r($response->headers());
        // print $response->body() . "\n";

        if (strpos($response->statusCode(), '20') !== false) {
          return true;
        }else {
          return false;
        }
      } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
      }
    }
  }

  public static function sendUserToSendgrid($sendgrid, $email)
  {

    $new_contact = json_decode('[
      {
        "email": "'.$email.'",
        "first_name": "",
        "last_name": ""
      }
    ]');

    try {

      $response = $sendgrid->client->contactdb()->recipients()->post($new_contact);
      //print $response->statusCode() . "\n";
      //print_r($response->headers());
      //print $response->body() . "\n";

      $response_data = json_decode($response->body());

      if($response_data->{"new_count"} == 1) {
        return $response_data->{"persisted_recipients"};
      }else {
        return false;
      }
    } catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
  }

  public static function processEmailRemainders($days)
  {
    // Get all users who don't have a reminder sent
    // and created their account 1 days ago (created + 3days < today)
    $users = CCUCEntity::getRemainderUsers($days);
    $sendgrid = new \SendGrid(\Drupal::state()->get('sendgrid_api_key') ? \Drupal::state()->get('sendgrid_api_key') : '');
    $success = 0;

    foreach ($users as $uId) {
      $user = CCUCEntity::getUserById($uId);

      if($user) {
        // Get email template
        // Send email using Sendgrid
        $email = $user->get('email')->value;
        $token = $user->get('token')->value;
        $emailTemplate = 'email_reminder_template';
        $subject = 'Confirmă abonarea la Clubul Călătorilor!';

        if (self::sendEmail($sendgrid, $token, $email, $emailTemplate, $subject)) {
          if ($days == 1) {
            $user->set('remainder', 1);
          } elseif ($days == 3) {
            $user->set('remainder', 2);
          }
          $user->save();
          $success++;
        } else {
          \Drupal::logger('clubulcalatorilor_sendgrid')->notice('Remainder email failed = '.$email);
        }
      }
    }

    \Drupal::logger('clubulcalatorilor_sendgrid')->notice('Remainder emails sent = '.$success);
    return '';
  }

  public static function processConfirmationRemoval($days)
  {
    // Delete all users that are still in the list after 15 days
    $users = CCUCEntity::getRemainderUsers($days);

    $deletedUsers = '';
    $count = 0;
    foreach ($users as $uId) {
      $user = CCUCEntity::getUserById($uId);
      if($user && $user->get('remainder')->value == 2) {
        // Delete user
        try {
          $deletedUsers .= ', '.$user->get('email')->value;
          $user->delete();
          $count++;
        } catch (EntityStorageException $e) {
          \Drupal::logger('clubulcalatorilor_sendgrid')->error($e);
        }
      }
    }
    \Drupal::logger('clubulcalatorilor_sendgrid')->notice($count.' users removed'.$deletedUsers);
    return true;
  }
}
