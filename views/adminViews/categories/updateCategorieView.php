<?= 
$render->show("header",parameter:[
    "title"=>"Update categorie",
    /*"style"=>"./style/css/home/home.css"*/
])

?>

<?php

use Utils\middlewares\CsrfMiddleware;

$invalide_fields=$validationStatus;
$invalide_fields_message=$session->getSessionFlash("invalideForm");


$csrf=new CsrfMiddleware($session);

$tokenInput=$csrf->getCsrfInput();
?>

<main>
    <div>
        <a href="<?= $router->generate("admin_categories_home") ?>">Admin</a>
    </div>
    <form action="" method="POST">

        <?php if (!empty($invalide_fields_message)): ?>
            <h1><?=  $invalide_fields_message; ?>  </h1>
        <?php endif ?>
        <div>
            <label for="name">Changer le nom de la categorie</label>
            <input type="text" name="name" id="name" value="<?= isset($_POST["name"])?htmlentities($_POST["name"]):htmlentities($post->name); ?>" >
            <section>
                <?php if(is_array($invalide_fields) ): ?>
                    <?= $errorMessage->getErrorMessage("name",$invalide_fields) ?>
                <?php endif; ?>
            </section>
        </div>
        <div>
            <label for="slug">Changer le slug</label>
            <input type="text" name="slug" id="slug" value="<?= isset($_POST["slug"])?htmlentities($_POST["slug"]):htmlentities($post->slug); ?>">
            <section>
                <?php if(is_array($invalide_fields) ): ?>
                    <?= $errorMessage->getErrorMessage("slug",$invalide_fields) ?>
                <?php endif; ?>
            </section>
        </div>
       
        
        <div>
            <button type="submit" >modifier la categorie</button>
            <?= $tokenInput; ?>
        </div>

    </form>    
    
</main>

<?= $render->show("footer") ?>