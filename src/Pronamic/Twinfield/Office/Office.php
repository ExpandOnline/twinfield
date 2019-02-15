<?php

namespace Pronamic\Twinfield\Office;

class Office
{
    /**
     * @var string the code of the office
     */
    private $code;
    /**
     * @var string the code of the country of the office
     */
    private $countryCode;
    /**
     * @var string the name of the office
     */
    private $name;

	/**
	 * @var string
	 */
    private $baseCurrency;

	/**
	 * @var string
	 */
    private $reportingCurrency;

    public function getCode()
    {
        return $this->code;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

	/**
	 * @return string
	 */
	public function getBaseCurrency(): string {
		return $this->baseCurrency;
	}

	/**
	 * @param string $baseCurrency
	 */
	public function setBaseCurrency(string $baseCurrency) {
		$this->baseCurrency = $baseCurrency;
	}

	/**
	 * @return string
	 */
	public function getReportingCurrency(): string {
		return $this->reportingCurrency;
	}

	/**
	 * @param string $reportingCurrency
	 */
	public function setReportingCurrency(string $reportingCurrency) {
		$this->reportingCurrency = $reportingCurrency;
	}


}