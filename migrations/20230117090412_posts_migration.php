<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class PostsMigration extends AbstractMigration
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
        $this->table("posts")
        ->addColumn("name","string")
        ->addColumn("slug","string")
        ->addColumn("content","text",["limit"=>MysqlAdapter::TEXT_LONG])
        ->addColumn("created_at","datetime")
        ->addColumn("updated_at","datetime")
        ->create();
    }
}
