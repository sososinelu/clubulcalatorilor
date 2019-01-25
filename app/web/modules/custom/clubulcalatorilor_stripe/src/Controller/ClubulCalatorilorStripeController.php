<?php

namespace Drupal\clubulcalatorilor_stripe\Controller;


use Drupal\Core\Controller\ControllerBase;


class ClubulCalatorilorStripeController extends ControllerBase
{
  public function monthlyPlan()
  {
    // @todo get product key from settings form
    $productKey = 'plan_EOHrFKCZKjQhGa';
    // @todo get price from settings form
    $price = 20.00;
    $stripeToken = \Drupal::request()->request->get('stripeToken');
    $email = \Drupal::request()->request->get('stripeEmail');

    $this->processStripePayment($productKey, $price, $stripeToken, $email);
  }

  public function trimesterPlan()
  {
    // @todo get product key from settings form
    $productKey = 'plan_EOHpJ0s66rXhS8';
    // @todo get price from settings form
    $price = 48.00;
    $stripeToken = \Drupal::request()->request->get('stripeToken');
    $email = \Drupal::request()->request->get('stripeEmail');

    $this->processStripePayment($productKey, $price, $stripeToken, $email);
  }

  public function byannualPlan()
  {
    // @todo get product key from settings form
    $productKey = 'plan_EOHtnYGXajHNZb';
    // @todo get price from settings form
    $price = 84.00;
    $stripeToken = \Drupal::request()->request->get('stripeToken');
    $email = \Drupal::request()->request->get('stripeEmail');

    $this->processStripePayment($productKey, $price, $stripeToken, $email);
  }

  public function annualPlan()
  {
    // @todo get product key from settings form
    $productKey = 'plan_EOHt8m1dIa7UX9';
    // @todo get price from settings form
    $price = 144.00;
    $stripeToken = \Drupal::request()->request->get('stripeToken');
    $email = \Drupal::request()->request->get('stripeEmail');

    $this->processStripePayment($productKey, $price, $stripeToken, $email);
  }

  public function processStripePayment($productKey, $price, $stripeToken, $email)
  {
    // 4242 4242 4242 4242

    // Stripe secret API key
    // @todo get secret API key from settings form
    \Stripe\Stripe::setApiKey("sk_test_pRgltPtYdkjnr3skB3NkQMxo");

    try
    {
      //var_dump($email, $stripeToken);
      //exit;

      // Check if the customer exists

      $customer = \Stripe\Customer::create([
        'email' => $email,
        'source'  => $stripeToken
      ]);

      var_dump($customer);
      exit;

      $subscription = \Stripe\Subscription::create([
        'customer' => $customer->id,
        'items' => [['plan' => $productKey]],
      ]);

      // Redirect to success page / Ajax return
      exit('Stripe success');

      // Add user to Sendgrid and send confirmation email if new
      // OR
      // move user to premium list
    }
    catch(Exception $exception)
    {
      // Redirect to fail page / Ajax return
      \Drupal::logger('clubulcalatorilor_stripe')->notice('Unable to sign up customer ' . $email . ' >>> '.$exception);
      exit('Stripe fail');
    }
  }
}
