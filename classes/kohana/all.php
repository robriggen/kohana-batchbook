<?php

class Kohana_Batchbookbase
{
    protected $_account;

    protected $_token;

    public function __construct()
    {
        $config = Kohana::$config->load('batchbook');

        $this->_account = $config->get('account');
        $this->_token = $config->get('token');
    }

}

/**
 * Big Yellow
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Communication
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 * @copyright   Copyright (c) 2010 Big Yellow Technologies, LLC
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Rob Riggen <rob@bigyellowtech.com>
 */
class Kohana_Communication
{
    /**
     * int $_id id of communication
     */
    private $_id;

    /**
     * string $_to to of communication
     */
    private $_to;

    /**
     * string $_from from of communication
     */
    private $_from ;

    /**
     * string $_subject subject of communication
     */
    private $_subject;

    /**
     * string $_body body of communication
     */
    private $_body;

    /**
     * string $_date date of communication
     */
    private $_date;

    /**
     * string $_ctype communication type for communication
     */
    private $_ctype;

    /**
     * constructor
     *
     * @param int $id optional id of communication
     */ 
    public function __construct($id = null)
    {
        if (!empty($id)) {
            $this->setId($id);
        }
    }

    /**
     * Get Id
     *
     * Get id of communication
     *
     * @param null
     * @return int $id id of communication
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set Id
     *
     * Set id for communication
     *
     * @param int $value id of communication
     * @return Kohana_Communication
     */
    public function setId($value)
    {
        $this->_id = (integer) $value;

        return $this;
    }

    /**
     * Get To
     *
     * Get to of communication
     *
     * @param null
     * @return string $subject subject of communication
     */
    public function getTo()
    {
        return $this->_to;
    }

    /**
     * Set To
     *
     * Set to of communication
     *
     * @param string $value subject of communication
     * @return Kohana_Communication
     */
    public function setTo($value)
    {
        $this->_to = (string) $value;

        return $this;
    }

    /**
     * Get From 
     *
     * Get from of communication
     *
     * @param null
     * @return string $subject subject of communication
     */
    public function getFrom()
    {
        return $this->_from;
    }

    /**
     * Set From
     *
     * Set from of communication
     *
     * @param string $value subject of communication
     * @return Kohana_Communication
     */
    public function setFrom($value)
    {
        $this->_from = (string) $value;

        return $this;
    }

    /**
     * Get Subject
     *
     * Get subject of communication
     *
     * @param null
     * @return string $subject subject of communication
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * Set Subject
     *
     * Set subject of communication
     *
     * @param string $value subject of communication
     * @return Kohana_Communication
     */
    public function setSubject($value)
    {
        $this->_subject = (string) $value;

        return $this;
    }

    /**
     * Get Body
     *
     * Get body of communication
     *
     * @param string $value body of communication
     * @return string
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * Set body of communication
     *
     * @param string $value
     * @return Kohana_Communication
     */
    public function setBody($value)
    {
        $this->_body = (string) $value;

        return $this;
    }

    /**
     * Get Date
     *
     * Get date of communication
     *
     * @param null
     * @return string $date date of communication
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * Set Date
     *
     * Set date for communication
     *
     * @param string $value date for communication
     * @return Kohana_Communication
     */
    public function setDate($value)
    {
        $this->_date = (string) $value;

        return $this;
    }

    /**
     * Get Ctype
     *
     * Get ctype for communication
     *
     * @param null
     * @return string $ctype ctype name
     */
    public function getCtype()
    {
        return $this->_ctype;
    }

    /**
     * Set Ctype
     *
     * Set ctype for communication
     *
     * @param string $value ctype for communication
     * @return Kohana_Communication
     */
    public function setCtype($value)
    {
        $this->_ctype = (string) $value;

        return $this;
    }

}

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

/**
 * Big Yellow
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Company
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 * @copyright   Copyright (c) 2010 Big Yellow Technologies, LLC
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Rob Riggen <rob@bigyellowtech.com>
 */
class Kohana_Company
{
    /**
     * int $_id id of company
     */
    private $_id;

    /**
     * string $_name first name of company
     */
    private $_name;

    /**
     * string $_lastName last name of company
     */
    private $_lastName;

    /**
     * string $_title title of company
     */
    private $_title;


    /**
     * string $_company company for company
     */
    private $_company;

    /**
     * string $_notes notes for company
     */
    private $_notes;


    /**
     * array $_ locations for a company
     */
    private $_locations;



    /**
     * array $_ locations for a company
     */
    private $_tags;


    /**
     * constructor
     *
     * @param int $id optional id of company
     */ 
    public function __construct($id = null)
    {
        if (!empty($id)) {
            $this->setId($id);
        }
    }


    /**
     * Get Id
     *
     * Get id of company
     *
     * @param null
     * @return int $id id of company
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set Id
     *
     * Set id for company
     *
     * @param int $value id of company
     * @return Kohana_Company
     */
    public function setId($value)
    {
        $this->_id = (integer) $value;

        return $this;
    }



