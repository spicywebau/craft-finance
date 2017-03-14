<?php
namespace Craft;

/**
 * Class FinanceVariable
 *
 * @package Craft
 */
class FinanceVariable
{
	/**
	 * Gets financial information from a stock label.
	 *
	 * @param $stocks
	 * @return mixed
	 */
	public function get($stocks)
	{
		return craft()->finance->getFinance($stocks);
	}
}
