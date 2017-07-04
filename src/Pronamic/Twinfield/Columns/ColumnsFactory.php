<?php
namespace Pronamic\Twinfield\Columns;

use Pronamic\Twinfield\Columns\Mapper\BrowseResultMapper;
use \Pronamic\Twinfield\Factory\ParentFactory;
use \Pronamic\Twinfield\Request as Request;
use Pronamic\Twinfield\Response\Response;



class ColumnsFactory extends ParentFactory
{

    public function browse($office, $code) {
        // Attempts to process the login
        if (!$this->getLogin()->process()) {
            return;
        }

        // Get the secure service class
        $service = $this->getService();

        // No office passed, get the office from the Config
        if (! $office) {
            $office = $this->getConfig()->getOffice();
        }

        // Make a request to read a single customer. Set the required values
        $request_customer = new Request\Read\Browse();
        $request_customer
            ->setOffice($office)
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $service->send($request_customer);
        $this->setResponse($response);

        // Return a mapped Customer if successful or false if not.
        if ($response->isSuccessful()) {
            return BrowseResultMapper::mapColumns($response);
        } else {
            return false;
        }
    }

    public function getColumns($office, $code, $columns) {
        // Attempts to process the login
        if (!$this->getLogin()->process()) {
            return;
        }

        // Get the secure service class
        $service = $this->getService();

        // No office passed, get the office from the Config
        if (!$office) {
            $office = $this->getConfig()->getOffice();
        }

        // Make a request to read a single customer. Set the required values
        $request_customer = new Request\Columns\Columns($code);
        $request_customer
            ->setOffice($office)
            ->setColumns($columns);

        // Send the Request document and set the response to this instance.
        $response = $service->send($request_customer);
        $this->setResponse($response);

        // Return a mapped Customer if successful or false if not.
        if ($response->isSuccessful()) {
           return BrowseResultMapper::map($response);
        } else {
            return false;
        }
    }

}