    /**
     * Get Locations
     *
     * Get locations for a company
     *
     * @param null
     * @return array $locations of company
     */
    public function getLocations()
    {
        return $this->_locations;
    }

    /**
     * Set Locations
     *
     * Set locations for company
     *
     * @param array $value locations of company 
     */
    public function setLocations($value)
    {
        $this->_locations = (array) $value;

        return $this;
    }





    /**
     * Get Tags
     *
     * Get tags for a company
     *
     * @param null
     * @return array $tags of company
     */
    public function getTags()
    {
        return $this->_tags;
    }

    /**
     * Set Tags
     *
     * Set tags for company
     *
     * @param array $value tags of company 
     */
    public function setTags($value)
    {
        $this->_tags = (array) $value;

        return $this;
    }




    /**
     * Get First Name
     *
     * Get first name of company
     *
     * @param null
     * @return string $firstName first name of company
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set First Name
     *
     * Set first name of company
     *
     * @param string $value first name of company
     * @return Kohana_Company
     */
    public function setFirstName($value)
    {
        $this->_firstName = (string) $value;

        return $this;
    }

    /**
     * Get Last name
     *
     * Get last name of company
     *
     * @param string $value first name of company
     * @return string
     */
    public function getLastName()
    {
        return $this->_lastName;
    }

    /**
     * Set last name of company
     *
     * @param string $value
     * @return Kohana_Company
     */
    public function setLastName($value)
    {
        $this->_lastName = (string) $value;

        return $this;
    }

    /**
     * Get Title
     *
     * Get title of company
     *
     * @param null
     * @return string $title title of company
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Set Title
     *
     * Set title for company
     *
     * @param string $value title for company
     * @return Kohana_Company
     */
    public function setTitle($value)
    {
        $this->_title = (string) $value;

        return $this;
    }

    /**
     * Get Company
     *
     * Get company for company
     *
     * @param null
     * @return string $company company name
     */
    public function getCompany()
    {
        return $this->_company;
    }

    /**
     * Set Company
     *
     * Set company for company
     *
     * @param string $value company for company
     * @return Kohana_Company
     */
    public function setCompany($value)
    {
        $this->_company = (string) $value;

        return $this;
    }

    /**
     * Get Notes
     *
     * Get notes for company
     *
     * @param null
     * @return string $notes notes for company
     */
    public function getNotes()
    {
        return $this->_notes;
    }

    /**
     * Set Notes
     *
     * Set notes for company
     * 
     * @param string $value notes for company
     * @return Kohana_Company
     */
    public function setNotes($value)
    {
        $this->_notes = (string) $value;

        return $this;
    }

}

/**
 * Big Yellow
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Company Service
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */
class Kohana_CompanyService extends Kohana_Batchbookbase
{

    /**
     * Construct new Company Service
     *
     * @param void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create Company From XML
     *
     * @param SimpleXMLElement $xmlElement
     * @return Kohana_Company
     */
    private function _populateCompanyFromXmlElement(
        SimpleXMLElement $xmlElement,
        Kohana_Company $company = null
    )
    {
        if (null === $company) {
            $company = new Kohana_Company();
        }
        $company
            ->setId($xmlElement->id)
            ->setFirstName($xmlElement->first_name)
            ->setLastName($xmlElement->last_name)
            ->setTitle($xmlElement->title)
            ->setCompany($xmlElement->company)
            ->setNotes($xmlElement->notes) 
        ;


        $locations =  array(); 
        $tags =  array(); 

        

        foreach( $xmlElement->tags->tag as $xmlTag ) { 
            $tag = new Kohana_Tag();
            $tag->setName( $xmlTag['name'] )
                  ; 

            array_push( $tags,$tag); 
        } 
 
        foreach( $xmlElement->locations->location as $xmlLocation ) {
            

            $location = new Kohana_Location();
            $location
                     ->setId( $xmlLocation->id )
                     ->setLabel( $xmlLocation->label )
                     ->setEmail( $xmlLocation->email )
                     ->setWebsite( $xmlLocation->website )
                     ->setPhone( $xmlLocation->phone )
                     ->setCell( $xmlLocation->cell )
                     ->setFax( $xmlLocation->fax )
                     ->setStreet1( $xmlLocation->street_1 )
                     ->setStreet2( $xmlLocation->street_2 )
                     ->setCity( $xmlLocation->city )
                     ->setState( $xmlLocation->state )
                     ->setPostalCode( $xmlLocation->postal_code )
                     ->setCountry( $xmlLocation->country )
            ; 

            array_push( $locations,$location); 
        } 
        
        $company->setLocations( $locations );                 
        $company->setTags( $tags );                 

         return $company;
    }

