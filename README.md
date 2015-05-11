# PayPal Component

## Installation

The best way to install PayPal Component is using  [Composer](http://getcomposer.org/):

```sh
$ composer require seberm/paypal-component
```

##Usage

Register extension in your `config.neon` file:

```yml
paypal:
	api:
		username: 'seberm_1332081338_biz_api1.gmail.com'
		password: '1332081363'
		signature: 'AWiH1IO0zFZrEQbbn0JwDZHbWukIAebmYjpOylRCqBGGgztea2bku.N4'
	sandbox: true # default is false

extensions:
	paypal: Seberm\DI\PayPalExtension
```

Then in your presenter:

```php

  /** @var IOrderFactory @inject */
  public $factory;

  protected function createComponentPaypal()
  {
    $control = $this->factory->create();

    // Will be called after user completes all steps on Paypal site.
    $control->onConfirmation[] = [$this, 'confirmed'];

    // Will be called after money transfer
    $control->onSuccessPayment[] = [$this, 'success'];

    // All prices are represents Euros
    $control->setCurrencyCode(API::CURRENCY_EURO);

    // Lets add item to cart!
    $control->addItemToCart('Refrigerator', 'Best refrigerator ever made!', 200);

    return $control;
  }

  public function confirmed(Response $response)
  {
    dump($response);

    // IMPORTANT! You have to confirm payment to process it
    $this['paypal']->confirmExpressCheckout();
  }

  public function success($responseData)
  {
    dump("Payment successfully processed! Seller has money on his Paypal account :)");
  }

```
