<?= 
$render->show("header",parameter:[
    "title"=>"Categorie admin",
    /*"style"=>"./style/css/home/home.css"*/
])
?>

<?php 

$success=$session->getSessionFlash("success");

?>

<main>
    
    <div>
       <h1> La list des categories</h1> 
    </div>
     <div>
        <a href="<?= $router->generate("admin_home") ?>">Article Admin</a>
    </div>
	<?php if(!empty($success)): ?>

		<h2><?= $success; ?></h2>

	<?php endif; ?>

	<div></div>

    <div>
        <a href="<?= $router->generate("create_categorie_home") ?>">creer une categorie</a>
    </div>

    <div id="paginateLinks" >
        
        <?= $paginateLinks;  ?>
    </div>


    <section id="container" >

    	<table>
    		<thead>
    			<tr>
    				<th>Categories</th>
    			</tr>
    			<tr>
    				<th>Titre</th>
    				<th>Action</th>
    			</tr>
    		</thead>
    		<tbody>
    			<?php foreach ($categories as $value): ?>
    				<tr>
    					<td>
    						<?= $value->name; ?>
    					</td>
    					<td>
                            <a href="<?= $router->generate("update_categorie_home",["slug"=>$value->slug,"id"=>$value->id]); ?>?pos=<?= $_GET["p"]??1; ?>">Editer</a>               
                        </td>
                        <td>
                            <form action="<?= $router->generate("delete_categorie_home",[ "id"=>$value->id ]); ?>?pos=<?= $_GET["p"]??1; ?>" method="POST" >
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