    /**
     * Index Of Companys
     *
     * @param string $name
     * @param string $email
     * @param integer $offset
     * @param integer $limit
     * @return array
     */
    public function indexOfCompanies($name = null, $email= null, $offset = null, $limit = null)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/companies.xml'
        );
        if (null !== $name) {
            $httpClient->setParameterGet('name', $name);
        }
        if (null !== $email) {
            $httpClient->setParameterGet('email', $email);
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
        $companys = array();
        foreach ($xmlResponse->company as $companyElement) {
            $companys[] = $this->_populateCompanyFromXmlElement($companyElement);
        }
        return $companys;
    }

    /**
     * Get Company
     *
     * @param integer $id
     * @return Kohana_Company
     */
    public function getCompany($id)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/companies/' . $id . '.xml'
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
                throw new Exception('Could not get Company.');
        } 
        $xmlResponse = simplexml_load_string($response->getBody());
        return $this->_populateCompanyFromXmlElement($xmlResponse);
    }

    /**
     * Post Company
     *
     * @param Kohana_Company $company
     * @return Kohana_CompanyService   Provides a fluent interface
     */
    public function postCompany(Kohana_Company $company)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/companies.xml'
        );
        $httpClient->setParameterPost(
            'company[first_name]',
            $company->getFirstName()
        );
        $httpClient->setParameterPost(
            'company[last_name]',
            $company->getLastName()
        );
        $httpClient->setParameterPost(
            'company[title]',
            $company->getTitle()
        );
        $httpClient->setParameterPost(
            'company[company]',
            $company->getCompany()
        );
        $httpClient->setParameterPost(
            'company[notes]',
            $company->getNotes()
        );


        $companyLocations = $company->getLocations();


        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::POST);
        if (201 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('Company not created.');
        }


        $location = $response->getHeader('location');
        $httpClient = new Zend_Http_Client($location);
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $this->_populateCompanyFromXmlElement($xmlResponse, $company);

        if( $companyLocations != null ) {
            $this->postLocationsOnCompany($company,$companyLocations ); 
        }

        return $this;
    }


    /**
     * Post Locations on a Company
     *
     * @param Kohana_Company $company
     * @param array $locations
     * @return 
     */
    
    public function postLocationsOnCompany(Kohana_Company $company,array $locations) {
        //If there is a location set on this company, then add it
        if( $locations != null ) { 

            $companyIdAsStr = strval($company->getId());

            $httpClient = new Zend_Http_Client(
                'https://' . $this->_account . '.batchbook.com/service/companies/' . $companyIdAsStr . '/locations.xml'
            ); 


            $httpClient->setAuth($this->_token, 'x'); 

            foreach( $locations as $aLocation ) { 

                $httpClient->setParameterPost(
                    'location[label]',
                    $aLocation->getLabel()
                );

                $httpClient->setParameterPost(
                    'location[email]',
                    $aLocation->getEmail()
                );

                $httpClient->setParameterPost(
                    'location[website]',
                    $aLocation->getWebsite()
                );

                $httpClient->setParameterPost(
                    'location[phone]',
                    $aLocation->getPhone()
                );

                $httpClient->setParameterPost(
                    'location[cell]',
                    $aLocation->getCell()
                );

                $httpClient->setParameterPost(
                    'location[fax]',
                    $aLocation->getFax()
                );

                $httpClient->setParameterPost(
                    'location[street_1]',
                    $aLocation->getStreet1()
                );

                $httpClient->setParameterPost(
                    'location[street_2]',
                    $aLocation->getStreet2()
                );

                $httpClient->setParameterPost(
                    'location[city]',
                    $aLocation->getCity()
                );

                $httpClient->setParameterPost(
                    'location[state]',
                    $aLocation->getState()
                );

                $httpClient->setParameterPost(
                    'location[postal_code]',
                    $aLocation->getPostalCode()
                );


                $httpClient->setParameterPost(
                    'location[country]',
                    $aLocation->getCountry()
                ); 

                $response = $httpClient->request(Zend_Http_Client::POST); 


                if (201 != $response->getStatus()) {
                    //TODO: throw more specific exception 
                    throw new Exception('Location on Company not updated.');
                } 
            } 
        } 
    }

    /**
     * Put Company
     *
     * @param Kohana_Company $company
     * @return Kohana_CompanyService   Provides a fluent interface */
    public function putCompany(Kohana_Company $company)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/companies/' . $company->getId() . '.xml'
        );
        $paramsPut = array(
            'company[first_name]'    => $company->getFirstName(),
            'company[last_name]'     => $company->getLastName(),
            'company[title]'         => $company->getTitle(),
            'company[company]'       => $company->getCompany(),
            'company[notes]'         => $company->getNotes(), 
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
            throw new Exception('Company not updated.');
        }


        //update the locations
        $this->postLocationsOnCompany($company,$company->getLocations() ); 

        return $this;
    }

    /**
     * Delete Company
     *
     * @param Kohana_Company $company
     * @return Kohana_CompanyService   Provides a fluent interface
     */
    public function deleteCompany(Kohana_Company $company)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/companies/' . $company->getId() . '.xml'
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::DELETE);
        if (200 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('Company not deleted.');
        }
        return $this;
    }



    /**
     * Add Super Tag
     * 
     * NOTE: Super Tags cannot be created via the API, so they need to be created via the HTML interface before you apply them 
     *
     * @param Kohana_Company $company
     * @param string $tag
     */ 
    public function addSuperTag(Kohana_Company $company,Kohana_SuperTag $tag) {

        $realTagName = str_replace( ' ', '_',strtolower($tag->getName() ) );
        $reqUrl = 'https://' . $this->_account . '.batchbook.com/service/companies/' . $company->getId() . '/super_tags/' . $realTagName . '.xml';
        error_log( 'requrl:' . $reqUrl );


        $httpClient = new Zend_Http_Client(
            $reqUrl 
        );


        $paramsPut = array();

        $fields = $tag->getFields();

        foreach( $fields as $key => $value ) { 
           
            //keys must be lower case and have spaces replaced with underscore 
            $realKey = str_replace( ' ', '_',strtolower($key) ); 
            $realValue = urlencode( $value ); 

            error_log('realKey:' . $realKey );
            error_log('realValue:' . $realValue );

            $paramsPut['super_tag[' . strtolower($realKey) . ']' ] = $value; 
        };

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
            throw new Exception('SuperTag \'' . $tag->getName() . '\' not added to Company with id=' . $company->getId() . "\n" . $response->getMessage() . "\n" .
            $response->getBody() . "\n" . $httpClient->getLastRequest() );
        } 

    } 


    /**
     * Add Tag
     *
     * @param Kohana_Company $company
     * @param string $tag
     */ 
    public function addTag(Kohana_Company $company,Kohana_Tag $tag)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/companies/' . $company->getId() . '/add_tag.xml'
        );
        $paramsPut = array(
            'tag'    => $tag->getName(), 
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
            throw new Exception('Tag not added to company with id=' . $company->getId() );
        } 
    }



}

