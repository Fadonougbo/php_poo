<?php

require "../vendor/autoload.php";


$hash=password_hash("gaut",PASSWORD_ARGON2I,["cost"=>12]);
$verrify=password_verify("gaut",'$argon2i$v=19$m=65536,t=4,p=1$Y21lTW1qbTVCMTNOVzdFWA$QCS2cfULMeD861e/oGnn9bKYLwNtdi/+yI0NrHjhx6Y');

dump($verrify);


?>