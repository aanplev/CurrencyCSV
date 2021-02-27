<?php

declare(strict_types = 1);

/**
 * Class CurrencyCSVDirector
 */
class CurrencyCSVDirector
{
	/**
	 * CurrencyCSVDirector constructor.
	 *
	 * strict_types = 1
	 *
	 * @param string $currency
	 *
	 * @throws Exception
	 */
	public function __construct(string $currency)
	{
		$exchangeRate = (new CurrencyHandler($currency))->getExchangeRate();
		$files = (new S3CloudHandler())->getFiles();

		foreach ($files as $filename) {
			(new CSVHandler($currency, $exchangeRate, $filename));
		}
	}
}
