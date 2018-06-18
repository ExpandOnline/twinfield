<?php
namespace Pronamic\Twinfield\Request\Catalog;

/**
 * Class GeneralLedger
 *
 * @package Pronamic\Twinfield\Request\Catalog
 */
class GeneralLedger extends Catalog
{

	/**
	 * GeneralLedger constructor.
	 *
	 * @param $office
	 * @param $dimType
	 */
	public function __construct($office, $dimType)
	{
		parent::__construct();
		$this->add('type', 'dimensions');
		$this->setOffice($office);
		$this->setDimType($dimType);
	}

	/**
	 * @param $office
	 *
	 * @return $this
	 */
	public function setOffice($office)
	{
		$this->add('office', $office);
		return $this;
	}

	/**
	 * @param $dimType
	 *
	 * @return $this
	 */
	public function setDimType($dimType)
	{
		$this->add('dimtype', $dimType);
		return $this;
	}
}
