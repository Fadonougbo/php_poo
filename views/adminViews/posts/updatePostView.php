<?= 
$render->show("header",parameter:[
    "title"=>"Update",
    /*"style"=>"./style/css/home/home.css"*/
])

?>

<?php

use Utils\middlewares\CsrfMiddleware;

$invalide_fields=$validationStatus;
$invalide_fields_message=$session->getSessionFlash("invalideForm");

$date=(new DateTime($post->updated_at))->format("Y-m-d H:i");


$csrf=new CsrfMiddleware($session);

$tokenInput=$csrf->getCsrfInput();

?>

<main>
    <div>
        <a href="<?= $router->generate("admin_home") ?>">Admin</a>
    </div>
    <form action="" method="POST">

        <?php if (!empty($invalide_fields_message)): ?>
            <h1><?=  $invalide_fields_message; ?>  </h1>
        <?php endif ?>
        <div>
            <label for="name">Changer le nom de l'article</label>
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
            <label for="content">Changer le contenu de l'article</label>
            <textarea name="content" id="content" cols="30" rows="10" placeholder="" ><?= isset($_POST["content"])?htmlentities($_POST["content"]):htmlentities($post->content); ?></textarea>
            <section>
                <?php if(is_array($invalide_fields) ): ?>
                    <?= $errorMessage->getErrorMessage("content",$invalide_fields) ?>
                <?php endif; ?>
            </section>
        </div>


        <div>
            <p><strong>categories liées a l'article</strong></p>
                <ul>
                    <?php if (!empty($categories_post)): ?>

                        <?php foreach ($categories_post[$post->id] as $value): ?>
                            <li><?= $value["name"]; ?></li>
                        <?php endforeach ?>

                    <?php else: ?>
                        <li>vide</li>
                    <?php endif ?>  
                    
                </ul>
        </div>

        <div>
            <select name="categories_lists[]" id="" multiple>
                <?php foreach ($categories as $v): ?>
                    <option value="<?=$v->id; ?>" <?=in_array($v->name,$categorieNameList)?"selected":"" ?> ><?=$v->name; ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <label for="date">Changer la date et l'heur de publication</label>
            <input type="text" name="updated_at"  id="date" value="<?= $post->updated_at ?>" > 
            <section>
                <?php if(is_array($invalide_fields) ): ?>
                    <?= $errorMessage->getErrorMessage("updated_at",$invalide_fields) ?>
                <?php endif; ?>
            </section>
        </div>


        <div>
            <button type="submit" >modifier l'article</button>
            <?= $tokenInput; ?>
        </div>

    </form>    
    
</main>

<?= $render->show("footer") ?>