/**
 * 
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Person
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook 
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Chris Kohlhardt <chrisk@gliffy.com>
 */



class Kohana_Deal
{

    /**
     * int $_id id of deal
     */ 
    private $_id;

    /**
     * string $_title of deal
     */ 
    private $_title;

    /**
     * string $_description of deal
     */ 
    private $_description;


    /**
     * float $_amount of deal
     */ 
    private $_amount;

    /**
     * string $_status of deal
     */ 
    private $_status;

    /**
     * constructor
     *
     * @param int $id optional id of deal
     */ 
    public function __construct($id = null)
    {
        if (!empty($id)) {
            $this->setId($id);
        }
    }

    /**
     * Get Id
     *
     * Get id of deal
     *
     * @param null
     * @return int $id id of deal
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set Id
     *
     * Set id for deal
     *
     * @param int $value id of deal
     * @return Kohana_Deal
     */
    public function setId($value)
    {
        $this->_id = (integer) $value;

        return $this;
    }


    /**
     * Get title
     *
     * Get title of deal
     *
     * @param null
     * @return string $title  of deal
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Set Title
     *
     * Set title for deal
     *
     * @param string $value title of deal
     * @return Kohana_Deal
     */
    public function setTitle($value)
    {
        $this->_title = (string) $value;

        return $this;
    }


    /**
     * Get description
     *
     * Get description of deal
     *
     * @param null
     * @return string $description  of deal
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Set Description
     *
     * Set description for deal
     *
     * @param string $value description of deal
     * @return Kohana_Deal
     */
    public function setDescription($value)
    {
        $this->_description = (string) $value;

        return $this;

    }

    /**
     * Get status
     *
     * Get status of deal
     *
     * @param null
     * @return string $status  of deal
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * Set Status
     *
     * Set status for deal
     *
     * @param string $value status of deal
     * @return Kohana_Deal
     */
    public function setStatus($value)
    {
        $this->_status = (string) $value;

        return $this;
    } 

    /**
     * Get amount
     *
     * Get amount of deal
     *
     * @param null
     * @return string $amount  of deal
     */
    public function getAmount()
    {
        return $this->_amount;
    }

    /**
     * Set Amount
     *
     * Set amount for deal
     *
     * @param string $value amount of deal
     * @return Kohana_Deal
     */
    public function setAmount($value)
    {
        $this->_amount = (float) $value;

        return $this;
    } 



}

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



/**
 * 
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Location
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook 
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Chris Kohlhardt <chrisk@gliffy.com>
 */



class Kohana_Location {



    /**
     * integer $_id of location
     */ 
    private $_id;


    /**
     * string $_label of location
     */ 
    private $_label;


    /**
     * string $_email of location
     */ 
    private $_email;


    /**
     * string $_website of location
     */ 
    private $_website;

    /**
     * string $_phone of location
     */ 
    private $_phone;

    /**
     * string $_cell of location
     */ 
    private $_cell;

    /**
     * string $_fax of location
     */ 
    private $_fax;

