<?php

namespace Drupal\clubulcalatorilor_sendgrid\Controller;


use Drupal\Core\Controller\ControllerBase;


class ClubulCalatorilorSendgridController extends ControllerBase
{
  /**
   * ClubulCalatorilorSendgridController constructor.
   */
  public function __construct()
  {

  }

  public function testSendgrid()
  {
    $sendgrid = new \SendGrid(\Drupal::state()->get('sendgrid_api_key') ? \Drupal::state()->get('sendgrid_api_key') : '');

    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("info@clubulcalatorilor.ro", "Clubul Călătorilor");
    $email->setSubject("Confirmă abonarea la Clubul Călătorilor!");
    $email->addTo("sorinsoso4@gmail.com", "");

    $body_data = array (
      '#theme' => 'email_confirmation_template',
      '#vars' => array(
        "unique_url" => \Drupal::request()->getSchemeAndHttpHost().'/email-confirmation?token='
      )
    );

    $body =  \Drupal::service('renderer')->render($body_data);

    $email->addContent("text/html", $body);

    try {
      $response = $sendgrid->send($email);

      print $response->statusCode() . "\n";
      print_r($response->headers());
      print $response->body() . "\n";
    } catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

    return true;
  }

  public static function sendConfirmationEmail($sendgrid, $token)
  {
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("info@clubulcalatorilor.ro", "Clubul Călătorilor");
    $email->setSubject("Confirmă abonarea la Clubul Călătorilor!");
    $email->addTo("sorinsoso4@gmail.com", "");

    $body_data = array (
      '#theme' => 'email_confirmation_template',
      '#vars' => array(
        "unique_url" => \Drupal::request()->getSchemeAndHttpHost().'/email-confirmation?token='.$token
      )
    );

    $body =  \Drupal::service('renderer')->render($body_data);

    $email->addContent("text/html", $body);

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

  public function moveUserToList($sendgrid, $user_id)
   {
    $list_id = (\Drupal::state()->get('sendgrid_cc_list_id')) ? \Drupal::state()->get('sendgrid_cc_list_id'): '';

    if($user_id && $list_id) {
      try {

        $response = $sendgrid->client->contactdb()->lists()->_($list_id)->recipients()->_($user_id)->post();
        print $response->statusCode() . "\n";
        print_r($response->headers());
        print $response->body() . "\n";
      } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
      }
    }
  }

  public function sendUserToSendgrid($sendgrid)
  {

    $new_contact = json_decode('[
      {
        "email": "example1@example.com",
        "first_name": "",
        "last_name": ""
      }
    ]');

    $list_id = json_decode('{"list_id": 4485808}');


    try {

      $response = $sendgrid->client->contactdb()->recipients()->post($new_contact);
      //print $response->statusCode() . "\n";
      //print_r($response->headers());
      print $response->body() . "\n";
    } catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
  }
}
