<?php
/**
 * Created by PhpStorm.
 * User: sven
 * Date: 6/30/17
 * Time: 9:30 AM
 */

namespace Pronamic\Twinfield\Request\Columns;


class Column
{

    protected $field;
    protected $label;
    protected $visible;
    protected $ask;
    protected $operator;
    protected $from;
    protected $to;
    protected $finderparam;



    public function __construct($xml = null)
    {
        if (!empty($xml)) {
            $this->load($xml);
        }
    }

    public function load($xml) {
        $fields = [
            'field',
            'label',
            'visible',
            'ask',
            'operator',
            'from',
            'to',
            'finderparam'
        ];
        foreach ($fields as $field) {
            $this->{'set' . $field}($xml->getElementsByTagName($field)->item(0)->nodeValue);
        }
    }

    /**
     * @return \DOMDocument
     */
    public function unload(\DOMDocument $doc) {
        $fields = [
            'field',
            'label',
            'visible',
            'ask',
            'operator',
            'from',
            'to',
            'finderparam'
        ];
        $node = $doc->createElement('column');
        foreach ($fields as $field) {
            $node->appendChild($doc->createElement($field, $this->{'get' . $field}()));
        }
        return $node;
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAsk()
    {
        return $this->ask;
    }

    /**
     * @param mixed $ask
     */
    public function setAsk($ask)
    {
        $this->ask = $ask;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param mixed $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFinderparam()
    {
        return $this->finderparam;
    }

    /**
     * @param mixed $finderparam
     */
    public function setFinderparam($finderparam)
    {
        $this->finderparam = $finderparam;
        return $this;
    }





}