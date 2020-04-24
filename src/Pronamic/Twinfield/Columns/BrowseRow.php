<?php

namespace Pronamic\Twinfield\Columns;


use Pronamic\Twinfield\Exception\CellNotFoundException;

class BrowseRow
{

    /**
     * @var BrowseCell[]
     */
    protected $cells = [];

    /**
     * BrowseRow constructor.
     * @param \DOMDocument $data
     */
    public function __construct(\DOMElement $data)
    {
        $this->load($data);
    }

    /**
     * @param \DOMDocument $xml
     */
    public function load(\DOMNode $xml) {
        // We loop through childNodes instead of finding by element name purely for performance reasons
		// We can recognise whether it is a td or not by if it has any attributes.
    	foreach($xml->childNodes as $td) {
    		/** @var \DOMElement $td */
    		if($td->hasAttributes() === false) {
    			//means it is not a td
				continue;
			}
			$cell = new BrowseCell($td);
			$this->cells[$cell->getField()] = $cell;
		}
    }

    /**
     * @param $field
     * @return BrowseCell
     * @throws CellNotFoundException
     */
    public function getCell($field) {
        if (!array_key_exists($field, $this->cells)) {
            throw new CellNotFoundException($field, array_keys($this->cells));
        }
        return $this->cells[$field];
    }

    public function getFields() {
        return array_keys($this->cells);
    }


}

