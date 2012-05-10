<?php
/** 
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Deal Service
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */
class Kohana_DealService extends Kohana_Batchbookbase
{
   

    /**
     * Construct new Deal Service
     *
     * @param void
     */
    public function __construct()
    {
        parent::__construct();
    }



    /**
     * Create Deal From XML
     *
     * @param SimpleXMLElement $xmlElement
     * @return Kohana_Deal
     */
    private function _populateDealFromXmlElement(
        SimpleXMLElement $xmlElement,
        Kohana_Deal $deal = null
    )
    {
        if (null === $deal) {
            $deal = new Kohana_Deal();
        }
        $deal
            ->setId($xmlElement->id)
            ->setTitle($xmlElement->title)
            ->setDescription($xmlElement->description)
            ->setStatus($xmlElement->status);
        return $deal;
    }


    /**
     * Index Of Deals
     *
     * @param string $name
     * @param string $email
     * @param integer $offset
     * @param integer $limit
     * @return array
     */
    public function indexOfDeals($status = null,$assignedToEmail = null)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/deals.xml'
        );
        if (null !== $status) {
            $httpClient->setParameterGet('status', $status);
        }
        if (null !== $assignedToEmail) {
            $httpClient->setParameterGet('assigned_to', $assignedToEmail);
        }

        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $deals = array();
        foreach ($xmlResponse->deal as $dealElement) {
            $deals[] = $this->_populateDealFromXmlElement($dealElement);
        }
        return $deals;
    }


    /**
     * Get Deal
     *
     * @param integer $id
     * @return Kohana_Deal
     */
    public function getDeal($id)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/deals/' . $id . '.xml'
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        switch ($response->getStatus()) {
            case 200:
                break;
            case 404:
                return null;
                break;
            default;
                //TODO: throw more specific exception
                throw new Exception('Could not get Deal.');
        } 
        $xmlResponse = simplexml_load_string($response->getBody());
        return $this->_populateDealFromXmlElement($xmlResponse);
    }




    /**
     * Post Deal
     *
     * @param Kohana_Deal $deal
     * @return Kohana_DealService   Provides a fluent interface
     */
    public function postDeal(Kohana_Deal $deal)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/deals.xml'
        );

        $httpClient->setParameterPost(
            'deal[title]',
            $deal->getTitle()
        );
        $httpClient->setParameterPost(
            'deal[amount]',
            $deal->getAmount()
        );
        $httpClient->setParameterPost(
            'deal[status]',
            $deal->getStatus()
        );
        $httpClient->setParameterPost(
            'deal[description]',
            $deal->getDescription()
        );

        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::POST);
        if (201 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('Deal not created.');
        }
        $location = $response->getHeader('location');
        $httpClient = new Zend_Http_Client($location);
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $this->_populateDealFromXmlElement($xmlResponse, $deal);
        return $this;
    }

    /**
     * Put Deal
     *
     * @param Kohana_Deal $deal
     * @return Kohana_DealService   Provides a fluent interface
     */
    public function putDeal(Kohana_Deal $deal)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/deals/' . $deal->getId() . '.xml'
        );
        $paramsPut = array(
            'deal[title]'    => $deal->getTitle(),
            'deal[description]'     => $deal->getDescription(),
            'deal[amount]'         => $deal->getAmount(),
            'deal[status]'       => $deal->getStatus(), 
        );
        $httpClient->setAuth($this->_token, 'x');
        $httpClient->setHeaders(
            Zend_Http_Client::CONTENT_TYPE,
            Zend_Http_Client::ENC_URLENCODED
        );
        $httpClient->setRawData(
            http_build_query($paramsPut, '', '&'),
            Zend_Http_Client::ENC_URLENCODED
        );
        $response = $httpClient->request(Zend_Http_Client::PUT);
        if (200 != $response->getStatus()) {
            //TODO: throw more specific exception
            echo $httpClient->getLastRequest();
            throw new Exception('Deal not updated:' . $response->getMessage() . "\n" . $response->getBody() );
        }
        return $this;
    }

    /**
     * Delete Deal
     *
     * @param Kohana_Deal $deal
     * @return Kohana_DealService   Provides a fluent interface
     */
    public function deleteDeal(Kohana_Deal $deal)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/deals/' . $deal->getId() . '.xml'
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::DELETE);
        if (200 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('Deal not deleted.');
        }
        return $this;
    }


    /**
        Note: REST API supports adding company to the deal too
    */
    public function addPersonToDeal(Kohana_Deal $deal, Kohana_Person $person)
    {
    
        

        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/deals/' . $deal->getId() . '/add_related_contact.xml'
        );


        $paramsPut = array(
            'contact_id'    => $person->getId(), 
        );

        $httpClient->setAuth($this->_token, 'x');
        $httpClient->setHeaders(
            Zend_Http_Client::CONTENT_TYPE,
            Zend_Http_Client::ENC_URLENCODED
        );
        $httpClient->setRawData(
            http_build_query($paramsPut, '', '&'),
            Zend_Http_Client::ENC_URLENCODED
        );
        $response = $httpClient->request(Zend_Http_Client::PUT);
        if (200 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('Person not added to deal');
        }
        return $this; 
    }


}

