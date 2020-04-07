<?php
namespace models;

use \core\Model;


class Category extends Model {
    public function getCategories(){
        $sql = $this->db->query('SELECT * FROM categories');
        $sql = $sql->fetchAll();
        return $sql;
    }
}