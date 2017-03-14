<?php
namespace Craft;

/**
 * Class FinancePlugin
 *
 * Thank you for using Craft Finance!
 * @see https://github.com/spicywebau/craft-finance
 * @package Craft
 */
class FinancePlugin extends BasePlugin
{
	public function getName()
	{
		return "Finance";
	}

	public function getDescription()
	{
		return Craft::t("Get financial data from Yahoo! in your templates");
	}

	public function getVersion()
	{
		return '1.0.0';
	}

	public function getSchemaVersion()
	{
		return '1.0.0';
	}

	public function getCraftMinimumVersion()
	{
		return '2.0';
	}

	public function getPHPMinimumVersion()
	{
		return '5.4';
	}

	public function getDeveloper()
	{
		return "Spicy Web";
	}

	public function getDeveloperUrl()
	{
		return 'http://spicyweb.com.au';
	}

	public function getDocumentationUrl()
	{
		return 'https://github.com/spicywebau/craft-finance';
	}

	public function getReleaseFeedUrl()
	{
		return 'https://raw.githubusercontent.com/spicywebau/craft-finance/master/releases.json';
	}

	public function isCraftRequiredVersion()
	{
		return version_compare(craft()->getVersion(), $this->getCraftMinimumVersion(), '>=');
	}

	public function isPHPRequiredVersion()
	{
		return version_compare(PHP_VERSION, $this->getPHPMinimumVersion(), '>=');
	}

	/**
	 * Checks for environment compatibility when installing.
	 *
	 * @return bool
	 */
	public function onBeforeInstall()
	{
		$craftCompatible = $this->isCraftRequiredVersion();
		$phpCompatible = $this->isPHPRequiredVersion();

		if(!$craftCompatible)
		{
			self::log(Craft::t("Finance is not compatible with Craft {version} - requires Craft {required} or greater", [
				'version' => craft()->getVersion(),
				'required' => $this->getCraftMinimumVersion(),
			]), LogLevel::Error, true);
		}

		if(!$phpCompatible)
		{
			self::log(Craft::t("Finance is not compatible with PHP {version} - requires PHP {required} or greater", [
				'version' => PHP_VERSION,
				'required' => $this->getPHPMinimumVersion(),
			]), LogLevel::Error, true);
		}

		return $craftCompatible && $phpCompatible;
	}
}
