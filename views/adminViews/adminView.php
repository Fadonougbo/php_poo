<?= 
$render->show("header",parameter:[
    "title"=>"Admin",
    /*"style"=>"./style/css/home/home.css"*/
])
?>

<?php 

$success=$session->getSessionFlash("success");



?>

<main>

    <div>
       <h1> La list des articles</h1> 
    </div>
     <div>
        <a href="<?= $router->generate("admin_categories_home") ?>">Categorie Admin</a>
    </div>
	<?php if(!empty($success)): ?>

		<h2><?= $success; ?></h2>

	<?php endif; ?>

	<div></div>

    <div>
        <a href="<?= $router->generate("create_post_home") ?>">creer un article</a>
    </div>

    <div id="paginateLinks" >
        
        <?= $paginateLinks;  ?>
    </div>


    <section id="container" >

    	<table>
    		<thead>
    			<tr>
    				<th>Posts</th>
    			</tr>
    			<tr>
    				<th>Titre</th>
                    <th>categories</th>
    				<th>Action</th>
    			</tr>
    		</thead>
    		<tbody>
    			<?php foreach ($posts as $value): ?>
    				<tr>
    					<td>
    						<a href="<?= $router->generate("blog_post",[ "id"=>$value->id, "slug"=>$value->slug ]) ?>"><?= $value->name; ?></a>
    					</td>
                        <td>
                            <ol>
                                <?php if (isset($categories_post[$value->id])): ?>

                                    <?php foreach ($categories_post[$value->id] as $v): ?>
                                          <li><?= $v["name"] ?></li>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </ol>
                        </td>
    					<td>
                            <a href="<?= $router->generate("update_post_home",["slug"=>$value->slug,"id"=>$value->id]); ?>?pos=<?= isset($_GET["p"])?htmlentities($_GET["p"]):1; ?>">Editer</a>               
                        </td>
                        <td>
                            <form action="<?= $router->generate("delete_post_home",[ "id"=>$value->id ]); ?>?pos=<?= isset($_GET["p"])?htmlentities($_GET["p"]):1; ?>" method="POST" >
                                <button type="submit" >Supprimer</button>
                            </form>             
                        </td>
    				</tr>

		        <?php endforeach ?>
    		</tbody>
    	</table>


    </section>
    
    
</main>

<?= $render->show("footer") ?>