<?php

namespace Drupal\clubulcalatorilor_stripe\Controller;


use Drupal\Core\Controller\ControllerBase;


class ClubulCalatorilorStripeController extends ControllerBase
{
  /**
   * ClubulCalatorilorStripeController constructor.
   */
  public function __construct()
  {

  }

  public function monthlyPlan()
  {
    // @todo get product key from settings form
    $productKey = '';
    // @todo get price from settings form
    $price = '';
    $stripeToken = \Drupal::request()->request->get('stripeToken');
    $email = \Drupal::request()->request->get('stripeEmail');

    var_dump($_POST);

    exit;

    return [];
  }

  public function trimesterPlan()
  {
    // @todo get product key from settings form
    $productKey = '';
    // @todo get price from settings form
    $price = '';
    $stripeToken = \Drupal::request()->request->get('stripeToken');
    $email = \Drupal::request()->request->get('stripeEmail');

    var_dump($_POST);

    exit;

    return [];
  }

  public function byannualPlan()
  {
    // @todo get product key from settings form
    $productKey = '';
    // @todo get price from settings form
    $price = '';
    $stripeToken = \Drupal::request()->request->get('stripeToken');
    $email = \Drupal::request()->request->get('stripeEmail');

    var_dump($_POST);

    exit;

    return [];
  }

  public function annualPlan()
  {
    // @todo get product key from settings form
    $productKey = '';
    // @todo get price from settings form
    $price = '';
    $stripeToken = \Drupal::request()->request->get('stripeToken');
    $email = \Drupal::request()->request->get('stripeEmail');

    var_dump($_POST);

    exit;

    return [];
  }

  public function processStripePayment()
  {

    var_dump($_POST);

    exit;

    return 'HELLLO';
  }
}
