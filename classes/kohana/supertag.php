
<?php
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

