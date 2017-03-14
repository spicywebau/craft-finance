<?php
namespace Craft;

/**
 * Class FinanceService
 *
 * @package Craft
 */
class FinanceService extends BaseApplicationComponent
{
	/**
	 * Gets financial data from a list of stock labels.
	 *
	 * @param $stocks
	 * @return array
	 */
	public function getFinance($stocks)
	{
		$data = $this->_requestData($stocks);
		$models = [];

		if($data)
		{
			foreach($data as $label => $settings)
			{
				$model = new FinanceModel();
				$model->label = $label;

				foreach($settings as $parameter => $value)
				{
					switch($parameter)
					{
						case 'n': $model->name = $value; break;
						case 'a': $model->ask = floatval($value); break;
						case 'b': $model->bid = floatval($value); break;
						case 'c1': $model->change = floatval($value); break;
						case 'd1': $model->dateLastTraded = $this->_parseDateTime($value, isset($settings['t1']) ? $settings['t1'] : null); break;
					}
				}

				$models[] = $model;
			}
		}

		return $models;
	}

	/**
	 * Parses a raw date string from Yahoo! into a date object.
	 *
	 * @param $date
	 * @param null $time
	 * @return DateTime
	 */
	private function _parseDateTime($date, $time=null)
	{
		if(isset($time))
		{
			$date .= ' ' . $time;
		}

		// @see http://stackoverflow.com/a/21398106/556609
		return DateTime::createFromFormat('m/d/Y g:ia', $date, 'America/New_York');
	}

	/**
	 * Requests the data from Yahoo!'s finance service.
	 *
	 * @param $stocks
	 * @param array $parameters
	 * @return array|bool
	 */
	private function _requestData($stocks, $parameters=['n', 'a', 'b', 'c1', 'd1', 't1'])
	{
		if(!is_array($stocks))
		{
			$stocks = [$stocks];
		}

		$url = 'http://finance.yahoo.com/d/quotes.csv?s=' . implode('+', $stocks) . '&f=' . implode('', $parameters);
		$csv = $this->_readExternalFile($url);

		if($csv)
		{
			$data = [];
			$lines = str_getcsv($csv, "\n");

			foreach($lines as $i => $line)
			{
				$settings = [];
				$label = $stocks[$i];
				$values = str_getcsv($line);

				foreach($values as $j => $value)
				{
					$parameter = $parameters[$j];
					$settings[$parameter] = $value;
				}

				$data[$label] = $settings;
			}

			return $data;
		}

		return false;
	}

	/**
	 * Reads in data from an external link.
	 *
	 * @param $url
	 * @return bool|mixed|string
	 */
	private function _readExternalFile($url)
	{
		if(function_exists('curl_init'))
		{
			FinancePlugin::log("Reading file with `curl`");

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_AUTOREFERER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$data = curl_exec($ch);
			$error = curl_error($ch);

			if(!empty($error))
			{
				FinancePlugin::log("Error reading file (\"{$error}\")", LogLevel::Error);

				$data = false;
			}

			curl_close($ch);

			return $data;
		}

		$allowUrlFopen = preg_match('/1|yes|on|true/i', ini_get('allow_url_fopen'));
		if($allowUrlFopen)
		{
			FinancePlugin::log("Reading file with `file_get_contents`");

			return @file_get_contents($url);
		}

		return false;
	}
}
