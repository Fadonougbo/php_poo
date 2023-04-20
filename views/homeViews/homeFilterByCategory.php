<?= 
$render->show("header",parameter:[
    "title"=>"Home",
    "style"=>"./style/css/home/home.css"
])

?>

<main>

    <div>
       <h1><?= $category->name?> </h1> 
    </div>

    <div id="paginateLinks" >
        
        <?= $paginateLinks;  ?>
    </div>

    <section>
        <?php foreach ($allCategoriesList as $categorie): ?>
            <a href="<?= $router->generate("blog_filterByCategory",["slug"=>$categorie->slug,"id"=>$categorie->id]) ?>">
                <?= $categorie->name ?>
            </a>
        <?php endforeach ?>
    </section>

    <section id="container" >
        <?php foreach ($posts as $value): ?>

            <div class="card" >
                <?php 
                    $categorieLiaison=$articleCategorieInfo[$value->id]??[];
                 ?>

                 <div>
                    <?php if (!empty($categorieLiaison)): ?>

                        <?php foreach ($categorieLiaison as $key => $cate): ?>
                           <mark>
                                <a href="<?= $router->generate("blog_filterByCategory",["slug"=>$cate['slug'],"id"=>$cate["id"]]) ?>">
                                    <?= $cate["name"] ?>
                                </a>
                            </mark>  
                        <?php endforeach ?>
                    <?php endif ?>
                 </div>
                <h2>
                    <?= $value->name; ?> <em> Posté le <?= $value->created_at; ?></em> 
                </h2>

                <div class="content_card" >

                  <p> <?= $value->subStringContent(500) ; ?> </p>
                  <a href="<?= $router->generate("blog_post",[ "id"=>$value->id, "slug"=>$value->slug ]) ?>">Lire la suite &rightarrow;</a>

                </div>

            </div>

        <?php endforeach ?>

    </section>
    
    
</main>

<?= $render->show("footer") ?>