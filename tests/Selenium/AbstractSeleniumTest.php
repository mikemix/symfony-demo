<?php

namespace Accesto\Tests;

use Facebook\WebDriver\Exception\WebDriverCurlException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use PHPUnit\Framework\TestCase;

abstract class AbstractSeleniumTest extends TestCase
{
    /** @var RemoteWebDriver */
    protected $driver;

    protected function getBaseUrl()
    {
        return getenv('PHPUNIT_BASEURL') ?: 'http://demo/app_test.php';
    }

    protected function getBrowser()
    {
        return getenv('PHPUNIT_BROWSER') ?: 'firefox';
    }

    protected function getSeleniumHost()
    {
        return getenv('PHPUNIT_SELENIUM_HOST') ?: 'http://selenium:4444/wd/hub';
    }

    protected function setUp()
    {
        try {
            $this->driver = RemoteWebDriver::create($this->getSeleniumHost(), DesiredCapabilities::chrome());
            $this->driver->manage()->window()->maximize();
        } catch (WebDriverCurlException $e) {
            throw new \PHPUnit_Framework_SkippedTestError($e->getMessage());
        }
    }

    protected function tearDown()
    {
        $this->driver->quit();
    }

    protected function visit($url)
    {
        $this->driver->get($this->getBaseUrl().$url);
    }

    protected function fill($field, $value)
    {
        usleep(1000);

        $element = WebDriverBy::name($field);

        $this->driver->wait()->until(WebDriverExpectedCondition::elementToBeClickable($element));
        $this->driver->findElement($element)->click()->sendKeys($value);
    }
}
