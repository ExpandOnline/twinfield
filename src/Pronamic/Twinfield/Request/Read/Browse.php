<?php
/**
 * Created by PhpStorm.
 * User: sven
 * Date: 6/30/17
 * Time: 3:29 PM
 */

namespace Pronamic\Twinfield\Request\Read;


class Browse extends Read
{
    /**
     * Sets office and code if they are present.
     *
     * @access public
     */
    public function __construct($office = null, $code = null)
    {
        parent::__construct();

        $this->add('type', 'browse');

        if (null !== $office) {
            $this->setOffice($office);
        }

        if (null !== $code) {
            $this->setCode($code);
        }
    }

    /**
     * Sets the office code for this Article request.
     *
     * @access public
     * @param int $office
     * @return \Pronamic\Twinfield\Request\Read\Article
     */
    public function setOffice($office)
    {
        $this->add('office', $office);
        return $this;
    }

    /**
     * Sets the code for this Article request.
     *
     * @access public
     * @param string $code
     * @return \Pronamic\Twinfield\Request\Read\Article
     */
    public function setCode($code)
    {
        $this->add('code', $code);
        return $this;
    }
}