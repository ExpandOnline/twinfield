<?php

namespace Pronamic\Twinfield\GeneralLedger;

use Pronamic\Twinfield\Factory\FinderFactory;
use Pronamic\Twinfield\Request as Request;

/**
 * Class GeneralLedgerFactory
 *
 * @package Pronamic\Twinfield\GeneralLedger
 */
class GeneralLedgerFactory extends FinderFactory
{

	/**
	 * @param        $dimType
	 * @param string $pattern
	 * @param int    $field
	 * @param int    $firstRow
	 * @param int    $maxRows
	 * @param array  $options
	 *
	 * @return array
	 */
	public function listNames($options = array())
	{
		$response = $this->searchFinder('DIM', '*', 0, 1, 2000, $options);
		$generalLedgers = [];
		foreach ($response->data->Items->ArrayOfString as $generalLedger)
		{
			$generalLedgers[$generalLedger->string[0]] = $generalLedger->string[1];
		}
		return $generalLedgers;
	}

}