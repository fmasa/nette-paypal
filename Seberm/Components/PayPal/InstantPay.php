<?php
/**
 * FastPay = pay in one step
 *
 * @author Martin Knor
 * @author Otto Sabart <seberm[at]seberm[dot]com> (www.seberm.com)
 */

namespace Seberm\Components\PayPal;


class InstantPay extends Control
{

	public function initPayment($amount, $description = NULL)
	{
		$response = $this->api->doExpressCheckout($amount, $description, $this->currencyCode, $this->paymentType, $this->buildUrl('processPay'), $this->buildUrl('cancel'), $this->session);

		if ($response->error) {
			$this->onError($response->errors);
			return;
		}

		// We want use the useraction == commit
		$this->redirectToPaypal(true);
	}



	public function handleProcessPay()
	{
		$responseDetails = $this->getShippingDetails();

		if ($responseDetails->error) {
			$this->onError($responseDetails->errors);
			return;
		}

		$responsePay = $this->api->doPayment($this->paymentType, $this->session);
		if ($responsePay->error) {
			$this->onError($responsePay->errors);
			return;
		}

		$this->onSuccessPayment($responseDetails->responseData);
	}



	public function handleCancel()
	{
		$response = $this->getShippingDetails();

		if ($response->error) {
			$this->onError($response->errors);
			return;
		}

		$this->onCancel($response->responseData);
	}



	public function getShippingDetails()
	{
		return $this->api->getShippingDetails($this->session);
	}

}
