<?= 
$render->show("header",parameter:[
    "title"=>"Home",
    "style"=>"./style/css/home/home.css"
])

?>

<main>

    <div>
       <h1> Les articles du jour</h1> 
    </div>

    <div id="paginateLinks" >
        
        <?= $paginateLinks;  ?>
    </div>

    <section id="container" >
        <?php foreach ($posts as $value): ?>

            <div class="card" >

                <h2><?= $value->name; ?> <em> Posté le <?= $value->created_at; ?></em> </h2>

                <div class="content_card" >

                  <p> <?= $value->subStringContent(500) ; ?> </p>
                  <a href="<?= $router->generate("blog_post",[ "id"=>$value->id, "slug"=>$value->slug ]) ?>">Lire la suite &rightarrow;</a>

                </div>

            </div>

        <?php endforeach ?>

    </section>
    
    
</main>

<?= $render->show("footer") ?>