    /**
     * string $_street_1 of location
     */ 
    private $_street_1;

    /**
     * string $_street_2 of location
     */ 
    private $_street_2;

    /**
     * string $_city of location
     */ 
    private $_city;

    /**
     * string $_state of location
     */ 
    private $_state;

    /**
     * string $_postal_code of location
     */ 
    private $_postalCode;

    /**
     * string $_country of location
     */ 
    private $_country;


    /**
     * constructor
     *
     * If no label is specified, default to 'work'
     *
     * @param int $id optional id of deal
     */ 
    public function __construct($label = null)
    {
        if ( empty($label) ) {
            $this->setLabel('work');
        } 
    }


    public function getId()
    {
        return $this->_id;
    }

    public function setId($value)
    {
        $this->_id = (integer) $value;

        return $this;
    }



    public function getLabel()
    {
        return $this->_label;
    }

    public function setLabel($value)
    {
        $this->_label = (string) $value;

        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setEmail($value)
    {
        $this->_email = (string) $value;

        return $this;
    }



    public function getWebsite()
    {
        return $this->_website;
    }

    public function setWebsite($value)
    {
        $this->_website = (string) $value;

        return $this;
    }

    public function getPhone()
    {
        return $this->_phone;
    }

    public function setPhone($value)
    {
        $this->_phone = (string) $value;

        return $this;
    }

    public function getCell()
    {
        return $this->_cell;
    }

    public function setCell($value)
    {
        $this->_cell = (string) $value;

        return $this;
    }

    public function getFax()
    {
        return $this->_fax;
    }

    public function setFax($value)
    {
        $this->_fax = (string) $value;

        return $this;
    }


    public function getStreet1()
    {
        return $this->_street_1;
    }

    public function setStreet1($value)
    {
        $this->_street_1 = (string) $value;

        return $this;
    }




    public function getStreet2()
    {
        return $this->_street_2;
    }

    public function setStreet2($value)
    {
        $this->_street_2 = (string) $value;

        return $this;
    }



    public function getCity()
    {
        return $this->_city;
    }

    public function setCity($value)
    {
        $this->_city = (string) $value;

        return $this;
    }




    public function getState()
    {
        return $this->_state;
    }

    public function setState($value)
    {
        $this->_state = (string) $value;

        return $this;
    }



    public function getPostalCode()
    {
        return $this->_postalCode;
    }

    public function setPostalCode($value)
    {
        $this->_postalCode = (string) $value;

        return $this;
    }


    public function getCountry()
    {
        return $this->_country;
    }

    public function setCountry($value)
    {
        $this->_country = (string) $value;

        return $this;
    } 


}


/**
 * Big Yellow
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Person
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 * @copyright   Copyright (c) 2010 Big Yellow Technologies, LLC
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Rob Riggen <rob@bigyellowtech.com>
 */
class Kohana_Person
{
    /**
     * int $_id id of person
     */
    private $_id;

    /**
     * string $_firstName first name of person
     */
    private $_firstName;

    /**
     * string $_lastName last name of person
     */
    private $_lastName;

    /**
     * string $_title title of person
     */
    private $_title;


    /**
     * string $_company company for person
     */
    private $_company;

    /**
     * string $_notes notes for person
     */
    private $_notes;


    /**
     * array $_ locations for a person
     */
    private $_locations;


    /**
     * array $_ locations for a person
     */
    private $_tags;


    /**
     * constructor
     *
     * @param int $id optional id of person
     */ 
    public function __construct($id = null)
    {
        if (!empty($id)) {
            $this->setId($id);
        }
    }

    /**
     * Get Id
     *
     * Get id of person
     *
     * @param null
     * @return int $id id of person
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set Id
     *
     * Set id for person
     *
     * @param int $value id of person
     * @return Kohana_Person
     */
    public function setId($value)
    {
        $this->_id = (integer) $value;

        return $this;
    }



    /**
     * Get Locations
     *
     * Get locations for a person
     *
     * @param null
     * @return array $locations of person
     */
    public function getLocations()
    {
        return $this->_locations;
    }

    /**
     * Set Locations
     *
     * Set locations for person
     *
     * @param array $value locations of person 
     */
    public function setLocations($value)
    {
        $this->_locations = (array) $value;

        return $this;
    }

    /**
     * Get Tags
     *
     * Get tags for a person
     *
     * @param null
     * @return array $tags of person
     */
    public function getTags()
    {
        return $this->_tags;
    }

    /**
     * Set Tags
     *
     * Set tags for person
     *
     * @param array $value tags of person 
     */
    public function setTags($value)
    {
        $this->_tags = (array) $value;

        return $this;
    }




    /**
     * Get First Name
     *
     * Get first name of person
     *
     * @param null
     * @return string $firstName first name of person
     */
    public function getFirstName()
    {
        return $this->_firstName;
    }

