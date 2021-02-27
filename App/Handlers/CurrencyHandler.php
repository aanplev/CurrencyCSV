<?php

use App\Helpers\ExchangeRateHelper;
use GuzzleHttp\Exception\GuzzleException;
use Logs\LogFile;

/**
 * Class CurrencyHandler
 */
class CurrencyHandler
{
	private string $url      = 'https://api.exchangeratesapi.io/latest';
	private string $currency = '';

	private array $supportedCurrencies = [
		'usd',
	];

	/**
	 * CurrencyHandler constructor.
	 *
	 * @param string $currency
	 *
	 * @throws Exception
	 */
	public function __construct(string $currency)
	{
		$this->setCurrency($currency);

		if (!$this->currency) {
			throw new Exception('Currency is not set');
		}
	}

	/**
	 * Set the value of the currency
	 *
	 * @param string $currency
	 *
	 * @throws Exception
	 */
	public function setCurrency($currency = '')
	{
		if (in_array($currency, $this->supportedCurrencies)) {
			$this->url      .= '?base=' . strtoupper($currency);
			$this->currency = $currency;
		} else {
			throw new Exception($currency . ' currency is not supported');
		}
	}

	/**
	 * Get the currency exchange rate
	 *
	 * @return float
	 * @throws Exception
	 */
	public function getExchangeRate() : float
	{
		try {
			$response = (new GuzzleHttp\Client)->request('GET', $this->url);

			if (ExchangeRateHelper::validateResponse($response)) {
				$data = json_decode($response->getBody(), true);

				if (ExchangeRateHelper::validateData($data, $this->currency)) {
					return (float) $data['rates']['RUB'];
				} else {
					throw new Exception('Data is not valid');
				}
			} else {
				throw new Exception('Request to get the exchange rate failed');
			}
		} catch (GuzzleException $e) {
			(new LogFile)->setFilename(date('d.m.Y') . '_error_exchange_rate')
				->setMessage($e->getMessage())
				->write();
		}
	}


}
