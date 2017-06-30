<?php
namespace Pronamic\Twinfield\Request\Columns;

/**
 * Abstract parent class Read. Read is the name for the request component
 * READ.
 *
 * All aspects of READ request require a parent element called 'read'
 *
 * The construct makes this element, appends to itself. All requirements to
 * add new elements to this <read> dom element are done through the add()
 * method.
 *
 * @package Pronamic\Twinfield
 * @subpackage Request\Read
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
class Columns extends \DOMDocument
{
    /**
     * Holds the <read> element that all
     * additional elements shoudl be a child of.
     *
     * @access private
     * @var \DOMElement
     */
    private $columnsElement;

    /**
     * Creates the <read> element and adds it to the property
     * readElement
     *
     * @access public
     */
    public function __construct($code)
    {
        parent::__construct();

        $this->columnsElement = $this->createElement('columns');
        $this->columnsElement->setAttribute('code', $code);
        $this->appendChild($this->columnsElement);
    }

    /**
     * Adds additional elements to the <read> dom element.
     *
     * See the documentation over what <read> requires to know
     * and what additional elements you need.
     *
     * @access protected
     * @param string $element
     * @param mixed $value
     * @return void
     */
    protected function add($element, $value)
    {
        $_element = $this->createElement($element, $value);
        $this->columnsElement->appendChild($_element);
    }

    /**
     * Sets the office code for this customer request.
     *
     * @access public
     * @param int $office
     * @return \Pronamic\Twinfield\Request\Columns\Columns
     */
    public function setOffice($office)
    {
        $this->add('office', $office);
        return $this;
    }


    /**
     * @param Column[] $columns
     * @return $this
     */
    public function setColumns($columns) {
        foreach($columns as $column) {
            $this->columnsElement->appendChild($column->unload($this));
        }
        return $this;
    }
}
