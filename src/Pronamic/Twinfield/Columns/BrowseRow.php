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
        foreach ($xml->getElementsByTagName('td') as $td) {
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

