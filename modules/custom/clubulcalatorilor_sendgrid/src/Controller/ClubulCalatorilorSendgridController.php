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

  public function testSendgrid() {

    $sendgrid = new \SendGrid(\Drupal::state()->get('sendgrid_api_key') ? \Drupal::state()->get('sendgrid_api_key') : '');

    //$new_contact = new \SendGrid\Contacts\Recipient('', '', "sorinsoso4@gmail.com");

    //echo "<pre>";var_dump($new_contact);echo "</pre>";exit;

    $new_contact = json_decode('[
  {
    "email": "example@example.com", 
    "first_name": "", 
    "last_name": ""
  }
]');

    //echo "<pre>";var_dump($request_body);echo "</pre>";exit;

    try {
      $response = $sendgrid->client->contactdb()->recipients()->post($new_contact);

      //echo "<pre>";var_dump($response);echo "</pre>";exit;

      print $response->statusCode() . "\n";
      print_r($response->headers());
      print $response->body() . "\n";
    } catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

    // Send emails

//    $email = new \SendGrid\Mail\Mail();
//    $email->setFrom("info@clubulcalatorilor.ro", "Clubul Călătorilor");
//    $email->setSubject("Sending with SendGrid is Fun");
//    $email->addTo("sorinsoso4@gmail.com", "Sorin Secan");
//    $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
//    $email->addContent(
//      "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
//    );
//
//
//    //echo "<pre>";var_dump($sendgrid);echo "</pre>";exit;
//
//    try {
//      $response = $sendgrid->send($email);
//
//      //echo "<pre>";var_dump($response);echo "</pre>";exit;
//
//      print $response->statusCode() . "\n";
//      print_r($response->headers());
//      print $response->body() . "\n";
//    } catch (Exception $e) {
//      echo 'Caught exception: ',  $e->getMessage(), "\n";
//    }

    return true;
  }
}
