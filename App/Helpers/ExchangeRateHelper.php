<?php

namespace App\Helpers;

/**
 * Class ExchangeRateHelper
 *
 * @package App\Helpers
 */
class ExchangeRateHelper
{
	/**
	 * Validation of the response from the request
	 *
	 * @param object $response
	 *
	 * @return bool
	 */
	public static function validateResponse(object $response) : bool
	{
		$headers = $response->getHeader('content-type');

		return (int) $response->getStatusCode() === 200 && is_array($headers) && array_key_exists(0, $headers) && is_string($headers[0]) && strpos($headers[0], 'application/json') !== false;
	}

	/**
	 * Validation of the body after the request
	 *
	 * @param array  $data
	 * @param string $currency
	 *
	 * @return bool
	 */
	public static function validateData(array $data, string $currency) : bool
	{
		return array_key_exists('base', $data) && $data['base'] === $currency && array_key_exists('rates', $data) && array_key_exists('RUB', $data['rates']);
	}
}
