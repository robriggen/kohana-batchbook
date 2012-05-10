<?php
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
