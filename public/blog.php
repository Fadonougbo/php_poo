<?php

require "../vendor/autoload.php";


$arr1=["name"=>"gaut","age"=>12,"tel"=>109378];
$arr2=["name","tel"];

$arr1=array_flip($arr1);

$array=array_flip( array_intersect($arr1,$arr2) ) ;


?>