    /**
     * Set First Name
     *
     * Set first name of person
     *
     * @param string $value first name of person
     * @return Kohana_Person
     */
    public function setFirstName($value)
    {
        $this->_firstName = (string) $value;

        return $this;
    }

    /**
     * Get Last name
     *
     * Get last name of person
     *
     * @param string $value first name of person
     * @return string
     */
    public function getLastName()
    {
        return $this->_lastName;
    }

    /**
     * Set last name of person
     *
     * @param string $value
     * @return Kohana_Person
     */
    public function setLastName($value)
    {
        $this->_lastName = (string) $value;

        return $this;
    }

    /**
     * Get Title
     *
     * Get title of person
     *
     * @param null
     * @return string $title title of person
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Set Title
     *
     * Set title for person
     *
     * @param string $value title for person
     * @return Kohana_Person
     */
    public function setTitle($value)
    {
        $this->_title = (string) $value;

        return $this;
    }

    /**
     * Get Company
     *
     * Get company for person
     *
     * @param null
     * @return string $company company name
     */
    public function getCompany()
    {
        return $this->_company;
    }

    /**
     * Set Company
     *
     * Set company for person
     *
     * @param string $value company for person
     * @return Kohana_Person
     */
    public function setCompany($value)
    {
        $this->_company = (string) $value;

        return $this;
    }

    /**
     * Get Notes
     *
     * Get notes for person
     *
     * @param null
     * @return string $notes notes for person
     */
    public function getNotes()
    {
        return $this->_notes;
    }

    /**
     * Set Notes
     *
     * Set notes for person
     * 
     * @param string $value notes for person
     * @return Kohana_Person
     */
    public function setNotes($value)
    {
        $this->_notes = (string) $value;

        return $this;
    }

}

/**
 * Big Yellow
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Person Service
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */
class Kohana_PersonService extends Kohana_Batchbookbase
{

    /**
     * Construct new Person Service
     *
     * @param void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create Person From XML
     *
     * @param SimpleXMLElement $xmlElement
     * @return Kohana_Person
     */
    private function _populatePersonFromXmlElement(
        SimpleXMLElement $xmlElement,
        Kohana_Person $person = null
    )
    {
        if (null === $person) {
            $person = new Kohana_Person();
        }
        $person
            ->setId($xmlElement->id)
            ->setFirstName($xmlElement->first_name)
            ->setLastName($xmlElement->last_name)
            ->setTitle($xmlElement->title)
            ->setCompany($xmlElement->company)
            ->setNotes($xmlElement->notes) 
        ;


        $locations =  array(); 
        $tags =  array(); 

        

        foreach( $xmlElement->tags->tag as $xmlTag ) { 
            $tag = new Kohana_Tag();
            $tag->setName( $xmlTag['name'] )
                  ; 

            array_push( $tags,$tag); 
        } 
 
        foreach( $xmlElement->locations->location as $xmlLocation ) {
            

            $location = new Kohana_Location();
            $location
                     ->setId( $xmlLocation->id )
                     ->setLabel( $xmlLocation->label )
                     ->setEmail( $xmlLocation->email )
                     ->setWebsite( $xmlLocation->website )
                     ->setPhone( $xmlLocation->phone )
                     ->setCell( $xmlLocation->cell )
                     ->setFax( $xmlLocation->fax )
                     ->setStreet1( $xmlLocation->street_1 )
                     ->setStreet2( $xmlLocation->street_2 )
                     ->setCity( $xmlLocation->city )
                     ->setState( $xmlLocation->state )
                     ->setPostalCode( $xmlLocation->postal_code )
                     ->setCountry( $xmlLocation->country )
            ; 

            array_push( $locations,$location); 
        } 
        
        $person->setLocations( $locations );                 
        $person->setTags( $tags );                 

         return $person;
    }

    /**
     * Index Of Persons
     *
     * @param string $name
     * @param string $email
     * @param integer $offset
     * @param integer $limit
     * @return array
     */
    public function indexOfPersons($name = null, $email= null, $offset = null, $limit = null)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/people.xml'
        );
        if (null !== $name) {
            $httpClient->setParameterGet('name', $name);
        }
        if (null !== $email) {
            $httpClient->setParameterGet('email', $email);
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
        $persons = array();
        foreach ($xmlResponse->person as $personElement) {
            $persons[] = $this->_populatePersonFromXmlElement($personElement);
        }
        return $persons;
    }

