
<?php
/** 
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * ToDo Service
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */
class Kohana_ToDoService extends Kohana_Batchbookbase
{

    /**
     * Construct new ToDo Service
     *
     * @param void 
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Create ToDo From XML
     *
     * @param SimpleXMLElement $xmlElement
     * @return Kohana_ToDo
     */
    private function _populateToDoFromXmlElement(
        SimpleXMLElement $xmlElement,
        Kohana_ToDo $todo = null
    )
    {
        if (null === $todo) {
            $todo = new Kohana_ToDo();
        }
        $todo 
            ->setId($xmlElement->id)
            ->setTitle($xmlElement->title)
            ->setDescription($xmlElement->description)
            ->setDueDate($xmlElement->due_date)
            ->setFlagged($xmlElement->flagged)
            ->setComplete($xmlElement->complete)
            ;
        return $todo;
    }



    /**
     * Get ToDo
     *
     * @param integer $id
     * @return Kohana_ToDo
     */
    public function getToDo($id)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/todos/' . $id . '.xml'
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
                throw new Exception('Could not get ToDo.');
        } 
        $xmlResponse = simplexml_load_string($response->getBody());
        return $this->_populateToDoFromXmlElement($xmlResponse);
    }




    /**
     * Post ToDo
     *
     * @param Kohana_ToDo $todo
     * @return Kohana_ToDoService   Provides a fluent interface
     */
    public function postToDo(Kohana_ToDo $todo)
    {


        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/todos.xml'
        );

        $httpClient->setParameterPost(
            'todo[title]',
            $todo->getTitle()
        );
        $httpClient->setParameterPost(
            'todo[description]',
            $todo->getDescription()
        );

        $httpClient->setParameterPost(
            'todo[due_date]',
            $todo->getDueDate()
        );
        $httpClient->setParameterPost(
            'todo[flagged]',
            $todo->getFlagged()
        );
        $httpClient->setParameterPost(
            'todo[complete]',
            $todo->getComplete()
        );




        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::POST);
        if (201 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('ToDo not created.');
        }


        $location = $response->getHeader('location');
        $httpClient = new Zend_Http_Client($location);
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $this->_populateToDoFromXmlElement($xmlResponse, $todo); 
    

        return $this;
    }

    /**
     * Put ToDo
     *
     * @param Kohana_ToDo $todo
     * @return Kohana_ToDoService   Provides a fluent interface
     */
    public function putToDo(Kohana_ToDo $todo)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/todos/' . $todo->getId() . '.xml'
        );
        $paramsPut = array(
            'todo[title]'    => $todo->getTitle(),
            'todo[description]'     => $todo->getDescription(),
            'todo[due_date]'         => $todo->getDueDate(),
            'todo[flagged]'       => $todo->getFlagged(), 
            'todo[complete]'       => $todo->getComplete(), 
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
            throw new Exception('ToDo not updated:' . $response->getMessage() . "\n" . $response->getBody() );
        }
        return $this;
    }

    /**
     * Delete ToDo
     *
     * @param Kohana_ToDo $todo
     * @return Kohana_ToDoService   Provides a fluent interface
     */
    public function deleteToDo(Kohana_ToDo $todo)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/todos/' . $todo->getId() . '.xml'
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::DELETE);
        if (200 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('ToDo not deleted.');
        }
        return $this;
    }

    public function addPersonToToDo(Kohana_ToDo $todo, Kohana_Person $person)
    {
    
        

        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/todos/' . $todo->getId() . '/add_related_contact.xml'
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
            throw new Exception('Person not added to todo');
        }
        return $this; 
    }



}

