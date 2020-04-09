<?php
namespace models;

use \core\Model;


/**
 * Responsible for manipulating the database ads_category table.
 */
class Category extends Model 
{
    //-----------------------------------------------------------------------
    //        Constructor
    //-----------------------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    
    //-----------------------------------------------------------------------
    //        Methods
    //-----------------------------------------------------------------------
    /**
     * Returns all registered categories.
     * 
     * @return array All registered categories or null if there are none
     */
    public function getCategories()
    {
        $sql = $this->db->query('SELECT * FROM categories');
        $sql = $sql->fetchAll();
        
        return $sql;
    }
}