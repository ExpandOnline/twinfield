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
			$budgets[] = !is_array($budgetArray) ? $budgetArray->string[0] : $budgetArray[0];
		}
		return $budgets;
	}

	/**
	 * @param $office
	 * @param $year
	 *
	 * @return array
	 */
	public function listAll($office, $year)
	{
		$budgetNames = $this->listNames('*', 0, 1, 100, [
			[
				'office',
				$office,
			]
		]);
		if ($this->getLogin()->process()) {
			$service = $this->getService();
			$budgetRequest = new Request\Catalog\Budget();
			foreach ($budgetNames as $budgetName) {
				$budgetRequest->addBudget($budgetName);
			}
			$budgetRequest->setDim1From(0);
			$budgetRequest->setDim1To(9999);
			$budgetRequest->setOffice($office);
			$budgetRequest->setPeriodFrom(0);
			$budgetRequest->setPeriodTo(56);
			$budgetRequest->setYear($year);
			$response = $service->send($budgetRequest);
			$this->setResponse($response);
			if ($response->isSuccessful()) {
				$responseDOM = $response->getResponseDocument();
				$budgets = array();
				foreach ((new \DOMXPath($responseDOM))->query('//budgets/budget') as $budget) {
					$budgetArray = [];
					/**
					 * @var \DOMElement $budget
					 */
					$budgetArray['period'] = $budget->getElementsByTagName('period')->item(0)->textContent;
					$dim1 = $budget->getElementsByTagName('dim1')->item(0);
					$budgetArray['dim1'] = [
						'code' => $dim1->textContent,
						'name' => $dim1->getAttribute('name'),
						'type' => $dim1->getAttribute('type'),
					];
					$budgetArray['current_balance'] = $budget->getElementsByTagName('current')->item(0)
						->getElementsByTagName('balance')->item(0)->textContent;
					$budgetArray['budget_balance'] = $budget->getElementsByTagName('budget')->item(0)
						->getElementsByTagName('balance')->item(0)->textContent;
					$groups = [];
					foreach ($budget->getElementsByTagName('group') as $group) {
						$groups[] = [
							'id' => $group->getAttribute('id'),
							'name' => $group->getAttribute('name'),
							'code' => $group->textContent,
						];
					}
					$budgetArray['groups'] = $groups;
					$budgets[] = $budgetArray;
				}

				return $budgets;
			}
		}
	}
}