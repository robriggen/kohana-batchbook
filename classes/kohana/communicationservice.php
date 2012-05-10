<?php
/**
 * Big Yellow
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Communication Service
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */
class Kohana_CommunicationService extends Kohana_Batchbookbase
{

    /**
     * Construct new Communication Service
     *
     * @param void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create Communication From XML
     *
     * @param SimpleXMLElement $xmlElement
     * @return Kohana_Communication
     */
    private function _populateCommunicationFromXmlElement(
        SimpleXMLElement $xmlElement,
        Kohana_Communication $communication = null
    )
    {
        if (null === $communication) {
            $communication = new Kohana_Communication();
        }
        $communication
            ->setId($xmlElement->id)
            ->setSubject($xmlElement->subject)
            ->setBody($xmlElement->body)
            ->setDate($xmlElement->date)
            ->setCtype($xmlElement->ctype)
        ;
        return $communication;
    }

    /**
     * Index Of Communications
     *
     * @param string $name
     * @param string $email
     * @param integer $offset
     * @param integer $limit
     * @return array
     */
    public function indexOfCommunications($contact_id = null, $ctype = null, $offset = null, $limit = null)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/communications.xml'
        );
        if (null !== $contact_id) {
            $httpClient->setParameterGet('contact_id', $contact_id);
        }
        if (null !== $ctype) {
            $httpClient->setParameterGet('ctype', $ctype);
        }
        if (null !== $offset) {
            $httpClient->setParameterGet('offset', $offset);
        }
        if (null !== $limit) {
            $httpClient->setParameterGet('limit', $limit);
        }
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $communications = array();
        foreach ($xmlResponse->communication as $communicationElement) {
            $communications[] = $this->_populateCommunicationFromXmlElement($communicationElement);
        }
        return $communications;
    }

    /**
     * Get Communication
     *
     * @param integer $id
     * @return Kohana_Communication
     */
    public function getCommunication($id)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/communications/' . $id . '.xml'
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
                throw new Exception('Could not get Communication.');
        }
        $xmlResponse = simplexml_load_string($response->getBody());
        return $this->_populateCommunicationFromXmlElement($xmlResponse);
    }

    /**
     * Post Communication
     *
     * @param Kohana_Communication $communication
     * @return Kohana_CommunicationService   Provides a fluent interface
     */
    public function postCommunication(Kohana_Communication $communication)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/communications.xml'
        );
        $httpClient->setParameterPost(
            'communication[subject]',
            $communication->getSubject()
        );
        $httpClient->setParameterPost(
            'communication[body]',
            $communication->getBody()
        );
        $httpClient->setParameterPost(
            'communication[date]',
            $communication->getDate()
        );
        $httpClient->setParameterPost(
            'communication[ctype]',
            $communication->getCtype()
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::POST);
        if (201 != $response->getStatus()) {
            //TODO: throw more specific exception
            var_dump($response);
            throw new Exception('Communication not created.');
        }
        $location = $response->getHeader('location');
        $httpClient = new Zend_Http_Client($location);
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $this->_populateCommunicationFromXmlElement($xmlResponse, $communication);
        return $this;
    }

    /**
     * Put Communication
     *
     * @param Kohana_Communication $communication
     * @return Kohana_CommunicationService   Provides a fluent interface
     */
    public function putCommunication(Kohana_Communication $communication)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/communications/' . $communication->getId() . '.xml'
        );
        $paramsPut = array(
            'communication[subject]'    => $communication->getSubject(),
            'communication[body]'     => $communication->getBody(),
            'communication[date]'         => $communication->getDate(),
            'communication[ctype]'       => $communication->getCtype(),
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
            throw new Exception('Communication not updated.');
        }
        return $this;
    }

    /**
     * Delete Communication
     *
     * @param Kohana_Communication $communication
     * @return Kohana_CommunicationService   Provides a fluent interface
     */
    public function deleteCommunication(Kohana_Communication $communication)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/communications/' . $communication->getId() . '.xml'
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::DELETE);
        if (200 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('Communication not deleted.');
        }
        return $this;
    }

    public function addParticipant(Kohana_Communication $communication, $id, $role)
    {
        $paramsPut = array(
            'contact_id'    => $id,
            'role'     => $role,
        );
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/communications/' . $communication->getId() . '/add_participant.xml'
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
            throw new Exception('Participant not added.');
        }
        return $this;
        
    }

    public function addTag(Kohana_Communication $communication, $tag)
    {
        $paramsPut = array(
            'tag'     => $tag,
        );
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/communications/' . $communication->getId() . '/add_tag.xml'
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
            throw new Exception('Tag not added.');
        }
        return $this;
        
    }
}
