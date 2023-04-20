<?php

require "../vendor/autoload.php";

$x=array_reduce(["a","b","c"],function($acc,$el){

	return $acc." ,$el";

}," s");


var_dump($x);
?>