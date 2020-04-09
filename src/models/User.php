<?php
namespace models;

use \core\Model;


/**
 * Responsible for manipulating the database users table.
 */
class User extends Model 
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
     * Registers a new user in database users table.
     * 
     * @param string $name Name of the user
     * @param string $email Email of the user
     * @param string $pass Password of the user
     * @param string $phone Phone of the user
     * @return boolean If user was successfully registered
     */
    public function register($name, $email, $pass, $phone)
    {
        $sql = $this->db->prepare('SELECT id FROM users WHERE email = :email');
        $sql->bindValue(':email', $email);
        $sql->execute();

        // If user has already been registered, returns
        if ($sql->rowCount() != 0) { return false; }
        
        // If user has not been registered, register it
        $sql = $this->db->prepare('INSERT INTO users SET name = :name, email = :email, pass = :pass, phone = :phone');
        $sql->bindValue(':name', $name);
        $sql->bindValue(':pass', md5($pass));
        $sql->bindValue(':email', $email);
        $sql->bindValue(':phone', $phone);
        $sql->execute();

        return true;
    }

    /**
     * Try to login in the system.
     * 
     * @param string $email Email of the user
     * @param string $pass Password of the user
     * @return boolean If email and pass are corrects
     */
    public function login($email, $pass)
    {
        $sql = $this->db->prepare('SELECT id FROM users WHERE email = :email AND pass = :pass');
        $sql->bindValue(':email', $email);
        $sql->bindValue(':pass', md5($pass));
        $sql->execute();

        if ($sql->rowCount() == 0) { return false; }
        
        // Saves user id in current session
        $sql = $sql->fetch();
        $_SESSION['userID'] = $sql['id'];

        return true;
    }

    /**
     * Returns a user's registered name with the given id.
     * 
     * @param int $id Id of the user
     * @return string Name of the user or empty string if it is not found
     */
    public function getName($id)
    {
        $response = "";
        $sql = $this->db->query("SELECT name FROM users WHERE id = '$id'");

        if ($sql->rowCount() != 0)
            $response = $sql->fetch()['name'];
        
       return $response;
    }

    /**
     * Returns a user's registered phone with the given id.
     * 
     * @param int $id Id of the user
     * @return string Phone of the user or empty string if it is not found
     */
    public function getPhone($id)
    {
        $response = "";
        $sql = $this->db->query("SELECT phone FROM users WHERE id = '$id'");

        if ($sql->rowCount() != 0)
            $response = $sql->fetch()['phone'];
        
        return $response;
    }

    /**
     * Returns total of registered users.
     * 
     * @return int Total of registered users
     */
    public function countUsers()
    {
        $sql = $this->db->query("SELECT id FROM users");
        
        return $sql->rowCount();
    }
}
