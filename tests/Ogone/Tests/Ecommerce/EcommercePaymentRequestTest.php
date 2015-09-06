<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\Tests\Ecommerce;

use Ogone\Tests\ShaComposer\FakeShaComposer;
use Ogone\Ecommerce\EcommercePaymentRequest;

class EcommercePaymentRequestTest extends \TestCase
{
	/** @test */
	public function IsValidWhenRequiredFieldsAreFilledIn()
	{
		$paymentRequest = $this->provideMinimalPaymentRequest();
		$paymentRequest->validate();
	}

	/** @test */
	public function IsValidWhenAllFieldsAreFilledIn()
	{
		$paymentRequest = $this->provideCompletePaymentRequest();
		$paymentRequest->validate();
	}

	/**
	 * @test
	 * @expectedException \RuntimeException
	 */
	public function IsInvalidWhenFieldsAreMissing()
	{
		$paymentRequest = new EcommercePaymentRequest(new FakeShaComposer);
		$paymentRequest->validate();
	}

	/** @test */
	public function UnimportantParamsUseMagicSetters()
	{
		$paymentRequest = new EcommercePaymentRequest(new FakeShaComposer);
		$paymentRequest->setBgcolor('FFFFFF');
		$this->assertEquals('FFFFFF', $paymentRequest->getBgcolor());
	}

	/**
	 * @test
	 * @dataProvider provideBadParameters
	 * @expectedException \InvalidArgumentException
	 */
	public function BadParametersCauseExceptions($method, $value)
	{
		$paymentRequest = new EcommercePaymentRequest(new FakeShaComposer);
		$paymentRequest->$method($value);
	}

	/**
	 * @test
	 * @expectedException \BadMethodCallException
	 */
	public function UnknownMethodFails()
	{
		$paymentRequest = new EcommercePaymentRequest(new FakeShaComposer);
		$paymentRequest->getFoobar();
	}

	public function provideBadParameters()
	{
		$longString = str_repeat('longstring', 100);
		$notAUri    = 'http://not a uri';
		$longUri    = "http://www.example.com/$longString";

		return [
			['setAccepturl', $notAUri],
			['setAmount', 10.50],
			['setAmount', -1],
			['setAmount', 1000000000000000],
			['setBrand', 'Oxfam'],
			['setCancelurl', $notAUri],
			['setCancelurl', $longUri],
			['setCurrency', 'Belgische Frank'],
			//array('setCustomername', ''),
			['setDeclineurl', $notAUri],
			['setDynamicTemplateUri', $notAUri],
			['setEmail', 'foo @ bar'],
			['setEmail', "$longString@example.com"],
			['setExceptionurl', $notAUri],
			//array('setFeedbackMessage', ''),
			//array('setFeedbackParams', ''),
			['setLanguage', 'West-Vlaams'],
			['setOgoneUri', $notAUri],
			['setOrderDescription', $longString],
			['setOrderid', "Weird çh@®a©†€rs"],
			['setOrderid', $longString],
			['setOwnerAddress', $longString],
			['setOwnercountry', 'Benidorm'],
			['setOwnerPhone', $longString],
			['setOwnerTown', $longString],
			['setOwnerZip', $longString],
			['setParamvar', $longString],
			['setPaymentMethod', 'Digital'],
			['setPspid', $longString],
			['setOperation', 'UNKNOWN_OPERATION'],
			['setOperation', EcommercePaymentRequest::OPERATION_REFUND],
		];
	}
}
