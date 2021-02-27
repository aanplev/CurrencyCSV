<?php

/**
 * Class CSVHandler
 */
class CSVHandler
{
	private array  $data;
	private string $filename;

	/**
	 * CSVHandler constructor.
	 *
	 *
	 * @param string $currencyName
	 * @param float  $currencyValue
	 * @param string $filename
	 * @param int    $places
	 *
	 * @throws Exception
	 */
	public function __construct(
		string $currencyName,
		float $currencyValue,
		string $filename,
		int $places = 2)
	{
		$this->filename = $filename;

		$this->prepare($currencyName, $currencyValue, $places);
		$this->save();
	}

	/**
	 * Preparing for file conversion
	 *
	 * @param $currencyName
	 * @param $currencyValue
	 * @param $places
	 */
	private function prepare($currencyName, $currencyValue, $places)
	{
		if (($handle = fopen($this->filename, 'r')) !== false) {
			$firstLine = true;

			while (($part = fgetcsv($handle)) !== false) {
				if ($firstLine) {
					$part[]      = strtoupper($currencyName);
					$currencyKey = array_search('RUB', $part);
				} else {
					if ($part[$currencyKey]) {
						$part[] = round($part[$currencyKey] / $currencyValue, $places);
					} else {
						$part[] = 0;
					}
				}

				$firstLine = false;
				$data[]    = $part;
			}

			fclose($handle);
		}
	}

	/**
	 * Save file
	 *
	 * @throws Exception
	 */
	private function save()
	{
		if ($this->data) {
			$fp = fopen($this->filename, 'w');

			foreach ($this->data as $fields) {
				fputcsv($fp, $fields);
			}

			fclose($fp);
		} else {
			throw new Exception('Data is empty');
		}
	}
}