    /**
     * Get Person
     *
     * @param integer $id
     * @return Kohana_Person
     */
    public function getPerson($id)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/people/' . $id . '.xml'
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
                throw new Exception('Could not get Person.');
        } 
        $xmlResponse = simplexml_load_string($response->getBody());
        return $this->_populatePersonFromXmlElement($xmlResponse);
    }

    /**
     * Post Person
     *
     * @param Kohana_Person $person
     * @return Kohana_PersonService   Provides a fluent interface
     */
    public function postPerson(Kohana_Person $person)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/people.xml'
        );
        $httpClient->setParameterPost(
            'person[first_name]',
            $person->getFirstName()
        );
        $httpClient->setParameterPost(
            'person[last_name]',
            $person->getLastName()
        );
        $httpClient->setParameterPost(
            'person[title]',
            $person->getTitle()
        );
        $httpClient->setParameterPost(
            'person[company]',
            $person->getCompany()
        );
        $httpClient->setParameterPost(
            'person[notes]',
            $person->getNotes()
        );


        $personLocations = $person->getLocations();


        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::POST);
        if (201 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('Person not created.');
        }


        $location = $response->getHeader('location');
        $httpClient = new Zend_Http_Client($location);
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $this->_populatePersonFromXmlElement($xmlResponse, $person);

        if( $personLocations != null ) {
            $this->postLocationsOnPerson($person,$personLocations ); 
        }

        return $this;
    }


    /**
     * Post Locations on a Person
     *
     * @param Kohana_Person $person
     * @param array $locations
     * @return 
     */
    
    public function postLocationsOnPerson(Kohana_Person $person,array $locations) {
        //If there is a location set on this person, then add it
        if( $locations != null ) { 

            $personIdAsStr = strval($person->getId());

            $httpClient = new Zend_Http_Client(
                'https://' . $this->_account . '.batchbook.com/service/people/' . $personIdAsStr . '/locations.xml'
            ); 


            $httpClient->setAuth($this->_token, 'x'); 

            foreach( $locations as $aLocation ) { 

                $httpClient->setParameterPost(
                    'location[label]',
                    $aLocation->getLabel()
                );

                $httpClient->setParameterPost(
                    'location[email]',
                    $aLocation->getEmail()
                );

                $httpClient->setParameterPost(
                    'location[website]',
                    $aLocation->getWebsite()
                );

                $httpClient->setParameterPost(
                    'location[phone]',
                    $aLocation->getPhone()
                );

                $httpClient->setParameterPost(
                    'location[cell]',
                    $aLocation->getCell()
                );

                $httpClient->setParameterPost(
                    'location[fax]',
                    $aLocation->getFax()
                );

                $httpClient->setParameterPost(
                    'location[street_1]',
                    $aLocation->getStreet1()
                );

                $httpClient->setParameterPost(
                    'location[street_2]',
                    $aLocation->getStreet2()
                );

                $httpClient->setParameterPost(
                    'location[city]',
                    $aLocation->getCity()
                );

                $httpClient->setParameterPost(
                    'location[state]',
                    $aLocation->getState()
                );

                $httpClient->setParameterPost(
                    'location[postal_code]',
                    $aLocation->getPostalCode()
                );


                $httpClient->setParameterPost(
                    'location[country]',
                    $aLocation->getCountry()
                ); 

                $response = $httpClient->request(Zend_Http_Client::POST); 


                if (201 != $response->getStatus()) {
                    //TODO: throw more specific exception 
                    throw new Exception('Location on Person not updated.');
                } 
            } 
        } 
    }

    /**
     * Put Person
     *
     * @param Kohana_Person $person
     * @return Kohana_PersonService   Provides a fluent interface */
    public function putPerson(Kohana_Person $person)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/people/' . $person->getId() . '.xml'
        );
        $paramsPut = array(
            'person[first_name]'    => $person->getFirstName(),
            'person[last_name]'     => $person->getLastName(),
            'person[title]'         => $person->getTitle(),
            'person[company]'       => $person->getCompany(),
            'person[notes]'         => $person->getNotes(), 
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
            //echo '<pre>'; print_r($response); die();
            throw new Exception('Person not updated.');
        }


        //update the locations
        $this->postLocationsOnPerson($person,$person->getLocations() ); 

        return $this;
    }

    /**
     * Delete Person
     *
     * @param Kohana_Person $person
     * @return Kohana_PersonService   Provides a fluent interface
     */
    public function deletePerson(Kohana_Person $person)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/people/' . $person->getId() . '.xml'
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::DELETE);
        if (200 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('Person not deleted.');
        }
        return $this;
    }



    /**
     * Add Super Tag
     * 
     * NOTE: Super Tags cannot be created via the API, so they need to be created via the HTML interface before you apply them 
     *
     * @param Kohana_Person $person
     * @param string $tag
     */ 
    public function addSuperTag(Kohana_Person $person,Kohana_SuperTag $tag) {

        $realTagName = str_replace( ' ', '_',strtolower($tag->getName() ) );
        $reqUrl = 'https://' . $this->_account . '.batchbook.com/service/people/' . $person->getId() . '/super_tags/' . $realTagName . '.xml';
        error_log( 'requrl:' . $reqUrl );


        $httpClient = new Zend_Http_Client(
            $reqUrl 
        );


        $paramsPut = array();

        $fields = $tag->getFields();

        foreach( $fields as $key => $value ) { 
           
            //keys must be lower case and have spaces replaced with underscore 
            $realKey = str_replace( ' ', '_',strtolower($key) ); 
            $realValue = urlencode( $value ); 

            error_log('realKey:' . $realKey );
            error_log('realValue:' . $realValue );

            $paramsPut['super_tag[' . strtolower($realKey) . ']' ] = $value; 
        };

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
            throw new Exception('SuperTag \'' . $tag->getName() . '\' not added to Person with id=' . $person->getId() . "\n" . $response->getMessage() . "\n" .
            $response->getBody() . "\n" . $httpClient->getLastRequest() );
        } 

    } 


    /**
     * Add Tag
     *
     * @param Kohana_Person $person
     * @param string $tag
     */ 
    public function addTag(Kohana_Person $person,Kohana_Tag $tag)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_account . '.batchbook.com/service/people/' . $person->getId() . '/add_tag.xml'
        );
        $paramsPut = array(
            'tag'    => $tag->getName(), 
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
            throw new Exception('Tag not added to person with id=' . $person->getId() );
        } 
    }



}


