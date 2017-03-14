<?php
namespace Craft;

/**
 * Class FinanceModel
 *
 * @package Craft
 */
class FinanceModel extends BaseComponentModel
{
	protected function defineAttributes()
	{
		return [
			'label' => AttributeType::String,
			'name' => AttributeType::String,
			'ask' => AttributeType::Number,
			'bid' => AttributeType::Number,
			'change' => AttributeType::Number,
			'dateLastTraded' => AttributeType::DateTime,
		];
	}
}
