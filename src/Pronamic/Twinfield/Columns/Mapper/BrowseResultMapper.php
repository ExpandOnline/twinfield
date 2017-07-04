<?php
namespace Pronamic\Twinfield\Columns\Mapper;

use Pronamic\Twinfield\Columns\BrowseRow;
use Pronamic\Twinfield\Request\Columns\Column;
use \Pronamic\Twinfield\Response\Response;

class BrowseResultMapper
{
    /**
     * Maps a Response object to clean Column entities.
     *
     * @param \Pronamic\Twinfield\Response\Response $response
     * @return \Pronamic\Twinfield\Request\Columns\Column[]
     */
    public static function mapColumns(Response $response)
    {
        $responseDOM = $response->getResponseDocument();
        $browse = $responseDOM->getElementsByTagName('browse')->item(0);
        $columns = $browse->getElementsByTagName('columns')->item(0);
        $columnObjects = [];
        foreach($columns->getElementsByTagName('column') as $column) {
            $cObject = new Column($column);
            $columnObjects[$cObject->getField()] = $cObject;
        }
        return $columnObjects;
    }

    /**
     * @param Response $response
     * @return array
     */
    public static function map(Response $response) {
        $responseDOM = $response->getResponseDocument();
        $browse = $responseDOM->getElementsByTagName('browse')->item(0);
        $rows = [];
        foreach ($browse->getElementsByTagName('tr') as $row) {
            $rows[] = new BrowseRow($row);
        }
        return $rows;
    }
}