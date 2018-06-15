<?php

namespace Pronamic\Twinfield\Hierarchy;

use Pronamic\Twinfield\Factory\FinderFactory;
use Pronamic\Twinfield\GeneralLedger\GeneralLedgerFactory;

/**
 * Class HierarchyFactory
 *
 * @package Pronamic\Twinfield\Hierarchy
 */
class HierarchyFactory extends FinderFactory
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
	public function list($pattern = '*', $field = 0, $firstRow = 1, $maxRows = 100, $options = array())
	{
		$response = $this->searchFinder(self::TYPE_HIERARCHIES, $pattern, $field, $firstRow, $maxRows, $options);
		$hierarchy = [];
		foreach ($response->data->Items->ArrayOfString as $budgetArray)
		{
			$hierarchy[] = !is_array($budgetArray) ? $budgetArray->string[0] : $budgetArray[0];
		}
		return $hierarchy;
	}

	/**
	 * @param $office
	 * @param $code
	 *
	 * @return array
	 */
	public function load($office, $code)
	{
		$generalLedgerFactory = new GeneralLedgerFactory($this->getConfig());
		$basNames = $generalLedgerFactory->listNames([['dimtype','BAS'], ['office', $office]]);
		$pnlNames = $generalLedgerFactory->listNames([['dimtype','PNL'], ['office', $office]]);
		if ($this->getLogin()->process()) {
			$result = $this->getClient('/webservices/hierarchies.asmx?wsdl')->Load(['hierarchyCode' => $code])->hierarchy;
			$response = [];
			if (!property_exists($result->RootNode->ChildNodes, 'HierarchyNode')) {
				return $response;
			}
			$categories = is_array($result->RootNode->ChildNodes->HierarchyNode)
				? $result->RootNode->ChildNodes->HierarchyNode : [$result->RootNode->ChildNodes->HierarchyNode];
			foreach ($categories as $category) {
				if (!property_exists($category->ChildNodes, 'HierarchyNode')) {
					continue;
				}
				$subcategories = is_array($category->ChildNodes->HierarchyNode)
					? $category->ChildNodes->HierarchyNode : [$category->ChildNodes->HierarchyNode];
				foreach ($subcategories as $subcategory) {
					if (!property_exists($subcategory->Accounts, 'HierarchyAccount')) {
						continue;
					}
					$generalLedgers = is_array($subcategory->Accounts->HierarchyAccount)
						? $subcategory->Accounts->HierarchyAccount : [$subcategory->Accounts->HierarchyAccount];
					foreach ($generalLedgers as $generalLedger) {
						$response[] = [
							'hierarchy_code' => $code,
							'category' => $category->Name,
							'sub_category' => $subcategory->Name,
							'code' => $generalLedger->Code,
							'name' => $generalLedger->Type == 'BAS' ? $basNames[$generalLedger->Code] : $pnlNames[$generalLedger->Code],
							'type' => $generalLedger->Type,
							'balance_type' => $generalLedger->BalanceType,
						];
					}
				}
			}
			return $response;
		}
	}

}