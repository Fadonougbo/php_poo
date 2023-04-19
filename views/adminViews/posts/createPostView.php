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

    <form action="" method="POST">

        <?php if (!empty($invalide_fields_message)): ?>

            <h1><?= $invalide_fields_message; ?></h1>
            
        <?php endif ?>
        
        <div>
            <section>
                <label for="name">Ajouter un article</label>
                <input type="text" name="name" id="name" placeholder="name" value="<?= isset($_POST["name"])?htmlentities($_POST["name"]):""; ?>" >
            </section>
            
            <section>
                <?php if(is_array($invalide_fields) ): ?>
                    <?= $errorMessage->getErrorMessage("name",$invalide_fields) ?>
                <?php endif; ?>
            </section>
        </div>
        <div>
            <label for="slug">Changer le slug</label>
            <input type="text" name="slug" id="slug" placeholder="slug" value="<?= isset($_POST["slug"])?htmlentities($_POST["slug"]):""; ?>" >
            <section>
                <?php if(is_array($invalide_fields) ): ?>
                    <?= $errorMessage->getErrorMessage("slug",$invalide_fields) ?>
                <?php endif; ?>
            </section>
        </div>
        <div>
            <label for="content">Changer le contenu de l'article</label>
            <textarea name="content" id="content" cols="30" rows="10" placeholder="content"><?= isset($_POST["content"])?htmlentities($_POST["content"]):"" ?> </textarea>
            <section>
                <?php if(is_array($invalide_fields) ): ?>
                    <?= $errorMessage->getErrorMessage("content",$invalide_fields) ?>
                <?php endif; ?>
            </section>
        </div>

        <div>
            <select name="categories_lists[]" id="" multiple>
                <?php foreach ($categories as $v): ?>
                    <option value="<?=$v->id; ?>" ><?=$v->name; ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <button type="submit" >Ajouter un article</button>
        </div>

    </form>    
    
</main>

<?= $render->show("footer") ?>