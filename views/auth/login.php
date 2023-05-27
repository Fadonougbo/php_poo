<?= 
$render->show("header",parameter:[
    "title"=>"Home",
    "style"=>"./style/css/home/home.css"
])

?>

<?php

use Utils\middlewares\CsrfMiddleware;

$invalide_fields=$validationStatus;
$invalide_fields_message=$session->getSessionFlash("invalideForm");
$invalide_user_info_message=$session->getSessionFlash("no_connect");

$csrf=new CsrfMiddleware($session);

$tokenInput=$csrf->getCsrfInput();

?>

<main>

    <div>
       <h1>Veillez vous authentifiez</h1> 
    </div>


    <section id="container" >
        
        <form action="" method="POST" >
            <?php if (!empty($invalide_fields_message)): ?>
                <h1><?=  $invalide_fields_message; ?>  </h1>
            <?php endif ?>
            <?php if (!empty($invalide_user_info_message)): ?>
                <h1><?=  $invalide_user_info_message; ?>  </h1>
            <?php endif ?>
            <section>
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" placeholder="ex:john" value="<?= isset($_POST["username"])?htmlentities($_POST["username"]):""; ?>" >
                <section>
                <?php if(is_array($invalide_fields) ): ?>
                    <?= $errorMessage->getErrorMessage("username",$invalide_fields) ?>
                <?php endif; ?>
            </section>
            </section>
            <section>
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="ex:******" value="<?= isset($_POST["password"])?htmlentities($_POST["password"]):""; ?>" >
                <?php if(is_array($invalide_fields) ): ?>
                    <?= $errorMessage->getErrorMessage("password",$invalide_fields) ?>
                <?php endif; ?>
            </section>
            <section>
                <button type="submit" >je me connecte</button>
                <?= $tokenInput; ?>
            </section>
        </form>
    </section>
    
    
</main>

<?= $render->show("footer") ?>