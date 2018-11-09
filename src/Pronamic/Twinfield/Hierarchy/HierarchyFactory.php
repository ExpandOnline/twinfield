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
		$generalLedgerFactory->getClient('/webservices/session.asmx?wsdl')->selectCompany(['company' => $office]);

		$basNames = $generalLedgerFactory->listNames([['dimtype','BAS'], ['office', $office]]);
		$pnlNames = $generalLedgerFactory->listNames([['dimtype','PNL'], ['office', $office]]);
		if ($this->getLogin()->process()) {
			$this->getClient('/webservices/session.asmx?wsdl')->selectCompany(['company' => $office]);
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
						$name = null;
						if($generalLedger->Type == 'BAS' && isset($basNames[$generalLedger->Code])) {
							$name = $basNames[$generalLedger->Code];
						}
						if($generalLedger->Type != 'BAS' && isset($pnlNames[$generalLedger->Code])) {
							$name = $pnlNames[$generalLedger->Code];
						}
						// Hidden general ledgers will not show in the finder, so get them in a different way.
						if($name === null) {
							$name = $this->getName($office, $generalLedger->Type, $generalLedger->Code);
						}

						$response[] = [
							'hierarchy_code' => $code,
							'category' => $category->Name,
							'sub_category' => $subcategory->Name,
							'code' => $generalLedger->Code,
							'name' => $name,
							'type' => $generalLedger->Type,
							'balance_type' => $generalLedger->BalanceType,
						];
					}
				}
			}
			return $response;
		}
	}

	public function getName($office, $type, $code) {
		$responseXml = $this->processXmlString("			
			<read>
				<type>dimensions</type>
				<office>$office</office>
				<dimtype>$type</dimtype>
				<code>$code</code>
			</read>
		");
		$responseDOM = new \DOMDocument();
		$responseDOM->loadXML($responseXml->ProcessXmlStringResult);
		return $responseDOM->getElementsByTagName('dimension')->item(0)->getElementsByTagName('name')->item(0)->nodeValue;
	}

}
