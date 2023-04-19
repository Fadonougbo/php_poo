<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CategoriesMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {

        $this->table("categories")
        ->addColumn("name","string")
        ->addColumn("slug","string")
        ->create();

        $this->table("posts_categories")
        ->addColumn("posts_id","integer")
        ->addColumn("categories_id","integer")
        ->addForeignKey("posts_id","posts","id",
            [
                "delete"=>"CASCADE",
                "update"=>"CASCADE"
            ])
        ->addForeignKey("categories_id","categories","id",
            [
                "delete"=>"CASCADE",
                "update"=>"CASCADE"
            ])
        ->create();

    }
}
