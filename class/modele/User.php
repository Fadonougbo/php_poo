<?php 
namespace Utils\modele;
use Interfaces\Users;

class User implements Users
{

    public $id;
    public $username;
    public $password;

    public function getUserName():string 
    {
        return $this->username;
    }

    public function getRoles():array
    {
        return [];
    }
}


?>