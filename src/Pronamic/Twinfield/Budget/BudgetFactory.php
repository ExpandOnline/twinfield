<?php

namespace Pronamic\Twinfield\Budget;

use Pronamic\Twinfield\Factory\FinderFactory;
use Pronamic\Twinfield\Request as Request;

/**
 * Class OfficeFactory
 *
 * @package Pronamic\Twinfield\Office
 */
class BudgetFactory extends FinderFactory
{
	/**
	 * @param string $pattern
	 * @param int    $field
	 * @param int    $firstRow
	 * @param int    $maxRows
	 * @param array  $options
	 *
	 * @return array
	 */
	public function listNames($pattern = '*', $field = 0, $firstRow = 1, $maxRows = 100, $options = array())
	{
		$response = $this->searchFinder(self::TYPE_BUDGETS, $pattern, $field, $firstRow, $maxRows, $options);
		$budgets = [];
		foreach($response->data->Items->ArrayOfString as $budgetArray)
		{
			$budgetArray = !is_array($budgetArray) ? $budgetArray->string : $budgetArray;
			$budgets[$budgetArray[0]] =  $budgetArray[1];
		}
		return $budgets;
	}

	/**
	 * @param $office
	 * @param $year
	 *
	 * @return array
	 */
	public function listAll($office, $year) {
		$creds = $this->getLogin()->getConfig()->getCredentials();
		$this->getLogin()->getConfig()->setCredentials($creds['user'], $creds['password'], $creds['organisation'], $office);
		$budgetNames = $this->listNames('*', 0, 1, 100, [
			[
				'office',
				$office,
			]
		]);
		if ($this->getLogin()->process()) {

			$resultArray = [];

			foreach ($budgetNames as $budgetCode => $budgetName) {
				$result = $this->getClient('/webservices/BudgetService.svc?wsdl')->Query(
					new \SoapVar('<ns1:Query i:type="b:GetBudgetByCostCenter" xmlns:a="http://schemas.datacontract.org/2004/07/Twinfield.WebServices" xmlns:i="http://www.w3.org/2001/XMLSchema-instance" xmlns:b="http://schemas.datacontract.org/2004/07/Twinfield.WebServices.BudgetService">
			<b:Code>' . $budgetCode . '</b:Code>
			<b:Dim1From/>
			<b:Dim1To/>
			<b:Dim2From/>
			<b:Dim2To/>
			<b:Dim3From/>
			<b:Dim3To/>
			<b:IncludeFinal>true</b:IncludeFinal>
			<b:IncludeProvisional>true</b:IncludeProvisional>
			<b:PeriodFrom>0</b:PeriodFrom>
			<b:PeriodTo>12</b:PeriodTo>
			<b:Year>' . $year . '</b:Year>
		</ns1:Query>', XSD_ANYXML)
				);

				if (!property_exists($result->BudgetTotals, 'GetBudgetTotalResult')) {
					return [];
				}
				foreach ($result->BudgetTotals->GetBudgetTotalResult as $budget) {

					$budget->Code = $budgetCode;
					$budget->Name = $budgetName;

					$resultArray[] = $budget;


				}

			}

			return $resultArray;
		}
	}

}