<?php
namespace models;

use \core\Model;


class User extends Model {
    public function register($name, $email, $pass, $phone){
        $sql = $this->db->prepare('SELECT id FROM users WHERE email = :email');
        $sql->bindValue(':email', $email);
        $sql->execute();

        if ($sql->rowCount() == 0){ // Se usuário não foi cadastrado, cadastra ele
            $sql = $this->db->prepare('INSERT INTO users SET name = :name, email = :email, pass = :pass, phone = :phone');
            $sql->bindValue(':name', $name);
            $sql->bindValue(':pass', md5($pass));
            $sql->bindValue(':email', $email);
            $sql->bindValue(':phone', $phone);
            $sql->execute();

            return true;
        }
        else{
            return false;
        }
    }

    public function login($email, $pass){
        $sql = $this->db->prepare('SELECT id FROM users WHERE email = :email AND pass = :pass');
        $sql->bindValue(':email', $email);
        $sql->bindValue(':pass', md5($pass));
        $sql->execute();

        if ($sql->rowCount() != 0){
            // Salva sessão
            $sql = $sql->fetch();
            $_SESSION['userID'] = $sql['id'];

            return true;
        } else{
            return false;
        }
    }

    public function getName($id){
        $sql = $this->db->query("SELECT name FROM users WHERE id = '$id'");

        if ($sql->rowCount() == 0)
            return false;
        else
            return $sql->fetch()['name'];
    }

    public function getPhone($id){
        $sql = $this->db->query("SELECT phone FROM users WHERE id = '$id'");

        if ($sql->rowCount() == 0)
            return false;
        else
            return $sql->fetch()['phone'];
    }

    public function countUsers(){
        $sql = $this->db->query("SELECT id FROM users");
        return $sql->rowCount();
    }
}
