<?php
namespace Pronamic\Twinfield\Request\Catalog;

/**
 * Class Budget
 *
 * @package Pronamic\Twinfield\Request\Catalog
 */
class Budget extends Catalog
{
	/**
	 * Budget constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->add('type', 'budgets');
		$this->add('status', 'both');
	}

	/**
	 * Sets the officecode for the dimension request.
	 *
	 * @access public
	 * @param int $office
	 */
	public function setOffice($office)
	{
		$this->add('office', $office);
	}

	/**
	 * @param $year
	 */
	public function setYear($year)
	{
		$this->add('year', $year);
	}

	/**
	 * @param $from
	 */
	public function setPeriodFrom($from)
	{
		$this->add('periodfrom', $from);
	}

	/**
	 * @param $to
	 */
	public function setPeriodTo($to)
	{
		$this->add('periodto', $to);
	}

	/**
	 * @param $budget
	 */
	public function addBudget($budget)
	{
		$this->add('budget', $budget);
	}

	/**
	 * @param $dim1From
	 */
	public function setDim1From($dim1From)
	{
		$this->add('dim1from', $dim1From);
	}

	/**
	 * @param $dim1To
	 */
	public function setDim1To($dim1To)
	{
		$this->add('dim1to', $dim1To);
	}

}
