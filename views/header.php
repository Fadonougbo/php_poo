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
        </nav>
    </header>