/**
 * 
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Tag
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook 
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Chris Kohlhardt <chrisk@gliffy.com>
 */



class Kohana_SuperTag extends  Kohana_Tag 
{


    /**
     * string $_id of location
     */ 
    private $_fields;



    /**
     * Get SuperTag fields
     * 
     *
     * @param null
     * @return array $fields tag
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * Set SuperTag fields
     * 
     *
     * @param array $value supertag fields
     * @return Kohana_SuperTag
     */
    public function setFields($value)
    {
        $this->_fields = (array) $value;

        return $this;
    } 

}



/**
 * 
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Tag
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook 
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Chris Kohlhardt <chrisk@gliffy.com>
 */



class Kohana_Tag
{



    /**
     * string $_id of location
     */ 
    private $_name;



    /**
     * Get Tag Name
     * 
     *
     * @param null
     * @return string $name of tag
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set Tag Name
     * 
     *
     * @param string $value name of tag
     * @return Kohana_Tag
     */
    public function setName($value)
    {
        $this->_name = (string) $value;

        return $this;
    } 

}




/**
 * 
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Tag
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook 
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Chris Kohlhardt <chrisk@gliffy.com>
 */



class Kohana_ToDo
{ 


    /**
     * string $_id of todo 
     */ 
    private $_id; 


    /**
     * string $_title of todo 
     */ 
    private $_title;


    /**
     * string $_description of todo 
     */ 
    private $_description;


    /**
     * string $_due_date of todo 
     */ 
    private $_due_date;

    /**
     * string $_flagged of todo 
     */ 
    private $_flagged;

    /**
     * string $_complete of todo 
     */ 
    private $_complete;


    /**
     * constructor
     *
     * @param int $id optional id of deal
     */ 
    public function __construct($id = null)
    {
        if (!empty($id)) {
            $this->setId($id);
        }
    }

    /**
     * Get Id
     *
     * Get id of deal
     *
     * @param null
     * @return int $id id of deal
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set Id
     *
     * Set id for deal
     *
     * @param int $value id of deal
     * @return Kohana_Deal
     */
    public function setId($value)
    {
        $this->_id = (integer) $value;

        return $this;
    }




    /**
     * Get Title 
     * 
     *
     * @param null
     * @return string $title of todo
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Set Title 
     * 
     *
     * @param string $value title of todo
     * @return Kohana_ToDo
     */
    public function setTitle($value)
    {
        $this->_title = (string) $value;

        return $this;
    } 


    /**
     * Get Description 
     * 
     *
     * @param null
     * @return string $description of todo
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Set Description 
     * 
     *
     * @param string $value description of todo
     * @return Kohana_ToDo
     */
    public function setDescription($value)
    {
        $this->_description = (string) $value;

        return $this;
    } 


    /**
     * Get DueDate 
     * 
     *
     * @param null
     * @return string $due_date of todo
     */
    public function getDueDate()
    {
        return $this->_due_date;
    }

    /**
     * Set DueDate 
     * 
     *
     * @param string $value due_date of todo
     * @return Kohana_ToDo
     */
    public function setDueDate($value)
    {
        $this->_due_date = (string) $value;

        return $this;
    } 

    /**
     * Get Flagged 
     * 
     *
     * @param null
     * @return string $flagged of todo
     */
    public function getFlagged()
    {
        return $this->_flagged;
    }

    /**
     * Set Flagged 
     * 
     *
     * @param string $value flagged of todo
     * @return Kohana_ToDo
     */
    public function setFlagged($value)
    {
        $this->_flagged = (boolean) $value;

        return $this;
    } 


    /**
     * Get Complete 
     * 
     *
     * @param null
     * @return string $complete of todo
     */
    public function getComplete()
    {
        return $this->_complete;
    }

    /**
     * Set Complete 
     * 
     *
     * @param string $value complete of todo
     * @return Kohana_ToDo
     */
    public function setComplete($value)
    {
        $this->_complete = (boolean) $value;

        return $this;
    } 


}

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

