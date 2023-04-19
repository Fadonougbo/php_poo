<?php 

namespace Interfaces;

interface SessionInterface
{
    public function startSession():void;
    
    public function getSession(string $key);

    public function setSession(string $key,$value):bool;

    public function deleteSession(string $key):bool;
}



?>