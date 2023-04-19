<?=

$render->show("header",parameter:[
    "title"=>"{$post->name}",
    "style"=>"./style/css/postShow/postShow.css"
])

?>

<?php

$date=(new DateTime($post->updated_at))->format("Y-m-d H:i");

?>

<main>
    
    <section id="container" >

                <h2><?= $post->name; ?> <em> Posté le <?= $date ; ?></em> </h2>

                <div class="content_card" >

                  <p> <?= nl2br($post->content); ?> </p>

                </div>

    </section>
    
</main>

<?= $render->show("footer") ?>