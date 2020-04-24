<?php
/**
 * Created by PhpStorm.
 * User: sven
 * Date: 7/3/17
 * Time: 2:15 PM
 */

namespace Pronamic\Twinfield\Columns;


class BrowseCell
{

    protected $field;

    protected $hideForUser;

    protected $type;

    protected $value;

    /**
     * BrowseCell constructor.
     */
    public function __construct(\DOMNode $xml)
    {
        $this->load($xml);
    }

    public function load(\DOMNode $xml) {
        // Using indexes for performance reasons. This means if twinfield changes the order of attributes this will break
        $this->field = $xml->attributes->item(0)->nodeValue;
        $this->hideForUser = $xml->attributes->item(1)->nodeValue;
        $this->type = $xml->attributes->item(2)->nodeValue;
        $this->value = $xml->nodeValue;
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
    }

    /**
     * @return mixed
     */
    public function getHideForUser()
    {
        return $this->hideForUser;
    }

    /**
     * @param mixed $hideForUser
     */
    public function setHideForUser($hideForUser)
    {
        $this->hideForUser = $hideForUser;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }



}
