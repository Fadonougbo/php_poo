<?php

use Utils\session\Session;

$session=new Session();
$user=$session->getSession("userinfo");


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title??"Sans nom"; ?></title>
    <link rel="stylesheet" href="<?= $style; ?>">
</head>
<body>
    <header>
        <nav>
            <a href="<?= $router->generate("blog_home") ?>">Home</a>
            <?php if($user): ?>
                <a href="/admin/categories">Categorie Admin</a>
                <a href="/admin">Article Admin</a>
                <form action="/logout" method="POST">
                    <button type="submit" >deconnection</button>
                </form>
            <?php endif; ?>
        </nav>
    </header>