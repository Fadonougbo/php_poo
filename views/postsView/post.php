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

     <?php 
        $categorieLiaison=$articleCategorieInfo[$post->id]??[];
     ?>
    
    <section id="container" >

        <div>
            <?php if (!empty($categorieLiaison)): ?>
                <?php foreach ($categorieLiaison as $cate): ?>
                   <mark>
                        <a href="<?= $router->generate("blog_filterByCategory",["slug"=>$cate['slug'],"id"=>$cate["id"]]) ?>">
                            <?= $cate["name"] ?>
                        </a>
                    </mark>  
                <?php endforeach ?>
            <?php endif ?>
        </div>
        <h2><?= $post->name; ?> <em> Posté le <?= $date ; ?></em> </h2>

        <div class="content_card" >

          <p> <?= nl2br($post->content); ?> </p>

        </div>

    </section>
    
</main>

<?= $render->show("footer") ?>