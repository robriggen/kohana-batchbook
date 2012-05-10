<?php
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
