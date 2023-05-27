<?php
namespace Interfaces;

interface Users
{
    public function getUserName():string;

    public function getRoles():array;
}

?>