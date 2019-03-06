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
      // @todo  Check if the customer exists and use existing customer id to create the payment
      // https://stackoverflow.com/questions/27588258/stripe-check-if-a-customer-exists

      $customer = \Stripe\Customer::create([
        'email' => $email,
        'source'  => $stripeToken
      ]);

      //\Drupal::logger('clubulcalatorilor_stripe')->notice($customer);

      $subscription = \Stripe\Subscription::create([
        'customer' => $customer->id,
        'items' => [['plan' => $productKey]],
      ]);



      // @todo
      // Add user to Sendgrid premium list
      // OR
      // move user from standard to premium list

      // @todo
      // create user and send password email
      // set email, price paid, product ID, product name, stripe customer id

      // @todo Redirect to success page / Ajax return
      exit('Stripe success');
    }
    catch(Exception $exception)
    {
      // @todo Redirect to fail page / Ajax return
      \Drupal::logger('clubulcalatorilor_stripe')->notice('Unable to sign up customer ' . $email . ' >>> '.$exception);
      exit('Stripe fail');
    }
  }
}
