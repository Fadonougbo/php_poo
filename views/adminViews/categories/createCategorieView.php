<?= 
$render->show("header",parameter:[
    "title"=>"Create",
    /*"style"=>"./style/css/home/home.css"*/
])

?>

<?php 

$invalide_fields=$validationStatus;

$invalide_fields_message=$session->getSessionFlash("invalideForm");


?>

<main>
     <div>
        <a href="<?= $router->generate("admin_categories_home") ?>">Admin</a>
    </div>
    <form action="" method="POST">

        <?php if (!empty($invalide_fields_message)): ?>

            <h1><?= $invalide_fields_message; ?></h1>
            
        <?php endif ?>
        
        <div>
            <section>
                <label for="name">Ajouter une categorie</label>
                <input type="text" name="name" id="name" placeholder="name" value="<?= isset($_POST["name"])?htmlentities($_POST["name"]):""; ?>" >
            </section>
            
            <section>
                <?php if(is_array($invalide_fields) ): ?>
                    <?= $errorMessage->getErrorMessage("name",$invalide_fields) ?>
                <?php endif; ?>
            </section>
        </div>
        <div>
            <label for="slug">Creer un slug</label>
            <input type="text" name="slug" id="slug" placeholder="slug" value="<?= isset($_POST["slug"])?htmlentities($_POST["slug"]):""; ?>" >
            <section>
                <?php if(is_array($invalide_fields) ): ?>
                    <?= $errorMessage->getErrorMessage("slug",$invalide_fields) ?>
                <?php endif; ?>
            </section>
        </div>

        <div>
            <button type="submit" >Enregistré la categorie</button>
        </div>

    </form>    
    
</main>

<?= $render->show("footer") ?>